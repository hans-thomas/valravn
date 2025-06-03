<?php

namespace Hans\Valravn\Models\Contracts;

use Hans\Valravn\Repositories\Contracts\Repository;
use Hans\Valravn\Services\Contracts\VService;

interface EntityClasses
{
    /**
     * Return related repository class.
     *
     * @return Repository
     */
    public function getRepository(): Repository;

    /**
     * Return related service class.
     *
     * @return VService
     */
    public function getService(): VService;

    /**
     * Return related relations service class.
     *
     * @return VService
     */
    public function getRelationsService(): VService;
}
