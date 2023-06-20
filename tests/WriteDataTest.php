<?php

namespace JuanchoSL\DataTransfer\Tests;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;
use JuanchoSL\DataTransfer\Repositories\ArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\JsonArrayDataTransfer;
use JuanchoSL\DataTransfer\Repositories\JsonObjectDataTransfer;
use JuanchoSL\DataTransfer\Repositories\ObjectDataTransfer;
use PHPUnit\Framework\TestCase;

class WriteDataTest extends TestCase
{

    public function testArrayOfStrings()
    {
        $data = [];
        $obj = new ArrayDataTransfer($data);
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
        
        $obj = new ObjectDataTransfer($data);
        
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
        $obj = new JsonArrayDataTransfer(json_encode($data));
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
        $obj = new JsonObjectDataTransfer(json_encode($data));
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
        $obj = new JsonArrayDataTransfer(json_encode($data));
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
        $obj = new JsonArrayDataTransfer(json_encode($data));
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
        $obj = new JsonObjectDataTransfer(json_encode($data));
        $this->assertTrue($obj->has('key'), 'Key is setted');
        $this->assertEquals('value', $obj->get('key')->get('subkey'), 'Values are equals');
        $this->assertEquals('value', $obj->key->subkey, 'Values are equals');
    }

    public function testJsonOfArrayB()
    {
        $data = [
            'index' => ['subindex' => 'value']
        ];
        $obj = new JsonObjectDataTransfer(json_encode($data));
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
        $obj = new JsonObjectDataTransfer(json_encode($data));
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(DataTransferInterface::class, $obj->get('index'), 'Value is instance');
        foreach ($obj->get('index') as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }

}