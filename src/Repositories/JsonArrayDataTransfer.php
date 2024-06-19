<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

class JsonArrayDataTransfer extends JsonDataTransfer
{

    public function __construct(string $json)
    {
        trigger_error("Sub classes JSON are deprecated, use JsonDataTransfer instead " . get_called_class(), E_USER_DEPRECATED);
        parent::__construct($json);
    }

}