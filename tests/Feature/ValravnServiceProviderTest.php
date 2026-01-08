<?php

namespace Hans\Valravn\Tests\Feature;

use Hans\Valravn\Exceptions\Package\PublishedVersionOutDatedException;
use Hans\Valravn\Tests\TestCase;
use Hans\Valravn\ValravnServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;

class ValravnServiceProviderTest extends TestCase
{
    protected function tearDown(): void
    {
        $configFile = __DIR__.'/../../config/config.php';
        $publishedVersion = config('valravn.config_version');
        $newVersion = str_split($publishedVersion, strpos($publishedVersion, '.'))[0].'.999';

        // revert 'config_version' key
        $configContent = file_get_contents($configFile);
        $configContent = str_replace($newVersion, $publishedVersion, $configContent);
        file_put_contents($configFile, $configContent);

        $this->cleanUp([
            base_path('config/valravn.php'),
            base_path('routes/app/blog.php'),
            base_path('routes/web.php'),
        ]);

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

        $configFilePath = __DIR__.'/../../config/config.php';
        self::assertFileExists($configFilePath);


        if (ValravnServiceProvider::getConfigVersion() !== '1.0.1') {
            $newConfigContent = str_replace(ValravnServiceProvider::getConfigVersion(), '1.0.1', file_get_contents($configFilePath));
            file_put_contents($configFilePath, $newConfigContent);
        }
        self::assertEquals('1.0.1', ValravnServiceProvider::getConfigVersion());

        $publishedVersion = config('valravn.config_version');

        $newVersion = str_split($publishedVersion, strpos($publishedVersion, '.'))[0].'.999';
        $newConfigContent = file_get_contents($configFilePath);
        $newConfigContent = str_replace($publishedVersion, $newVersion, $newConfigContent);
        file_put_contents($configFilePath, $newConfigContent);

        self::assertEquals($newVersion, ValravnServiceProvider::getConfigVersion());

        $this->expectException(PublishedVersionOutDatedException::class);

        app(ValravnServiceProvider::class, ['app' => $this->app])->boot();
    }

    #[Test]
    public function registeringRoute(): void
    {
        self::assertFalse(Route::has('blog.posts.index'));

        $fs = new Filesystem();
        $fs->ensureDirectoryExists(base_path('routes/app'));
        $fs->put(base_path('routes/app/blog.php'), file_get_contents(__DIR__.'/../Instances/routes/blog.stub'));
        $fs->put(base_path('routes/web.php'), file_get_contents(__DIR__.'/../Instances/routes/blog.stub'));

        self::assertFileExists(base_path('routes/app/blog.php'));

        app(ValravnServiceProvider::class, ['app' => $this->app])->boot();

        self::assertTrue(Route::has('blog.posts.index'));
        self::assertFalse(Route::has('web.posts.index'));
    }
}
