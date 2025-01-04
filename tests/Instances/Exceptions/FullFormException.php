<?php

namespace Hans\Valravn\Tests\Instances\Exceptions;

use Hans\Valravn\Exceptions\VException;
use Symfony\Component\HttpFoundation\Response;

class FullFormException extends VException
{
    protected string $errorCodePrefix = 'FFEcx';

    public static function failedRemove(string $param):self
    {
        return new self("Failed to remove $param",1);
    }
    public static function notFount():self
    {
        return new self('Failed to find your data',2,Response::HTTP_NOT_FOUND);
    }
    public static function failedWithDifferentPrefix($newPrefix):self
    {
        return new self('Failed with new prefix',3,errorCodePrefix: $newPrefix);
    }
}