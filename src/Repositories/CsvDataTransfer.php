<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\UnprocessableEntityException;

class CsvDataTransfer extends ArrayDataTransfer
{
    protected string $separator = ',';

    public function __construct(array|string $csv)
    {
        if (is_string($csv)) {
            if (is_file($csv) && file_exists($csv)) {
                $csv = file($csv);
            } else {
                $csv = explode(PHP_EOL, $csv);
            }
        }
        if (empty($csv)) {
            throw new UnprocessableEntityException("No contents has been received");
        }
        $result = [];
        $headers = str_getcsv(current($csv), $this->separator);
        $csv = array_slice($csv, 1);
        foreach ($csv as $line) {
            $body = str_getcsv($line, $this->separator);
            $result[] = array_combine($headers, $body);
        }
        parent::__construct($result);
    }

}