<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use Countable;
use Iterator;
use JsonSerializable;
use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;

/**
 * @implements \Iterator<int|string, mixed>
 */
class ObjectDataTransfer extends JsonObjectDataTransfer implements DataTransferInterface, Iterator, Countable, JsonSerializable
{

    public function __construct(object $object)
    {
        parent::__construct(json_encode($object, JSON_THROW_ON_ERROR));
    }
}