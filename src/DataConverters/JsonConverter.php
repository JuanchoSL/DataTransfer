<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class JsonConverter extends AbstractConverter
{

    /**
     * Retrieve the data as an json string
     * @return bool|string
     */
    public function getData(): mixed
    {
        return json_encode($this->data);
    }

    /**
     * Return the json representation of the entity
     * @return bool|string
     */
    public function __tostring(): string
    {
        return $this->getData();
    }
}