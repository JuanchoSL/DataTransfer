<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\UnprocessableEntityException;
use SimpleXMLElement;
use ZipArchive;

class OdsDataTransfer extends ArrayDataTransfer
{

    public function __construct(string $object)
    {
        if (is_string($object)) {
            if (!is_file($object) || str_contains($object, chr(0)) !== false) {
                $object_file = tempnam(sys_get_temp_dir(), 'ods');
                file_put_contents($object_file, $object);
                $object = $object_file;
            }
            if (is_file($object) && file_exists($object)) {
                $input = new ZipArchive();
                $e = $input->open($object, ZipArchive::RDONLY);
                if ($e !== true) {
                    throw new \Exception($input->getStatusString(), $e);
                }
                $xmls = simplexml_load_string(zlib_decode($this->getFileData($input, 'content.xml')));
                $ns = $xmls->getDocNamespaces(true, false);
                $childs = $xmls->children($ns['office']);//->children($ns['table'])->children($ns['table']);
                $refs = $res = [];
                foreach ($childs->body->spreadsheet->children($ns['table'])->table as $sheet) {
                    $sheet_name = (string) $sheet['name'];
                    $headers = [];
                    foreach ($sheet->{'table-row'} as $row) {
                        $res = [];
                        foreach ($row->{'table-cell'} as $cell) {
                            $v = $cell->children($ns['text']);//->asXML();
                            if ($v instanceof SimpleXMLElement) {
                                if (empty((string) $v->p) && $cell['style-name'] == 'ce1' && isset($cell['number-columns-repeated'])) {
                                    for ($i = 1; $i < $cell['number-columns-repeated']; $i++) {
                                        $res[] = $v = '';
                                    }
                                } else {
                                    //$v = $v?->asXML() ?? '.';
                                    $v = property_exists($v, 'p') ? (string) $v->p : $v?->asXML();
                                }
                            }
                            $res[] = $v ?? ' ';
                        }
                        if (empty($headers)) {
                            $headers = array_filter($res);
                            foreach ($headers as $index => $header) {
                                $headers[$index] = (is_object($header) && property_exists($header, 'p')) ? $header->p : $header;
                            }
                            continue;
                        }
                        if (count($headers) > count($res)) {
                            continue;
                        }
                        $refs[$sheet_name][] = array_combine($headers, array_slice($res, 0, count($headers)));
                    }
                }
                unset($headers);
                $input->close();
            }
            if (empty($refs)) {
                throw new UnprocessableEntityException("No contents has been received");
            }
        }
        parent::__construct($refs);
        //$this->data = $refs;
    }

    protected function getFileData(ZipArchive $input, string $path)
    {
        if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
            return stream_get_contents($input->getStreamName($path, ZipArchive::FL_COMPRESSED));
        } else {
            return $input->getFromName($path, 0, ZipArchive::FL_COMPRESSED);
        }
    }
}