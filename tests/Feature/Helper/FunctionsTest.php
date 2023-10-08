<?php

namespace Hans\Valravn\Tests\Feature\Helper;

use Hans\Valravn\Exceptions\Package\PackageException;
use Hans\Valravn\Exceptions\ValravnException;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Factories\UserFactory;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Core\Models\User;
use Hans\Valravn\Tests\Core\Resources\User\UserResource;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Optional;

class FunctionsTest extends TestCase
{
    private User $user;
    private Post $post;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::new()->create();
        $this->post = PostFactory::new()->create();
    }

    /**
     * @test
     *
     * @return void
     */
    public function user(): void
    {
        self::assertInstanceOf(
            Optional::class,
            \user()
        );
        self::assertEquals(
            \optional(),
            \user()
        );

        $this->actingAs($this->user);

        self::assertInstanceOf(
            User::class,
            \user()
        );
        self::assertEquals(
            $this->user->toArray(),
            \user()->toArray()
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function generate_order(): void
    {
        self::assertIsFloat(generate_order());
    }

    /**
     * @test
     *
     * @return void
     * @throws ValravnException
     *
     */
    public function resolveRelatedIdToModel(): void
    {
        $model = resolveRelatedIdToModel(1, Post::class);

        self::assertInstanceOf(
            Post::class,
            $model
        );
        self::assertTrue(
            $this->post->is($model)
        );
    }

    /**
     * @test
     *
     * @return void
     * @throws ValravnException
     *
     */
    public function resolveRelatedIdToModelWithInvalidModel(): void
    {
        $this->expectExceptionObject(PackageException::invalidEntity($entity = GEazy::class));

        resolveRelatedIdToModel(1, $entity);
    }

    /**
     * @test
     *
     * @return void
     * @throws ValravnException
     *
     */
    public function resolveRelatedIdToModelWithInvalidId(): void
    {
        $model = resolveRelatedIdToModel(9999, Post::class);

        self::assertFalse($model);
    }

    /**
     * @test
     *
     * @return void
     */
    public function resolveMorphableToResource(): void
    {
        $resource = resolveMorphableToResource($this->post);

        self::assertInstanceOf(
            JsonResource::class,
            $resource
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function resolveMorphableToResourceWithResourceCollectionableImplemented(): void
    {
        $resource = resolveMorphableToResource($this->user);

        self::assertInstanceOf(
            UserResource::class,
            $resource
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function slugify(): void
    {
        // poetry meaning: if you are alive now, don't let the present pass without happiness -Omar Khayyam
        // p.s.: obviously it is very complex and deep, so I can't translate it properly.
        $non_english_string = 'گر یک نفست ز زندگانی گذرد / مگذار ک جز به شادمانی گذرد';
        $slug = slugify($non_english_string);

        self::assertIsString($slug);
        self::assertEquals(
            'گر-یک-نفست-ز-زندگانی-گذرد-مگذار-ک-جز-به-شادمانی-گذرد',
            $slug
        );
    }
}
