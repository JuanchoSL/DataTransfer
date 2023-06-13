<?php

namespace JuanchoSL\DataExtractor\Contracts;

interface ExtractorInterface
{

    public function get(string $index, mixed $default = null): mixed;

    public function has(string $index): bool;

}