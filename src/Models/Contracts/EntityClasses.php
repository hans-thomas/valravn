<?php

    namespace Hans\Valravn\Models\Contracts;

    use Hans\Valravn\Repositories\Contracts\Repository;

    interface EntityClasses {
        public function getRepository(): Repository;

        public function getService(): object;

        public function getRelationsService(): object;
    }
