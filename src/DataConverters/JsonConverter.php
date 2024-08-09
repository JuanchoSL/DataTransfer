<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class JsonConverter extends AbstractConverter
{

    public function getData(): mixed
    {
        return json_encode($this->data);
    }
}