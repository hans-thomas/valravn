<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ControllerTest extends TestCase
{
    #[Test]
    public function crud(): void
    {
        $file = app_path('Http/Controllers/V1/Blog/Post/PostCrudController.php');
        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:controller blog posts')
             ->expectsOutput('Controller class created.')
             ->doesntExpectOutput('Controller class exists or could not be created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->doesntExpectOutput('Relations class exists or could not be created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->doesntExpectOutput('Actions class exists or could not be created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

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
    public function crudExists(): void
    {
        $file = app_path('Http/Controllers/V1/Blog/Post/PostCrudController.php');
        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:controller blog posts')
             ->expectsOutput('Controller class created.')
             ->doesntExpectOutput('Controller class exists or could not be created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->doesntExpectOutput('Relations class exists or could not be created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->doesntExpectOutput('Actions class exists or could not be created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        $this->artisan('valravn:controller blog posts')
             ->doesntExpectOutput('Controller class created.')
             ->expectsOutput('Controller class exists or could not be created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->doesntExpectOutput('Relations class exists or could not be created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->doesntExpectOutput('Actions class exists or could not be created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function relations(): void
    {
        $file = app_path('Http/Controllers/V1/Blog/Post/PostRelationsController.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:controller blog posts --relations')
             ->expectsOutput('Controller class created.')
             ->doesntExpectOutput('Controller class exists or could not be created.')
             ->expectsOutput('Relations class created.')
             ->doesntExpectOutput('Relations class exists or could not be created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->doesntExpectOutput('Actions class exists or could not be created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function relationsExists(): void
    {
        $file = app_path('Http/Controllers/V1/Blog/Post/PostRelationsController.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:controller blog posts --relations')
             ->expectsOutput('Controller class created.')
             ->doesntExpectOutput('Controller class exists or could not be created.')
             ->expectsOutput('Relations class created.')
             ->doesntExpectOutput('Relations class exists or could not be created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->doesntExpectOutput('Actions class exists or could not be created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        $this->artisan('valravn:controller blog posts --relations')
             ->doesntExpectOutput('Controller class created.')
             ->expectsOutput('Controller class exists or could not be created.')
             ->doesntExpectOutput('Relations class created.')
             ->expectsOutput('Relations class exists or could not be created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->doesntExpectOutput('Actions class exists or could not be created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function relationsWithoutParam(): void
    {
        $file = app_path('Http/Controllers/V1/Blog/Post/PostRelationsController.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:controller blog posts')
             ->expectsOutput('Controller class created.')
             ->expectsConfirmation('Should create relations?', 'yes')
             ->expectsOutput('Relations class created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function actions(): void
    {
        $file = app_path('Http/Controllers/V1/Blog/Post/PostActionsController.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:controller blog posts --actions')
             ->expectsOutput('Controller class created.')
             ->doesntExpectOutput('Controller class exists or could not be created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->doesntExpectOutput('Relations class exists or could not be created.')
             ->expectsOutput('Actions class created.')
             ->doesntExpectOutput('Actions class exists or could not be created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function actionsExists(): void
    {
        $file = app_path('Http/Controllers/V1/Blog/Post/PostActionsController.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:controller blog posts --actions')
             ->expectsOutput('Controller class created.')
             ->doesntExpectOutput('Controller class exists or could not be created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->doesntExpectOutput('Relations class exists or could not be created.')
             ->expectsOutput('Actions class created.')
             ->doesntExpectOutput('Actions class exists or could not be created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        $this->artisan('valravn:controller blog posts --actions')
             ->doesntExpectOutput('Controller class created.')
             ->expectsOutput('Controller class exists or could not be created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->doesntExpectOutput('Relations class exists or could not be created.')
             ->doesntExpectOutput('Actions class created.')
             ->expectsOutput('Actions class exists or could not be created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function actionsWithoutParams(): void
    {
        $file = app_path('Http/Controllers/V1/Blog/Post/PostActionsController.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:controller blog posts')
             ->expectsOutput('Controller class created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->expectsConfirmation('Should create actions?', 'yes')
             ->expectsOutput('Actions class created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

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

        $this->artisan('valravn:controller blog posts --requests')
             ->expectsOutput('Controller class created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->expectsOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($store);
        self::assertFileExists($update);
        self::assertFileExists($batchUpdate);
    }

    #[Test]
    public function requestsExists(): void
    {
        $store = app_path('Http/Requests/V1/Blog/Post/PostStoreRequest.php');
        $update = app_path('Http/Requests/V1/Blog/Post/PostUpdateRequest.php');
        $batchUpdate = app_path('Http/Requests/V1/Blog/Post/PostBatchUpdateRequest.php');

        self::assertFileDoesNotExist($store);
        self::assertFileDoesNotExist($update);

        $this->artisan('valravn:controller blog posts --requests')
             ->expectsOutput('Controller class created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->expectsOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        $this->artisan('valravn:controller blog posts --requests')
             ->doesntExpectOutput('Controller class created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->doesntExpectOutput('Request classes created.')
             ->expectsOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

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

        $this->artisan('valravn:controller blog posts')
             ->expectsOutput('Controller class created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->expectsConfirmation('Should create requests?', 'yes')
             ->expectsOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?')
             ->doesntExpectOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

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

        $this->artisan('valravn:controller blog posts --resources')
             ->expectsOutput('Controller class created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($store);
        self::assertFileExists($update);
    }

    #[Test]
    public function resourcesExists(): void
    {
        $store = app_path('Http/Resources/V1/Blog/Post/PostResource.php');
        $update = app_path('Http/Resources/V1/Blog/Post/PostCollection.php');

        self::assertFileDoesNotExist($store);
        self::assertFileDoesNotExist($update);

        $this->artisan('valravn:controller blog posts --resources')
             ->expectsOutput('Controller class created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        $this->artisan('valravn:controller blog posts --resources')
             ->doesntExpectOutput('Controller class created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->doesntExpectOutput('Resource classes created.')
             ->expectsOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($store);
        self::assertFileExists($update);
    }

    #[Test]
    public function resourcesWithoutParam(): void
    {
        $store = app_path('Http/Resources/V1/Blog/Post/PostResource.php');
        $update = app_path('Http/Resources/V1/Blog/Post/PostCollection.php');

        self::assertFileDoesNotExist($store);
        self::assertFileDoesNotExist($update);

        $this->artisan('valravn:controller blog posts')
             ->expectsOutput('Controller class created.')
             ->expectsConfirmation('Should create relations?')
             ->doesntExpectOutput('Relations class created.')
             ->expectsConfirmation('Should create actions?')
             ->doesntExpectOutput('Actions class created.')
             ->expectsConfirmation('Should create requests?')
             ->doesntExpectOutput('Request classes created.')
             ->doesntExpectOutput('Some request classes are exist or could not be created.')
             ->expectsConfirmation('Should create resources?', 'yes')
             ->expectsOutput('Resource classes created.')
             ->doesntExpectOutput('Some resource classes are exist or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($store);
        self::assertFileExists($update);
    }
}
