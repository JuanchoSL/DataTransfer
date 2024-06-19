<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Factories\DataTransferFactory;

/**
 * @implements \Iterator<int|string, mixed>
 */
abstract class BaseDataTransfer extends BaseCollectionable implements DataTransferInterface
{

    public function append(mixed $value): int
    {
        return parent::append($this->dataConverter($value));
    }

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

    public function get(string|int $index, mixed $default = null): mixed
    {
        if (!$this->has($index)) {
            if (!is_null($default)) {
                $this->set($index, $default);
            }
        }
        return $this->data[$index] ?? null;
    }

    public function set(string|int $key, mixed $value): self
    {
        @$this->data[$key] = $this->dataConverter($value);
        return $this;
    }

    public function has(string|int $index): bool
    {
        return isset($this->data[$index]);
    }

    public function remove(string|int $key): void
    {
        unset($this->data[$key]);
    }

    protected function dataConverter(mixed $value): mixed
    {
        return DataTransferFactory::create($value);
    }

}