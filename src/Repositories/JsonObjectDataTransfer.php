<?php

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\ExpectationFailedException;
use Countable;
use Iterator;
use JsonSerializable;
use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;

/**
 * @implements \Iterator<int|string, DataTransferInterface>
 */
class JsonObjectDataTransfer extends JsonArrayDataTransfer implements DataTransferInterface, Iterator, Countable, JsonSerializable
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