<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class ExcelCsvConverter extends CsvConverter
{
    protected string $separator = ';';
}