<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\PolicyService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

class Policy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:policy 
		{namespace : Group of the entity}
		{name : Name of the entity}
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate policy class.';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Throwable
     *
     */
    public function handle()
    {
        $this->withProgressBar(1, function (ProgressBar $progress) {
            if (PolicyService::make($this->argument('namespace'), $this->argument('name'))->createPolicy()) {
                $this->info('Policy class created.');
            } else {
                $this->info('Policy class exists or could not be created.');
            }
        });
    }
}
