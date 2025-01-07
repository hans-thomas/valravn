<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;

class EntityTest extends TestCase
{
    #[Test]
    public function entity(): void
    {
        $exception = app_path('Exceptions/Blog/Post/PostException.php');

        self::assertFileDoesNotExist($exception);

        $crud = app_path('Http/Controllers/V1/Blog/Post/PostCrudController.php');
        $relations = app_path('Http/Controllers/V1/Blog/Post/PostRelationsController.php');
        $actions = app_path('Http/Controllers/V1/Blog/Post/PostActionsController.php');
        $store = app_path('Http/Requests/V1/Blog/Post/PostStoreRequest.php');
        $update = app_path('Http/Requests/V1/Blog/Post/PostUpdateRequest.php');
        $batchUpdate = app_path('Http/Requests/V1/Blog/Post/PostBatchUpdateRequest.php');
        $resource = app_path('Http/Resources/V1/Blog/Post/PostResource.php');
        $collection = app_path('Http/Resources/V1/Blog/Post/PostCollection.php');

        self::assertFileDoesNotExist($crud);
        self::assertFileDoesNotExist($relations);
        self::assertFileDoesNotExist($actions);
        self::assertFileDoesNotExist($store);
        self::assertFileDoesNotExist($update);
        self::assertFileDoesNotExist($batchUpdate);
        self::assertFileDoesNotExist($resource);
        self::assertFileDoesNotExist($collection);

        $policy = app_path('Policies/Blog/PostPolicy.php');

        self::assertFileDoesNotExist($policy);

        $contract = app_path('Repositories/Contracts/Blog/IPostRepository.php');
        $repository = app_path('Repositories/Blog/PostRepository.php');

        self::assertFileDoesNotExist($contract);
        self::assertFileDoesNotExist($repository);

        $crudService = app_path('Services/Blog/Post/PostCrudService.php');
        $relationsService = app_path('Services/Blog/Post/PostRelationsService.php');
        $actionsService = app_path('Services/Blog/Post/PostActionsService.php');

        self::assertFileDoesNotExist($crudService);
        self::assertFileDoesNotExist($relationsService);
        self::assertFileDoesNotExist($actionsService);

        $model = app_path('Models/Blog/Post.php');
        $factory = base_path('database/factories/Blog/PostFactory.php');
        $seeder = base_path('database/seeders/Blog/PostSeeder.php');
        $datePrefix = now()->format('Y_m_d_His');
        $migration = base_path("database/migrations/Blog/{$datePrefix}_create_posts_table.php");

        self::assertFileDoesNotExist($model);
        self::assertFileDoesNotExist($factory);
        self::assertFileDoesNotExist($seeder);
        self::assertFileDoesNotExist($migration);

        Artisan::call('valravn:entity blog posts BPEcx');

        self::assertFileExists($exception);

        self::assertFileExists($crud);
        self::assertFileExists($relations);
        self::assertFileExists($actions);
        self::assertFileExists($store);
        self::assertFileExists($update);
        self::assertFileExists($resource);
        self::assertFileExists($collection);

        self::assertFileExists($policy);

        self::assertFileExists($contract);
        self::assertFileExists($repository);

        self::assertFileExists($crudService);
        self::assertFileExists($relationsService);
        self::assertFileExists($actionsService);

        self::assertFileExists($model);
        self::assertFileExists($factory);
        self::assertFileExists($seeder);
        self::assertFileExists($migration);
    }

    #[Test]
    public function version(): void
    {
        $exception = app_path('Exceptions/Blog/Post/PostException.php');

        self::assertFileDoesNotExist($exception);

        $crud = app_path('Http/Controllers/V2/Blog/Post/PostCrudController.php');
        $relations = app_path('Http/Controllers/V2/Blog/Post/PostRelationsController.php');
        $actions = app_path('Http/Controllers/V2/Blog/Post/PostActionsController.php');
        $store = app_path('Http/Requests/V2/Blog/Post/PostStoreRequest.php');
        $update = app_path('Http/Requests/V2/Blog/Post/PostUpdateRequest.php');
        $batchUpdate = app_path('Http/Requests/V2/Blog/Post/PostBatchUpdateRequest.php');
        $resource = app_path('Http/Resources/V2/Blog/Post/PostResource.php');
        $collection = app_path('Http/Resources/V2/Blog/Post/PostCollection.php');

        self::assertFileDoesNotExist($crud);
        self::assertFileDoesNotExist($relations);
        self::assertFileDoesNotExist($actions);
        self::assertFileDoesNotExist($store);
        self::assertFileDoesNotExist($update);
        self::assertFileDoesNotExist($batchUpdate);
        self::assertFileDoesNotExist($resource);
        self::assertFileDoesNotExist($collection);

        $policy = app_path('Policies/Blog/PostPolicy.php');

        self::assertFileDoesNotExist($policy);

        $contract = app_path('Repositories/Contracts/Blog/IPostRepository.php');
        $repository = app_path('Repositories/Blog/PostRepository.php');

        self::assertFileDoesNotExist($contract);
        self::assertFileDoesNotExist($repository);

        $crudService = app_path('Services/Blog/Post/PostCrudService.php');
        $relationsService = app_path('Services/Blog/Post/PostRelationsService.php');
        $actionsService = app_path('Services/Blog/Post/PostActionsService.php');

        self::assertFileDoesNotExist($crudService);
        self::assertFileDoesNotExist($relationsService);
        self::assertFileDoesNotExist($actionsService);

        $model = app_path('Models/Blog/Post.php');
        $factory = base_path('database/factories/Blog/PostFactory.php');
        $seeder = base_path('database/seeders/Blog/PostSeeder.php');
        $datePrefix = now()->format('Y_m_d_His');
        $migration = base_path("database/migrations/Blog/{$datePrefix}_create_posts_table.php");

        self::assertFileDoesNotExist($model);
        self::assertFileDoesNotExist($factory);
        self::assertFileDoesNotExist($seeder);
        self::assertFileDoesNotExist($migration);

        Artisan::call('valravn:entity blog posts BPTEcx --v 2');

        self::assertFileExists($exception);

        self::assertFileExists($crud);
        self::assertFileExists($relations);
        self::assertFileExists($actions);
        self::assertFileExists($store);
        self::assertFileExists($update);
        self::assertFileExists($batchUpdate);
        self::assertFileExists($resource);
        self::assertFileExists($collection);

        self::assertFileExists($policy);

        self::assertFileExists($contract);
        self::assertFileExists($repository);

        self::assertFileExists($crudService);
        self::assertFileExists($relationsService);
        self::assertFileExists($actionsService);

        self::assertFileExists($model);
        self::assertFileExists($factory);
        self::assertFileExists($seeder);
        self::assertFileExists($migration);
    }
}
