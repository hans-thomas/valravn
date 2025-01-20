<?php

namespace Hans\Valravn\Commands\Services;

use Hans\Valravn\Commands\Services\Contracts\CommandsService;
use Hans\Valravn\Http\Requests\Contracts\Relations\HasManyRequest;
use Hans\Valravn\Http\Requests\Contracts\RelationsRequest;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;

final class RelationService extends CommandsService
{
    private readonly string $relatedNamespace;
    private readonly ?string $relatedName;

    public function __construct(
        string $namespace,
        string $name,
        string $version,
        string $relatedNamespace,
        ?string $relatedName
    ) {
        parent::__construct($namespace, $name, $version);

        $this->relatedNamespace = ucfirst($relatedNamespace);
        $this->relatedName = Str::of($relatedName)->singular()->ucfirst()->toString();
    }

    /**
     * @throws FilesystemException
     */
    public function creatOneToMany(RelationsRequest|string $extends): bool
    {
        $relation = Str::plural($this->relatedName);
        $extends = is_object($extends) ? get_class($extends) : $extends;

        if ($extends === HasManyRequest::class) {
            $content = $this->getStub('relations/has-many.stub');
        } else {
            $content = $this->getStub('relations/many-to-many.stub');
        }

        $content = Str::replace('{{RELATION::VERSION}}', $this->version, $content);
        $content = Str::replace('{{RELATION::NAMESPACE}}', $this->namespace, $content);
        $content = Str::replace('{{RELATION::MODEL}}', $this->name, $content);
        $content = Str::replace('{{RELATION::RELATED-NAMESPACE}}', $this->relatedNamespace, $content);
        $content = Str::replace('{{RELATION::RELATED-MODEL}}', $this->relatedName, $content);
        $content = Str::replace('{{RELATION::RELATION}}', $relation, $content);
        $content = Str::replace('{{RELATION::EXTENDS}}', class_basename($extends), $content);

        $file = "Http/Requests/$this->version/$this->namespace/$this->name/{$this->name}{$relation}Request.php";
        if ($this->filesystem->exists($file)) {
            return false;
        }

        $this->filesystem->write(
            $file,
            $content
        );

        return true;
    }

    public function createMorphTo(): bool
    {
        $morphTo = $this->getStub('relations/morph-to.stub');

        $morphTo = Str::replace('{{RELATION::NAMESPACE}}', $this->namespace, $morphTo);
        $morphTo = Str::replace('{{RELATION::MODEL}}', $this->name, $morphTo);
        $morphTo = Str::replace('{{RELATION::VERSION}}', $this->version, $morphTo);
        $morphTo = Str::replace('{{RELATION::RELATION}}', $this->relatedNamespace, $morphTo);

        $file = "Http/Requests/$this->version/$this->namespace/$this->name/{$this->name}{$this->relatedNamespace}Request.php";
        if ($this->filesystem->exists($file)) {
            return false;
        }

        $this->filesystem->write(
            $file,
            $morphTo
        );

        return true;
    }
}
