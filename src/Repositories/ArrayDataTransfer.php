<?php

namespace JuanchoSL\DataTransfer\Repositories;

use Countable;
use Iterator;
use JsonSerializable;
use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;

class ArrayDataTransfer extends BaseDataTransfer implements DataTransferInterface, Iterator, Countable, JsonSerializable
{
    public function __construct(array $array)
    {
        $this->data = $array;
    }

    public function unset($key): self
    {
        unset($this->data[$key]);
        return $this;
    }
    
    public function set($key, $value): self
    {
        $this->data[$key] = $this->dataConverter($value);
        return $this;
    }

    public function get(string $index, mixed $default = null): mixed
    {
        $return = $this->data[$index] ?? $default;
        return $this->dataConverter($return);
    }

    public function count(): int
    {
        return count(array_keys($this->data));
    }

    public function has(string $index): bool
    {
        return array_key_exists($index, $this->data);
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