<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

class CsvDataTransfer extends ArrayDataTransfer
{

    public function __construct(array|string $csv)
    {
        if (is_string($csv)) {
            if (is_file($csv) && file_exists($csv)) {
                $csv = file($csv);
            } else {
                $csv = explode(PHP_EOL, $csv);
            }
        }
        $result = [];
        $headers = str_getcsv(current($csv));
        $csv = array_slice($csv, 1);
        foreach ($csv as $line) {
            $body = str_getcsv($line);
            $result[] = array_combine($headers, $body);
        }
        parent::__construct($result);
    }

}