<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class ObjectConverter extends JsonConverter
{
    public function getData(): mixed
    {
        return json_decode(parent::getData(), false);
    }
    
    public function __tostring(): string
    {
        return serialize($this->getData());
    }
}