<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Factories;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\DataConverters\ArrayConverter;
use JuanchoSL\DataTransfer\DataConverters\CsvConverter;
use JuanchoSL\DataTransfer\DataConverters\JsonConverter;
use JuanchoSL\DataTransfer\DataConverters\ObjectConverter;
use JuanchoSL\DataTransfer\DataConverters\XmlConverter;
use JuanchoSL\DataTransfer\DataConverters\XmlObjectConverter;

class DataConverterFactory
{
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

    public static function asXmlObject(DataTransferInterface $dto): \SimpleXMLElement
    {
        return XmlObjectConverter::convert($dto);
    }

    public static function asObject(DataTransferInterface $dto): \stdClass
    {
        return ObjectConverter::convert($dto);
    }
}