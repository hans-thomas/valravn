<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\RequestService;
use Illuminate\Console\Command;
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
     * @throws Throwable
     *
     * @return void
     */
    public function handle()
    {
        $service = new RequestService(
            $this->argument('namespace'),
            $this->argument('name'),
            $this->option('v')
        );

        $service->createStoreRequest()
                ->createUpdateRequest();

        if ($this->option('batch-update')) {
            $service->createBatchUpdateRequest();
        }

        $this->info('request classes successfully created!');
    }
}
