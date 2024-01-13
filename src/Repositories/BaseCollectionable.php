<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

/**
 * @implements \Iterator<int|string, mixed>
 */
abstract class BaseCollectionable implements \Iterator, \JsonSerializable, \Countable
{

    /**
     * @var array<int|string, mixed>
     */
    protected array $data;


    public function empty(): bool
    {
        return empty($this->data);
    }

    //Iterator
    public function current(): mixed
    {
        $key = $this->key();
        return (is_null($key)) ? null : $this->data[$key];
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

    //JsonSerializable
    public function jsonSerialize(): mixed
    {
        return $this->data;
    }

    //Countable
    public function count(): int
    {
        return count(array_keys((array) $this->data));
    }

}
