<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Repositories\BaseCollectionable;

class Collection extends BaseCollectionable implements DataTransferInterface
{

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
        @$this->data[$key] = $value;
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
}
