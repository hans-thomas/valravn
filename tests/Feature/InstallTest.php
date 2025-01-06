<?php

namespace Hans\Valravn\Tests\Feature;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallTest extends TestCase
{
    protected function tearDown(): void
    {
        file_put_contents(base_path('bootstrap/providers.php'), str_replace(
            '    App\\Providers\\RepositoryServiceProvider::class,'.PHP_EOL,
            '',
            file_get_contents(base_path('bootstrap/providers.php'))
        ));

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function install(): void
    {
        $configFile = config_path('valravn.php');
        $serviceProviderFile = app_path('Providers/RepositoryServiceProvider.php');
        File::delete([$configFile, $serviceProviderFile]);

        self::assertFileDoesNotExist($configFile);
        self::assertFileDoesNotExist($serviceProviderFile);

        Artisan::call('valravn:install');

        self::assertFileExists($configFile);
        self::assertFileExists($serviceProviderFile);

        $serviceProviderContent = file_get_contents(__DIR__.'/../../src/stubs/RepositoryServiceProvider.stub');

        self::assertEquals(
            $serviceProviderContent,
            file_get_contents($serviceProviderFile)
        );

        self::assertStringContainsString(
            'App\\Providers\\RepositoryServiceProvider::class',
            file_get_contents(base_path('bootstrap/providers.php'))
        );
    }
}
