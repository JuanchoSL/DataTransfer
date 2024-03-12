<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

class JsonObjectDataTransfer extends JsonArrayDataTransfer
{

    public function __construct(string $json)
    {
        /*
        $json = json_decode($json, false);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ExpectationFailedException(json_last_error_msg());
        }*/
        parent::__construct($json);
    }
}