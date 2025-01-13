<?php

namespace Hans\Valravn\Policies\Traits;

trait PolicyHelperTrait
{
    /**
     * Guess the ability.
     *
     * @return string
     */
    protected function guessAbility(): string
    {
        return $this->normalizeModelName($this->getModel()).'-'.debug_backtrace()[1]['function'];
    }

    /**
     * normalize the model name.
     *
     * @param  string  $model
     *
     * @return string
     */
    private function normalizeModelName(string $model): string
    {
        $namespace = $exploded = explode('\\', strtolower($model));
        $class = array_splice($namespace, count($exploded)-1);

        if (count($namespace) <= 2) {
            $name = end($class);
        }else{
            $name = end($namespace).'-'.end($class);
        }

        return $name;
    }
}
