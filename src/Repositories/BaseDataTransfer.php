<?php

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;

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
        if (is_array($value)) {
            $value = new ArrayDataTransfer($value);
        } elseif (is_object($value)) {
            $value = new ObjectDataTransfer($value);
        } elseif (is_string($value)) {
            if (substr($value, 0, 1) == '{' && substr($value, -1) == '}') {
                $value = new JsonArrayDataTransfer($value);
            } else {
                $value = htmlspecialchars($value);
            }
        }
        return $value;
    }
}