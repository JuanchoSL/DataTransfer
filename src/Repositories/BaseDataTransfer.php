<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\DataTransfer\DataContainer;
use JuanchoSL\DataTransfer\Factories\DataTransferFactory;

/**
 * @implements \Iterator<int|string, mixed>
 */
abstract class BaseDataTransfer extends DataContainer
{

    public function append(mixed $value): int
    {
        return parent::append($this->dataConverter($value));
    }

    public function set(string|int $key, mixed $value): self
    {
        parent::set($key, $this->dataConverter($value));
        return $this;
    }

    protected function dataConverter(mixed $value): mixed
    {
        return DataTransferFactory::create($value);
    }

}