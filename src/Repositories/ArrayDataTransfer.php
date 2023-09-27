<?php

namespace JuanchoSL\DataTransfer\Repositories;

use Countable;
use Iterator;
use JsonSerializable;
use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;

/**
 * @implements \Iterator<int|string, DataTransferInterface>
 */
class ArrayDataTransfer extends BaseDataTransfer implements DataTransferInterface, Iterator, Countable, JsonSerializable
{

    /**
     * @param array<int|string, mixed> $array
     */
    public function __construct(array $array)
    {
        //$this->data = $array;
        $this->data = [];
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function unset(string|int $key): self
    {
        unset($this->data[$key]);
        return $this;
    }

    public function set(string|int $key, mixed $value): self
    {
        @$this->data[$key] = $this->dataConverter($value);
        return $this;
    }

    public function get(string|int $index, mixed $default = null): mixed
    {
        return $this->data[$index] ?? $this->dataConverter($default);
    }

    public function count(): int
    {
        return count(array_keys((array) $this->data));
    }

    public function has(string|int $index): bool
    {
        return array_key_exists($index, (array) $this->data);
    }

    /**
     * Especifica los datos que deberían serializarse para JSON
     * Serializa el objeto a un valor que puede ser serializado de forma nativa por json_encode().
     * @return mixed Devuelve los datos que pueden ser serializados por json_encode(), los cuales son un valor de cualquier tipo distinto de `resource`.
     */
    public function jsonSerialize(): mixed
    {
        return $this->data;
    }
}