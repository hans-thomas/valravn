<?php

namespace Hans\Valravn\Services\Routing\Relations;

use Hans\Valravn\Services\Contracts\Routeing\VRelations;

class BelongsTo extends VRelations
{
    protected function routes(string $name, string $parameter, string $action): void
    {
        $this->get('{' . $name . "}/$parameter", $action);
        $this->post('{' . $name . "}/$parameter/{related}", $action);
    }
}
