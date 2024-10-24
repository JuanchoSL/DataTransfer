<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\PreconditionRequiredException;
use JuanchoSL\Exceptions\UnprocessableEntityException;
use Vtiful\Kernel\Excel;

class ExcelXlsxDataTransfer extends ArrayDataTransfer
{

    public function __construct(string $excel)
    {
        if(!extension_loaded('xlswriter')){
            throw new PreconditionRequiredException("The extension XLSWRITER is not available");
        }
        $excel_data = [];
        if (is_string($excel)) {
            if (is_file($excel) && file_exists($excel)) {
                $excel_reader = new Excel(['path' => pathinfo($excel, PATHINFO_DIRNAME)]);
                $excel_reader = $excel_reader->openFile(pathinfo($excel, PATHINFO_BASENAME));
                $sheetList = $excel_reader->sheetList();
                if (empty($sheetList)) {
                    $csv = $excel_reader->openSheet()->getSheetData();
                    $excel_data = $this->fillData($csv);
                } else {
                    foreach ($sheetList as $sheetName) {
                        $csv = $excel_reader->openSheet($sheetName)->getSheetData();
                        if (count($csv) > 1) {
                            $excel_data[$sheetName] = $this->fillData($csv);
                        }
                    }
                }
            }
        }
        if (empty($excel_data)) {
            throw new UnprocessableEntityException("No contents has been received from '{$excel}'");
        }
        parent::__construct((array) $excel_data);
    }

    protected function fillData($csv)
    {
        $return = [];
        $headers = current($csv);
        $csv = array_slice($csv, 1);
        foreach ($csv as $line) {
            $return[] = array_combine($headers, $line);
        }
        return $return;
    }
}