<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class CsvConverter extends ArrayConverter
{

    protected string $separator = ',';

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

        return $this->collection2csv($data);
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
                $title[$key] = strpos($key, ' ') !== false || strpos($key, $this->separator) !== false ? '"' . $key . '"' : $key;
                if (!empty($value)) {
                    $value = '"' . $value . '"';
                }
                $data[$key] = $value;
            }
        }
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