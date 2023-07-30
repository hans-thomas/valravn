<?php

namespace Hans\Valravn\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class Entity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:entity 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{--v=1 : Version of the entity}
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the whole needed classes for an entity.';

    /**
     * Execute the console command.
     *
     * @throws Throwable
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->argument('name');
        $namespace = $this->argument('namespace');
        $version = 'V'.filter_var($this->option('v'), FILTER_SANITIZE_NUMBER_INT);

        Artisan::call("valravn:exception $namespace $name");

        Artisan::call("valravn:model $namespace $name --seeder --factory --migration");

        Artisan::call("valravn:controllers $namespace $name --v $version --requests --resources");

        Artisan::call("valravn:policy $namespace $name");

        Artisan::call("valravn:repository $namespace $name");

        Artisan::call("valravn:service $namespace $name --relations --actions");
    }
}
