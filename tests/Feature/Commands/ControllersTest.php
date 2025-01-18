<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ControllersTest extends TestCase
{
    #[Test]
    public function controllers(): void
    {
        $crud = app_path('Http/Controllers/V1/Blog/Post/PostCrudController.php');
        $relations = app_path('Http/Controllers/V1/Blog/Post/PostRelationsController.php');
        $actions = app_path('Http/Controllers/V1/Blog/Post/PostActionsController.php');

        self::assertFileDoesNotExist($crud);
        self::assertFileDoesNotExist($relations);
        self::assertFileDoesNotExist($actions);

        $this->artisan('valravn:controllers blog posts')
             ->expectsOutput('Controller classes created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource and ResourceCollection classes created.')
             ->assertExitCode(0);

        self::assertFileExists($crud);
        self::assertFileExists($relations);
        self::assertFileExists($actions);
    }

    #[Test]
    public function requests(): void
    {
        $store = app_path('Http/Requests/V1/Blog/Post/PostStoreRequest.php');
        $update = app_path('Http/Requests/V1/Blog/Post/PostUpdateRequest.php');
        $batchUpdate = app_path('Http/Requests/V1/Blog/Post/PostBatchUpdateRequest.php');

        self::assertFileDoesNotExist($store);
        self::assertFileDoesNotExist($update);

        $this->artisan('valravn:controllers blog posts --requests')
             ->expectsOutput('Controller classes created.')
             ->expectsOutput('Request classes created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource and ResourceCollection classes created.')
             ->assertExitCode(0);

        self::assertFileExists($store);
        self::assertFileExists($update);
        self::assertFileExists($batchUpdate);
    }

    #[Test]
    public function requestsWithoutParam(): void
    {
        $store = app_path('Http/Requests/V1/Blog/Post/PostStoreRequest.php');
        $update = app_path('Http/Requests/V1/Blog/Post/PostUpdateRequest.php');
        $batchUpdate = app_path('Http/Requests/V1/Blog/Post/PostBatchUpdateRequest.php');

        self::assertFileDoesNotExist($store);
        self::assertFileDoesNotExist($update);

        $this->artisan('valravn:controllers blog posts')
             ->expectsOutput('Controller classes created.')
             ->expectsConfirmation('Should create requests?', 'yes')
             ->expectsOutput('Request classes created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource and ResourceCollection classes created.')
             ->assertExitCode(0);

        self::assertFileExists($store);
        self::assertFileExists($update);
        self::assertFileExists($batchUpdate);
    }

    #[Test]
    public function resources(): void
    {
        $resource = app_path('Http/Resources/V1/Blog/Post/PostResource.php');
        $collection = app_path('Http/Resources/V1/Blog/Post/PostCollection.php');

        self::assertFileDoesNotExist($resource);
        self::assertFileDoesNotExist($collection);

        $this->artisan('valravn:controllers blog posts --resources')
            ->expectsOutput('Controller classes created.')
            ->expectsConfirmation('Should create requests?')
            ->doesntExpectOutput('Request classes created.')
            ->expectsOutput('Resource and ResourceCollection classes created.')
            ->assertExitCode(0);

        self::assertFileExists($resource);
        self::assertFileExists($collection);
    }

    #[Test]
    public function resourcesWithoutParam(): void
    {
        $resource = app_path('Http/Resources/V1/Blog/Post/PostResource.php');
        $collection = app_path('Http/Resources/V1/Blog/Post/PostCollection.php');

        self::assertFileDoesNotExist($resource);
        self::assertFileDoesNotExist($collection);

        $this->artisan('valravn:controllers blog posts')
            ->expectsOutput('Controller classes created.')
            ->expectsConfirmation('Should create requests?')
            ->doesntExpectOutput('Request classes created.')
            ->expectsConfirmation('Should create resources?','yes')
            ->expectsOutput('Resource and ResourceCollection classes created.')
            ->assertExitCode(0);

        self::assertFileExists($resource);
        self::assertFileExists($collection);
    }

    #[Test]
    public function version(): void
    {
        $crud = app_path('Http/Controllers/V2/Blog/Post/PostCrudController.php');
        $relations = app_path('Http/Controllers/V2/Blog/Post/PostRelationsController.php');
        $actions = app_path('Http/Controllers/V2/Blog/Post/PostActionsController.php');
        $store = app_path('Http/Requests/V2/Blog/Post/PostStoreRequest.php');
        $update = app_path('Http/Requests/V2/Blog/Post/PostUpdateRequest.php');
        $resource = app_path('Http/Resources/V2/Blog/Post/PostResource.php');
        $collection = app_path('Http/Resources/V2/Blog/Post/PostCollection.php');

        self::assertFileDoesNotExist($crud);
        self::assertFileDoesNotExist($relations);
        self::assertFileDoesNotExist($actions);
        self::assertFileDoesNotExist($store);
        self::assertFileDoesNotExist($update);
        self::assertFileDoesNotExist($resource);
        self::assertFileDoesNotExist($collection);

        $this->artisan('valravn:controllers blog posts --requests --resources --v=2')
            ->expectsOutput('Controller classes created.')
            ->expectsOutput('Request classes created.')
            ->expectsOutput('Resource and ResourceCollection classes created.')
            ->assertExitCode(0);

        self::assertFileExists($crud);
        self::assertFileExists($relations);
        self::assertFileExists($actions);
        self::assertFileExists($store);
        self::assertFileExists($update);
        self::assertFileExists($resource);
        self::assertFileExists($collection);
    }
}
