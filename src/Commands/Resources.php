<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\ResourceService;
use Illuminate\Console\Command;
use Throwable;

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
     * @return void
     * @throws Throwable
     *
     */
    public function handle()
    {
        ResourceService::make(
            $this->argument('namespace'),
            $this->argument('name'),
            $this->option('v')
        )
                       ->createResource()
                       ->createCollection();


        $this->info('resource and collection classes successfully created!');
    }
}
