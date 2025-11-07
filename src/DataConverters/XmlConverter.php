<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

use DOMDocument;

class XmlConverter extends XmlObjectConverter
{

    /**
     * Parse and returns the xml string composition
     * @return string
     */
    public function getData(): mixed
    {
        return parent::getData()->asXML();
    }

    /**
     * Returns the xml string composition
     * @return string
     */
    public function __tostring(): string
    {
        //return $this->getData();
        $dom = new DOMDocument('1.0');
        // 3. Formatea la salida
        $dom->formatOutput = true;
        // 4. Carga el XML de SimpleXML en el documento DOM
        $dom->loadXML($this->getData());
        // 5. Guarda el XML formateado (imprime o guarda en un archivo)
        return $dom->saveXML();
    }
}