<?php

namespace JuanchoSL\DataTransfer\Tests\Functional;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Factories\DataConverterFactory;
use JuanchoSL\DataTransfer\Factories\DataTransferFactory;
use JuanchoSL\DataTransfer\Repositories\CsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\DifDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ExcelCsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\TabsvDataTransfer;
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

    public function testToXml4()
    {
        $xml = '<readings><reading clientID="583ef6329df6b" period="2016-01">37232</reading><reading clientID="583ef6329df6b" period="2016-02">36537</reading></readings>';
        $xml_obj = simplexml_load_string($xml);
        $obj = DataTransferFactory::create($xml);
        $convert = DataConverterFactory::asXmlObject($obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertInstanceOf(\SimpleXMLElement::class, $convert);
        $this->assertEquals($xml_obj, $convert);
    }

    public function testToCsv()
    {
        $csv = 'user,user_id,password,prioridad,id,descripcion
root,2,,baja,,
root,1,contraseña,Alta,1,"Descripción del texto"';
        $obj = DataTransferFactory::create(new CsvDataTransfer(explode(PHP_EOL, $csv)));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = DataConverterFactory::asCsv($obj);
        $this->assertEquals($csv, $converted);
    }

    public function testToTsv()
    {
        $csv = "user\tuser_id\tpassword\tprioridad\tid\tdescripcion
root\t2\t\tbaja\t\t
root\t1\tcontraseña\tAlta\t1\t\"Descripción del texto\"";
        $obj = DataTransferFactory::create(new TabsvDataTransfer(explode(PHP_EOL, $csv)));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = DataConverterFactory::asTabs($obj);
        $this->assertEquals($csv, $converted);
    }

    public function testToExcelCsv()
    {
        $csv = 'user;user_id;password;prioridad;id;descripcion
root;2;;baja;;
root;1;contraseña;Alta;1;"Descripción del texto"';
        $obj = DataTransferFactory::create(new ExcelCsvDataTransfer(explode(PHP_EOL, $csv)));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = DataConverterFactory::asExcelCsv($obj);
        $this->assertEquals($csv, $converted);
    }

    public function testToDif()
    {
        $dif = <<<EOH
TABLE
0,1
"JuanchoSL DataTransfer"
VECTORS
0,2
""
TUPLES
0,3
""
DATA
0,0
""
-1,0
BOT
1,0
"name"
1,0
"date"
-1,0
BOT
1,0
"My event"
1,0
"25.05.2001"
-1,0
BOT
1,0
"Second event"
1,0
"25.06.2001"
-1,0
EOD
EOH;
        $obj = DataTransferFactory::create(new DifDataTransfer($dif));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = DataConverterFactory::asDif($obj);
        $this->assertEquals($dif, $converted);
    }

    public function testYaml()
    {
        $yaml = "event1:\n  name: My Event\n  date: 25.05.2001";
        $array = ["event1" => ['name' => 'My Event', 'date' => '25.05.2001']];
        $obj = DataTransferFactory::create($array);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = DataConverterFactory::asYaml($obj);
        $this->assertEquals(str_replace("\r\n", "\n", $yaml), $converted);
    }

    public function testIni()
    {
        $yaml = "[event1]" . PHP_EOL . "name=My Event" . PHP_EOL . "date=25.05.2001";
        $array = ["event1" => ['name' => 'My Event', 'date' => '25.05.2001']];
        $obj = DataTransferFactory::create($array);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = DataConverterFactory::asIni($obj);
        $this->assertEquals($yaml, $converted);
    }
}