<?php

namespace JuanchoSL\DataTransfer\Tests\Functional;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Factories\DataTransferFactory;
use JuanchoSL\DataTransfer\Repositories\CsvDataTransfer;
use PHPUnit\Framework\TestCase;

class DataTransferTest extends TestCase
{

    public function testFromJsonWithObject()
    {
        $data = '{"index": "value"}';
        $obj = DataTransferFactory::create($data);
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('value', $obj->index, 'Values are equals');
    }
    public function testFromJsonWithArrayPlain()
    {
        $data = ['value_0', 'value_1'];
        $obj = DataTransferFactory::create($data);
        $this->assertCount(2, $obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj, 'Value is instance');
        foreach ($obj as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }
    public function testFromJsonWithArrayObjects()
    {
        $data = [
            'index' => ['subindex' => 'value']
        ];
        $obj = DataTransferFactory::create($data);
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        $this->assertTrue($obj->get('index')->has('subindex'), 'SubKey is setted');
        $this->assertEquals('value', $obj->get('index')->get('subindex'), 'Values are equals');
        $this->assertEquals('value', $obj->index->subindex, 'Values are equals');
    }
    public function testFromJsonWithArrayArrays()
    {
        $data = [
            'index' => ['value_0', 'value_1']
        ];
        $obj = DataTransferFactory::create($data);
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        foreach ($obj->get('index') as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }
    public function testFromArrayIndexed()
    {
        $data = ['value_0', 'value_1'];
        $obj = DataTransferFactory::create($data);
        $this->assertCount(2, $obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj, 'Value is instance');
        foreach ($obj as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }
    public function testFromArrayAssociative()
    {
        $data = ["index" => "value"];
        $obj = DataTransferFactory::create($data);
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('value', $obj->index, 'Values are equals');
    }
    public function testFromArrayWithObjects()
    {
        $o = new \stdClass;
        $o->subindex = 'value';
        $data = [
            'index' => $o
        ];
        $obj = DataTransferFactory::create($data);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        $this->assertTrue($obj->get('index')->has('subindex'), 'SubKey is setted');
        $this->assertEquals('value', $obj->get('index')->get('subindex'), 'Values are equals');
        $this->assertEquals('value', $obj->index->subindex, 'Values are equals');
    }
    public function testFromArrayWithArrayIndexed()
    {
        $data = [
            'index' => ['value_0', 'value_1']
        ];
        $obj = DataTransferFactory::create($data);
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        foreach ($obj->get('index') as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }
    public function testFromArrayWithArrayAssociative()
    {
        $data = [
            'index' => ['subindex' => 'value']
        ];
        $obj = DataTransferFactory::create($data);
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        $this->assertTrue($obj->get('index')->has('subindex'), 'SubKey is setted');
        $this->assertEquals('value', $obj->get('index')->get('subindex'), 'Values are equals');
        $this->assertEquals('value', $obj->index->subindex, 'Values are equals');
    }
    public function testFromObjectWithPlain()
    {
        $data = new \stdClass;
        $data->index = 'value';
        $obj = DataTransferFactory::create($data);
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('value', $obj->index, 'Values are equals');
    }
    public function testFromObjectWithObject()
    {
        $o = new \stdClass;
        $o->subindex = 'value';
        $data = new \stdClass;
        $data->index = $o;
        $obj = DataTransferFactory::create($data);
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        $this->assertTrue($obj->get('index')->has('subindex'), 'SubKey is setted');
        $this->assertEquals('value', $obj->get('index')->get('subindex'), 'Values are equals');
        $this->assertEquals('value', $obj->index->subindex, 'Values are equals');
    }
    public function testFromObjectWithArrayIndexed()
    {
        $a = ['value_0', 'value_1'];
        $data = new \stdClass;
        $data->index = $a;

        $obj = DataTransferFactory::create($data);
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        foreach ($obj->get('index') as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }
    public function testFromObjectWithArrayAssociative()
    {
        $a = ['subindex' => 'value'];
        $data = new \stdClass;
        $data->index = $a;

        $obj = DataTransferFactory::create($data);
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        $this->assertTrue($obj->get('index')->has('subindex'), 'SubKey is setted');
        $this->assertEquals('value', $obj->get('index')->get('subindex'), 'Values are equals');
        $this->assertEquals('value', $obj->index->subindex, 'Values are equals');
    }
    public function testXml()
    {
        $data = '<?xml version="1.0" encoding="UTF-8"?>
        <root>
        <list>
        <item type="Language">
                    <name>PHP</name>
                    <link>www.php.net</link>
                    aaa
                </item>
                <item type="Database">
                    <name>Java</name>
                    <link>www.oracle.com/br/technologies/java/‎</link>
                    bbb
                </item>
                </list>
                <readings>
                <reading clientID="583ef6329df6b" period="2016-01">37232</reading>
                <reading clientID="583ef6329df6b" period="2016-02">36537</reading>
                </readings>
                </root>';
        $obj = DataTransferFactory::create(simplexml_load_string($data));
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('root'), 'Key is setted');
        $this->assertTrue($obj->root->has('list'), 'Key is setted');
        $this->assertTrue($obj->root->list->has("item"), 'Key is setted');
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj->root->list->item, 'Value are instances');
        $this->assertEquals("PHP", $obj->root->list->item->get(0)->name->value, 'Values are equals');
        foreach ($obj->root->readings->reading as $key => $value) {
            $this->assertEquals("583ef6329df6b", $value->attributes->clientID, 'Values are equals');
        }
    }
    public function testFromCsvString()
    {
        $csv = 'user,user_id,password,prioridad,id,descripcion
"root","2",,"baja",,
"root","1","contraseña","Alta","1","Descripción del texto"';
        $obj = DataTransferFactory::create(new CsvDataTransfer(explode(PHP_EOL, $csv)));
        $this->assertCount(2, $obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        foreach ($obj as $entity) {
            $this->assertTrue($entity->has("user"));
            $this->assertEquals('root', $entity->get("user"));
            $this->assertEquals('root', $entity->user);
        }
    }
    public function testFromCsvArray()
    {
        $csv = 'user,user_id,password,prioridad,id,descripcion
        "root","2",,"baja",,
"root","1","contraseña","Alta","1","Descripción del texto"';
        $obj = DataTransferFactory::create(new CsvDataTransfer($csv));
        $this->assertCount(2, $obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj);
        $this->assertContainsOnlyInstancesOf(DataTransferInterface::class, $obj);
        foreach ($obj as $entity) {
            $this->assertTrue($entity->has("user"));
            $this->assertEquals('root', $entity->get("user"));
            $this->assertEquals('root', $entity->user);
        }
    }
}