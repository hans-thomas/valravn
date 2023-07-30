<?php

namespace Hans\Valravn\Exceptions;

use Illuminate\Support\Str;

abstract class ErrorCode
{
    protected static string $prefix = 'ECx';

    public function __get(string $name)
    {
        return $this->{$name};
    }

    public static function __callStatic(string $name, array $arguments)
    {
        if (property_exists(static::class, $name)) {
            return static::$prefix.( new static() )->{$name};
        }

        $property = Str::of($name)->snake()->upper()->toString();
        if (property_exists(static::class, $property)) {
            return static::$prefix.( new static() )->{$property};
        }

        return static::$prefix;
    }
}
