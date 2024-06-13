<?php

namespace JuanchoSL\DataTransfer\Tests\Unit;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\DataConverters\ArrayConverter;
use JuanchoSL\DataTransfer\DataConverters\CsvConverter;
use JuanchoSL\DataTransfer\DataConverters\JsonConverter;
use JuanchoSL\DataTransfer\DataConverters\ObjectConverter;
use JuanchoSL\DataTransfer\DataConverters\XmlObjectConverter;
use JuanchoSL\DataTransfer\Repositories\ArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\CsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\XmlObjectDataTransfer;
use PHPUnit\Framework\TestCase;
use JuanchoSL\DataTransfer\DataConverters\XmlConverter;

class ConverterDataTest extends TestCase
{


    public function testToJson()
    {
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = new ArrayDataTransfer($arr);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $json = JsonConverter::convert($obj);
        $this->assertIsString($json);
        $this->assertJsonStringEqualsJsonString(json_encode($arr), $json);
    }

    public function testToArray()
    {
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = new ArrayDataTransfer($arr);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $json = ArrayConverter::convert($obj);
        $this->assertIsArray($json);
        $this->assertEquals($arr, $json);
    }

    public function testToObject()
    {
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = new ArrayDataTransfer($arr);
        $json = ObjectConverter::convert($obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertInstanceOf(\stdClass::class, $json);
        $this->assertEquals(json_decode(json_encode($arr), false), $json);
    }

    public function testToXml()
    {
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = new ArrayDataTransfer($arr);
        $xml = XmlConverter::convert($obj);
        //print_r($xml);exit;
        $obj2 = new XmlObjectDataTransfer(simplexml_load_string($xml));
        $str = XmlConverter::convert($obj2->root);
        $this->assertIsString($str);
        $this->assertEqualsIgnoringCase($xml, $str);
    }
    public function testToXml2()
    {
        $xml = '<readings><reading clientID="583ef6329df6b" period="2016-01">37232</reading><reading clientID="583ef6329df6b" period="2016-02">36537</reading></readings>';
        $obj = new XmlObjectDataTransfer(simplexml_load_string($xml));
        //echo $obj->asXml();exit;
        //echo $obj->asXml();exit;
        $this->assertXmlStringEqualsXmlString($xml, XmlConverter::convert($obj));
    }
    public function testToXml3()
    {
        $xml = '<readings><reading clientID="583ef6329df6b" period="2016-01">37232</reading><reading clientID="583ef6329df6b" period="2016-02">36537</reading></readings>';
        $xml_obj = simplexml_load_string($xml);
        $obj = new XmlObjectDataTransfer($xml_obj);
        $convert = XmlObjectConverter::convert($obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertInstanceOf(\SimpleXMLElement::class, $convert);
        $this->assertEquals($xml_obj, $convert);
    }
    public function testToCsv()
    {
        $csv = 'user,user_id,password,prioridad,id,descripcion
"root","2",,"baja",,
"root","1","contraseña","Alta","1","Descripción del texto"';
        $obj = new CsvDataTransfer(explode(PHP_EOL, $csv));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = CsvConverter::convert($obj);
        $this->assertEquals($csv, $converted);
    }
}