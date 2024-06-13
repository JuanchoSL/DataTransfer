<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class ArrayConverter extends AbstractConverter
{

    public function getData()
    {
        return json_decode(json_encode($this->data), true);
    }
}