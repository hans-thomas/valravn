<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class ResourcesTest extends TestCase
{
    #[Test]
    public function resources(): void
    {
        $resource = app_path('Http/Resources/V1/Blog/Post/PostResource.php');
        $collection = app_path('Http/Resources/V1/Blog/Post/PostCollection.php');

        self::assertFileDoesNotExist($resource);
        self::assertFileDoesNotExist($collection);

        Artisan::call('valravn:resources blog posts');

        self::assertFileExists($resource);

        $resourceStub = $this->getStub('resources/resource.stub');
        $resourceStub = str_replace('{{RESOURCE::VERSION}}', 'V1', $resourceStub);
        $resourceStub = str_replace('{{RESOURCE::NAMESPACE}}', 'Blog', $resourceStub);
        $resourceStub = str_replace('{{RESOURCE::MODEL}}', 'Post', $resourceStub);
        $resourceStub = str_replace('{{RESOURCE::PLURAL}}', 'posts', $resourceStub);

        self::assertEquals($resourceStub, file_get_contents($resource));

        self::assertFileExists($collection);

        $collectionStub = $this->getStub('resources/collection.stub');
        $collectionStub = str_replace('{{COLLECTION::VERSION}}', 'V1', $collectionStub);
        $collectionStub = str_replace('{{COLLECTION::NAMESPACE}}', 'Blog', $collectionStub);
        $collectionStub = str_replace('{{COLLECTION::MODEL}}', 'Post', $collectionStub);
        $collectionStub = str_replace('{{COLLECTION::PLURAL}}', 'posts', $collectionStub);

        self::assertEquals($collectionStub, file_get_contents($collection));
    }

    #[Test]
    public function version(): void
    {
        $resource = app_path('Http/Resources/V2/Blog/Post/PostResource.php');
        $collection = app_path('Http/Resources/V2/Blog/Post/PostCollection.php');

        self::assertFileDoesNotExist($resource);
        self::assertFileDoesNotExist($collection);

        Artisan::call('valravn:resources blog posts --v 2');

        self::assertFileExists($resource);
        self::assertFileExists($collection);
    }
}
