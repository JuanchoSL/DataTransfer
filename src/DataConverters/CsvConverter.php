<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

use JuanchoSL\DataManipulation\Manipulators\Strings\StringsManipulators;

class CsvConverter extends ArrayConverter
{

    protected string $separator = ',';
    protected string $enclose = '"';

    /**
     * Parse and returns the csv string composition using , as separator
     * @return string
     */
    public function getData(): mixed
    {
        $data = parent::getData();
        while ((!is_numeric($key = key($data)))) {
            $data = current($data);
        }
        //return $this->collection2csv($data);
        
        $headers = false;
        $memory = fopen("php://memory", "rw");
        foreach ($data as $values) {
            if (!$headers) {
                $headers = true;
                fputcsv($memory, array_keys($values), $this->separator, $this->enclose, "\\");
            }
            fputcsv($memory, $values, $this->separator, $this->enclose, "\\");
        }
        rewind($memory);
        $response = fread($memory, fstat($memory)['size']);
        fclose($memory);
        return (string)(new StringsManipulators($response))->eol(PHP_EOL)->trim();
    }

    /**
     * Summary of array2csv
     * @param iterable $array
     * @param array<int|string, mixed> $title
     * @param array<int|string, mixed> $data
     * @return void
     */
    protected function array2csv(iterable $array, array &$title, array &$data): void
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->array2csv($value, $title, $data);
            } else {
                //$title[$key] = strpos($key, ' ') !== false || strpos($key, $this->separator) !== false ? '"' . $key . '"' : $key;
                $title[$key] = $this->needsEnclose($key);
                if (!empty($value)) {
                    //$value = '"' . $value . '"';
                    $value = $this->needsEnclose($value);
                }
                $data[$key] = $value;
            }
        }
    }

    protected function needsEnclose(string $value): string
    {
        if (strpos($this->enclose, $value) !== false) {
            $value = str_replace($this->enclose, $this->enclose . $this->enclose, $value);
        }
        foreach ([$this->enclose, $this->separator, "\n", "\r", "\t", " "] as $critical_char) {
            if (strpos($value, $critical_char) !== false) {
                return $this->enclose . $value . $this->enclose;
            }
        }
        return $value;
    }
    protected function collection2csv(iterable $array): string
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

    /**
     * Returns the csv string composition using , as separator
     * @return string
     */
    public function __tostring(): string
    {
        return $this->getData();
    }
}