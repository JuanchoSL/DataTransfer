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
class ArrayDataTransfer extends BaseDataTransfer implements DataTransferInterface, Iterator, Countable, JsonSerializable
{

    /**
     * @param array<int|string, mixed> $array
     */
    public function __construct(array $array)
    {
        $this->data = [];
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }

}