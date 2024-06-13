<?php

namespace JuanchoSL\DataTransfer\Tests\Functional;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Factories\DataTransferFactory;
use PHPUnit\Framework\TestCase;

class WriteDataTransferTest extends TestCase
{

    public function testFromJsonWithObject()
    {
        $obj = DataTransferFactory::create();
        $obj->index = "value";
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('value', $obj->index, 'Values are equals');
    }
    public function testFromJsonWithArrayPlain()
    {
        $obj = DataTransferFactory::create();
        $obj->append("value_0");
        $obj->append("value_1");
        $this->assertCount(2, $obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj, 'Value is instance');
        foreach ($obj as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }
    public function testFromJsonWithArrayObjects()
    {
        $obj = DataTransferFactory::create();
        $obj->index = ['subindex' => 'value'];
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        $this->assertTrue($obj->get('index')->has('subindex'), 'SubKey is setted');
        $this->assertEquals('value', $obj->get('index')->get('subindex'), 'Values are equals');
        $this->assertEquals('value', $obj->index->subindex, 'Values are equals');
    }
    public function testFromJsonWithArrayArrays()
    {
        $obj = DataTransferFactory::create();
        $obj->index = ['value_0', 'value_1'];
        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        foreach ($obj->get('index') as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }
    public function testFromArrayIndexed()
    {
        $obj = DataTransferFactory::create();
        $obj->append("value_0");
        $obj->append("value_1");
        $this->assertCount(2, $obj);
        $this->assertInstanceOf(DataTransferInterface::class, $obj, 'Value is instance');
        foreach ($obj as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }

    public function testFromArrayWithObjects()
    {
        $o = new \stdClass;
        $o->subindex = 'value';
        $obj = DataTransferFactory::create();
        $obj->index = $o;
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        $this->assertTrue($obj->get('index')->has('subindex'), 'SubKey is setted');
        $this->assertEquals('value', $obj->get('index')->get('subindex'), 'Values are equals');
        $this->assertEquals('value', $obj->index->subindex, 'Values are equals');
    }

    public function testFromObjectWithObject()
    {
        $o = new \stdClass;
        $o->subindex = 'value';
        $obj = DataTransferFactory::create();
        $obj->index = $o;
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
        $obj = DataTransferFactory::create();
        $obj->index = $a;

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

        $obj = DataTransferFactory::create();
        $obj->index = $a;

        $this->assertCount(1, $obj);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        $this->assertTrue($obj->get('index')->has('subindex'), 'SubKey is setted');
        $this->assertEquals('value', $obj->get('index')->get('subindex'), 'Values are equals');
        $this->assertEquals('value', $obj->index->subindex, 'Values are equals');
    }
    public function testArrayOfStringsFailback()
    {
        $obj = DataTransferFactory::create();
        $this->assertFalse($obj->has('index'), 'Key is not setted');
        $this->assertFalse($obj->has('other_index'), 'No has new value');
        $this->assertNull($obj->get('index'), 'Key is not setted and return failback');
        $this->assertNull($obj->get('other_index'), 'Key is not setted and return failback');
        $obj->set('index', 'value');
        $obj->other_index = 'other_value';
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertTrue($obj->has('other_index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('other_value', $obj->other_index, 'Values are equals');
    }
    public function testArrayOfStringsFailbackDefault()
    {
        $obj = DataTransferFactory::create();
        $this->assertFalse($obj->has('index'), 'Key is not setted');
        $this->assertFalse($obj->has('other_index'), 'No has new value');
        $this->assertIsString($obj->get('index', 'value'), 'Key is not setted and return failback');
        $this->assertIsString($obj->get('other_index', 'other_value'), 'Key is not setted and return failback');
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertTrue($obj->has('other_index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('other_value', $obj->other_index, 'Values are equals');
    }
}