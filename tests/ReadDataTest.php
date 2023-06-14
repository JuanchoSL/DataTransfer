<?php

namespace JuanchoSL\DataTransfer\Tests;

use JuanchoSL\DataTransfer\Contracts\ExtractorInterface;
use JuanchoSL\DataTransfer\Repositories\ArrayExtractor;
use JuanchoSL\DataTransfer\Repositories\JsonArrayExtractor;
use JuanchoSL\DataTransfer\Repositories\JsonObjectExtractor;
use JuanchoSL\DataTransfer\Repositories\ObjectExtractor;
use PHPUnit\Framework\TestCase;

class ReadDataTest extends TestCase
{

    public function testArrayOfStrings()
    {
        $data = [
            'index' => 'value'
        ];
        $obj = new ArrayExtractor($data);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('value', $obj->index, 'Values are equals');
        $this->assertFalse($obj->has('new_value'), 'No has new value');
        $obj->set('new_value', 'other_value');
        $this->assertTrue($obj->has('new_value'), 'Has new value');
        $this->assertEquals('other_value', $obj->new_value, 'Values are equals');
        $obj->unset('new_value');
        $this->assertFalse($obj->has('new_value'), 'No has new value');
    }
    
    public function testObjects()
    {
        $data = new \stdClass;
        $data->index = 'value';
        
        $obj = new ObjectExtractor($data);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('value', $obj->index, 'Values are equals');
        $this->assertFalse($obj->has('new_value'), 'No has new value');
        $obj->set('new_value', 'other_value');
        $this->assertTrue($obj->has('new_value'), 'Has new value');
        $this->assertEquals('other_value', $obj->new_value, 'Values are equals');
        $obj->unset('new_value');
        $this->assertFalse($obj->has('new_value'), 'No has new value');
    }
    
    public function testJsonArrayOfStrings()
    {
        $data = [
            'index' => 'value'
        ];
        $obj = new JsonArrayExtractor(json_encode($data));
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('value', $obj->index, 'Values are equals');
        $this->assertFalse($obj->has('new_value'), 'No has new value');
        $obj->set('new_value', 'other_value');
        $this->assertTrue($obj->has('new_value'), 'Has new value');
        $this->assertEquals('other_value', $obj->new_value, 'Values are equals');
        $obj->unset('new_value');
        $this->assertFalse($obj->has('new_value'), 'No has new value');
    }
    
    public function testJsonObjectOfStrings()
    {
        $data = [
            'index' => 'value'
        ];
        $obj = new JsonObjectExtractor(json_encode($data));
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('value', $obj->index, 'Values are equals');
        $this->assertFalse($obj->has('new_value'), 'No has new value');
        $obj->set('new_value', 'other_value');
        $this->assertTrue($obj->has('new_value'), 'Has new value');
        $this->assertEquals('other_value', $obj->new_value, 'Values are equals');
        $obj->unset('new_value');
        $this->assertFalse($obj->has('new_value'), 'No has new value');
    }
    
    public function testJsonOfArray()
    {
        $data = [
            'index' => ['subindex' => 'value']
        ];
        $obj = new JsonArrayExtractor(json_encode($data));
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(ExtractorInterface::class, $obj->get('index'), 'Value is instance');
        $this->assertTrue($obj->get('index')->has('subindex'), 'SubKey is setted');
        $this->assertEquals('value', $obj->get('index')->get('subindex'), 'Values are equals');
        $this->assertEquals('value', $obj->index->subindex, 'Values are equals');
    }

    public function testArrayOfArray()
    {
        $data = [
            'index' => ['value_0', 'value_1']
        ];
        $obj = new JsonArrayExtractor(json_encode($data));
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(ExtractorInterface::class, $obj->get('index'), 'Value is instance');
        foreach ($obj->get('index') as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }


    public function testJsonOfStringsB()
    {
        $data = [
            'index' => 'value'
        ];
        $obj = new JsonObjectExtractor(json_encode($data));
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
        $this->assertEquals('value', $obj->index, 'Values are equals');

    }

    public function testJsonOfArrayB()
    {
        $data = [
            'index' => ['subindex' => 'value']
        ];
        $obj = new JsonObjectExtractor(json_encode($data));
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(ExtractorInterface::class, $obj->get('index'), 'Value is instance');
        $this->assertTrue($obj->get('index')->has('subindex'), 'SubKey is setted');
        $this->assertEquals('value', $obj->get('index')->get('subindex'), 'Values are equals');
        $this->assertEquals('value', $obj->index->subindex, 'Values are equals');
    }

    public function testArrayOfArrayB()
    {
        $data = [
            'index' => ['value_0', 'value_1']
        ];
        $obj = new JsonObjectExtractor(json_encode($data));
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertInstanceOf(ExtractorInterface::class, $obj->get('index'), 'Value is instance');
        foreach ($obj->get('index') as $key => $value) {
            $this->assertEquals("value_{$key}", $value, 'Values are equals');
        }
    }

}