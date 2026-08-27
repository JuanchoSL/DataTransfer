<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

use JuanchoSL\Validators\Types\Strings\StringValidation;

class IniConverter extends ArrayConverter
{

    /**
     * Parse and returns the ini string composition
     * @return string
     */
    public function getData(): mixed
    {
        $data = parent::getData();
        if (!is_numeric(key($data))) {
            //$data = [$data];
        }
        $str = '';
        return trim($this->collection2ini($str, $data), "\r\n");
    }

    protected function collection2ini(string &$str, array $array, string|int $title = ''): string
    {
        foreach ($array as $key => $value) {
            if (is_scalar($value)) {
                if (!empty($title) /*&& is_string($title)*/) {
                    //$str .= PHP_EOL . "[{$title}]" . PHP_EOL;
                    unset($title);
                }
                if (StringValidation::isMultibyte($value) or StringValidation::isValueContainingAny($value, ' ', '=', '"')) {
                    $value = '"' . $value . '"';
                }
                $str .= "{$key}={$value}" . PHP_EOL;
            } elseif (is_iterable($value)) {
                if (empty($title)) {
                    $str .= PHP_EOL . "[{$key}]" . PHP_EOL;
                } else {
                    if (is_numeric($key)) {
                        $str .= PHP_EOL . "[{$title}]" . PHP_EOL;
                        $key = '';
                    } else {
                        $str .= PHP_EOL . "//{$title}" . PHP_EOL;
                    }
                    unset($title);
                }
                $this->collection2ini($str, $value, $key);
            }
        }
        return $str;
    }

    /**
     * Returns the ini string composition
     * @return string
     */
    public function __tostring(): string
    {
        return $this->getData();
    }
}