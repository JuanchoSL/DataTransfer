<?php

namespace JuanchoSL\DataTransfer\Tests\Unit;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Enums\Format as FormatEnum;
use JuanchoSL\DataTransfer\Factories\Format;
use JuanchoSL\DataTransfer\Repositories\ArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\CsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ExcelCsvDataTransfer;
use JuanchoSL\DataTransfer\Repositories\XmlDataTransfer;
use PHPUnit\Framework\TestCase;

class SaverDataTest extends TestCase
{

    public function setUp(): void
    {
        defined('TMPDIR') or define('TMPDIR', sys_get_temp_dir());
    }

    public function testToJson()
    {
        if (version_compare(PHP_VERSION, '8.1.0', '<')) {
            $this->markTestSkipped();
        }
        $format = Format::tryFrom(FormatEnum::JSON->name);
        $filename = TMPDIR . DIRECTORY_SEPARATOR . __FUNCTION__ . '.' . $format->value;
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = new ArrayDataTransfer($arr);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $json = $obj->saveAs($filename, Format::JSON);
        $this->assertTrue($json);
        $json = file_get_contents($filename);
        $this->assertIsString($json);
        $this->assertJsonStringEqualsJsonString(json_encode($arr), $json);
        unlink($filename);
    }

    public function testToArray()
    {
        if (version_compare(PHP_VERSION, '8.1.0', '<')) {
            $this->markTestSkipped();
        }
        $format = Format::tryFrom(FormatEnum::ARRAY ->name);
        $filename = TMPDIR . DIRECTORY_SEPARATOR . __FUNCTION__ . '.' . $format->value;
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = new ArrayDataTransfer($arr);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $json = $obj->saveAs($filename, Format::ARRAY);
        $this->assertTrue($json);
        $json = unserialize(file_get_contents($filename));
        $this->assertIsArray($json);
        $this->assertEquals($arr, $json);
        unlink($filename);
    }

    public function testToObject()
    {
        if (version_compare(PHP_VERSION, '8.1.0', '<')) {
            $this->markTestSkipped();
        }
        $format = Format::tryFrom(FormatEnum::OBJECT->name);
        $filename = TMPDIR . DIRECTORY_SEPARATOR . __FUNCTION__ . '.' . $format->value;
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = new ArrayDataTransfer($arr);
        $json = $obj->saveAs($filename, Format::OBJECT);
        $this->assertTrue($json);
        $json = unserialize(file_get_contents($filename));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertInstanceOf(\stdClass::class, $json);
        $this->assertEquals(json_decode(json_encode($arr), false), $json);
        unlink($filename);
    }

    public function testToYml()
    {
        if (version_compare(PHP_VERSION, '8.1.0', '<')) {
            $this->markTestSkipped();
        }
        $format = Format::tryFrom(FormatEnum::YML->name);
        $filename = TMPDIR . DIRECTORY_SEPARATOR . __FUNCTION__ . '.' . $format->value;
        $arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
        $obj = new ArrayDataTransfer($arr);
        $str = $obj->exportAs(Format::YAML);
        $json = $obj->saveAs($filename, Format::YML);
        $this->assertTrue($json);
        $this->assertEquals($str, file_get_contents($filename));
        unlink($filename);
    }
    public function testToXml2()
    {
        if (version_compare(PHP_VERSION, '8.1.0', '<')) {
            $this->markTestSkipped();
        }
        $format = Format::tryFrom(FormatEnum::XML->name);
        $filename = TMPDIR . DIRECTORY_SEPARATOR . __FUNCTION__ . '.' . $format->value;
        $xml = '<readings><reading clientID="583ef6329df6b" period="2016-01">37232</reading><reading clientID="583ef6329df6b" period="2016-02">36537</reading></readings>';
        $obj = new XmlDataTransfer(simplexml_load_string($xml));
        $json = $obj->saveAs($filename, Format::XML);
        $this->assertTrue($json);
        $this->assertXmlStringEqualsXmlString($xml, file_get_contents($filename));
        unlink($filename);
    }
    public function testToXml3()
    {
        if (version_compare(PHP_VERSION, '8.1.0', '<')) {
            $this->markTestSkipped();
        }
        $format = Format::tryFrom(FormatEnum::XML->name);
        $filename = TMPDIR . DIRECTORY_SEPARATOR . __FUNCTION__ . '.' . $format->value;
        $xml = '<readings><reading clientID="583ef6329df6b" period="2016-01">37232</reading><reading clientID="583ef6329df6b" period="2016-02">36537</reading></readings>';
        $xml_obj = simplexml_load_string($xml);
        $obj = new XmlDataTransfer($xml_obj);
        $json = $obj->saveAs($filename, Format::XML);
        $this->assertTrue($json);
        $convert = new \SimpleXMLElement(file_get_contents($filename));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertInstanceOf(\SimpleXMLElement::class, $convert);
        $this->assertEquals($xml_obj, $convert);
        unlink($filename);
    }

    public function testToCsv()
    {
        if (version_compare(PHP_VERSION, '8.1.0', '<')) {
            $this->markTestSkipped();
        }
        $format = Format::tryFrom(FormatEnum::CSV->name);
        $filename = TMPDIR . DIRECTORY_SEPARATOR . __FUNCTION__ . '.' . $format->value;
        $csv = 'user,user_id,password,prioridad,id,descripción
root,2,,baja,,
root,1,contraseña,Alta,1,"Descripción del texto"';
        $obj = new CsvDataTransfer(explode(PHP_EOL, $csv));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = $obj->saveAs($filename, Format::CSV);
        $this->assertTrue($converted);
        $this->assertEquals($csv, file_get_contents($filename));
        unlink($filename);
    }

    public function testToExcelCsv()
    {
        if (version_compare(PHP_VERSION, '8.1.0', '<')) {
            $this->markTestSkipped();
        }
        $format = Format::tryFrom(FormatEnum::EXCEL_CSV->name);
        $filename = TMPDIR . DIRECTORY_SEPARATOR . __FUNCTION__ . '.' . $format->value;
        $csv = 'user;user_id;password;prioridad;id;descripción
root;2;;baja;;
root;1;contraseña;Alta;1;"Descripción del texto"';
        $obj = new ExcelCsvDataTransfer(explode(PHP_EOL, $csv));
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = $obj->saveAs($filename, Format::EXCEL_CSV);
        $this->assertTrue($converted);
        $this->assertStringEndsWith($csv, file_get_contents($filename));
        unlink($filename);
    }

    public function testYaml()
    {
        if (version_compare(PHP_VERSION, '8.1.0', '<')) {
            $this->markTestSkipped();
        }
        $format = Format::tryFrom(FormatEnum::YAML->name);
        $filename = TMPDIR . DIRECTORY_SEPARATOR . __FUNCTION__ . '.' . $format->value;
        $yaml = "event1:\n  name: My Event\n  date: 25.05.2001";
        $array = ["event1" => ['name' => 'My Event', 'date' => '25.05.2001']];
        $obj = new ArrayDataTransfer($array);
        $this->assertCount(1, $obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = $obj->saveAs($filename, Format::YAML);
        $this->assertTrue($converted);
        $this->assertEquals(str_replace("\r\n", "\n", $yaml), file_get_contents($filename));
        unlink($filename);
    }

    public function testIni()
    {
        if (version_compare(PHP_VERSION, '8.1.0', '<')) {
            $this->markTestSkipped();
        }
        $format = Format::tryFrom(FormatEnum::INI->name);
        $filename = TMPDIR . DIRECTORY_SEPARATOR . __FUNCTION__ . '.' . $format->value;
        $ini = "[event1]" . PHP_EOL . "name=My Event" . PHP_EOL . "date=25.05.2001";
        $array = ["event1" => ['name' => 'My Event', 'date' => '25.05.2001']];
        $obj = new ArrayDataTransfer($array);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = $obj->exportAs(Format::INI);
        $json = $obj->saveAs($filename, Format::INI);
        $this->assertTrue($json);
        $this->assertEquals($ini, file_get_contents($filename));
        unlink($filename);
    }
}