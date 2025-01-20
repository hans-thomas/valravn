<?php

namespace Hans\Valravn\Commands\Services;

use Illuminate\Support\Str;

final class PolicyService extends Contracts\CommandsService
{
    public function __construct(string $namespace, string $name)
    {
        parent::__construct($namespace, $name, '1');
    }

    public function createPolicy(): bool
    {
        $file = "Policies/$this->namespace/{$this->name}Policy.php";
        if ($this->filesystem->exists($file)) {
            return false;
        }

        $stub = $this->getStub('policies/policy.stub');
        $stub = Str::replace('{{POLICY::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{POLICY::MODEL}}', $this->name, $stub);

        $this->filesystem->write($file, $stub);

        return true;
    }
}
