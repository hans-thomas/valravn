<?php

namespace Hans\Valravn\Exceptions;

use Hans\Valravn\Exceptions\Package\PackageException;
use Illuminate\Support\Str;

abstract class ErrorCode
{
    /**
     * Prefix of defined codes.
     *
     * @var string
     */
    protected static string $prefix = 'ECx';

    /**
     * Singleton instance of the class.
     *
     * @var self
     */
    private static self $instance;

    /**
     * Make a singleton instance.
     *
     * @return static
     */
    public static function make(): static
    {
        if (isset(self::$instance)) {
            return self::$instance;
        }

        return self::$instance = new static();
    }

    /**
     * @param string $name
     *
     * @throws ValravnException
     *
     * @return string
     */
    public function __get(string $name)
    {
        return self::__callStatic($name, []);
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @throws ValravnException
     *
     * @return string
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (property_exists(static::class, $name)) {
            $property = $name;
        } elseif (property_exists(static::class, $camel = Str::of($name)->camel()->toString())) {
            $property = $camel;
        } elseif (property_exists(static::class, $snake = Str::of($name)->snake()->upper()->toString())) {
            $property = $snake;
        }

        if (isset($property)) {
            return static::$prefix.(new static())->{$property};
        }

        throw PackageException::errorCodeNotFound($name);
    }
}
