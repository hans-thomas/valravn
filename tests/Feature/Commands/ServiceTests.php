<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;

class ServiceTests extends TestCase
{
    #[Test]
    public function crud(): void
    {
        $crud = app_path('Services/Blog/Post/PostCrudService.php');

        self::assertFileDoesNotExist($crud);

        Artisan::call('valravn:service blog posts');

        self::assertFileExists($crud);

        $crudStub = $this->getStub('services/crud.stub');
        $crudStub = str_replace('{{CRUD-SERVICE::NAMESPACE}}', 'Blog', $crudStub);
        $crudStub = str_replace('{{CRUD-SERVICE::MODEL}}', 'Post', $crudStub);

        self::assertEquals($crudStub, file_get_contents($crud));
    }

    #[Test]
    public function relations(): void
    {
        $relations = app_path('Services/Blog/Post/PostRelationsService.php');

        self::assertFileDoesNotExist($relations);

        Artisan::call('valravn:service blog posts -r');

        self::assertFileExists($relations);

        $relationsStub = $this->getStub('services/relations.stub');
        $relationsStub = str_replace('{{CRUD-SERVICE::NAMESPACE}}','Blog', $relationsStub);
        $relationsStub = str_replace('{{CRUD-SERVICE::MODEL}}','Post', $relationsStub);

        self::assertEquals($relationsStub, file_get_contents($relations));
    }

    #[Test]
    public function actions(): void
    {
        $actions = app_path('Services/Blog/Post/PostActionsService.php');

        File::delete($actions);
        self::assertFileDoesNotExist($actions);

        Artisan::call('valravn:service blog posts -a');

        self::assertFileExists($actions);

        $actionsStub = $this->getStub('services/actions.stub');
        $actionsStub = str_replace('{{CRUD-SERVICE::NAMESPACE}}','Blog', $actionsStub);
        $actionsStub = str_replace('{{CRUD-SERVICE::MODEL}}','Post', $actionsStub);

        self::assertEquals($actionsStub, file_get_contents($actions));
    }
}
