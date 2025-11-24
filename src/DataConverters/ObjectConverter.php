<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class ObjectConverter extends JsonConverter
{

    /**
     * Retrieve the data as a stdClass
     * @return mixed
     */
    public function getData(): mixed
    {
        return json_decode(parent::getData(), false);
    }

    /**
     * Serialize and return the element
     * @return string
     */
    public function __tostring(): string
    {
        return serialize($this->getData());
    }
}