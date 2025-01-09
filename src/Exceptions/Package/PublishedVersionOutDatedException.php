<?php

namespace Hans\Valravn\Exceptions\Package;

use Hans\Valravn\Exceptions\VException;

class PublishedVersionOutDatedException extends VException
{
    protected string $errorCodePrefix = 'ValravnECx';

    public function __construct()
    {
        parent::__construct('Published config file is outdated! please republish it.', 3);
    }
}
