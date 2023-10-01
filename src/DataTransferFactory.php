<?php

namespace JuanchoSL\DataTransfer;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Repositories\ArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\JsonArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ObjectDataTransfer;

class DataTransferFactory
{
    public static function create(mixed $value): DataTransferInterface|string|null
    {
        if (is_array($value)) {
            $value = new ArrayDataTransfer($value);
        } elseif (is_string($value)) {
            //if (!is_null(json_decode($value))) {
            if (
                (substr($value, 0, 1) == '{' && substr($value, -1) == '}')
                ||
                (substr($value, 0, 1) == '[' && substr($value, -1) == ']')
            ) {
                $value = new JsonArrayDataTransfer($value);
            } else {
                $value = htmlspecialchars($value);
            }
        } elseif (is_object($value)) {
            if (is_subclass_of($value, DataTransferInterface::class)) {
                return $value;
            }
            $value = new ObjectDataTransfer($value);
        }
        return $value;
    }
}