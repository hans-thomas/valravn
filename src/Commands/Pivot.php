<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\MigrationService;
use Hans\Valravn\Commands\Services\RelationService;
use Hans\Valravn\Http\Requests\Contracts\Relations\BelongsToManyRequest;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

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
		{--v=1 : Version of the entity}
		{--r|request : Generate a relationship request} 
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate pivot migration file for many-to-many relationships.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $service = new MigrationService($this->argument('namespace'), $this->argument('name'));

        $this->withProgressBar(2, function (ProgressBar $progress) use ($service) {
            $this->newLine();
            if ($service->createPivot($this->argument('related-namespace'), $this->argument('related-name'))) {
                $this->info('Pivot migration file created.');
            } else {
                $this->error('Pivot migration file exists or could not be created.');
            }
            $progress->advance();
            $this->newLine();

            if ($this->option('request') || $this->confirm('Should create request for relationship?')) {
                $relationService = new RelationService(
                    $this->argument('namespace'),
                    $this->argument('name'),
                    $this->option('v'),
                    $this->argument('related-namespace'),
                    $this->argument('related-name')
                );
                if ($relationService->creatOneToMany(BelongsToManyRequest::class)) {
                    $this->info("Relation belongsToMany request class created.");
                } else {
                    $this->error("Relation belongsToMany request class exists or could not be created.");
                }
            }
            $progress->advance();
            $this->newLine();
        });

        return self::SUCCESS;
    }
}
