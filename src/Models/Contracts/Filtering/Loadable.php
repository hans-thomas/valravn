<?php

    namespace Hans\Valravn\Models\Contracts\Filtering;

    interface Loadable {
        public function getLoadableRelations(): array;
    }
