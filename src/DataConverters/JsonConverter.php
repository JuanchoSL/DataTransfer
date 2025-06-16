<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class JsonConverter extends AbstractConverter
{

    /**
     * Retrieve the data as an json string with pretty print
     * @return bool|string
     */
    public function getData(): mixed
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }

    /**
     * Return the json representation of the entity
     * @return bool|string
     */
    public function __tostring(): string
    {
        return json_encode($this->data);
    }
}