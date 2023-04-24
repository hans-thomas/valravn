<?php

    namespace Hans\Valravn\Models\Contracts\Filtering;

    interface Filterable {
        public function getFilterableAttributes(): array;
    }
