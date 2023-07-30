<?php

namespace Hans\Valravn\Exceptions\Package;

    use Hans\Valravn\Exceptions\ValravnException;
    use Illuminate\Database\Eloquent\Model;
    use Symfony\Component\HttpFoundation\Response;

    class PackageException extends ValravnException
    {
        public static function failedToDelete(Model $model): ValravnException
        {
            return self::make(
                'Failed to delete ['.get_class($model)."] $model->id",
                PackageErrorCode::failedToDelete(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        public static function invalidEntity(string $entity): ValravnException
        {
            return self::make(
                "Invalid entity class for resolving to model. [$entity]",
                PackageErrorCode::invalidEntity(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
