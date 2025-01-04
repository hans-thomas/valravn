<?php

namespace Hans\Valravn\Tests\Instances\Exceptions;

use Hans\Valravn\Exceptions\VException;
use Symfony\Component\HttpFoundation\Response;

class FullFormWithoutPrefixException extends VException
{
    public static function failed(): self
    {
        return new self("The prefix is not defined", 1, Response::HTTP_BAD_REQUEST);
    }
}