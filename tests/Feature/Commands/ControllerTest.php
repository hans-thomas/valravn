<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class ControllerTest extends TestCase
{
    #[Test]
    public function crud(): void
    {
        $file = app_path('Http/Controllers/V1/Blog/Post/PostCrudController.php');
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:controller blog posts');

        self::assertFileExists($file);

        $crud_file = $this->getStub('controllers/crud.stub');
        $crud_file = str_replace('{{CRUD::VERSION}}', 'V1', $crud_file);
        $crud_file = str_replace('{{CRUD::NAMESPACE}}', 'Blog', $crud_file);
        $crud_file = str_replace('{{CRUD::MODEL}}', 'Post', $crud_file);
        $crud_file = str_replace('{{CRUD::MODEL-lower}}', 'post', $crud_file);

        self::assertEquals(
            $crud_file,
            file_get_contents($file)
        );
    }

    #[Test]
    public function relations(): void
    {
        $file = app_path('Http/Controllers/V1/Blog/Post/PostRelationsController.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:controller blog posts --relations');

        self::assertFileExists($file);
    }

    #[Test]
    public function actions(): void
    {
        $file = app_path('Http/Controllers/V1/Blog/Post/PostActionsController.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:controller blog posts --actions');

        self::assertFileExists($file);
    }

    #[Test]
    public function requests(): void
    {
        $store = app_path('Http/Requests/V1/Blog/Post/PostStoreRequest.php');
        $update = app_path('Http/Requests/V1/Blog/Post/PostUpdateRequest.php');
        $batchUpdate = app_path('Http/Requests/V1/Blog/Post/PostBatchUpdateRequest.php');

        self::assertFileDoesNotExist($store);
        self::assertFileDoesNotExist($update);

        Artisan::call('valravn:controller blog posts --requests');

        self::assertFileExists($store);
        self::assertFileExists($update);
        self::assertFileExists($batchUpdate);
    }

    #[Test]
    public function resources(): void
    {
        $store = app_path('Http/Resources/V1/Blog/Post/PostResource.php');
        $update = app_path('Http/Resources/V1/Blog/Post/PostCollection.php');

        self::assertFileDoesNotExist($store);
        self::assertFileDoesNotExist($update);

        Artisan::call('valravn:controller blog posts --resources');

        self::assertFileExists($store);
        self::assertFileExists($update);
    }
}
