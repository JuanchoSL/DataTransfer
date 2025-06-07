<?php

namespace JuanchoSL\DataTransfer\Tests\Unit;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Factories\DataConverterFactory;
use JuanchoSL\DataTransfer\Factories\DataTransferFactory;
use JuanchoSL\DataTransfer\Repositories\ArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\CsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ExcelCsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\XmlDataTransfer;
use PHPUnit\Framework\TestCase;
use JuanchoSL\DataTransfer\DataConverters\XmlConverter;

class MimetypeReaderDataTest extends TestCase
{


    public function testToJson()
    {
        $arr = array(array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta")));
        $mime_type = 'application/json';
        $obj = DataTransferFactory::byMimeType(json_encode($arr), $mime_type);
        $this->assertCount(1, $obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);

        $json = DataConverterFactory::asMimeType($obj, $mime_type);

        $this->assertIsString($json);
        $this->assertJsonStringEqualsJsonString(json_encode($arr), $json);
    }

    public function testToXml2()
    {
        $xml = '<readings><reading clientID="583ef6329df6b" period="2016-01">37232</reading><reading clientID="583ef6329df6b" period="2016-02">36537</reading></readings>';
        $mime_type = 'text/xml';
        $obj = DataTransferFactory::byMimeType($xml, $mime_type);
        $this->assertCount(1, $obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        
        $converted = DataConverterFactory::asMimeType($obj, $mime_type);

        $this->assertXmlStringEqualsXmlString($xml, $converted);
    }

    public function testCsv()
    {
        $csv = 'user,user_id,password,prioridad,id,descripcion
"root","2",,"baja",,
"root","1","contraseña","Alta","1","Descripción del texto"';
        $mime = 'text/csv';
        $obj = DataTransferFactory::byMimeType($csv, $mime);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = DataConverterFactory::asMimeType($obj, $mime);

        $this->assertEquals($csv, $converted);
    }

    public function testExcelCsv()
    {
        $csv = 'user;user_id;password;prioridad;id;descripcion
"root";"2";;"baja";;
"root";"1";"contraseña";"Alta";"1";"Descripción del texto"';
        $mime = 'application/csv';
        $obj = DataTransferFactory::byMimeType($csv, $mime);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = DataConverterFactory::asMimeType($obj, $mime);
        $this->assertEquals($csv, $converted);
    }
    public function testYaml()
    {
        $yaml = "event1:\n  name: My Event\n  date: 25.05.2001";
        $mime_type = 'application/yaml';
        $obj = DataTransferFactory::byMimeType($yaml, $mime_type);
        $this->assertCount(1, $obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);

        $converted = DataConverterFactory::asMimeType($obj, $mime_type);

        $this->assertEquals(str_replace("\r\n", "\n", $yaml), $converted);

    }

}