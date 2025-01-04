<?php

namespace Hans\Valravn\Tests\Instances\Exceptions;

use Hans\Valravn\Exceptions\VException;
use Symfony\Component\HttpFoundation\Response;

class FullFormWithEmptyPrefixException extends VException
{
    protected string $errorCodePrefix = '';

    public static function failedAgain(): self
    {
        return new self("The prefix is empty", 1, Response::HTTP_BAD_REQUEST);
    }
}