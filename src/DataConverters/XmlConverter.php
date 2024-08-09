<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class XmlConverter extends XmlObjectConverter
{

    public function getData(): mixed
    {
        return parent::getData()->asXML();
    }
}