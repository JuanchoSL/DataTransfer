<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class CsvConverter extends AbstractConverter
{

    public function getData()
    {
        $data = ArrayConverter::convert($this->data);
        return $this->collection2csv($data);
    }

    protected function array2csv($array, &$title, &$data)
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
    protected function collection2csv($array)
    {
        $results = [];
        $title = [];
        foreach ($array as $key => $value) {
            $data = [];
            $this->array2csv($value, $title, $data);
            $diff = array_diff_key($data, $title);
            foreach ($diff as $key) {
                $title[$key] = $key;
            }
            $results[] = $data;
        }
        $texts = [];
        foreach ($results as $index => $result) {
            $text = '';
            foreach ($title as $key => $value) {
                $text .= $result[$key] ?? '';
                $text .= ',';
            }
            $texts[] = substr($text, 0, -1);
        }
        return implode(',', $title) . PHP_EOL . implode(PHP_EOL, $texts);
    }
}