<?php

namespace Hans\Valravn\Exceptions\Package;

use Hans\Valravn\Exceptions\VException;

class InvalidEntityException extends VException
{
    protected string $errorCodePrefix = 'ValravnECx';

    public function __construct(string $entity)
    {
        parent::__construct("Invalid entity class for resolving to model. [$entity]", 2);
    }
}
