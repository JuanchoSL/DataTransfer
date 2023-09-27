<?php

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\DataTransferFactory;

/**
 * @implements \Iterator<int|string, DataTransferInterface>
 */
abstract class BaseDataTransfer implements DataTransferInterface, \Iterator, \Countable, \JsonSerializable
{

    protected mixed $data;
    public function __clone()
    {
        foreach ($this as $key => $val) {
            if (is_object($val) || is_array($val)) {
                $val = unserialize(serialize($val));
            }
            $this->set($key, $val);
        }
    }

    public function __get(string $key): mixed
    {
        return $this->get($key);
    }
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }
    public function __unset(string $key): void
    {
        $this->unset($key);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->set($key, $value);
    }

    public function current(): mixed
    {
        $key = $this->key();
        return (is_null($key)) ? null : $this->get($key);
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

    protected function dataConverter(mixed $value): mixed
    {
        return DataTransferFactory::create($value);
    }
}