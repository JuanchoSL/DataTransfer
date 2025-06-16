<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class ArrayConverter extends JsonConverter
{

    /**
     * Retrieve the data as an associative array
     * @return mixed
     */
    public function getData(): mixed
    {
        return json_decode((string) parent::getData(), true);
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