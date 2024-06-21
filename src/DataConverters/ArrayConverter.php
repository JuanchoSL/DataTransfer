<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class ArrayConverter extends JsonConverter
{

    public function getData()
    {
        return json_decode(parent::getData(), true);
    }
}