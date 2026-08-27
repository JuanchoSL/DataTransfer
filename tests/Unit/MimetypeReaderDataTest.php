<?php

namespace JuanchoSL\DataTransfer\Tests\Unit;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Factories\DataConverterFactory;
use JuanchoSL\DataTransfer\Factories\DataTransferFactory;
use PHPUnit\Framework\TestCase;

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
root,2,,baja,,
root,1,contraseña,Alta,1,"Descripción del texto"';
        $mimes = ['text/csv', 'application/csv'];
        foreach ($mimes as $mime) {
            $obj = DataTransferFactory::byMimeType($csv, $mime);
            $this->assertInstanceOf(DataTransferInterface::class, $obj);
            $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
            $converted = DataConverterFactory::asMimeType($obj, $mime);
            $this->assertEquals($csv, $converted);
        }
    }

    public function testExcelCsv()
    {
        $csv = 'user;user_id;password;prioridad;id;descripcion
root;2;;baja;;
root;1;contraseña;Alta;1;"Descripción del texto"';
        $mime = 'application/vnd.ms-excel';
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

    public function testTabsv()
    {
        $tsv = "user\tuser_id\tpassword\tprioridad\tid\tdescripcion
root\t2\t\tbaja\t\t
root\t1\tcontraseña\tAlta\t1\t\"Descripción del texto\"";
        $mime = 'text/tab-separated-values';
        $obj = DataTransferFactory::byMimeType($tsv, $mime);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = DataConverterFactory::asMimeType($obj, $mime);
        $this->assertEquals($tsv, $converted);
    }

    public function testDif()
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
        $mime = 'application/x-dif';
        $obj = DataTransferFactory::byMimeType($dif, $mime);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        $converted = DataConverterFactory::asMimeType($obj, $mime);
        $this->assertEquals($dif, $converted);
    }


    public function testIni()
    {
        $ini = <<<'EOH'
name="My Event"
date=25.05.2001
EOH;

        $mimes = ['text/plain', 'application/x-wine-extension-ini'];
        foreach ($mimes as $mime) {
            $obj = DataTransferFactory::byMimeType($ini, $mime);
            $this->assertInstanceOf(DataTransferInterface::class, $obj);
            //$this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
            $converted = DataConverterFactory::asMimeType($obj, $mime);
            $this->assertEquals($ini, $converted);
        }
    }

    public function testIniSections()
    {
        $ini = <<<'EOH'
[event1]
name="My Event"
date=25.05.2001
EOH;

        $mimes = ['text/plain', 'application/x-wine-extension-ini'];
        foreach ($mimes as $mime) {
            $obj = DataTransferFactory::byMimeType($ini, $mime);
            $this->assertInstanceOf(DataTransferInterface::class, $obj);
            $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
            $converted = DataConverterFactory::asMimeType($obj, $mime);
            $this->assertEquals($ini, $converted);
        }
    }

    public function testIniMultiSections()
    {
        $ini = <<<'EOH'
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
        $mimes = ['text/plain', 'application/x-wine-extension-ini'];
        foreach ($mimes as $mime) {
            $obj = DataTransferFactory::byMimeType($ini, $mime);
            $this->assertInstanceOf(DataTransferInterface::class, $obj);
            $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
            $converted = DataConverterFactory::asMimeType($obj, $mime);
            $this->assertEquals($ini, $converted);
        }
    }

}