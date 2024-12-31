<?php

namespace Hans\Valravn\Models\Contracts;

use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Hans\Valravn\Http\Resources\Contracts\VResourceCollection;

interface ResourceCollectionable
{
    /**
     * Return related resource class.
     *
     * @return VJsonResource
     */
    public static function getResource(): VJsonResource;

    /**
     * Convert current instance to a related resource class.
     *
     * @return VJsonResource
     */
    public function toResource(): VJsonResource;

    /**
     * Return related resource collection class.
     *
     * @return VResourceCollection
     */
    public static function getResourceCollection(): VResourceCollection;
}
