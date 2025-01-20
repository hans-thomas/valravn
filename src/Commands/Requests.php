<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\RequestService;
use Illuminate\Console\Command;
use League\Flysystem\FilesystemException;
use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

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
		{--batch-update : Create batch update request}
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
     * @return int
     * @throws FilesystemException
     */
    public function handle(): int
    {
        $service = new RequestService(
            $this->argument('namespace'),
            $this->argument('name'),
            $this->option('v')
        );

        $this->withProgressBar(2, function (ProgressBar $progressBar) use ($service) {
            if ($service->createStoreRequest()) {
                $this->info('Store request created.');
            } else {
                $this->error('Store request exists or could not be created.');
            }
            $progressBar->advance();

            if ($service->createUpdateRequest()) {
                $this->info('Update request created.');
            } else {
                $this->error('Update request exists or could not be created.');
            }
            $progressBar->advance();

            if ($this->option('batch-update')) {
                if ($service->createBatchUpdateRequest()) {
                    $this->info('Batch-Update request created.');
                } else {
                    $this->error('Batch-Update request exists or could not be created.');
                }
            }
            $progressBar->advance();
        });

        return self::SUCCESS;
    }
}
