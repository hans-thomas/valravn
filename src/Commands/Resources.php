<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\ResourceService;
use Illuminate\Console\Command;
use League\Flysystem\FilesystemException;
use Symfony\Component\Console\Helper\ProgressBar;

class Resources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:resources 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{--v=1 : Version of the entity}
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate resource and resource collection classes.';

    /**
     * Execute the console command.
     *
     * @throws FilesystemException
     *
     * @return int
     */
    public function handle(): int
    {
        $service = new ResourceService($this->argument('namespace'), $this->argument('name'), $this->option('v'));

        $this->withProgressBar(2, function (ProgressBar $progress) use ($service) {
            $this->newLine();
            if ($service->createResource()) {
                $this->info('Resource class created.');
            } else {
                $this->error('Resource class exists or could not be created.');
            }
            $progress->advance();
            $this->newLine();

            if ($service->createCollection()) {
                $this->info('ResourceCollection class created.');
            } else {
                $this->error('ResourceCollection class exists or could not be created.');
            }
            $progress->advance();
            $this->newLine();
        });

        return self::SUCCESS;
    }
}
