<?php

namespace Hans\Valravn\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\Visibility;
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
    protected $description = 'Generate exception and error code classes.';

    private Filesystem $fs;

    public function __construct()
    {
        parent::__construct();
        $this->fs = Storage::createLocalDriver([
            'root'       => app_path(),
            'visibility' => Visibility::PUBLIC,
        ]);
    }

    /**
     * Execute the console command.
     *
     * @throws Throwable
     *
     * @return void
     */
    public function handle()
    {
        $namespace = ucfirst($this->argument('namespace'));
        $name = ucfirst(Str::singular($this->argument('name')));
        $directory = $name;
        $prefixCode = ucfirst($this->argument('prefix'));

        $exceptionStub = file_get_contents(__DIR__.'/stubs/exceptions/fullFormException.stub');

        if ($this->option('compact')) {
            $name = ucfirst($this->option('compact'));
            $exceptionStub = file_get_contents(__DIR__.'/stubs/exceptions/compactFormException.stub');
        }

        $exceptionStub = Str::replace('{{ENTITY::NAMESPACE}}', $namespace, $exceptionStub);
        $exceptionStub = Str::replace('{{ENTITY::NAME}}', $name, $exceptionStub);
        $exceptionStub = Str::replace('{{ENTITY::CODE}}', $prefixCode, $exceptionStub);
        $exceptionFile = "Exceptions/$namespace/$directory/{$name}Exception.php";
        $this->fs->write($exceptionFile, $exceptionStub);

        $this->info('Exception class successfully created!');
    }
}
