<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ServiceTests extends TestCase
{
    #[Test]
    public function crud(): void
    {
        $crud = app_path('Services/Blog/Post/PostCrudService.php');

        self::assertFileDoesNotExist($crud);

        $this->artisan('valravn:service blog posts')
            ->expectsOutput('CRUD service created.')
            ->doesntExpectOutput('CRUD service exists or could be created.')
            ->doesntExpectOutput('Relations service created.')
            ->doesntExpectOutput('Relations service exists or could be created.')
            ->doesntExpectOutput('Actions service created.')
            ->doesntExpectOutput('Actions service exists or could be created.')
            ->assertSuccessful();

        self::assertFileExists($crud);

        $crudStub = $this->getStub('services/crud.stub');
        $crudStub = str_replace('{{CRUD-SERVICE::NAMESPACE}}', 'Blog', $crudStub);
        $crudStub = str_replace('{{CRUD-SERVICE::MODEL}}', 'Post', $crudStub);

        self::assertEquals($crudStub, file_get_contents($crud));
    }

    #[Test]
    public function crudExists(): void
    {
        $crud = app_path('Services/Blog/Post/PostCrudService.php');

        self::assertFileDoesNotExist($crud);

        $this->artisan('valravn:service blog posts')
            ->expectsOutput('CRUD service created.')
            ->doesntExpectOutput('CRUD service exists or could be created.')
            ->doesntExpectOutput('Relations service created.')
            ->doesntExpectOutput('Relations service exists or could be created.')
            ->doesntExpectOutput('Actions service created.')
            ->doesntExpectOutput('Actions service exists or could be created.')
            ->assertSuccessful();

        $this->artisan('valravn:service blog posts')
            ->doesntExpectOutput('CRUD service created.')
            ->expectsOutput('CRUD service exists or could be created.')
            ->doesntExpectOutput('Relations service created.')
            ->doesntExpectOutput('Relations service exists or could be created.')
            ->doesntExpectOutput('Actions service created.')
            ->doesntExpectOutput('Actions service exists or could be created.')
            ->assertSuccessful();

        self::assertFileExists($crud);
    }

    #[Test]
    public function relations(): void
    {
        $relations = app_path('Services/Blog/Post/PostRelationsService.php');

        self::assertFileDoesNotExist($relations);

        $this->artisan('valravn:service blog posts -r')
            ->expectsOutput('CRUD service created.')
            ->doesntExpectOutput('CRUD service exists or could be created.')
            ->expectsOutput('Relations service created.')
            ->doesntExpectOutput('Relations service exists or could be created.')
            ->doesntExpectOutput('Actions service created.')
            ->doesntExpectOutput('Actions service exists or could be created.')
            ->assertSuccessful();

        self::assertFileExists($relations);

        $relationsStub = $this->getStub('services/custom.stub');
        $relationsStub = str_replace('{{CRUD-SERVICE::NAMESPACE}}', 'Blog', $relationsStub);
        $relationsStub = str_replace('{{CRUD-SERVICE::MODEL}}', 'Post', $relationsStub);
        $relationsStub = str_replace('{{CRUD-SERVICE::ACTION}}', 'Relations', $relationsStub);

        self::assertEquals($relationsStub, file_get_contents($relations));
    }

    #[Test]
    public function relationsExists(): void
    {
        $relations = app_path('Services/Blog/Post/PostRelationsService.php');

        self::assertFileDoesNotExist($relations);

        $this->artisan('valravn:service blog posts -r')
            ->expectsOutput('CRUD service created.')
            ->doesntExpectOutput('CRUD service exists or could be created.')
            ->expectsOutput('Relations service created.')
            ->doesntExpectOutput('Relations service exists or could be created.')
            ->doesntExpectOutput('Actions service created.')
            ->doesntExpectOutput('Actions service exists or could be created.')
            ->assertSuccessful();

        $this->artisan('valravn:service blog posts -r')
            ->doesntExpectOutput('CRUD service created.')
            ->expectsOutput('CRUD service exists or could be created.')
            ->doesntExpectOutput('Relations service created.')
            ->expectsOutput('Relations service exists or could be created.')
            ->doesntExpectOutput('Actions service created.')
            ->doesntExpectOutput('Actions service exists or could be created.')
            ->assertSuccessful();

        self::assertFileExists($relations);
    }

    #[Test]
    public function actions(): void
    {
        $actions = app_path('Services/Blog/Post/PostActionsService.php');

        self::assertFileDoesNotExist($actions);

        $this->artisan('valravn:service blog posts -a')
            ->expectsOutput('CRUD service created.')
            ->doesntExpectOutput('CRUD service exists or could be created.')
            ->doesntExpectOutput('Relations service created.')
            ->doesntExpectOutput('Relations service exists or could be created.')
            ->expectsOutput('Actions service created.')
            ->doesntExpectOutput('Actions service exists or could be created.')
            ->assertSuccessful();

        self::assertFileExists($actions);

        $actionsStub = $this->getStub('services/custom.stub');
        $actionsStub = str_replace('{{CRUD-SERVICE::NAMESPACE}}', 'Blog', $actionsStub);
        $actionsStub = str_replace('{{CRUD-SERVICE::MODEL}}', 'Post', $actionsStub);
        $actionsStub = str_replace('{{CRUD-SERVICE::ACTION}}', 'Actions', $actionsStub);

        self::assertEquals($actionsStub, file_get_contents($actions));
    }

    #[Test]
    public function actionsExists(): void
    {
        $actions = app_path('Services/Blog/Post/PostActionsService.php');

        self::assertFileDoesNotExist($actions);

        $this->artisan('valravn:service blog posts -a')
            ->expectsOutput('CRUD service created.')
            ->doesntExpectOutput('CRUD service exists or could be created.')
            ->doesntExpectOutput('Relations service created.')
            ->doesntExpectOutput('Relations service exists or could be created.')
            ->expectsOutput('Actions service created.')
            ->doesntExpectOutput('Actions service exists or could be created.')
            ->assertSuccessful();

        $this->artisan('valravn:service blog posts -a')
            ->doesntExpectOutput('CRUD service created.')
            ->expectsOutput('CRUD service exists or could be created.')
            ->doesntExpectOutput('Relations service created.')
            ->doesntExpectOutput('Relations service exists or could be created.')
            ->doesntExpectOutput('Actions service created.')
            ->expectsOutput('Actions service exists or could be created.')
            ->assertSuccessful();

        self::assertFileExists($actions);
    }
}
