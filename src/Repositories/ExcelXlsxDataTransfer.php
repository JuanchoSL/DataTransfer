<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\PreconditionRequiredException;
use JuanchoSL\Exceptions\UnprocessableEntityException;
use Vtiful\Kernel\Excel;
use ZipArchive;

class ExcelXlsxDataTransfer extends ArrayDataTransfer
{

    public function __construct(string $excel)
    {
        if (!extension_loaded('xlswriter')) {
            //throw new PreconditionRequiredException("The extension XLSWRITER is not available");
        }
        $excel_data = [];
        if (is_string($excel)) {
            if (!is_file($excel) || str_contains($excel, chr(0)) !== false) {
                $excel_file = tempnam(sys_get_temp_dir(), 'xlsx');
                file_put_contents($excel_file, $excel);
                $excel = $excel_file;
            }
            if (is_file($excel) && file_exists($excel)) {
                if (class_exists(Excel::class) && empty($excel_data)) {
                    try {
                        $excel_data = $this->xlsWriter($excel);
                    } catch (\Exception $e) {

                    }
                }
                if (class_exists(Ziparchive::class) && empty($excel_data)) {
                    $excel_data = $this->zipReader($excel);
                }
            }
        }
        if (empty($excel_data)) {
            throw new UnprocessableEntityException("No contents has been received from '{$excel}'");
        }
        parent::__construct((array) $excel_data);
    }

    protected function fillData($xls)
    {
        $return = [];
        $headers = current($xls);
        $xls = array_slice($xls, 1);
        foreach ($xls as $line) {
            $return[] = array_combine($headers, array_slice($line, 0, count($headers)));
        }
        return $return;
    }

    protected function xlsWriter(string $file_path)
    {
        $excel_reader = new Excel(['path' => pathinfo($file_path, PATHINFO_DIRNAME)]);
        $excel_reader = $excel_reader->openFile(pathinfo($file_path, PATHINFO_BASENAME));
        $sheetList = $excel_reader->sheetList();
        if (empty($sheetList)) {
            $xls = $excel_reader->openSheet()->getSheetData();
            $excel_data = $this->fillData($xls);
        } else {
            foreach ($sheetList as $sheetName) {
                $xls = $excel_reader->openSheet($sheetName)->getSheetData();
                if (count($xls) > 1) {
                    $excel_data[$sheetName] = $this->fillData($xls);
                }
            }
        }
        return $excel_data ?? null;
    }

    protected function zipReader(string $file_path)
    {
        $input = new ZipArchive();
        $e = $input->open($file_path);
        if ($e !== true) {
            throw new \Exception($input->getStatusString(), $e);
        }
        $values = simplexml_load_string($this->getFileData($input, 'xl/workbook.xml'));
        $sheets = $refs = $res = [];
        foreach ($values->sheets->sheet as $sheet) {
            $sheets[(string) $sheet['sheetId']] = (string) $sheet['name'];
            $refs[(string) $sheet['name']] = [];
        }
        $values = simplexml_load_string($this->getFileData($input, 'xl/sharedStrings.xml'));
        foreach ($values as $in) {
            $res[] = (string) $in->t;
        }
        foreach ($sheets as $sheet_index => $sheet_name) {
            $references = simplexml_load_string($this->getFileData($input, "xl/worksheets/sheet{$sheet_index}.xml"));
            foreach ($references as $name => $childs) {
                if ($name == 'sheetPr') {
                    //$page = current($childs->attributes()['codeName']);
                } elseif ($name !== 'sheetData') {
                    continue;
                }
                foreach ($childs->row as $cs) {
                    $ref = [];
                    foreach ($cs as $c) {
                        $i = intval((string) $c->v);
                        if (empty($c['t']) && in_array($c['s'], [14, 16, 17, 18, 28, 32])) {
                            switch ($c['s']) {
                                case 14:
                                case 32:
                                    $i = intval($i);
                                    $i = ($i - 25569) * 86400;
                                    $i = date("d/m/Y", $i);
                                    break;

                                case 17:
                                case 18:
                                case 28:
                                    $i = number_format(floatval($i), 2, '.', '');
                                    break;
                            }
                            $value = $i;
                        } else {
                            $value = array_key_exists($i, $res) ? $res[$i] : (string) $c->v;
                        }

                        $ref[] = $value;
                    }
                    if (empty($headers)) {
                        $headers = $ref;
                        continue;
                    }
                    if (count($headers) > count($ref)) {
                        continue;
                    }
                    $refs[$sheet_name][] = array_combine($headers, array_slice($ref, 0, count($headers)));
                }
            }
            unset($headers);
        }
        return $refs;
    }

    protected function getFileData(ZipArchive $input, string $path)
    {
        if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
            return zlib_decode(stream_get_contents($input->getStreamName($path, ZipArchive::FL_COMPRESSED)));
        } else {
            return zlib_decode($input->getFromName($path, 0, ZipArchive::FL_COMPRESSED));
        }
    }
}