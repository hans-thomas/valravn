<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ExceptionTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function fullFormException(): void
    {
        $exception = app_path('Exceptions/Blog/Post/PostException.php');
        File::delete($exception);

        self::assertFileDoesNotExist($exception);

        Artisan::call('valravn:exception blog posts BPEcx');

        self::assertFileExists($exception);

        $exception_file = file_get_contents(__DIR__.'/../../../src/Commands/stubs/exceptions/fullFormException.stub');
        $exception_file = str_replace('{{ENTITY::NAMESPACE}}', 'Blog', $exception_file);
        $exception_file = str_replace('{{ENTITY::NAME}}', 'Post', $exception_file);
        $exception_file = str_replace('{{ENTITY::CODE}}', 'BPEcx', $exception_file);

        self::assertEquals(
            $exception_file,
            file_get_contents($exception)
        );
    }

     /**
     * @test
     *
     * @return void
     */
    public function compactFormException(): void
    {
        $exception = app_path('Exceptions/Blog/Post/NotFoundException.php');
        File::delete($exception);

        self::assertFileDoesNotExist($exception);

        Artisan::call('valravn:exception blog posts BPEcx --compact=notFound');

        self::assertFileExists($exception);

        $exception_file = file_get_contents(__DIR__.'/../../../src/Commands/stubs/exceptions/compactFormException.stub');
        $exception_file = str_replace('{{ENTITY::NAMESPACE}}', 'Blog', $exception_file);
        $exception_file = str_replace('{{ENTITY::NAME}}', 'NotFound', $exception_file);
        $exception_file = str_replace('{{ENTITY::CODE}}', 'BPEcx', $exception_file);

        self::assertEquals(
            $exception_file,
            file_get_contents($exception)
        );
    }

     /**
     * @test
     *
     * @return void
     */
    public function compactFormExceptionWithEmptyCompact(): void
    {
        $exception = app_path('Exceptions/Blog/Post/PostException.php');
        File::delete($exception);

        self::assertFileDoesNotExist($exception);

        // Automatically switch to full form
        Artisan::call('valravn:exception blog posts BPEcx --compact=');

        self::assertFileExists($exception);
    }

     /**
     * @test
     *
     * @return void
     */
    public function compactFormExceptionWithNullCompact(): void
    {
        $exception = app_path('Exceptions/Blog/Post/PostException.php');
        File::delete($exception);

        self::assertFileDoesNotExist($exception);

        // Automatically switch to full form
        Artisan::call('valravn:exception blog posts BPEcx --compact');

        self::assertFileExists($exception);
    }

}
