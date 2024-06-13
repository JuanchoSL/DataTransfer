<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

class XmlObjectDataTransfer extends ArrayDataTransfer
{
    /*
        public function __construct(\SimpleXMLElement $xml)
        {
            $nodes = $xml->children();
            $attributes = $xml->attributes();

            if (0 !== count($attributes)) {
                foreach ($attributes as $attrName => $attrValue) {
                    $collection['attributes'][$attrName] = strval($attrValue);
                }
            }

            if (0 === $nodes->count()) {
                $collection['value'] = strval($xml);
                return $collection;
            }
        }
        */
    public function __construct(\SimpleXMLElement $xml)
    {
        $parser = function (\SimpleXMLElement $xml, array $collection = []) use (&$parser) {
            $nodes = $xml->children();
            $attributes = $xml->attributes();

            if (0 !== count($attributes)) {
                foreach ($attributes as $attrName => $attrValue) {
                    $collection['attributes'][$attrName] = strval($attrValue);
                }
            }

            if (0 === $nodes->count()) {
                $collection['value'] = strval($xml);
                return $collection;
            }

            foreach ($nodes as $nodeName => $nodeValue) {
                if (count($nodeValue->xpath('../' . $nodeName)) < 2) {
                    $collection[$nodeName] = $parser($nodeValue);
                    continue;
                }

                if (true) {
                    $values = $parser($nodeValue);
                    if (empty ($values) || !array_key_exists('value', $values)) {
                        if (!empty (trim((string) $nodeValue))) {
                            $values['value'] = trim((string) $nodeValue);
                        }
                    }
                    $collection[$nodeName][] = $values;
                } else {
                    $collection[$nodeName][] = $parser($nodeValue);
                }

            }

            return $collection;
        };

        parent::__construct([$xml->getName() => $parser($xml)]);

    }
}