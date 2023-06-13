<?php

namespace JuanchoSL\DataExtractor\Tests;

use JuanchoSL\DataExtractor\ArrayExtractor;
use JuanchoSL\DataExtractor\Contracts\ExtractorInterface;
use JuanchoSL\DataExtractor\JsonArrayExtractor;
use JuanchoSL\DataExtractor\JsonObjectExtractor;
use JuanchoSL\DataExtractor\ObjectExtractor;
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
    }

    public function testObjects()
    {
        $data = new \stdClass;
        $data->index = 'value';

        $obj = new ObjectExtractor($data);
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');
    }

    public function testJsonOfStrings()
    {
        $data = [
            'index' => 'value'
        ];
        $obj = new JsonArrayExtractor(json_encode($data));
        $this->assertTrue($obj->has('index'), 'Key is setted');
        $this->assertEquals('value', $obj->get('index'), 'Values are equals');

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

}