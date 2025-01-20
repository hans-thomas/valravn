<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\ExceptionService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

class Exception extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:exception 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{prefix : A unique string to prefix the error}
		{--c|compact= : Creates a compact exception}
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate exception class.';

    /**
     * Execute the console command.
     *
     * @throws Throwable
     *
     * @return void
     */
    public function handle()
    {
        $namespace = $this->argument('namespace');
        $name = $this->argument('name');
        $prefixCode = $this->argument('prefix');

        $service = new ExceptionService($namespace, $name, $prefixCode);

        $this->withProgressBar(1, function (ProgressBar $progress) use ($service) {
            $compactName = $this->option('compact');
            if ($compactName !== null || $this->confirm('Should create a compact exception?')) {
                if ($compactName == null) {
                    $compactName = $this->ask('What should be its name?', null);
                }
                $service->createCompactForm($compactName);
            } else {
                $service->createFullForm();
            }
            $this->info('Exception class created.');
            $progress->advance();
        });
    }
}
