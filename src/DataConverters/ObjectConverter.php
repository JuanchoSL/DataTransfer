<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class ObjectConverter extends JsonConverter
{
    public function getData()
    {
        return json_decode(parent::getData(), false);

    }
}