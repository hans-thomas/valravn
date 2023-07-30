<?php

namespace Hans\Valravn\Services\Routing\Relations;

use Hans\Valravn\Services\Contracts\Routeing\Relations;

class BelongsToMany extends Relations
{
    protected function routes(string $name, string $parameter, string $action): void
    {
        $this->get('{'.$name."}/$parameter", $action);
        $this->post('{'.$name."}/$parameter", $action);
        $this->attach('{'.$name."}/$parameter", $action);
        $this->detach('{'.$name."}/$parameter", $action);
    }
}
