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
     * Summary of byTrasversable
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
                /*if (is_array($contents)) {
                    $format = Format::ARRAY;
                } elseif (is_object($contents)) {
                    $format = Format::OBJECT;
                }*/
            } elseif (static::isJsonString($contents)) {
                $format = Format::JSON;
            } elseif (static::isXmlString($contents)) {
                $format = Format::XML;
            }
        }
        if (!empty($format)) {
            $class = Format::read($format);
            return new $class($contents);
        } elseif (is_scalar($contents)) {
            if (!mb_check_encoding($contents, 'UTF-8')) {
                $original = mb_detect_encoding($contents, 'UTF-8', true);
                $contents = mb_convert_encoding($contents, 'UTF-8', $original);
            }
            return $contents;
        }

        throw new UnsupportedMediaTypeException("The format of the data can not be detected");

    }
    public static function byFile(string $filepath, ?Format $format = null): DataTransferInterface|string|int|float|bool|null
    {
        if (empty($format)) {
            $extension = pathinfo($filepath, PATHINFO_EXTENSION);
            if (!empty($extension)) {
                $format = Format::tryFrom($extension);
            }
        }
        if (!file_exists($filepath)) {
            throw new DestinationUnreachableException("The filepath: {$filepath} does not exists");
        }
        $contents = file_get_contents($filepath);
        return static::byString($contents, $format);
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
    public static function isXmlString(string $value): bool
    {
        return (substr($value, 0, 1) == '<' && substr($value, -1) == '>' && substr($value, 0, 9) !== '<![CDATA[');
    }
    public static function isSerialized(string $value): bool
    {
        if (in_array(mb_substr($value, -1), ['}', ';'])) {
            return ($value == 'b:0;') ? true : @unserialize($value) !== false;
        }
        return false;
        return !empty(preg_match('/^([C|O|a|i|s]+):\d+(:("\w+":\d+:)?([\\\s\w\d:"{};*.]+))?/', $value));

    }
}