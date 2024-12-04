<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class ExcelCsvConverter extends CsvConverter
{
    protected string $separator = ';';

    public function __tostring(): string
    {
        return chr(239) . chr(187) . chr(191) . $this->getData();
    }
}