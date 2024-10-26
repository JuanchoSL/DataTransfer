<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Enums;

use JuanchoSL\DataTransfer\DataConverters\ArrayConverter;
use JuanchoSL\DataTransfer\DataConverters\CsvConverter;
use JuanchoSL\DataTransfer\DataConverters\ExcelCsvConverter;
use JuanchoSL\DataTransfer\DataConverters\ExcelXlsxConverter;
use JuanchoSL\DataTransfer\DataConverters\IniConverter;
use JuanchoSL\DataTransfer\DataConverters\JsonConverter;
use JuanchoSL\DataTransfer\DataConverters\ObjectConverter;
use JuanchoSL\DataTransfer\DataConverters\XmlConverter;
use JuanchoSL\DataTransfer\DataConverters\XmlObjectConverter;
use JuanchoSL\DataTransfer\DataConverters\YamlConverter;
use JuanchoSL\DataTransfer\Repositories\ArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\CsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ExcelCsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ExcelXlsxDataTransfer;
use JuanchoSL\DataTransfer\Repositories\IniDataTransfer;
use JuanchoSL\DataTransfer\Repositories\JsonDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ObjectDataTransfer;
use JuanchoSL\DataTransfer\Repositories\XmlDataTransfer;
use JuanchoSL\DataTransfer\Repositories\YamlDataTransfer;

enum Format: string
{
    case ARRAY = 'array';
    case CSV = 'csv';
    case EXCEL_CSV = 'csvx';
    case EXCEL_XLSX = 'xlsx';
    case INI = 'ini';
    case JSON = 'json';
    case OBJECT = 'object';
    case XML = 'xml';
    case XML_OBJECT = 'xml_object';
    case YAML = 'yaml';
    case YML = 'yml';

    public static function make(Format $type): string
    {
        return self::read($type);
    }
    public static function read(Format $type): string
    {
        return match ($type) {
            static::ARRAY => ArrayDataTransfer::class,
            static::CSV => CsvDataTransfer::class,
            static::EXCEL_CSV => ExcelCsvDataTransfer::class,
            static::EXCEL_XLSX => ExcelXlsxDataTransfer::class,
            static::INI => IniDataTransfer::class,
            static::JSON => JsonDataTransfer::class,
            static::OBJECT => ObjectDataTransfer::class,
            static::XML, static::XML_OBJECT => XmlDataTransfer::class,
            static::YAML, static::YML => YamlDataTransfer::class,
        };
    }
    public static function write(Format $type): string
    {
        return match ($type) {
            static::ARRAY => ArrayConverter::class,
            static::CSV => CsvConverter::class,
            static::EXCEL_CSV => ExcelCsvConverter::class,
            static::EXCEL_XLSX => ExcelXlsxConverter::class,
            static::INI => IniConverter::class,
            static::JSON => JsonConverter::class,
            static::OBJECT => ObjectConverter::class,
            static::XML => XmlConverter::class,
            static::XML_OBJECT => XmlObjectConverter::class,
            static::YAML, static::YML => YamlConverter::class,
        };
    }
}