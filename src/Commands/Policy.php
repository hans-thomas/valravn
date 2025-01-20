<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\PolicyService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

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
     * @return int
     */
    public function handle(): int
    {
        $service = new PolicyService($this->argument('namespace'), $this->argument('name'));
        $this->withProgressBar(1, function (ProgressBar $progress) use ($service) {
            $this->newLine();
            if ($service->createPolicy()) {
                $this->info('Policy class created.');
            } else {
                $this->error('Policy class exists or could not be created.');
            }
            $progress->advance();
            $this->newLine();
        });

        return self::SUCCESS;
    }
}
