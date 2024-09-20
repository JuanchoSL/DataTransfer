<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\UnprocessableEntityException;

class CsvDataTransfer extends ArrayDataTransfer
{
    protected string $separator = ',';

    /**
     * Summary of __construct
     * @param array<int|string>|string $csv
     * @throws \JuanchoSL\Exceptions\UnprocessableEntityException
     */
    public function __construct(array|string $csv)
    {
        if (is_string($csv)) {
            if (is_file($csv) && file_exists($csv)) {
                $csv = file($csv);
            } else {
                $csv = explode(PHP_EOL, $csv);
            }
        }
        if (!is_iterable($csv) or empty($csv)) {
            throw new UnprocessableEntityException("No contents has been received");
        }
        if (($current = current($csv)) === false) {
            throw new UnprocessableEntityException("No headers has been readed");
        }
        $result = [];
        if (count($csv) > 1) {
            $headers = str_getcsv((string) $current, $this->separator);
            $csv = array_slice($csv, 1);
            foreach ($csv as $line) {
                $body = str_getcsv((string) $line, $this->separator);
                $result[] = array_combine($headers, $body);
            }
        }
        parent::__construct($result);
    }

}