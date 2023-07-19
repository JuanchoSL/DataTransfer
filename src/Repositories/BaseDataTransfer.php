<?php

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\DataTransferFactory;

abstract class BaseDataTransfer implements DataTransferInterface, \Iterator, \Countable, \JsonSerializable
{

    protected $data;

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        return $this->set($key, $value);
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

    protected function dataConverter($value): mixed
    {
        return DataTransferFactory::create($value);
    }
}