<?php

    namespace Hans\Valravn\Models\Contracts;

    use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
    use Hans\Valravn\Http\Resources\Contracts\BaseResourceCollection;

    interface ResourceCollectionable {
        public static function getResource(): BaseJsonResource;

        public function toResource(): BaseJsonResource;

        public static function getResourceCollection(): BaseResourceCollection;
    }
