<?php

namespace JuanchoSL\DataTransfer\Repositories;

use Countable;
use Iterator;
use JsonSerializable;
use stdClass;
use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;

/**
 * @implements \Iterator<int|string, DataTransferInterface>
 */
class ObjectDataTransfer extends JsonObjectDataTransfer implements DataTransferInterface, Iterator, Countable, JsonSerializable
{

    public function __construct(object $object)
    {
        parent::__construct(json_encode($object));
        /*
        $this->data = new stdClass;
        foreach (array_keys(get_object_vars($object)) as $key) {
            $this->set($key, $object->{$key});
        }*/
    }
/*
    public function unset(string|int $key): self
    {
        unset($this->data->{$key});
        return $this;
    }
    public function set(string|int $key, mixed $value): self
    {
        $this->data->{$key} = $this->dataConverter($value);
        return $this;
    }

    public function get(string|int $index, mixed $default = null): mixed
    {
        return $this->data->{$index} ?? $this->dataConverter($default);
    }

    public function count(): int
    {
        return count(get_object_vars((object) $this->data));
    }

    public function has(string|int $index): bool
    {
        return property_exists((object) $this->data, (string) $index);
    }
*/
    /**
     * Especifica los datos que deberían serializarse para JSON
     * Serializa el objeto a un valor que puede ser serializado de forma nativa por json_encode().
     * @return mixed Devuelve los datos que pueden ser serializados por json_encode(), los cuales son un valor de cualquier tipo distinto de `resource`.
     */
    /*
    public function jsonSerialize(): mixed
    {
        return (array) $this->data;
    }
    */
}