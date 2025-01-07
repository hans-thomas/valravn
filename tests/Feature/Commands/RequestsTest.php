<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class RequestsTest extends TestCase
{
    protected function tearDown(): void
    {
        $this->clearUp([
            app_path('Http/Requests/V3/Blog/Post'),
            app_path('Http/Requests/V5/Blog/Post'),
        ]);

        parent::tearDown();
    }

    #[Test]
    public function requests(): void
    {
        $store = app_path('Http/Requests/V1/Blog/Post/PostStoreRequest.php');
        $update = app_path('Http/Requests/V1/Blog/Post/PostUpdateRequest.php');

        self::assertFileDoesNotExist($store);
        self::assertFileDoesNotExist($update);

        Artisan::call('valravn:requests blog posts');

        self::assertFileExists($store);
        self::assertFileExists($update);

        $storeStub = $this->getStub('requests/crud.stub');
        $storeStub = str_replace('{{REQUEST::VERSION}}', 'V1', $storeStub);
        $storeStub = str_replace('{{REQUEST::NAMESPACE}}', 'Blog', $storeStub);
        $storeStub = str_replace('{{REQUEST::MODEL}}', 'Post', $storeStub);
        $storeStub = str_replace('{{REQUEST::ACTION}}', 'Store', $storeStub);

        self::assertEquals($storeStub, file_get_contents($store));

        $updateStub = $this->getStub('requests/crud.stub');
        $updateStub = str_replace('{{REQUEST::VERSION}}', 'V1', $updateStub);
        $updateStub = str_replace('{{REQUEST::NAMESPACE}}', 'Blog', $updateStub);
        $updateStub = str_replace('{{REQUEST::MODEL}}', 'Post', $updateStub);
        $updateStub = str_replace('{{REQUEST::ACTION}}', 'Update', $updateStub);

        self::assertEquals($updateStub, file_get_contents($update));
    }

    #[Test]
    public function version(): void
    {
        $store = app_path('Http/Requests/V3/Blog/Post/PostStoreRequest.php');
        $update = app_path('Http/Requests/V3/Blog/Post/PostUpdateRequest.php');

        self::assertFileDoesNotExist($store);
        self::assertFileDoesNotExist($update);

        Artisan::call('valravn:requests blog posts --v 3');

        self::assertFileExists($store);
        self::assertFileExists($update);

        $storeStub = $this->getStub('requests/crud.stub');
        $storeStub = str_replace('{{REQUEST::VERSION}}', 'V3', $storeStub);
        $storeStub = str_replace('{{REQUEST::NAMESPACE}}', 'Blog', $storeStub);
        $storeStub = str_replace('{{REQUEST::MODEL}}', 'Post', $storeStub);
        $storeStub = str_replace('{{REQUEST::ACTION}}', 'Store', $storeStub);

        self::assertEquals($storeStub, file_get_contents($store));

        $updateStub = $this->getStub('requests/crud.stub');
        $updateStub = str_replace('{{REQUEST::VERSION}}', 'V3', $updateStub);
        $updateStub = str_replace('{{REQUEST::NAMESPACE}}', 'Blog', $updateStub);
        $updateStub = str_replace('{{REQUEST::MODEL}}', 'Post', $updateStub);
        $updateStub = str_replace('{{REQUEST::ACTION}}', 'Update', $updateStub);

        self::assertEquals($updateStub, file_get_contents($update));
    }

    #[Test]
    public function batchUpdate(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostBatchUpdateRequest.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:requests blog posts --batch-update');

        self::assertFileExists($file);

        $batchUpdateStub = $this->getStub('requests/batch-update.stub');
        $batchUpdateStub = str_replace('{{REQUEST::VERSION}}', 'V1', $batchUpdateStub);
        $batchUpdateStub = str_replace('{{REQUEST::NAMESPACE}}', 'Blog', $batchUpdateStub);
        $batchUpdateStub = str_replace('{{REQUEST::MODEL}}', 'Post', $batchUpdateStub);

        self::assertEquals($batchUpdateStub, file_get_contents($file));
    }

    #[Test]
    public function batchUpdateWithVersion(): void
    {
        $file = app_path('Http/Requests/V5/Blog/Post/PostBatchUpdateRequest.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:requests blog posts --batch-update --v 5');

        self::assertFileExists($file);

        $batchUpdateStub = $this->getStub('requests/batch-update.stub');
        $batchUpdateStub = str_replace('{{REQUEST::VERSION}}', 'V5', $batchUpdateStub);
        $batchUpdateStub = str_replace('{{REQUEST::NAMESPACE}}', 'Blog', $batchUpdateStub);
        $batchUpdateStub = str_replace('{{REQUEST::MODEL}}', 'Post', $batchUpdateStub);

        self::assertEquals($batchUpdateStub, file_get_contents($file));
    }
}
