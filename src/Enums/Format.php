<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Enums;

use JuanchoSL\DataTransfer\Repositories\ArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\CsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\IniDataTransfer;
use JuanchoSL\DataTransfer\Repositories\JsonDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ObjectDataTransfer;
use JuanchoSL\DataTransfer\Repositories\XmlDataTransfer;
use JuanchoSL\DataTransfer\Repositories\YamlDataTransfer;

enum Format: string
{
    case ARRAY = 'array';
    case CSV = 'csv';
    case INI = 'ini';
    case JSON = 'json';
    case OBJECT = 'object';
    case XML = 'xml';
    case YAML = 'yaml';
    case YML = 'yml';

    public static function make(Format $type)
    {
        return match ($type) {
            static::ARRAY => ArrayDataTransfer::class,
            static::CSV => CsvDataTransfer::class,
            static::INI => IniDataTransfer::class,
            static::JSON => JsonDataTransfer::class,
            static::OBJECT => ObjectDataTransfer::class,
            static::XML => XmlDataTransfer::class,
            static::YAML, static::YML => YamlDataTransfer::class,
        };
    }
}