<?php

namespace Hans\Valravn\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Contracts\Filesystem\Filesystem;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use League\Flysystem\Visibility;
    use Throwable;

    class Repository extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = '
		valravn:repository 
		{namespace : Group of the entity}
		{name : Name of the entity}
		';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Generate repository contract and repository classes.';

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
            $singular = ucfirst(Str::singular($this->argument('name')));
            $namespace = ucfirst($this->argument('namespace'));

            // repository contract
            $repositoryContractStub = file_get_contents(__DIR__.'/stubs/repositories/repository-contract.stub');
            $repositoryContractStub = Str::replace('{{IREPOSITORY::NAMESPACE}}', $namespace, $repositoryContractStub);
            $repositoryContractStub = Str::replace('{{IREPOSITORY::MODEL}}', $singular, $repositoryContractStub);
            $this->fs->write("Repositories/Contracts/$namespace/I{$singular}Repository.php", $repositoryContractStub);

            // repository class
            $repositoryStub = file_get_contents(__DIR__.'/stubs/repositories/repository.stub');
            $repositoryStub = Str::replace('{{REPOSITORY::NAMESPACE}}', $namespace, $repositoryStub);
            $repositoryStub = Str::replace('{{REPOSITORY::MODEL}}', $singular, $repositoryStub);
            $this->fs->write("Repositories/$namespace/{$singular}Repository.php", $repositoryStub);

            $this->info('repository classes successfully created!');
        }
    }
