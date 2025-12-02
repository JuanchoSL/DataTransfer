<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Factories;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Enums\Format;
use JuanchoSL\DataTransfer\Repositories\ArrayDataTransfer;
use JuanchoSL\Exceptions\DestinationUnreachableException;
use JuanchoSL\Exceptions\UnsupportedMediaTypeException;
use JuanchoSL\Validators\Types\Strings\StringValidation;

class DataTransferFactory
{
    /**
     * Imports an iterable|trasversable element and converts to DTO
     * @param object|array<int|string, mixed> $contents
     * @return \JuanchoSL\DataTransfer\Contracts\DataTransferInterface
     */
    public static function byTrasversable(object|array $contents): DataTransferInterface
    {
        if ($contents instanceof DataTransferInterface) {
            return $contents;
        } elseif ($contents instanceof \SimpleXMLElement) {
            $format = Format::XML;
        } else {
            $format = (is_array($contents)) ? Format::ARRAY : Format::OBJECT;
        }
        $class = Format::read($format);
        return new $class($contents);
    }

    /**
     * Read and parse a string and try to detect his format in order to process with the correct parser
     * @param string $contents
     * @param mixed $format
     * @throws \JuanchoSL\Exceptions\UnsupportedMediaTypeException
     * @return \JuanchoSL\DataTransfer\Contracts\DataTransferInterface|string|int|float|bool|null
     */
    public static function byString(string $contents, ?Format $format = null): DataTransferInterface|string|int|float|bool|null
    {
        if (!empty($contents) && empty($format)) {
            if (StringValidation::isSerialized($contents)) {
                $data = @unserialize($contents);
            }
            if (!empty($data)) {
                $contents = $data;
                if (is_array($contents) || is_object($contents)) {
                    return static::byTrasversable($contents);
                }
            } elseif (static::isJsonString($contents)) {
                $format = Format::JSON;
            } elseif (static::isExcelXmlString($contents)) {
                $format = Format::EXCEL_XML;
            } elseif (static::isXmlString($contents)) {
                $format = Format::XML;
            } elseif (static::isCsvString($contents)) {
                $format = Format::CSV;
            } elseif (static::isExcelCsvString($contents)) {
                $format = Format::EXCEL_CSV;
            } elseif (static::isIniString($contents)) {
                $format = Format::INI;
            } elseif (static::isYamlString($contents) && function_exists('yaml_parse')) {
                $format = Format::YAML;
            } elseif (static::isTabbedString($contents)) {
                $format = Format::TAB;
            }
        }
        if (!empty($format)) {
            $class = Format::read($format);
            return new $class($contents);
        } elseif (is_scalar($contents)) {
            if (!empty($contents) && !mb_check_encoding($contents, 'UTF-8')) {
                $original = mb_detect_encoding($contents, ['ASCII', 'ISO-8859-1'], true);
                if (!empty($original)) {
                    $contents = mb_convert_encoding($contents, 'UTF-8', $original);
                }
            }
            return $contents;
        }

        throw new UnsupportedMediaTypeException("The format of the data can not be detected");
    }

    /**
     * Read and process a file, You can indicate a Format or try to use the file extension in order to parse his content
     * @param string $filepath
     * @param mixed $format
     * @throws \JuanchoSL\Exceptions\DestinationUnreachableException
     * @return bool|DataTransferInterface|float|int|string|null
     */
    public static function byFile(string $filepath, ?Format $format = null): DataTransferInterface|string|int|float|bool|null
    {
        if (empty($format)) {
            //mime_content_type($filepath);
            $extension = pathinfo($filepath, PATHINFO_EXTENSION);
            if (!empty($extension)) {
                switch (strtolower($extension)) {
                    case 'csv':
                        return static::byMimeType($filepath, ['application/csv']);

                    case 'xml':
                        return static::byMimeType($filepath, ['application/xml']);
                }
                $format = Format::tryFrom($extension);
            }
        }
        if (!file_exists($filepath)) {
            throw new DestinationUnreachableException("The filepath: {$filepath} does not exists");
        }
        $contents = file_get_contents($filepath);
        return static::byString($contents, $format);
    }

    /**
     * Process data (file or string) as the indicated mime-type
     * @param string $contents
     * @param string|iterable $content_types
     * @throws \JuanchoSL\Exceptions\UnsupportedMediaTypeException
     * @return bool|DataTransferInterface|float|int|string|null
     */
    public static function byMimeType(string $contents, string|iterable $content_types): DataTransferInterface
    {
        $content_types = is_iterable($content_types) ? $content_types : [$content_types];
        foreach ($content_types as $index => $content_type) {
            if (($length = strpos($content_type, ';')) !== false) {
                $content_type = substr($content_type, 0, $length);
            }
            switch ($content_type) {
                case 'application/json':
                    $data = Format::JSON;
                    break;

                case 'application/xml':
                case 'text/xml':
                    $data = (static::isExcelXmlString(static::iFIsFileGivemeData($contents))) ? Format::EXCEL_XML : Format::XML;
                    break;

                case 'application/yaml':
                    $data = Format::YAML;
                    break;

                case 'application/csv':
                case 'text/csv':
                    $data = (static::isExcelCsvString(current(static::iFIsFileGivemeData($contents, 1)))) ? Format::EXCEL_CSV : Format::CSV;
                    break;

                case 'text/tab-separated-values':
                    $data = Format::TAB;
                    break;

                case 'application/vnd.ms-excel':
                    $str = static::iFIsFileGivemeData($contents);
                    if (static::isExcelXmlString($str)) {
                        $data = Format::EXCEL_XML;
                    } elseif (static::isExcelCsvString($str)) {
                        $data = Format::EXCEL_CSV;
                    } elseif (static::isCsvString($str)) {
                        $data = Format::CSV;
                    } else {
                        $data = Format::EXCEL_XLSX;
                    }
                    break;

                case 'application/vnd.oasis.opendocument.spreadsheet':
                    $data = Format::ODS;
                    break;

                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    $data = Format::EXCEL_XLSX;
                    break;

                case 'application/octet-stream':
                default:
                    return $data = static::byString(static::iFIsFileGivemeData($contents));
            }
            if (!empty($data)) {
                break;
            }
        }
        if (empty($data)) {
            throw new UnsupportedMediaTypeException("Any media-type are supported from ['" . implode(',', $content_types) . "']");
        }
        $content_types = $content_type;
        return (is_file($contents) && file_exists($contents)) ? static::byFile($contents, $data) : static::byString($contents, $data);
    }

    public static function create(mixed $value = null): DataTransferInterface|string|int|float|bool|null
    {
        if (is_string($value)) {
            if (is_file($value) && file_exists($value)) {
                return static::byFile($value);
            } else {
                return static::byString($value);
            }
        } elseif (is_object($value) || is_array($value)) {
            return static::byTrasversable($value);
        } elseif (is_null($value)) {
            return new ArrayDataTransfer([]);
        } else {
            return $value;
        }

    }

    public static function isJsonString(string $value): bool
    {
        return (substr($value, 0, 1) == '{' && substr($value, -1) == '}') || (substr($value, 0, 1) == '[' && substr($value, -1) == ']');
    }

    public static function isTabbedString(string $value): bool
    {
        return (!empty($value) && str_contains($value, "\t"));
    }

    public static function isXmlString(string $value): bool
    {
        return (!empty($value) && substr($value, 0, 1) == '<' && substr($value, -1) == '>' && substr($value, 0, 9) !== '<![CDATA[');
    }

    public static function isYamlString(string $value): bool
    {
        if (!function_exists('yaml_parse') || empty($value) || strlen($value) < 10) {
            return false;
        }
        $ndocs = 0;
        $yaml = @yaml_parse($value, 0, $ndocs/*, array('!date' => 'cb_yaml_date')*/);
        return !empty($yaml) && is_array($yaml);
    }

    public static function isIniString(string $value): bool
    {
        if (empty($value)) {
            return false;
        }
        $data = @parse_ini_string($value);
        $datas = @parse_ini_string($value, true);
        return (!empty($data) && is_array($data)) || (!empty($datas) && is_array($datas));
    }

    public static function isCsvString(string $value): bool
    {
        if (empty($value)) {
            return false;
        }
        $value = str_replace(["\r\n", "\n"], "\r", $value);
        $datas = explode("\r", $value);
        $current = current($datas);
        $num = substr_count($current, ',');
        if (empty($num) || count($datas) <= 1) {
            return false;
        }
        $data = @str_getcsv($current, ',', '"', "\\");
        return is_array($data) && !empty($data) && (count($data) > $num);// && !empty(current($data));
    }

    public static function isExcelXmlString(string $value): bool
    {
        return (!empty($value) && str_contains($value, 'mso-application progid="Excel.Sheet"'));
    }

    public static function isExcelCsvString(string $value): bool
    {
        if (empty($value)) {
            return false;
        }
        $value = str_replace(["\r\n", "\n"], "\r", $value);
        $data = explode("\r", $value);
        $current = current($data);
        $num = substr_count($current, ';');
        if (empty($num) || count($data) <= 1) {
            return false;
        }
        $data = @str_getcsv($current, ';', '"', "\\");
        return is_array($data) && !empty($data) && (count($data) > $num);// && !empty(current($data));
    }

    public static function isSerialized(string $value): bool
    {
        if (empty($value)) {
            return false;
        }
        if ($value != 'b:0;') {
            $value = @unserialize($value);
        }
        return ($value !== false);
    }

    protected static function iFIsFileGivemeData(string $file, int $num_lines_or_all_string = 0): array|string
    {
        if (is_file($file) && file_exists($file) && empty($num_lines_or_all_string)) {
            return file_get_contents($file);
        } elseif (empty($num_lines_or_all_string)) {
            return $file;
        } elseif (is_file($file) && file_exists($file)) {
            return array_slice(file($file), 0, $num_lines_or_all_string);
        } else {
            $path = tempnam(sys_get_temp_dir(), 'dto');
            file_put_contents($path, $file);
            return array_slice(file($path), 0, $num_lines_or_all_string);
        }
    }
}