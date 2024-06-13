<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\ExpectationFailedException;

class JsonArrayDataTransfer extends ArrayDataTransfer
{

    public function __construct(string $json)
    {
        $body = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ExpectationFailedException(json_last_error_msg());
        }
        parent::__construct((array) $body);
    }

}