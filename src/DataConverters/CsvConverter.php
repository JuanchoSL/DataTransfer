<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class CsvConverter extends ArrayConverter
{

    protected string $separator = ',';

    public function getData(): mixed
    {
        //$data = ArrayConverter::convert($this->data);
        $data = parent::getData();
        if (!is_numeric(key($data))) {
            $data = [$data];
        }
        return $this->collection2csv($data);
    }

    protected function array2csv(iterable $array, array &$title, array &$data): void
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->array2csv($value, $title, $data);
            } else {
                $title[$key] = $key;
                if (!empty($value)) {
                    $value = '"' . $value . '"';
                }
                $data[$key] = $value;
            }
        }
    }

    protected function collection2csv(iterable $array)
    {
        $results = [];
        $title = [];
        foreach ($array as $value) {
            $data = [];
            $this->array2csv($value, $title, $data);
            $diff = array_diff_key($data, $title);
            foreach ($diff as $key) {
                $title[$key] = $key;
            }
            $results[] = $data;
        }
        $texts = [];
        foreach ($results as $result) {
            $text = '';
            foreach ($title as $key => $value) {
                $text .= $result[$key] ?? '';
                $text .= $this->separator;
            }
            $texts[] = substr($text, 0, -1);
        }
        return implode($this->separator, $title) . PHP_EOL . implode(PHP_EOL, $texts);
    }
}