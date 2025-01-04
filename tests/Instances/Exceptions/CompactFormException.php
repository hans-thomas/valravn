<?php

namespace Hans\Valravn\Tests\Instances\Exceptions;

use Hans\Valravn\Exceptions\VException;
use Symfony\Component\HttpFoundation\Response;

class CompactFormException extends VException
{
    protected string $errorCodePrefix = 'CFEcx';

    public function __construct(string $class)
    {
        parent::__construct("Class '$class' does not exist", 1, Response::HTTP_NOT_FOUND);
    }
}
