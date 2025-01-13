<?php

namespace Hans\Valravn\Tests\Feature\Policies;

use Hans\Valravn\Tests\Core\Factories\UserFactory;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Instances\Policies\PostPolicy;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
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
}
