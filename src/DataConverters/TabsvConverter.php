<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class TabsvConverter extends ArrayConverter
{

    /**
     * Parse and returns the Tab separated string composition
     * @return string
     */
    public function getData(): mixed
    {
        $data = parent::getData();
        if (!is_numeric(key($data))) {
            $data = [$data];
        }
        return trim($this->collection2txt( $data), "\r\n");
    }
    
    protected function collection2txt(array $array): string
    {
        $str = implode("\t", array_keys(current($array))) . PHP_EOL;
        foreach ($array as $value) {
            $str .= implode("\t", array_values($value)) . PHP_EOL;
        }
        return $str;
    }

    /**
     * Returns the tab separated values string composition
     * @return string
     */
    public function __tostring(): string
    {
        return $this->getData();
    }
}