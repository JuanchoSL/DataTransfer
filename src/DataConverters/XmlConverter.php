<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class XmlConverter extends XmlObjectConverter
{

    public function getData()
    {
        return parent::getData()->asXML();
    }
}