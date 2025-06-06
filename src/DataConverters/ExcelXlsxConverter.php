<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

use JuanchoSL\DataTransfer\Factories\DataTransferFactory;
use JuanchoSL\Exceptions\PreconditionRequiredException;
use Vtiful\Kernel\Excel;

class ExcelXlsxConverter extends ArrayConverter
{
    public function getData(): mixed
    {
        if (!extension_loaded('xlswriter')) {
            throw new PreconditionRequiredException("The extension XLSWRITER is not available");
        }
        $data = parent::getData();
        if (!is_iterable(current(current($data)))) {
            $data = [$data];
        } elseif (is_numeric(key(current(current($data))))) {
            $data = current($data);
        }
        $excel = new Excel(['path' => sys_get_temp_dir()]);

        foreach ($data as $page => $content) {
            $content = CsvConverter::convert(DataTransferFactory::create($content));
            $content = ArrayConverter::convert(DataTransferFactory::create($content));
            $page = is_numeric($page) ? 'sheet_' . $page : $page;
            if (empty($filePath)) {
                $filePath = $excel->fileName('open_xlsx_file.xlsx', $page);
            } else {
                $filePath->addSheet($page);
            }
            $filePath->header(array_keys(current($content)))->data($content);
        }
        return $filePath;
    }

    public function __tostring(): string
    {
        return file_get_contents($this->getData()->output());
    }
}