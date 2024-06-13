<?php

namespace JuanchoSL\DataTransfer\Contracts;

interface DataTransferInterface extends CollectionTransferInterface, \JsonSerializable
{

    public function unset(string|int $index): self;

    public function set(string|int $index, mixed $value): self;

    public function get(string|int $index, mixed $default = null): mixed;

    public function has(string|int $index): bool;

}