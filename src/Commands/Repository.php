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
        $this->withProgressBar(2, function (ProgressBar $progress) {
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
        });

        return self::SUCCESS;
    }
}
