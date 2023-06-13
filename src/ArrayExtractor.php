<?php

namespace JuanchoSL\DataExtractor;

use Countable;
use Iterator;
use JsonSerializable;
use JuanchoSL\DataExtractor\Contracts\ExtractorInterface;

class ArrayExtractor implements ExtractorInterface, Iterator, Countable, JsonSerializable
{
    private array $data;

    public function __construct(array $array)
    {
        $this->data = $array;
    }

    public function get(string $index, mixed $default = null): mixed
    {
        $return = $this->data[$index] ?? $default;
        if (is_array($return)) {
            $return = new ArrayExtractor($return);
        } elseif (is_object($return)) {
            $return = new ObjectExtractor($return);
        }
        return $return;
    }

    public function count(): int
    {
        return count(array_keys($this->data));
    }

    public function has(string $index): bool
    {
        return array_key_exists($index, $this->data);
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
        return $this->data;
    }
}