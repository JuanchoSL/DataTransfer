<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class DifConverter extends ArrayConverter
{

    /**
     * Retrieve the data as DIF string
     * @return mixed
     */
    public function getData(): mixed
    {
        $data = parent::getData();
        $result = [
            "TABLE",
            "0,1",
            '"JuanchoSL DataTransfer"',
            "VECTORS",
            sprintf("0,%d", count(current($data))),
            '""',
            "TUPLES",
            sprintf("0,%d", count($data) + 1),
            '""',
            'DATA',
            '0,0',
            '""',
            '-1,0',
        ];
        $has_key = false;
        foreach ($data as $key => $value) {
            $value = (array) $value;
            if (!$has_key) {
                $result = $this->addRow(array_keys($value), $result);
                $has_key = true;
            }
            $result = $this->addRow($value, $result);
        }
        $result[] = "EOD";
        return implode(PHP_EOL, $result);
    }

    /**
     * Serialize and return the element
     * @return string
     */
    public function __tostring(): string
    {
        return $this->getData();
    }

    protected function addRow(array $iterable, array $parent)
    {
        $parent[] = "BOT";
        foreach ($iterable as $key => $value) {
            if (is_numeric($value)) {
                $parent[] = "0," . $value;
                $parent[] = "V";
            } elseif (is_string($value)) {
                $parent[] = "1,0";
                $parent[] = '"' . trim($value, '"') . '"';
            } elseif (is_bool($value)) {
                $parent[] = "0," . intval($value);
                $parent[] = ($value) ? 'TRUE' : 'FALSE';
            }
        }
        $parent[] = "-1,0";
        return $parent;
    }
}