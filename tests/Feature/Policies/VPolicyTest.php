<?php

namespace Hans\Valravn\Tests\Feature\Policies;

use Hans\Valravn\Tests\Core\Factories\UserFactory;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Instances\Policies\PostPolicy;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class VPolicyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(UserFactory::new()->create());
        Gate::policy(Post::class, PostPolicy::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    #[Test, dataProvider('provideData')]
    public function actions(string $action, array $args): void
    {
        $response = Gate::inspect($action, $args);

        self::assertInstanceOf(Response::class, $response);
        self::assertFalse($response->allowed());

        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage('This action is unauthorized.');

        $response->authorize();
    }

    public static function provideData(): array
    {
        $post = new Post(['id' => 1, 'title' => 'the title', 'content' => 'some text.']);

        return [
            ['viewAny', [Post::class]],
            ['view', [$post]],
            ['create', [Post::class]],
            ['update', [$post]],
            ['batchUpdate', [Post::class, collect([$post->id => ['name' => 'new name']])]],
            ['delete', [$post]],
            ['restore', [$post]],
            ['forceDelete', [$post]],
        ];
    }

    #[Test]
    public function guessAbilityWithNoCustomNamespace(): void
    {
        $spyUser = Mockery::spy(User::class)->makePartial();

        $mockPolicy = Mockery::mock(PostPolicy::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $mockPolicy->shouldReceive('getModel')->andReturn('App\Models\Post');

        self::assertFalse($mockPolicy->viewAny($spyUser));

        $spyUser->shouldHaveReceived()->can('post-_mockery_handleMethodCall')->once();
    }

    #[Test]
    public function guessAbilityWithCustomNamespace(): void
    {
        $spyUser = Mockery::spy(User::class)->makePartial();

        $mockPolicy = Mockery::mock(PostPolicy::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $mockPolicy->shouldReceive('getModel')->andReturn('App\Models\Blog\Post');

        self::assertFalse($mockPolicy->viewAny($spyUser));

        $spyUser->shouldHaveReceived()->can('blog-post-_mockery_handleMethodCall')->once();
    }

    #[Test]
    public function guessAbilityWithUncommonNamespace(): void
    {
        $spyUser = Mockery::spy(User::class)->makePartial();

        $mockPolicy = Mockery::mock(PostPolicy::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $mockPolicy->shouldReceive('getModel')->andReturn('Hans\Valravn\Tests\Core\Models\Post');

        self::assertFalse($mockPolicy->viewAny($spyUser));

        $spyUser->shouldHaveReceived()->can('models-post-_mockery_handleMethodCall')->once();
    }
}
