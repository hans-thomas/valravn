<?php

namespace Hans\Valravn\Tests\Feature;

use Hans\Valravn\Exceptions\Package\PublishedVersionOutDatedException;
use Hans\Valravn\Tests\TestCase;
use Hans\Valravn\ValravnServiceProvider;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;

class ValravnServiceProviderTest extends TestCase
{
    protected function tearDown(): void
    {
        $configFile = __DIR__.'/../../config/config.php';
        $publishedVersion = config('valravn.config_version');
        $newVersion = str_split($publishedVersion, strrpos($publishedVersion, '.'))[0].'.999';

        // revert 'config_version' key
        $configContent = file_get_contents($configFile);
        $configContent = str_replace($newVersion, $publishedVersion, $configContent);
        file_put_contents($configFile, $configContent);

        File::delete(base_path('config/valravn.php'));
        self::assertFileDoesNotExist(base_path('config/valravn.php'));

        parent::tearDown();
    }

    #[Test]
    public function publishedConfigFileVersion()
    {
        $publishedVersion = config('valravn.config_version');
        $configVersion = require __DIR__.'/../../config/config.php';
        $configVersion = $configVersion['config_version'];

        self::assertGreaterThanOrEqual($configVersion, $publishedVersion);
    }

    #[Test]
    public function publishedConfigFileVersionIsOutdated()
    {
        $this->artisan('vendor:publish', ['--tag' => 'valravn-config']);
        self::assertFileExists(base_path('config/valravn.php'));

        $configFile = __DIR__.'/../../config/config.php';
        $publishedVersion = config('valravn.config_version');

        $newVersion = str_split($publishedVersion, strrpos($publishedVersion, '.'))[0].'.999';
        // change 'config_version' key
        $newConfigContent = file_get_contents($configFile);
        $newConfigContent = str_replace($publishedVersion, $newVersion, $newConfigContent);
        file_put_contents($configFile, $newConfigContent);

        $this->expectException(PublishedVersionOutDatedException::class);

        $provider = new ValravnServiceProvider($this->app);
        $provider->boot();
    }
}
