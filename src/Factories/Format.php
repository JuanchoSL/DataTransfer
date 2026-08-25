<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Factories;

use JuanchoSL\DataTransfer\DataConverters\ArrayConverter;
use JuanchoSL\DataTransfer\DataConverters\CsvConverter;
use JuanchoSL\DataTransfer\DataConverters\DifConverter;
use JuanchoSL\DataTransfer\DataConverters\ExcelCsvConverter;
use JuanchoSL\DataTransfer\DataConverters\ExcelXlsxConverter;
use JuanchoSL\DataTransfer\DataConverters\IniConverter;
use JuanchoSL\DataTransfer\DataConverters\JsonConverter;
use JuanchoSL\DataTransfer\DataConverters\ObjectConverter;
use JuanchoSL\DataTransfer\DataConverters\TabsvConverter;
use JuanchoSL\DataTransfer\DataConverters\XmlConverter;
use JuanchoSL\DataTransfer\DataConverters\XmlObjectConverter;
use JuanchoSL\DataTransfer\DataConverters\YamlConverter;
use JuanchoSL\DataTransfer\Repositories\ArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\CsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\DifDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ExcelCsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ExcelXlsxDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ExcelXmlDataTransfer;
use JuanchoSL\DataTransfer\Repositories\IniDataTransfer;
use JuanchoSL\DataTransfer\Repositories\JsonDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ObjectDataTransfer;
use JuanchoSL\DataTransfer\Repositories\OdsDataTransfer;
use JuanchoSL\DataTransfer\Repositories\TabsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\XmlDataTransfer;
use JuanchoSL\DataTransfer\Repositories\YamlDataTransfer;
use ReflectionClass;
use ReflectionClassConstant;
use ValueError;

class Format
{

    const ARRAY = 'ARRAY';
    const CSV = 'CSV';
    const EXCEL_CSV = 'EXCEL_CSV';
    const EXCEL_XLSX = 'EXCEL_XLSX';
    const EXCEL_XML = 'EXCEL_XML';
    const ODS = 'ODS';
    const INI = 'INI';
    const TAB = 'TAB';
    const JSON = 'JSON';
    const OBJECT = 'OBJECT';
    const XML = 'XML';
    const XML_OBJECT = 'XML_OBJECT';
    const YAML = 'YAML';
    const YML = 'YML';
    const DIF = 'DIF';

    protected $name;
    protected $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
    public function __get($name)
    {
        return $this->{$name};
    }

    public static function make(Format|string $type): string
    {
        return self::read($type);
    }
    public static function read(Format|string $type): string
    {
        $type = (is_string($type)) ? static::tryFrom($type) : $type;
        return match ($type->name) {
            static::ARRAY => ArrayDataTransfer::class,
            static::CSV => CsvDataTransfer::class,
            static::EXCEL_CSV => ExcelCsvDataTransfer::class,
            static::EXCEL_XLSX => ExcelXlsxDataTransfer::class,
            static::EXCEL_XML => ExcelXmlDataTransfer::class,
            static::ODS => OdsDataTransfer::class,
            static::INI => IniDataTransfer::class,
            static::TAB => TabsvDataTransfer::class,
            static::JSON => JsonDataTransfer::class,
            static::OBJECT => ObjectDataTransfer::class,
            static::XML, static::XML_OBJECT => XmlDataTransfer::class,
            static::YAML, static::YML => YamlDataTransfer::class,
            static::DIF => DifDataTransfer::class,
        };
    }
    public static function write(Format|string $type): string
    {
        $type = (is_string($type)) ? static::tryFrom($type) : $type;
        return match ($type->name) {
            static::ARRAY => ArrayConverter::class,
            static::CSV => CsvConverter::class,
            static::EXCEL_CSV => ExcelCsvConverter::class,
            static::EXCEL_XLSX => ExcelXlsxConverter::class,
            static::INI => IniConverter::class,
            static::TAB => TabsvConverter::class,
            static::JSON => JsonConverter::class,
            static::OBJECT => ObjectConverter::class,
            static::XML => XmlConverter::class,
            static::DIF => DifConverter::class,
            static::XML_OBJECT => XmlObjectConverter::class,
            static::YAML, static::YML => YamlConverter::class,
        };
    }
    public static function from(int|string $type): static
    {
        $typee = static::tryFrom($type);
        if (is_null($typee)) {
            throw new ValueError("No relative data for {$type}");
        }
        return $typee;
    }

    public static function tryFrom(int|string $type): ?static
    {
        if (is_integer($type)) {
            $cases = static::cases();
            return (isset($cases[$type])) ? $cases[$type] : null;
        }

        return match (strtoupper($type)) {
            static::ARRAY => new static('ARRAY', 'array'),
            static::CSV => new static('CSV', 'csv'),
            static::EXCEL_CSV, 'CSV' => new static('EXCEL_CSV', 'csvx'),
            static::EXCEL_XLSX, 'XLSX' => new static('EXCEL_XLSX', 'xlsx'),
            static::EXCEL_XML, 'XMLX' => new static('EXCEL_XML', 'xmlx'),
            static::INI => new static(strtoupper($type), 'ini'),
            static::TAB, 'TAB' => new static('TAB', 'tab'),
            static::JSON => new static(strtoupper($type), 'json'),
            static::OBJECT => new static(strtoupper($type), 'object'),
            static::ODS => new static(strtoupper($type), 'ods'),
            static::DIF => new static(strtoupper($type), 'dif'),
            static::XML => new static(strtoupper($type), 'xml'),
            static::XML_OBJECT => new static(strtoupper($type), 'xml_object'),
            static::YAML, static::YML => new static(strtoupper($type), 'yml'),
            default => null
        };
    }
    public static function cases(): array
    {
        $results = [];
        $reflection = new ReflectionClass(static::class);
        foreach ($reflection->getConstants() as $constant) {
            $constant = new ReflectionClassConstant(static::class, $constant);
            $results[] = $constant->getName();
        }
        return $results;


        foreach ([
            static::ARRAY ,
            static::CSV,
            static::EXCEL_CSV,
            static::EXCEL_XLSX,
            static::EXCEL_XML,
            static::ODS,
            static::INI,
            static::TAB,
            static::JSON,
            static::OBJECT,
            static::XML,
            static::XML_OBJECT,
            static::YAML,
            static::YML,
            static::DIF
        ] as $enum) {
            $results[] = $enum;
        }
        return $results;
    }
}
