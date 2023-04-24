<?php


    namespace Hans\Valravn\Policies\Traits;

    use Horus;

    trait PolicyHelperTrait {
        protected function makeAbility(): string {
            return Horus::normalizeModelName( $this->getModel() ) . '-' . debug_backtrace()[ 1 ][ 'function' ];
        }
    }
