<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class XmlConverter extends XmlObjectConverter
{

    /**
     * Parse and returns the xml string composition
     * @return string
     */
    public function getData(): mixed
    {
        return parent::getData()->asXML();
    }

    /**
     * Returns the xml string composition
     * @return string
     */
    public function __tostring(): string
    {
        return $this->getData();
    }
}