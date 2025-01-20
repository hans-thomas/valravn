<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ExceptionTest extends TestCase
{
    #[Test]
    public function fullFormException(): void
    {
        $exception = app_path('Exceptions/Blog/Post/PostException.php');

        self::assertFileDoesNotExist($exception);

        $this->artisan('valravn:exception blog posts BPEcx')
             ->expectsConfirmation('Should create a compact exception?')
             ->expectsOutput('Exception class created.')
             ->doesntExpectOutput('Exception class exists or could no be created.')
             ->doesntExpectOutput('Compact exception class created.')
             ->doesntExpectOutput('Compact exception class exists or could no be created.')
             ->assertSuccessful();

        self::assertFileExists($exception);

        $exception_file = $this->getStub('exceptions/fullFormException.stub');
        $exception_file = str_replace('{{ENTITY::NAMESPACE}}', 'Blog', $exception_file);
        $exception_file = str_replace('{{ENTITY::NAME}}', 'Post', $exception_file);
        $exception_file = str_replace('{{ENTITY::CODE}}', 'BPEcx', $exception_file);

        self::assertEquals(
            $exception_file,
            file_get_contents($exception)
        );
    }

    #[Test]
    public function fullFormExceptionExists(): void
    {
        $exception = app_path('Exceptions/Blog/Post/PostException.php');

        self::assertFileDoesNotExist($exception);

        $this->artisan('valravn:exception blog posts BPEcx')
             ->expectsConfirmation('Should create a compact exception?')
             ->expectsOutput('Exception class created.')
             ->doesntExpectOutput('Exception class exists or could no be created.')
             ->doesntExpectOutput('Compact exception class created.')
             ->doesntExpectOutput('Compact exception class exists or could no be created.')
             ->assertSuccessful();

        $this->artisan('valravn:exception blog posts BPEcx')
             ->expectsConfirmation('Should create a compact exception?')
             ->doesntExpectOutput('Exception class created.')
             ->expectsOutput('Exception class exists or could no be created.')
             ->doesntExpectOutput('Compact exception class created.')
             ->doesntExpectOutput('Compact exception class exists or could no be created.')
             ->assertSuccessful();

        self::assertFileExists($exception);
    }

    #[Test]
    public function compactFormException(): void
    {
        $fullException = app_path('Exceptions/Blog/Post/PostException.php');
        $compactException = app_path('Exceptions/Blog/Post/NotFoundException.php');

        self::assertFileDoesNotExist($fullException);
        self::assertFileDoesNotExist($compactException);

        $this->artisan('valravn:exception blog posts BPEcx --compact=notFound')
             ->doesntExpectOutput('Exception class created.')
             ->doesntExpectOutput('Exception class exists or could no be created.')
             ->expectsOutput('Compact exception class created.')
             ->doesntExpectOutput('Compact exception class exists or could no be created.')
             ->assertSuccessful();

        self::assertFileDoesNotExist($fullException);
        self::assertFileExists($compactException);

        $exception_file = $this->getStub('exceptions/compactFormException.stub');
        $exception_file = str_replace('{{ENTITY::NAMESPACE}}', 'Blog', $exception_file);
        $exception_file = str_replace('{{ENTITY::NAME}}', 'NotFound', $exception_file);
        $exception_file = str_replace('{{ENTITY::CODE}}', 'BPEcx', $exception_file);

        self::assertEquals(
            $exception_file,
            file_get_contents($compactException)
        );
    }

    #[Test]
    public function compactFormExceptionExists(): void
    {
        $fullException = app_path('Exceptions/Blog/Post/PostException.php');
        $compactException = app_path('Exceptions/Blog/Post/NotFoundException.php');

        self::assertFileDoesNotExist($fullException);
        self::assertFileDoesNotExist($compactException);

        $this->artisan('valravn:exception blog posts BPEcx --compact=notFound')
             ->doesntExpectOutput('Exception class created.')
             ->doesntExpectOutput('Exception class exists or could no be created.')
             ->expectsOutput('Compact exception class created.')
             ->doesntExpectOutput('Compact exception class exists or could no be created.')
             ->assertSuccessful();

        $this->artisan('valravn:exception blog posts BPEcx --compact=notFound')
             ->doesntExpectOutput('Exception class created.')
             ->doesntExpectOutput('Exception class exists or could no be created.')
             ->doesntExpectOutput('Compact exception class created.')
             ->expectsOutput('Compact exception class exists or could no be created.')
             ->assertSuccessful();

        self::assertFileDoesNotExist($fullException);
        self::assertFileExists($compactException);
    }

    #[Test]
    public function compactFormExceptionWithoutParam(): void
    {
        $fullException = app_path('Exceptions/Blog/Post/PostException.php');
        $compactException = app_path('Exceptions/Blog/Post/NotFoundException.php');

        self::assertFileDoesNotExist($fullException);
        self::assertFileDoesNotExist($compactException);

        $this->artisan('valravn:exception blog posts BPEcx')
             ->expectsConfirmation('Should create a compact exception?', 'yes')
             ->expectsQuestion('What should be its name?', 'notFound')
             ->doesntExpectOutput('Exception class created.')
             ->doesntExpectOutput('Exception class exists or could no be created.')
             ->expectsOutput('Compact exception class created.')
             ->doesntExpectOutput('Compact exception class exists or could no be created.')
             ->assertSuccessful();

        self::assertFileDoesNotExist($fullException);
        self::assertFileExists($compactException);

        $exception_file = $this->getStub('exceptions/compactFormException.stub');
        $exception_file = str_replace('{{ENTITY::NAMESPACE}}', 'Blog', $exception_file);
        $exception_file = str_replace('{{ENTITY::NAME}}', 'NotFound', $exception_file);
        $exception_file = str_replace('{{ENTITY::CODE}}', 'BPEcx', $exception_file);

        self::assertEquals(
            $exception_file,
            file_get_contents($compactException)
        );
    }

    #[Test]
    public function compactFormExceptionWithoutParamWithEmptyCompactName(): void
    {
        $fullException = app_path('Exceptions/Blog/Post/PostException.php');
        $compactException = app_path('Exceptions/Blog/Post/NotFoundException.php');

        self::assertFileDoesNotExist($fullException);
        self::assertFileDoesNotExist($compactException);

        $this->expectExceptionMessage('The name of the compact exception can not be empty.');

        $this->artisan('valravn:exception blog posts BPEcx')
            ->expectsConfirmation('Should create a compact exception?', 'yes')
            ->expectsQuestion('What should be its name?', '')
            ->doesntExpectOutput('Exception class created.')
            ->doesntExpectOutput('Exception class exists or could no be created.')
            ->doesntExpectOutput('Compact exception class created.')
            ->doesntExpectOutput('Compact exception class exists or could no be created.')
            ->assertFailed();

        self::assertFileDoesNotExist($fullException);
        self::assertFileDoesNotExist($compactException);
    }

    #[Test]
    public function compactFormExceptionWithoutParamWithExceptionPostfix(): void
    {
        $fullException = app_path('Exceptions/Blog/Post/PostException.php');
        $compactException = app_path('Exceptions/Blog/Post/NotFoundException.php');

        self::assertFileDoesNotExist($fullException);
        self::assertFileDoesNotExist($compactException);

        $this->artisan('valravn:exception blog posts BPEcx')
            ->expectsConfirmation('Should create a compact exception?', 'yes')
            ->expectsQuestion('What should be its name?', 'notFoundException')
            ->doesntExpectOutput('Exception class created.')
            ->doesntExpectOutput('Exception class exists or could no be created.')
            ->expectsOutput('Compact exception class created.')
            ->doesntExpectOutput('Compact exception class exists or could no be created.')
            ->assertSuccessful();

        self::assertFileDoesNotExist($fullException);
        self::assertFileExists($compactException);

        $exception_file = $this->getStub('exceptions/compactFormException.stub');
        $exception_file = str_replace('{{ENTITY::NAMESPACE}}', 'Blog', $exception_file);
        $exception_file = str_replace('{{ENTITY::NAME}}', 'NotFound', $exception_file);
        $exception_file = str_replace('{{ENTITY::CODE}}', 'BPEcx', $exception_file);

        self::assertEquals(
            $exception_file,
            file_get_contents($compactException)
        );
    }

    #[Test]
    public function compactFormExceptionWithEmptyCompact(): void
    {
        $fullException = app_path('Exceptions/Blog/Post/PostException.php');
        $compactException = app_path('Exceptions/Blog/Post/NotFoundException.php');

        self::assertFileDoesNotExist($fullException);
        self::assertFileDoesNotExist($compactException);

        $this->artisan('valravn:exception blog posts BPEcx --compact=')
            ->expectsQuestion('What should be its name?', 'notFound')
            ->doesntExpectOutput('Exception class created.')
            ->doesntExpectOutput('Exception class exists or could no be created.')
            ->expectsOutput('Compact exception class created.')
            ->doesntExpectOutput('Compact exception class exists or could no be created.')
            ->assertSuccessful();

        self::assertFileDoesNotExist($fullException);
        self::assertFileExists($compactException);
    }

    #[Test]
    public function compactFormExceptionWithNullCompact(): void
    {
        $fullException = app_path('Exceptions/Blog/Post/PostException.php');
        $compactException = app_path('Exceptions/Blog/Post/NotFoundException.php');

        self::assertFileDoesNotExist($fullException);
        self::assertFileDoesNotExist($compactException);

        $this->artisan('valravn:exception blog posts BPEcx --compact')
            ->expectsConfirmation('Should create a compact exception?')
            ->expectsOutput('Exception class created.')
            ->doesntExpectOutput('Exception class exists or could no be created.')
            ->doesntExpectOutput('Compact exception class created.')
            ->doesntExpectOutput('Compact exception class exists or could no be created.')
            ->assertSuccessful();

        self::assertFileExists($fullException);
        self::assertFileDoesNotExist($compactException);
    }
}
