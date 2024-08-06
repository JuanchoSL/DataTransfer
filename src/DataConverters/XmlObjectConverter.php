<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

class XmlObjectConverter extends AbstractConverter
{

    public function getData()
    {
        $key = 'root';
        if ($this->data->count() == 1 && !empty($this->data->key()) && !is_numeric($this->data->key())) {
            $key = $this->data->key();
        }
        return $this->array2XML($this->data, $key);
    }

    protected function array2XML($data, $rootNodeName = 'root', $xml = NULL)
    {
        if ($xml == null) {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
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
                $value = htmlspecialchars($value);
                if ($key != 'value') {
                    $node = $xml->addChild($key);
                    $node[0] = $value;
                } else {
                    $xml[0] = $value;
                }
            }
        }
        return $xml;
    }
}