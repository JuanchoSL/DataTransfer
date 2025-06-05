<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;
use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\Exceptions\UnprocessableEntityException;

class XmlObjectConverter extends AbstractConverter
{

    public function getData(): mixed
    {
        //$key = 'root';
        if ($this->data->count() == 1 && !empty($this->data->key()) && !is_numeric($this->data->key())) {
            $key = $this->data->key();
        }
        $key ??= 'root';
        return $this->array2XML($this->data, (string) $key);
    }

    protected function array2XML(DataTransferInterface $data, string $rootNodeName = 'root', ?\SimpleXMLElement $xml = NULL): \SimpleXMLElement
    {
        if ($xml == null) {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
            if ($xml === false) {
                throw new UnprocessableEntityException("Come error creating root node {$rootNodeName}");
            }
        }
        foreach ($data as $key => $value) {
            if ($value instanceof DataTransferInterface) {
                if ($key == 'attributes') {
                    foreach ($value as $attr_key => $attr_value) {
                        $xml->addAttribute($attr_key, $attr_value);
                    }
                } else {
                    if ($key != $rootNodeName) {
                        if (is_numeric($key)) {
                            $name = $rootNodeName;
                            $node = $xml->addChild($name);
                        } else {
                            $name = $key;
                            if ($value->count() == 1 || ($value->valid() && !is_numeric($value->key()))) {
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

    public function __tostring(): string
    {
        return $this->getData()->asXML();
    }
}