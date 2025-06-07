<?php

namespace Hans\Valravn\Models\Contracts;

use Hans\Valravn\Repositories\Contracts\VRepository;
use Hans\Valravn\Services\Contracts\VService;

interface EntityClasses
{
    /**
     * Return related repository class.
     *
     * @return VRepository
     */
    public function getVRepository(): VRepository;

    /**
     * Return related service class.
     *
     * @return VService
     */
    public function getVService(): VService;

    /**
     * Return related relations service class.
     *
     * @return VService
     */
    public function getVRelationsService(): VService;
}
