<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JsonSerializable;

class ObjectDataTransfer extends JsonDataTransfer
{

    public function __construct(object|string $object)
    {
        if (is_string($object)) {
            if (is_file($object) && file_exists($object)) {
                $object = file_get_contents($object);
            }
            $object = unserialize($object);
        }
        if (!$object instanceof JsonSerializable) {
            $object = (array) $object;
        }
        parent::__construct(json_encode($object, JSON_THROW_ON_ERROR));
    }
}