<?php

namespace Hans\Valravn\Exceptions\Package;

use Hans\Valravn\Exceptions\VException;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

class PackageException extends VException
{
    public static function failedToDelete(Model $model): VException
    {
        return self::make(
            'Failed to delete ['.get_class($model)."] $model->id",
            PackageErrorCode::failedToDelete(),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public static function invalidEntity(string $entity): VException
    {
        return self::make(
            "Invalid entity class for resolving to model. [$entity]",
            PackageErrorCode::invalidEntity(),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public static function errorCodeNotFound(string $code): VException
    {
        return self::make(
            "Called error code not found. [$code]",
            PackageErrorCode::errorCodeNotFound(),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
