<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer;

class DataContainer extends Collection
{

    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->set($key, $value);
    }

    public function __isset(string $key): bool
    {
        return $this->has($key);
    }

    public function __unset(string $key): void
    {
        $this->remove($key);
    }

    public function __clone()
    {
        foreach ($this->data as $key => $val) {
            if (is_object($val) || is_array($val)) {
                $val = unserialize(serialize($val));
            }
            $this->set($key, $val);
        }
    }

}