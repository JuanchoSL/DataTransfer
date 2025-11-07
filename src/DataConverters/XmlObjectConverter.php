<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

use DOMDocument;
use JuanchoSL\Exceptions\UnprocessableEntityException;

class XmlObjectConverter extends AbstractConverter
{

    /**
     * Parse and returns the xml object
     * @return \SimpleXMLElement
     */
    public function getData(): mixed
    {
        $data = json_decode(json_encode($this->data), true);
        //if ($this->data->count() == 1 && !empty($this->data->key()) && !is_numeric($this->data->key())) {
        if (count($data) == 1 && !empty(key($data)) && !is_numeric(key($data))) {
            $key = key($data);
        }
        $key ??= 'root';
        return $this->array2XML($data, (string) $key);
    }

    protected function array2XML(iterable $data, string $rootNodeName = 'root', ?\SimpleXMLElement $xml = NULL): \SimpleXMLElement
    {
        if ($xml == null) {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
            if ($xml === false) {
                throw new UnprocessableEntityException("Come error creating root node {$rootNodeName}");
            }
        }
        foreach ($data as $key => $value) {
            if (is_iterable($value)) {
                if ($key == 'attributes') {
                    foreach ($value as $attr_key => $attr_value) {
                        $xml->addAttribute($attr_key, $attr_value);
                    }
                } else {
                    if ($key != $rootNodeName) {
                        if (is_numeric($key)) {
                            $name = ($rootNodeName == 'root') ? 'node' : $rootNodeName;
                            $node = $xml->addChild($name);
                        } else {
                            $name = $key;
                            if (count($value) == 1 || (!is_numeric(key($value)))) {
                                $node = $xml->addChild($name);
                            } else {
                                $node = $xml;
                            }
                        }
                        $this->array2XML($value, $name, $node);
                    } else {
                        $this->array2XML($value, $key, $xml);
                    }
                }
            } elseif (!is_numeric($key)) {
                //$value = htmlspecialchars($value);
                if ($key != 'value') {
                    $node = $xml->addChild($key);
                    $value = (string) $value;
                    if (strpos($value, '&lt;') !== false || strpos($value, '&amp;') !== false) {
                        $value = html_entity_decode($value);
                    }
                    if (strpos($value, '<') !== false || strpos($value, '&') !== false) {
                        $node = dom_import_simplexml($node);
                        $no = $node->ownerDocument;
                        $node->appendChild($no->createCDATASection($value));
                    } else {
                        $node[0] = $value;
                    }
                } else {
                    $xml[0] = $value;
                }
            }
        }
        return $xml;
    }

    /**
     * Returns the xml string composition
     * @return string
     */
    public function __tostring(): string
    {
        //return $this->getData()->asXML();
        $dom = new DOMDocument('1.0');
        // 3. Formatea la salida
        $dom->formatOutput = true;
        // 4. Carga el XML de SimpleXML en el documento DOM
        $dom->loadXML($this->getData()->asXML());
        // 5. Guarda el XML formateado (imprime o guarda en un archivo)
        return $dom->saveXML();
    }
}