<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\RepositoryService;
use Illuminate\Console\Command;
use League\Flysystem\FilesystemException;
use Symfony\Component\Console\Helper\ProgressBar;

class Repository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:repository 
		{namespace : Group of the entity}
		{name : Name of the entity}
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate repository contract and repository classes.';

    /**
     * Execute the console command.
     *
     * @throws FilesystemException
     *
     * @return int
     */
    public function handle(): int
    {
        $this->withProgressBar(3, function (ProgressBar $progress) {
            $this->newLine();
            $service = new RepositoryService($this->argument('namespace'), $this->argument('name'));

            if ($service->createContract()) {
                $this->info('Contract class created.');
            } else {
                $this->error('Contract class exists or could not be created.');
            }
            $progress->advance();
            $this->newLine();

            if ($service->createClass()) {
                $this->info('Repository class created.');
            } else {
                $this->error('Repository class exists or could not be created.');
            }
            $progress->advance();
            $this->newLine();

            $repositoryProvider = app_path('Providers/RepositoryServiceProvider.php');
            if (!file_exists($repositoryProvider)) {
                $this->warn('Could not find the repository provider class. Don\'t forget to register the repository class.');

                return;
            }

            $matches = [];
            $repositoryProviderContent = file_get_contents($repositoryProvider);

            // Register binding
            preg_match_all('/\$this->app->(bind|singleton)\([a-zA-Z:,\n ]*\);/m', $repositoryProviderContent, $matches);
            if (empty($matches[0])) {
                preg_match_all('/public function register\(\) \{[\n \/a-zA-Z]*/m', $repositoryProviderContent, $matches);
            }

            $lastBind = array_pop($matches[0]);
            $newBind = $lastBind;
            $newBind .= PHP_EOL;
            $newBind .= '$this->app->bind(I'.$service->getName().'Repository::class, '.$service->getName().'Repository::class);';
            $newBind .= PHP_EOL;

            $repositoryProviderContent = str_replace($lastBind, $newBind, $repositoryProviderContent);

            // Import classes
            preg_match_all('/use[ a-zA-Z\\\]+;/m', $repositoryProviderContent, $matches);
            if (empty($matches[0])) {
                preg_match_all('/namespace [ a-zA-Z\\\]+;/m', $repositoryProviderContent, $matches);
                $matches[0][0] .= PHP_EOL;
            }

            $lastImport = array_pop($matches[0]);
            $newImports = $lastImport;
            $newImports .= PHP_EOL;
            $newImports .= 'use App\Repositories\Contracts\\'.$service->getNamespace().'\I'.$service->getName().'Repository;'.PHP_EOL;
            $newImports .= 'use App\Repositories\\'.$service->getNamespace().'\\'.$service->getName().'Repository;';

            $repositoryProviderContent = str_replace($lastImport, $newImports, $repositoryProviderContent);
            if (file_put_contents($repositoryProvider, $repositoryProviderContent)) {
                $this->info('Repository registered in RepositoryServiceProvider.');

                $progress->advance();
                $this->newLine();
            }

            $this->error('Failed to write repository class registration in RepositoryServiceProvider.');
        });

        return self::SUCCESS;
    }
}
