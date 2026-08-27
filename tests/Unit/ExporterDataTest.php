<?php

namespace JuanchoSL\DataTransfer\Tests\Unit;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Factories\Format;
use JuanchoSL\DataTransfer\Repositories\ArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\CsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\DifDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ExcelCsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\IniDataTransfer;
use JuanchoSL\DataTransfer\Repositories\TabsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\XmlDataTransfer;
use PHPUnit\Framework\TestCase;

class ExporterDataTest extends TestCase
{


    public function testToJson()
    {
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = new ArrayDataTransfer($arr);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $json = $obj->exportAs(Format::JSON);
        $this->assertIsString($json);
        $this->assertJsonStringEqualsJsonString(json_encode($arr), $json);
    }

    public function testToArray()
    {
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = new ArrayDataTransfer($arr);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $json = $obj->exportAs(Format::ARRAY);
        $this->assertIsArray($json);
        $this->assertEquals($arr, $json);
    }

    public function testToObject()
    {
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = new ArrayDataTransfer($arr);
        $json = $obj->exportAs(Format::OBJECT);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertInstanceOf(\stdClass::class, $json);
        $this->assertEquals(json_decode(json_encode($arr), false), $json);
    }

    public function testToXml2()
    {
        $xml = '<readings><reading clientID="583ef6329df6b" period="2016-01">37232</reading><reading clientID="583ef6329df6b" period="2016-02">36537</reading></readings>';
        $obj = new XmlDataTransfer(simplexml_load_string($xml));
        $this->assertXmlStringEqualsXmlString($xml, $obj->exportAs(Format::XML));
    }
    public function testToXml3()
    {
        $xml = '<readings><reading clientID="583ef6329df6b" period="2016-01">37232</reading><reading clientID="583ef6329df6b" period="2016-02">36537</reading></readings>';
        $xml_obj = simplexml_load_string($xml);
        $obj = new XmlDataTransfer($xml_obj);
        $convert = $obj->exportAs(Format::XML_OBJECT);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertInstanceOf(\SimpleXMLElement::class, $convert);
        $this->assertEquals($xml_obj, $convert);
    }

    public function testToXml4()
    {
        $xml = '<readings><reading clientID="583ef6329df6b" period="2016-01">37232</reading><reading clientID="583ef6329df6b" period="2016-02">36537</reading></readings>';
        $xml_obj = simplexml_load_string($xml);
        $obj = new XmlDataTransfer($xml);
        $convert = $obj->exportAs(Format::XML_OBJECT);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertInstanceOf(\SimpleXMLElement::class, $convert);
        $this->assertEquals($xml_obj, $convert);
    }

    public function testToCsv()
    {
        $csv = 'user,user_id,password,prioridad,id,descripcion
root,2,,baja,,
root,1,contraseña,Alta,1,"Descripción del texto"';
        $obj = new CsvDataTransfer(explode(PHP_EOL, $csv));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = $obj->exportAs(Format::CSV);
        $this->assertEquals($csv, $converted);
    }

    public function testToTsv()
    {
        $csv = "user\tuser_id\tpassword\tprioridad\tid\tdescripcion
root\t2\t\tbaja\t\t
root\t1\tcontraseña\tAlta\t1\t\"Descripción del texto\"";
        $obj = new TabsvDataTransfer(explode(PHP_EOL, $csv));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = $obj->exportAs(Format::TAB);
        $this->assertEquals($csv, $converted);
    }

    public function testToExcelCsv()
    {
        $csv = 'user;user_id;password;prioridad;id;descripcion
root;2;;baja;;
root;1;contraseña;Alta;1;"Descripción del texto"';
        $obj = new ExcelCsvDataTransfer(explode(PHP_EOL, $csv));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = $obj->exportAs(Format::EXCEL_CSV);
        $this->assertEquals($csv, $converted);
    }

    public function testYaml()
    {
        $yaml = "event1:\n  name: My Event\n  date: 25.05.2001";
        $array = ["event1" => ['name' => 'My Event', 'date' => '25.05.2001']];
        $obj = new ArrayDataTransfer($array);
        $this->assertCount(1, $obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = $obj->exportAs(Format::YAML);
        $this->assertEquals(str_replace("\r\n", "\n", $yaml), $converted);
    }

    public function testIni()
    {
        $ini = "[event1]" . PHP_EOL . 'name="My Event"'. PHP_EOL . "date=25.05.2001";
        $array = ["event1" => ['name' => 'My Event', 'date' => '25.05.2001']];
        $obj = new ArrayDataTransfer($array);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = $obj->exportAs(Format::INI);
        $this->assertEquals($ini, $converted);
    }

    public function testIniMultiSections()
    {
        $ini = <<<EOH
[0]
user=root
user_id=2
password=
prioridad=baja
id=
descripcion=

[1]
user=root
user_id=1
password="contraseña"
prioridad=Alta
id=1
descripcion="Descripción del texto"
EOH;
        $obj = new IniDataTransfer($ini);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = $obj->exportAs(Format::INI);
        $this->assertEquals($ini, $converted);
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
"My Event"
1,0
"25.05.2001"
-1,0
BOT
1,0
"My Event"
1,0
"25.05.2001"
-1,0
EOD
EOH;
        $obj = new DifDataTransfer(explode(PHP_EOL, $dif));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = $obj->exportAs(Format::DIF);
        $this->assertEquals($dif, $converted);
    }
}