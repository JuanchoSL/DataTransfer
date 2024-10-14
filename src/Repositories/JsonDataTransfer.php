<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\ExpectationFailedException;
use JuanchoSL\Exceptions\UnprocessableEntityException;

class JsonDataTransfer extends ArrayDataTransfer
{

    public function __construct(string $json)
    {
        if (is_string($json)) {
            if (is_file($json) && file_exists($json)) {
                $json = file_get_contents($json);
            }
        }
        if (empty($json)) {
            throw new UnprocessableEntityException("No contents has been received");
        }
        $body = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ExpectationFailedException(json_last_error_msg());
        }
        parent::__construct((array) $body);
    }

}