<?php

namespace JuanchoSL\DataTransfer\Contracts;

interface CollectionTransferInterface extends \Iterator, \ArrayAccess, \Countable, \JsonSerializable
{

    public function isEmpty(): bool;

    public function hasElements(): bool;

    public function append(mixed $value): int;

}