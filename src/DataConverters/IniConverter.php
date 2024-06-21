<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class IniConverter extends ArrayConverter
{

    public function getData()
    {
        $data = parent::getData();
        if (!is_numeric(key($data))) {
            $data = [$data];
        }
        $str = '';
        return trim($this->collection2ini($data, $str),"\r\n");
    }

    protected function collection2ini($array, &$str, $title = '')
    {
        foreach ($array as $key => $value) {
            if (is_scalar($value)) {
                if (!empty($title) && is_string($title)) {
                    $str .= PHP_EOL . "[{$title}]" . PHP_EOL;
                    unset($title);
                }
                $str .= "{$key}={$value}" . PHP_EOL;
            } elseif (is_iterable($value)) {
                if (!empty($title)) {
                    if (is_numeric($key)) {
                        $str .= PHP_EOL . "[{$title}]" . PHP_EOL;
                        $key = '';
                    } else {
                        $str .= PHP_EOL . "//{$title}" . PHP_EOL;
                    }
                    unset($title);
                }
                $this->collection2ini($value, $str, $key);
            }
        }
        return $str;
    }
}