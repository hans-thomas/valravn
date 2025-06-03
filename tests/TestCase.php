<?php

namespace Hans\Valravn\Tests;

use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Core\Resources\Post\PostCollection;
use Hans\Valravn\Tests\Core\Resources\Post\PostResource;
use Hans\Valravn\ValravnServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Router;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->cleanUp([
            app_path('Http/Controllers/V1/Blog/Post'),
            app_path('Http/Requests/V1/Blog/Post'),
            app_path('Http/Resources/V1/Blog/Post'),
            app_path('Http/Controllers/V2/Blog/Post'),
            app_path('Http/Requests/V2/Blog/Post'),
            app_path('Http/Resources/V2/Blog/Post'),
            app_path('Exceptions/Blog/Post'),
            app_path('Models/Blog'),
            app_path('Policies/Blog'),
            app_path('Repositories/Contracts/Blog'),
            app_path('Repositories/Blog'),
            app_path('Services/Blog/Post'),

            base_path('database/factories/Blog'),
            base_path('database/seeders/Blog'),
            base_path('database/migrations'),
        ]);

        parent::tearDown();
    }

    protected function cleanUp(array $paths, array $ignoreFiles = []): void
    {
        $fs = new Filesystem();

        foreach ($paths as $path) {
            if ($fs->isFile($path)) {
                $fs->delete($path);
            }

            if ($fs->isDirectory($path) && !$fs->isEmptyDirectory($path, true)) {
                foreach ($fs->allFiles($path) as $file) {
                    if (!in_array($file, $ignoreFiles)) {
                        $fs->delete($file);
                    }
                }
            }
        }
    }

    /**
     * Get application timezone.
     *
     * @param Application $app
     *
     * @return string|null
     */
    protected function getApplicationTimezone($app)
    {
        return 'UTC';
    }

    /**
     * Get package providers.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ValravnServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Define routes setup.
     *
     * @param Router $router
     *
     * @return void
     */
    protected function defineRoutes($router)
    {
        $router->get(
            '/includes/posts/{post}',
            fn ($post) => PostResource::make(Post::findOrFail($post))->parseIncludes()
        );
        $router->get(
            '/includes/posts',
            fn () => PostCollection::make(Post::all())->parseIncludes()
        );
        $router->get(
            '/queries/posts/{post}',
            fn ($post) => PostResource::make(Post::findOrFail($post))->parseQueries()
        );
        $router->get(
            '/queries/posts',
            fn () => PostCollection::make(Post::all())->parseQueries()
        );
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(
            __DIR__.'/Core/migrations'
        );
    }

    protected function resourceToJson(VJsonResource $resource): array
    {
        return json_decode(
            $resource->toResponse(request())->content(),
            true
        );
    }

    protected function getStub(string $stub): string
    {
        return file_get_contents(__DIR__."/../src/Commands/stubs/$stub");
    }
}
