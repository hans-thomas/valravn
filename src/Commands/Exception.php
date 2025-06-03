<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\ExceptionService;
use Illuminate\Console\Command;
use League\Flysystem\FilesystemException;
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
     * @return int
     * @throws FilesystemException
     *
     * @throws Throwable
     */
    public function handle(): int
    {
        $namespace = $this->argument('namespace');
        $name = $this->argument('name');
        $prefixCode = $this->argument('prefix');

        $service = new ExceptionService($namespace, $name, $prefixCode);

        $compactName = $this->option('compact');

        $closure = function (ProgressBar $progress) use ($service, $compactName) {
            $this->newLine();
            if ($compactName === '' || filled($compactName) || $this->confirm('Should create a compact exception?')) {
                $compactName = $compactName ?: $this->ask('What should be its name?');
                if (blank($compactName)) {
                    throw new \Exception('The name of the compact exception can not be empty.');
                }
            }
            if ($compactName) {
                if ($service->createCompactForm($compactName)) {
                    $this->info('Compact exception class created.');
                } else {
                    $this->error('Compact exception class exists or could no be created.');
                }
            } else {
                if ($service->createFullForm()) {
                    $this->info('Exception class created.');
                } else {
                    $this->error('Exception class exists or could no be created.');
                }
            }
            $progress->advance();
            $this->newLine();
        };

        $this->withProgressBar(1, $closure);

        return self::SUCCESS;
    }
}
