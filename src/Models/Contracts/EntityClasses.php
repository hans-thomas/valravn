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
    public function getRepository(): VRepository;

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
