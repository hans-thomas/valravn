<?php

namespace Hans\Valravn\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Contracts\Filesystem\Filesystem;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use League\Flysystem\Visibility;
    use Throwable;

    class Pivot extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = '
		valravn:pivot 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{related-namespace : Group of the related entity}
		{related-name : Name of the related entity}
		';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Generate pivot migration file for many-to-many relationships.';

        private Filesystem $fs;

        public function __construct()
        {
            parent::__construct();
            $this->fs = Storage::createLocalDriver([
                'root'       => database_path(),
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
            $singular = Str::of($this->argument('name'))->singular()->ucfirst()->toString();
            $singularLower = strtolower($singular);
            $namespace = Str::of($this->argument('namespace'))->ucfirst()->toString();

            $relatedSingular = Str::of($this->argument('related-name'))->singular()->ucfirst()->toString();
            $relatedSingularLower = strtolower($relatedSingular);
            $relatedNamespace = Str::of($this->argument('related-namespace'))->ucfirst()->toString();

            $pivot = file_get_contents(__DIR__.'/stubs/migrations/pivot.stub');

            $pivot = Str::replace('{{PIVOT::NAMESPACE}}', $namespace, $pivot);
            $pivot = Str::replace('{{PIVOT::MODEL}}', $singular, $pivot);

            $pivot = Str::replace('{{PIVOT::RELATED-NAMESPACE}}', $relatedNamespace, $pivot);
            $pivot = Str::replace('{{PIVOT::RELATED-MODEL}}', $relatedSingular, $pivot);

            // alphabetic sort for pivot table name
            $names = [$singularLower, $relatedSingularLower];
            sort($names);

            $pivot = Str::replace('{{PIVOT::FIRST-MODEL-SINGLE-LOWER}}', $names[0], $pivot);
            $pivot = Str::replace('{{PIVOT::SECOND-MODEL-SINGLE-LOWER}}', $names[1], $pivot);

            $datePrefix = now()->format('Y_m_d_His');
            $this->fs->write(
                "migrations/$namespace/{$datePrefix}_create_{$names[0]}_{$names[1]}_table.php",
                $pivot
            );

            $this->info('pivot migration file successfully created!');
        }
    }
