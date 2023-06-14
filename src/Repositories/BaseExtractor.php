<?php

namespace JuanchoSL\DataTransfer\Repositories;

use Iterator;

abstract class BaseExtractor
{
    //protected Iterator $data;

    protected $data;

    public function __get($key)
    {
        return $this->get($key);
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
            $value = new ArrayExtractor($value);
        } elseif (is_object($value)) {
            $value = new ObjectExtractor($value);
        } elseif (is_string($value)) {
            //$value = filter_var($value, FILTER_SANITIZE_STRING);
            $value = htmlspecialchars($value);
        }
        return $value;
    }
}