<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\RelationService;
use Hans\Valravn\Exceptions\Package\NoArgsPassedException;
use Hans\Valravn\Http\Requests\Contracts\Relations\BelongsToManyRequest;
use Hans\Valravn\Http\Requests\Contracts\Relations\HasManyRequest;
use Hans\Valravn\Http\Requests\Contracts\Relations\MorphedByManyRequest;
use Hans\Valravn\Http\Requests\Contracts\Relations\MorphToManyRequest;
use Hans\Valravn\Http\Requests\Contracts\Relations\MorphToRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use League\Flysystem\FilesystemException;
use Symfony\Component\Console\Helper\ProgressBar;
use function PHPUnit\Framework\assertTrue;

class Relation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        valravn:relation
		{namespace : Group of the entity}
		{name : Name of the entity}
		{related-namespace : Group of the related entity}
		{related-name? : Name of the related entity}
		{--v=1 : Version of the entity}
		{--belongs-to-many : Belongs to many request}
		{--has-many : Has many request}
		{--morphed-by-many : Morphed by many request}
		{--morph-to-many : Morph to many request}
		{--morph-to : Morph to request}
		{--with-pivot : create a pivot migration}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate store and update request classes for a specific relationship.';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws FilesystemException
     */
    public function handle(): int
    {
        $service = new RelationService(
            $this->argument('namespace'),
            $this->argument('name'),
            $this->option('v'),
            $this->argument('related-namespace'),
            $this->argument('related-name')
        );

        $option = match (true) {
            $this->option('belongs-to-many') => BelongsToManyRequest::class,
            $this->option('morphed-by-many') => MorphedByManyRequest::class,
            $this->option('morph-to-many') => MorphToManyRequest::class,
            $this->option('has-many') => HasManyRequest::class,
            $this->option('morph-to') => MorphToRequest::class,
            default => null
        };

        if ($option === null && !$option = $this->choice(
                'What relation type should create?',
                [
                    class_basename(BelongsToManyRequest::class),
                    class_basename(MorphedByManyRequest::class),
                    class_basename(MorphToManyRequest::class),
                    class_basename(HasManyRequest::class),
                    class_basename(MorphToRequest::class),
                ])
        ) {
            $this->error('At least one argument should pass.');

            return self::FAILURE;
        }

        if (!class_exists($option)) {
            $namespace = substr(BelongsToManyRequest::class, 0, strrpos(BelongsToManyRequest::class, '\\'));
            $option = $namespace.'\\'.$option;
            assert(class_exists($option),'Request class is not exists.');
        }

        if ($this->argument('related-name') === null &&
            in_array($option, [
                BelongsToManyRequest::class,
                MorphedByManyRequest::class,
                MorphToManyRequest::class,
                HasManyRequest::class,
            ])
        ) {
            $this->error('The {related-name} parameter should not be empty when going to create a many-to-many relationship.');

            return self::FAILURE;
        }

        $this->withProgressBar(3, function (ProgressBar $progress) use ($service, $option) {
            $type = substr(class_basename($option), 0, strlen(class_basename($option)) - strlen('Request'));

            if (in_array($option,
                [
                    BelongsToManyRequest::class,
                    MorphedByManyRequest::class,
                    MorphToManyRequest::class,
                    HasManyRequest::class,
                ])) {

                if ($service->creatOneToMany($option)) {
                    $this->info("Relation $type request class created.");
                } else {
                    $this->error("Relation $type request class exists or could not be created.");
                }
                $progress->advance(2);

                if ($this->option('with-pivot') && !$this->option('has-many')) {
                    Artisan::call('valravn:pivot', [
                        'namespace'         => $this->argument('namespace'),
                        'name'              => $this->argument('name'),
                        'related-namespace' => $this->argument('related-namespace'),
                        'related-name'      => $this->argument('related-name')
                    ]);
                }
                $progress->advance();
            } elseif ($option === MorphToRequest::class) {
                if ($service->createMorphTo()) {
                    $this->info("Relation $type request class created.");
                } else {
                    $this->error("Relation $type request class exists or could not be created.");
                }
                $progress->advance(3);
            }
        });

        return self::SUCCESS;
    }
}
