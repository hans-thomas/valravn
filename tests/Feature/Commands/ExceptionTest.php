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
             ->assertExitCode(0);

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
    public function compactFormException(): void
    {
        $fullException = app_path('Exceptions/Blog/Post/PostException.php');
        $compactException = app_path('Exceptions/Blog/Post/NotFoundException.php');

        self::assertFileDoesNotExist($fullException);
        self::assertFileDoesNotExist($compactException);

        $this->artisan('valravn:exception blog posts BPEcx --compact=notFound')
             ->expectsOutput('Exception class created.')
             ->assertExitCode(0);

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
    public function compactFormExceptionWithoutParam(): void
    {
        $fullException = app_path('Exceptions/Blog/Post/PostException.php');
        $compactException = app_path('Exceptions/Blog/Post/NotFoundException.php');

        self::assertFileDoesNotExist($fullException);
        self::assertFileDoesNotExist($compactException);

        $this->artisan('valravn:exception blog posts BPEcx')
             ->expectsConfirmation('Should create a compact exception?', 'yes')
             ->expectsQuestion('What should be its name?', 'notFound')
             ->expectsOutput('Exception class created.')
             ->assertExitCode(0);

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
    public function compactFormExceptionWithoutParamWithExceptionPostfix(): void
    {
        $fullException = app_path('Exceptions/Blog/Post/PostException.php');
        $compactException = app_path('Exceptions/Blog/Post/NotFoundException.php');

        self::assertFileDoesNotExist($fullException);
        self::assertFileDoesNotExist($compactException);

        $this->artisan('valravn:exception blog posts BPEcx')
             ->expectsConfirmation('Should create a compact exception?', 'yes')
             ->expectsQuestion('What should be its name?', 'notFoundException')
             ->expectsOutput('Exception class created.')
             ->assertExitCode(0);

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
             ->expectsOutput('Exception class created.')
             ->assertExitCode(0);

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
             ->expectsQuestion('Should create a compact exception?', false)
             ->expectsOutput('Exception class created.')
             ->assertExitCode(0);

        self::assertFileExists($fullException);
        self::assertFileDoesNotExist($compactException);
    }
}
