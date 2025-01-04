<?php

namespace Hans\Valravn\Exceptions\Package;

use Hans\Valravn\Exceptions\VException;
use Illuminate\Database\Eloquent\Model;

class FailedToDeleteException extends VException
{
    protected string $errorCodePrefix = 'ValravnECx';

    public function __construct(Model $model)
    {
        parent::__construct('Failed to delete ['.get_class($model)."] $model->id", 1);
    }
}