<?php

namespace JuanchoSL\DataTransfer\Tests\Functional;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Factories\DataConverterFactory;
use JuanchoSL\DataTransfer\Factories\DataTransferFactory;
use JuanchoSL\DataTransfer\Repositories\CsvDataTransfer;
use PHPUnit\Framework\TestCase;

class ConverterDataTest extends TestCase
{


    public function testToJson()
    {
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = DataTransferFactory::create($arr);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $json = DataConverterFactory::asJson($obj);
        $this->assertIsString($json);
        $this->assertJsonStringEqualsJsonString(json_encode($arr), $json);
    }

    public function testToArray()
    {
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = DataTransferFactory::create($arr);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $json = DataConverterFactory::asArray($obj);
        $this->assertIsArray($json);
        $this->assertEquals($arr, $json);
    }

    public function testToObject()
    {
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = DataTransferFactory::create($arr);

        $json = DataConverterFactory::asObject($obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertInstanceOf(\stdClass::class, $json);
        $this->assertEquals(json_decode(json_encode($arr), false), $json);
    }

    public function testToXml()
    {
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = DataTransferFactory::create($arr);
        $xml = DataConverterFactory::asXml($obj);
        $obj2 = DataTransferFactory::create(simplexml_load_string($xml));
        $str = DataConverterFactory::asXml($obj2->root);
        $this->assertIsString($str);
        $this->assertEqualsIgnoringCase($xml, $str);
    }
    public function testToXml2()
    {
        $xml = '<readings><reading clientID="583ef6329df6b" period="2016-01">37232</reading><reading clientID="583ef6329df6b" period="2016-02">36537</reading></readings>';
        $obj = DataTransferFactory::create(simplexml_load_string($xml));
        $this->assertXmlStringEqualsXmlString($xml, DataConverterFactory::asXml($obj));
    }
    public function testToXml3()
    {
        $xml = '<readings><reading clientID="583ef6329df6b" period="2016-01">37232</reading><reading clientID="583ef6329df6b" period="2016-02">36537</reading></readings>';
        $xml_obj = simplexml_load_string($xml);
        $obj = DataTransferFactory::create($xml_obj);
        $convert = DataConverterFactory::asXmlObject($obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertInstanceOf(\SimpleXMLElement::class, $convert);
        $this->assertEquals($xml_obj, $convert);
    }
    public function testToCsv()
    {
        $csv = 'user,user_id,password,prioridad,id,descripcion
"root","2",,"baja",,
"root","1","contraseña","Alta","1","Descripción del texto"';
        $obj = DataTransferFactory::create(new CsvDataTransfer(explode(PHP_EOL, $csv)));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = DataConverterFactory::asCsv($obj);
        $this->assertEquals($csv, $converted);
    }
}