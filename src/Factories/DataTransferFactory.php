<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Factories;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Repositories\ArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\JsonArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ObjectDataTransfer;
use JuanchoSL\DataTransfer\Repositories\XmlObjectDataTransfer;

class DataTransferFactory
{
    public static function create(mixed $value = null): DataTransferInterface|string|int|float|bool|null
    {
        if (is_array($value)) {
            $value = new ArrayDataTransfer($value);
        } elseif (is_null($value)) {
            //$value = null;
            $value = new ArrayDataTransfer([]);
        } elseif (
            is_string($value) && (
                (substr($value, 0, 1) == '{' && substr($value, -1) == '}')
                ||
                (substr($value, 0, 1) == '[' && substr($value, -1) == ']')
            )
        ) {
            $value = new JsonArrayDataTransfer($value);
        } elseif (is_string($value) && substr($value, 0, 1) == '<' && substr($value, -1) == '>') {
            $value = new XmlObjectDataTransfer(simplexml_load_string($value));
        } elseif (is_object($value)) {
            if (is_subclass_of($value, DataTransferInterface::class)) {
                return $value;
            } elseif ($value instanceof \SimpleXMLElement) {
                return new XmlObjectDataTransfer($value);
            }
            $value = new ObjectDataTransfer($value);
        } elseif (!is_scalar($value)) {
            $value = new ArrayDataTransfer([]);
        }
        return $value;
    }
}