<?php

namespace Hans\Valravn\Tests\Feature;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class InstallTest extends TestCase
{
    private string $configFile;
    private string $serviceProviderFile;
    private string $providersFile;

    #[Test]
    public function install(): void
    {
        $this->configFile = config_path('valravn.php');
        $this->serviceProviderFile = app_path('Providers/RepositoryServiceProvider.php');

        self::assertFileDoesNotExist($this->configFile);
        self::assertFileDoesNotExist($this->serviceProviderFile);

        Artisan::call('valravn:install');

        self::assertFileExists($this->configFile);
        self::assertFileExists($this->serviceProviderFile);

        $serviceProviderContent = file_get_contents(__DIR__.'/../../src/stubs/RepositoryServiceProvider.stub');

        self::assertEquals(
            $serviceProviderContent,
            file_get_contents($this->serviceProviderFile)
        );

        self::assertStringContainsString(
            'App\\Providers\\RepositoryServiceProvider::class',
            file_get_contents($this->providersFile)
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (version_compare($this->app->version(), '11', '>=')) {
            $this->providersFile = base_path('bootstrap/providers.php');
        } elseif (version_compare($this->app->version(), '10', '>=')) {
            $this->providersFile = config_path('app.php');
        }
    }

    protected function tearDown(): void
    {
        file_put_contents($this->providersFile, str_replace(
            '    App\\Providers\\RepositoryServiceProvider::class,'.PHP_EOL,
            '',
            file_get_contents($this->providersFile)
        ));

        $this->configFile = config_path('valravn.php');
        $this->serviceProviderFile = app_path('Providers/RepositoryServiceProvider.php');

        $this->cleanUp([$this->configFile, $this->serviceProviderFile]);

        parent::tearDown();
    }
}
