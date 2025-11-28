<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\PreconditionRequiredException;
use JuanchoSL\Exceptions\UnprocessableEntityException;

class ExcelXmlDataTransfer extends ArrayDataTransfer
{

    public function __construct(string $xml)
    {
        if (!extension_loaded('xml')) {
            throw new PreconditionRequiredException("The extension XML is not available");
        }
        $xml_data = [];
        if (is_string($xml)) {
            if (!is_file($xml)) {
                $xml_file = tempnam(sys_get_temp_dir(), __CLASS__);
                file_put_contents($xml_file, $xml);
                $xml = $xml_file;
            }
            if (is_file($xml) && file_exists($xml)) {
                $references = simplexml_load_file($xml);
                foreach ($references as $name => $childs) {
                    if ($name !== 'Worksheet') {
                        continue;
                    }
                    $ns = $childs->getNamespaces();
                    $page = (string) $childs->attributes($ns['ss'])['Name'];
                    foreach ($childs->Table->Row as $cs) {
                        $ref = [];
                        foreach ($cs->Cell as $c) {
                            $ref[] = $c->Data[0];
                        }
                        if (empty($headers)) {
                            $headers = $ref;
                            continue;
                        }
                        if (count($headers) > count($ref)) {
                            continue;
                        }
                        $xml_data[$page][] = array_combine($headers, array_slice($ref, 0, count($headers)));
                    }
                }
            }
        }
        if (empty($xml_data)) {
            throw new UnprocessableEntityException("No contents has been received from '{$xml}'");
        }
        parent::__construct((array) $xml_data);
    }

}