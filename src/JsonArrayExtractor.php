<?php

namespace JuanchoSL\DataExtractor;

use JuanchoSL\Exceptions\ExpectationFailedException;
use Countable;
use Iterator;
use JsonSerializable;
use JuanchoSL\DataExtractor\Contracts\ExtractorInterface;

class JsonArrayExtractor implements ExtractorInterface, Iterator, Countable, JsonSerializable
{

    private ArrayExtractor $data;

    public function __construct(string $json)
    {
        $body = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ExpectationFailedException(json_last_error_msg());
        }
        $this->data = new ArrayExtractor($body);
    }

    public function get(string $index, mixed $default = null): mixed
    {
        return $this->data->get($index) ?? $default;
    }

    public function count(): int
    {
        return $this->data->count();
    }

    public function has(string $index): bool
    {
        return $this->data->has($index);
    }

    public function current(): mixed
    {
        return $this->data->current();
    }

    public function key(): string|int|null
    {
        return $this->data->key();
    }

    public function next(): void
    {
        $this->data->next();
    }

    public function rewind(): void
    {
        $this->data->rewind();
    }

    public function valid(): bool
    {
        return $this->data->valid();
    }
    /**
     * Especifica los datos que deberían serializarse para JSON
     * Serializa el objeto a un valor que puede ser serializado de forma nativa por json_encode().
     * @return mixed Devuelve los datos que pueden ser serializados por json_encode(), los cuales son un valor de cualquier tipo distinto de `resource`.
     */
    public function jsonSerialize(): mixed
    {
        return $this->data->jsonSerialize();
    }
}