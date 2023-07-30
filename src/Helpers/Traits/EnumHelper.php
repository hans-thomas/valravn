<?php

namespace Hans\Valravn\Helpers\Traits;

use ReflectionClass;

trait EnumHelper
{
    /**
     * Convert values of enum class to an array.
     *
     * @return array
     */
    public static function toArray(): array
    {
        $vars = collect();
        foreach (( new ReflectionClass(static::class) )->getConstants() as $name => $value) {
            $vars->put($name, $value->value ?? $value->name);
        }

        return $vars->toArray();
    }

    /**
     * Convert keys of enum class to an array.
     *
     * @return array
     */
    public static function toArrayKeys(): array
    {
        $vars = collect();
        foreach (( new ReflectionClass(static::class) )->getConstants() as $name => $value) {
            $vars->push($name);
        }

        return $vars->toArray();
    }

    /**
     * Convert values of enum class to an array except the given values.
     *
     * @param array $values
     *
     * @return array
     */
    public static function toArrayExcept(array $values): array
    {
        $vars = static::toArray();
        foreach ($vars as $index => $value) {
            if (in_array($value, $values)) {
                unset($vars[$index]);
            }
        }

        return $vars;
    }

    /**
     * Convert keys of enum class to an array except the given keys.
     *
     * @param array $keys
     *
     * @return array
     */
    public static function toArrayKeysExcept(array $keys): array
    {
        $vars = self::toArrayKeys();
        foreach ($vars as $index => $value) {
            if (in_array($value, $keys)) {
                unset($vars[$index]);
            }
        }

        return $vars;
    }

    /**
     * Convert given values of enum class to an array.
     *
     * @param array $values
     *
     * @return array
     */
    public static function toArrayOnly(array $values): array
    {
        $vars = static::toArray();
        foreach ($vars as $index => $value) {
            if (!in_array($value, $values)) {
                unset($vars[$index]);
            }
        }

        return $vars;
    }

    /**
     * Convert given keys of enum class to an array.
     *
     * @param array $keys
     *
     * @return array
     */
    public static function toArrayKeysOnly(array $keys): array
    {
        $vars = self::toArrayKeys();
        foreach ($vars as $index => $value) {
            if (!in_array($value, $keys)) {
                unset($vars[$index]);
            }
        }

        return array_values($vars);
    }

    /**
     * Create an array using all enum members.
     *
     * @return array
     */
    public static function all(): array
    {
        $vars = collect();
        foreach (( new ReflectionClass(static::class) )->getConstants() as $name => $value) {
            $vars->put($name, $value);
        }

        return $vars->toArray();
    }

    /**
     * Create an array using values of all enum members.
     *
     * @return array
     */
    public static function IndexedAll(): array
    {
        $vars = collect();
        foreach (( new ReflectionClass(static::class) )->getConstants() as $value) {
            $vars->push($value);
        }

        return $vars->toArray();
    }

    /**
     * Find a value using the given key, otherwise return the default value.
     *
     * @param string $key
     * @param        $default
     *
     * @return mixed|null
     */
    public static function tryFromKey(string $key, $default = null)
    {
        if (
            ($position = array_search(strtoupper($key), static::toArrayKeys())) !== false
        ) {
            return static::IndexedAll()[$position];
        }

        return $default;
    }
}
