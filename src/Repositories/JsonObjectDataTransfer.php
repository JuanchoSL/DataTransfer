<?php

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\ExpectationFailedException;
use Countable;
use Iterator;
use JsonSerializable;
use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;

class JsonObjectDataTransfer extends ObjectDataTransfer implements DataTransferInterface, Iterator, Countable, JsonSerializable
{

    // private ObjectExtractor $data;

    public function __construct(string $json)
    {
        $body = json_decode($json, false);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ExpectationFailedException(json_last_error_msg());
        }
        parent::__construct($body);
    }
}