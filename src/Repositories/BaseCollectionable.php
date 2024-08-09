<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\DataTransfer\Contracts\CollectionTransferInterface;

abstract class BaseCollectionable implements CollectionTransferInterface
{

    /**
     * @var array<int|string, mixed>
     */
    protected array $data = [];

    public function append(mixed $value): int
    {
        return array_push($this->data, $value);
    }

    public function hasElements(): bool
    {
        return !$this->isEmpty();
    }

    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    public function empty(): bool
    {
        //trigger_error("Use isEmpty instead empty method", E_USER_DEPRECATED);
        return $this->isEmpty();
    }

    //ArrayAccess
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetUnset($var): void
    {
        unset($this->data[$var]);
    }

    public function offsetGet($var): mixed
    {
        return isset($this->data[$var]) ? $this->data[$var] : null;
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
        return count(array_keys($this->data));
    }

}
