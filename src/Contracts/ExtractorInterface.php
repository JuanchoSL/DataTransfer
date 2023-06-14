<?php

namespace JuanchoSL\DataTransfer\Contracts;

interface ExtractorInterface
{

    public function unset(string $index): self;

    public function set(string $index, mixed $value): self;

    public function get(string $index, mixed $default = null): mixed;

    public function has(string $index): bool;

}