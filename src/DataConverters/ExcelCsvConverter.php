<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class ExcelCsvConverter extends CsvConverter
{
    protected string $separator = ';';

    /**
     * Parse and returns the csv string composition using ; as separator and prepending a BOM
     * @return string
     */
    public function __tostring(): string
    {
        return chr(239) . chr(187) . chr(191) . $this->getData();
    }
}