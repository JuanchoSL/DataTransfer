<?php

namespace JuanchoSL\DataExtractor;

use Countable;
use Iterator;
use JsonSerializable;
use stdClass;
use JuanchoSL\DataExtractor\Contracts\ExtractorInterface;

class ObjectExtractor implements ExtractorInterface, Iterator, Countable, JsonSerializable
{
    private stdClass $data;

    public function __construct(stdClass $object)
    {
        $this->data = $object;
    }
    public function get(string $index, mixed $default = null): mixed
    {
        $return = $this->data->{$index} ?? $default;
        if (is_array($return)) {
            $return = new ArrayExtractor($return);
        } elseif (is_object($return)) {
            $return = new ObjectExtractor($return);
        }
        return $return;
    }

    public function count(): int
    {
        return count(get_object_vars($this->data));
    }

    public function has(string $index): bool
    {
        return property_exists($this->data, $index);
    }

    public function current(): mixed
    {
        return $this->get($this->key());
    }

    public function key(): string|int|null
    {
        return key($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function rewind(): void
    {
        reset($this->data);
    }

    public function valid(): bool
    {
        return !is_null($this->key());
    }

    /**
     * Especifica los datos que deberían serializarse para JSON
     * Serializa el objeto a un valor que puede ser serializado de forma nativa por json_encode().
     * @return mixed Devuelve los datos que pueden ser serializados por json_encode(), los cuales son un valor de cualquier tipo distinto de `resource`.
     */
    public function jsonSerialize(): mixed
    {
        return (array) $this->data;
    }
}