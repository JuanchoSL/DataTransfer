<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Factories;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Repositories\ArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\JsonDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ObjectDataTransfer;
use JuanchoSL\DataTransfer\Repositories\XmlObjectDataTransfer;

class DataTransferFactory
{
    public static function create(mixed $value = null): DataTransferInterface|string|int|float|bool|null
    {
        if (is_array($value)) {
            $value = new ArrayDataTransfer($value);
        } elseif (is_string($value)) {
            if ((substr($value, 0, 1) == '{' && substr($value, -1) == '}') || (substr($value, 0, 1) == '[' && substr($value, -1) == ']')) {
                $value = new JsonDataTransfer($value);
            } elseif (substr($value, 0, 1) == '<' && substr($value, -1) == '>') {
                $value = new XmlObjectDataTransfer(simplexml_load_string($value));
            } elseif (!mb_check_encoding($value, 'UTF-8')) {
                $original = mb_detect_encoding($value, 'UTF-8', true);
                $value = mb_convert_encoding($value, 'UTF-8', $original);
            }
        } elseif (is_object($value)) {
            if (!is_subclass_of($value, DataTransferInterface::class)) {
                if ($value instanceof \SimpleXMLElement) {
                    $value = new XmlObjectDataTransfer($value);
                } else {
                    $value = new ObjectDataTransfer($value);
                }
            }
        } elseif (!is_scalar($value)) {
            $value = new ArrayDataTransfer([]);
        }
        return $value;
    }
}