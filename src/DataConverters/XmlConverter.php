<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class XmlConverter extends AbstractConverter
{

    public function getData()
    {
        return XmlObjectConverter::convert($this->data)->asXML();
    }
}