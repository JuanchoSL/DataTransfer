<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Factories;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\DataConverters\ArrayConverter;
use JuanchoSL\DataTransfer\DataConverters\CsvConverter;
use JuanchoSL\DataTransfer\DataConverters\ExcelCsvConverter;
use JuanchoSL\DataTransfer\DataConverters\ExcelXlsxConverter;
use JuanchoSL\DataTransfer\DataConverters\IniConverter;
use JuanchoSL\DataTransfer\DataConverters\JsonConverter;
use JuanchoSL\DataTransfer\DataConverters\ObjectConverter;
use JuanchoSL\DataTransfer\DataConverters\TabsvConverter;
use JuanchoSL\DataTransfer\DataConverters\XmlConverter;
use JuanchoSL\DataTransfer\DataConverters\XmlObjectConverter;
use JuanchoSL\DataTransfer\DataConverters\YamlConverter;
use JuanchoSL\Exceptions\UnsupportedMediaTypeException;

class DataConverterFactory
{
    /**
     * Summary of asArray
     * @param \JuanchoSL\DataTransfer\Contracts\DataTransferInterface $dto
     * @return array<int|string, mixed>
     */
    public static function asArray(DataTransferInterface $dto): array
    {
        return ArrayConverter::convert($dto);
    }

    public static function asJson(DataTransferInterface $dto): string
    {
        return JsonConverter::convert($dto);
    }

    public static function asXml(DataTransferInterface $dto): string
    {
        return XmlConverter::convert($dto);
    }

    public static function asCsv(DataTransferInterface $dto): string
    {
        return CsvConverter::convert($dto);
    }

    public static function asTabs(DataTransferInterface $dto): string
    {
        return TabsvConverter::convert($dto);
    }

    public static function asExcelCsv(DataTransferInterface $dto): string
    {
        return ExcelCsvConverter::convert($dto);
    }

    public static function asYaml(DataTransferInterface $dto): string
    {
        return YamlConverter::convert($dto);
    }

    public static function asIni(DataTransferInterface $dto): string
    {
        return IniConverter::convert($dto);
    }

    public static function asXmlObject(DataTransferInterface $dto): \SimpleXMLElement
    {
        return XmlObjectConverter::convert($dto);
    }

    public static function asObject(DataTransferInterface $dto): \stdClass
    {
        return ObjectConverter::convert($dto);
    }

    public static function asMimeType(DataTransferInterface $dto, string|iterable &$content_types): string
    {
        $content_types = is_iterable($content_types) ? $content_types : [$content_types];
        foreach ($content_types as $index => $content_type) {
            if (($length = strpos($content_type, ';')) !== false) {
                $content_type = substr($content_type, 0, $length);
            }
            switch ($content_type) {
                case 'application/json':
                    $data = static::asJson($dto);
                    break;

                case 'application/xml':
                case 'text/xml':
                    $data = static::asXml($dto);
                    break;

                case 'application/yaml':
                    $data = static::asYaml($dto);
                    break;

                case 'text/csv':
                case 'application/csv':
                    $data = static::asCsv($dto);
                    break;

                case 'text/tab-separated-values':
                    $data = static::asTabs($dto);
                    break;

                case 'application/vnd.ms-excel':
                    $data = static::asExcelCsv($dto);
                    break;

                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    $data = new ExcelXlsxConverter($dto);
                    break;

                default:
                    break;
            }
            if (!empty($data)) {
                break;
            }
        }
        if (empty($data)) {
            throw new UnsupportedMediaTypeException("Any media-type are supported from ['" . implode(',', $content_types) . "']");
        }
        $content_types = $content_type;
        return (string) $data;
    }
}