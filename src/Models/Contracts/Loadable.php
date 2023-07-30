<?php

namespace Hans\Valravn\Models\Contracts;

interface Loadable
{
    /**
     * List of relationships that can be loaded.
     *
     * @return array
     */
    public function getLoadableRelations(): array;
}
