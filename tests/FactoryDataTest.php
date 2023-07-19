<?php

namespace JuanchoSL\DataTransfer\Tests;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\DataTransferFactory;
use PHPUnit\Framework\TestCase;

class FactoryDataTest extends TestCase
{

    public function testArrayOfStrings()
    {
        $data = [];
        $obj = DataTransferFactory::create($data);
        $this->assertFalse($obj->has('index'), 'Key is not setted');
        $this->assertFalse($obj->has('other_index'), 'No has new value');
        $obj->set('index', 'value');
        $obj->other_index = 'other_value';
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertTrue($obj->has('other_index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('other_value', $obj->other_index, 'Values are equals');
    }

    public function testObjects()
    {
        $data = new \stdClass;

        $obj = DataTransferFactory::create($data);

        $this->assertFalse($obj->has('index'), 'Key is not setted');
        $this->assertFalse($obj->has('other_index'), 'No has new value');
        $obj->set('index', 'value');
        $obj->other_index = 'other_value';
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertTrue($obj->has('other_index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('other_value', $obj->other_index, 'Values are equals');
    }

    public function testJsonArrayOfStrings()
    {
        $data = [];
        $obj = DataTransferFactory::create(json_encode($data));
        $this->assertFalse($obj->has('index'), 'Key is not setted');
        $this->assertFalse($obj->has('other_index'), 'No has new value');
        $obj->set('index', 'value');
        $obj->other_index = 'other_value';
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertTrue($obj->has('other_index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('other_value', $obj->other_index, 'Values are equals');
    }

    public function testJsonObjectOfStrings()
    {
        $data = [
            'key' => 'value'
        ];
        $obj = DataTransferFactory::create(json_encode($data));
        $this->assertFalse($obj->has('index'), 'Key is not setted');
        $this->assertFalse($obj->has('other_index'), 'No has new value');
        $obj->set('index', 'value');
        $obj->other_index = 'other_value';
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertTrue($obj->has('other_index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('other_value', $obj->other_index, 'Values are equals');
    }

    public function testJsonOfArray()
    {
        $data = [
            'key' => ['subindex' => 'value']
        ];
        $obj = DataTransferFactory::create(json_encode($data));
        $this->assertTrue($obj->has('key'), 'Key is setted');
        $this->assertFalse($obj->key->has('index'), 'Key is not setted');
        $this->assertFalse($obj->key->has('other_index'), 'No has new value');
        $obj->key->set('index', 'value');
        $obj->key->other_index = 'other_value';
        $this->assertTrue($obj->key->has('index'), 'Key is setted');
        $this->assertTrue($obj->key->has('other_index'), 'Key is setted');
        $this->assertEquals('value', $obj->key->get('index'), 'Values are equals');
        $this->assertEquals('other_value', $obj->key->other_index, 'Values are equals');
    }

    public function testArrayOfArray()
    {
        $data = [
            'key' => ['value_0', 'value_1']
        ];
        $obj = DataTransferFactory::create(json_encode($data));
        $this->assertTrue($obj->has('key'), 'Key is setted');
        $this->assertFalse($obj->key->has('index'), 'Key is not setted');
        $this->assertFalse($obj->key->has('other_index'), 'No has new value');
        $obj->key->set('index', 'value');
        $obj->key->other_index = 'other_value';
        $this->assertTrue($obj->key->has('index'), 'Key is setted');
        $this->assertTrue($obj->key->has('other_index'), 'Key is setted');
        $this->assertEquals('value', $obj->key->get('index'), 'Values are equals');
        $this->assertEquals('other_value', $obj->key->other_index, 'Values are equals');
    }


    public function testJsonOfStringsB()
    {
        $data = [
            'key' => '{"subkey":"value"}'
        ];
        $obj = DataTransferFactory::create(json_encode($data));
        $this->assertTrue($obj->has('key'), 'Key is setted');
        $this->assertEquals('value', $obj->get('key')->get('subkey'), 'Values are equals');
        $this->assertEquals('value', $obj->key->subkey, 'Values are equals');
    }

    public function testJsonOfArrayB()
    {
        $data = [
            'index' => ['subindex' => 'value']
        ];
        $obj = DataTransferFactory::create(json_encode($data));
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        $this->assertTrue($obj->get('index')->has('subindex'), 'SubKey is setted');
        $this->assertEquals('value', $obj->get('index')->get('subindex'), 'Values are equals');
        $this->assertEquals('value', $obj->index->subindex, 'Values are equals');
    }

    public function testArrayOfArrayB()
    {
        $data = [
            'index' => ['value_0', 'value_1']
        ];
        $obj = DataTransferFactory::create(json_encode($data));
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        foreach ($obj->get('index') as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }
    public function testArrayOfDTO()
    {
        $data = [
            'index' => ['value_0', 'value_1']
        ];
        $obj1 = DataTransferFactory::create(json_encode($data));
        $obj2 = DataTransferFactory::create(json_encode($data));
        $obj = DataTransferFactory::create(['first' => $obj1, 'second' => $obj2]);
        $this->assertTrue($obj->has('first'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('first'), 'Value is instance');
        $this->assertTrue($obj->get('first')->has("index"), 'Subkey is setted');
        foreach ($obj->get('first')->get("index") as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }

}