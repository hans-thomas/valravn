<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
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

        $this->artisan('valravn:repository blog posts')
             ->expectsOutput('Contract class created.')
             ->expectsOutput('Repository class created.')
             ->assertSuccessful();

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

    #[Test]
    public function repositoryExists(): void
    {
        $contract = app_path('Repositories/Contracts/Blog/IPostRepository.php');
        $repository = app_path('Repositories/Blog/PostRepository.php');

        self::assertFileDoesNotExist($contract);
        self::assertFileDoesNotExist($repository);

        $this->artisan('valravn:repository blog posts')
             ->expectsOutput('Contract class created.')
             ->expectsOutput('Repository class created.')
             ->assertSuccessful();

        $this->artisan('valravn:repository blog posts')
             ->expectsOutput('Contract class exists or could not be created.')
             ->expectsOutput('Repository class exists or could not be created.')
             ->assertSuccessful();

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
