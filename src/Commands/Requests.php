<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\RequestService;
use Illuminate\Console\Command;
use League\Flysystem\FilesystemException;
use Symfony\Component\Console\Helper\ProgressBar;

class Requests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        valravn:requests 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{--v=1 : Version of the entity}
		{--b|batch-update : Create batch update request}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate basic request classes.';

    /**
     * Execute the console command.
     *
     * @throws FilesystemException
     *
     * @return int
     */
    public function handle(): int
    {
        $service = new RequestService(
            $this->argument('namespace'),
            $this->argument('name'),
            $this->option('v')
        );

        $this->withProgressBar(2, function (ProgressBar $progressBar) use ($service) {
            $this->newLine();
            if ($service->createStoreRequest()) {
                $this->info('Store request created.');
            } else {
                $this->error('Store request exists or could not be created.');
            }
            $progressBar->advance();
            $this->newLine();

            if ($service->createUpdateRequest()) {
                $this->info('Update request created.');
            } else {
                $this->error('Update request exists or could not be created.');
            }
            $progressBar->advance();
            $this->newLine();

            if ($this->option('batch-update')) {
                if ($service->createBatchUpdateRequest()) {
                    $this->info('Batch-Update request created.');
                } else {
                    $this->error('Batch-Update request exists or could not be created.');
                }
            }
            $progressBar->advance();
            $this->newLine();
        });

        return self::SUCCESS;
    }
}
