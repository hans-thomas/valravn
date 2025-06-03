<?php

namespace Hans\Valravn\Tests\Feature\Helper;

use Hans\Valravn\Exceptions\Package\InvalidEntityException;
use Hans\Valravn\Exceptions\VException;
use Hans\Valravn\Http\Resources\VJsonResource;
use Hans\Valravn\InstallCommand;
use Hans\Valravn\Tests\Core\Factories\CommentFactory;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Factories\UserFactory;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Core\Models\User;
use Hans\Valravn\Tests\Core\Resources\User\UserResource;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Optional;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FunctionsTest extends TestCase
{
    private User $user;
    private Post $post;
    private string $date;

    #[Test]
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
     * @throws VException
     *
     * @return void
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

    #[Test]
    public function resolveRelatedIdToModelWithInvalidModel(): void
    {
        $this->expectExceptionObject(new InvalidEntityException($entity = InstallCommand::class));

        resolveRelatedIdToModel(1, $entity);
    }

    #[Test]
    public function resolveRelatedIdToModelWithInvalidId(): void
    {
        $model = resolveRelatedIdToModel(9999, Post::class);

        self::assertFalse($model);
    }

    #[Test]
    public function resolveMorphableToVResource(): void
    {
        $resource = resolveMorphableToVResource($this->post);

        self::assertInstanceOf(
            VJsonResource::class,
            $resource
        );
    }

    #[Test]
    public function resolveMorphableToResource(): void
    {
        $resource = resolveMorphableToVResource(CommentFactory::new()->for($this->post)->create());

        self::assertInstanceOf(
            JsonResource::class,
            $resource
        );
    }

    #[Test]
    public function resolveMorphableToVResourceWithResourceCollectionableImplemented(): void
    {
        $resource = resolveMorphableToVResource($this->user);

        self::assertInstanceOf(
            UserResource::class,
            $resource
        );
    }

    #[Test]
    public function slugify(): void
    {
        // Poetry meaning: if you are alive now, don't let the present pass without happiness -Omar Khayyam
        $non_english_string = 'گر یک نفست ز زندگانی گذرد / مگذار ک جز به شادمانی گذرد';
        $slug = slugify($non_english_string);

        self::assertIsString($slug);
        self::assertEquals(
            'گر-یک-نفست-ز-زندگانی-گذرد-مگذار-ک-جز-به-شادمانی-گذرد',
            $slug
        );
    }

    #[Test]
    public function vlogLogFile(): void
    {
        self::assertFileDoesNotExist(storage_path("logs/valravn-$this->date.log"));

        vlog('The reason');

        self::assertFileExists(storage_path("logs/valravn-$this->date.log"));
    }

    #[Test]
    public function vlogContent(): void
    {
        $file = storage_path("logs/valravn-$this->date.log");
        $e = new NotFoundHttpException('Failed to found your data');
        vlog('The reason: {r}', ['r' => 'something', 'previous' => $e]);

        $actual = file_get_contents($file);
        $expected = <<<EOT
        At: [Hans\Valravn\Tests\Feature\Helper\FunctionsTest::vlogContent] => "The reason: something"
        EOT;

        self::assertStringContainsString(
            $expected,
            $actual
        );

        $expected = <<<'EOD'
        [object] (Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException(code: 0): Failed to found your data
        EOD;

        self::assertStringContainsString(
            $expected,
            $actual
        );
    }

    #[Test]
    public function vlogThrowableAsMessage(): void
    {
        $file = storage_path("logs/valravn-$this->date.log");
        $e = new NotFoundHttpException('Failed to found your data');
        vlog($e);

        $actual = file_get_contents($file);
        $expected = <<<EOT
        At: [Hans\Valravn\Tests\Feature\Helper\FunctionsTest::vlogThrowableAsMessage] => "Failed to found your data"
        EOT;

        self::assertStringContainsString(
            $expected,
            $actual
        );
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->post = PostFactory::new()->create();
        $this->date = now()->format('Y-m-d');
        config()->set('logging.channels.valravn', [
            'driver'               => 'daily',
            'path'                 => storage_path('logs/valravn.log'),
            'level'                => 'debug',
            'days'                 => 1,
            'replace_placeholders' => true,
        ]);
    }

    protected function tearDown(): void
    {
        File::delete(storage_path("logs/valravn-$this->date.log"));

        parent::tearDown();
    }
}
