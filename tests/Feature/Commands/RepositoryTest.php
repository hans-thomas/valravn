<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class RepositoryTest extends TestCase
{
    #[Test]
    public function repository(): void
    {
        $contract = app_path('Repositories/Contracts/Blog/IPostRepository.php');
        $repository = app_path('Repositories/Blog/PostRepository.php');

        self::assertFileDoesNotExist($contract);
        self::assertFileDoesNotExist($repository);

        Artisan::call('valravn:repository blog posts');

        self::assertFileExists($contract);

        $contractStub = $this->getStub('repositories/repository-contract.stub');
        $contractStub = str_replace('{{IREPOSITORY::NAMESPACE}}', 'Blog', $contractStub);
        $contractStub = str_replace('{{IREPOSITORY::MODEL}}', 'Post', $contractStub);

        self::assertEquals($contractStub, file_get_contents($contract));

        self::assertFileExists($repository);

        $repositoryStub = $this->getStub('repositories/repository.stub');
        $repositoryStub = str_replace('{{REPOSITORY::NAMESPACE}}', 'Blog', $repositoryStub);
        $repositoryStub = str_replace('{{REPOSITORY::MODEL}}', 'Post', $repositoryStub);

        self::assertEquals($repositoryStub, file_get_contents($repository));
    }
}
