<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

class ExcelCsvDataTransfer extends CsvDataTransfer
{
    protected string $separator = ';';

}