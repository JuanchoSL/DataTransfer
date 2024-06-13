<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class XmlObjectConverter extends AbstractConverter
{
    public function getData()
    {
        return simplexml_load_string(XmlConverter::convert($this->data));
    }

}