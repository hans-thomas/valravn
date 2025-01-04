<?php

namespace Hans\Valravn\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\Visibility;
use Throwable;

class Migration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:migration 
		{namespace : Group of the entity}
		{name : Name of the entity}
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate migration file.';

    private FilesystemAdapter $fs;

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
     * @return void
     * @throws Throwable
     *
     */
    public function handle()
    {
        $singular = ucfirst(Str::singular($this->argument('name')));
        $plural = ucfirst(Str::plural($this->argument('name')));
        $namespace = ucfirst($this->argument('namespace'));

        $migrationStub = file_get_contents(__DIR__.'/stubs/migrations/migration.stub');
        $migrationStub = Str::replace(
            '{{MODEL::NAMESPACE}}',
            $namespace,
            $migrationStub
        );
        $migrationStub = Str::replace('{{MODEL::CLASS}}', $singular, $migrationStub);

        $path = "migrations/$namespace";
        $datePrefix = now()->format('Y_m_d_His');
        $fileName = "create_".Str::snake($plural).'_table.php';

        foreach ($this->fs->allFiles($path) as $file) {
            if (preg_match("/[0-9 _]+_$fileName/s", $file)) {
                $this->info('migration class exists!');

                return;
            }
        }

        $this->fs->write(
            "$path/{$datePrefix}_$fileName",
            $migrationStub
        );

        $this->info('migration class successfully created!');
    }
}
