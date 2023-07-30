<?php

namespace Hans\Valravn\Models\Contracts;

use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Hans\Valravn\Http\Resources\Contracts\ValravnResourceCollection;

interface ResourceCollectionable
{
    /**
     * Return related resource class.
     *
     * @return ValravnJsonResource
     */
    public static function getResource(): ValravnJsonResource;

    /**
     * Convert current instance to a related resource class.
     *
     * @return ValravnJsonResource
     */
    public function toResource(): ValravnJsonResource;

    /**
     * Return related resource collection class.
     *
     * @return ValravnResourceCollection
     */
    public static function getResourceCollection(): ValravnResourceCollection;
}
