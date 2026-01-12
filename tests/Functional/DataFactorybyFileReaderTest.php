<?php

namespace JuanchoSL\DataTransfer\Tests\Functional;

use ArrayAccess;
use Countable;
use JuanchoSL\DataTransfer\Enums\Format;
use PHPUnit\Framework\TestCase;
use JuanchoSL\DataTransfer\Factories\DataTransferFactory;

class DataFactorybyFileReaderTest extends TestCase
{

    protected function getFileSingleProvider(): array
    {
        $dir = getcwd() . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'assets';
        return [
            'mac' => [$dir . DIRECTORY_SEPARATOR . 'testfile-mac.csv'],
            'purewin' => [$dir . DIRECTORY_SEPARATOR . 'testfile-pure-win.csv'],
            'pureunix' => [$dir . DIRECTORY_SEPARATOR . 'testfile-pure-unix.csv'],
            'puremac' => [$dir . DIRECTORY_SEPARATOR . 'testfile-pure-mac.csv'],
            'tsv' => [$dir . DIRECTORY_SEPARATOR . 'testfile.tsv'],
            'tsvunix' => [$dir . DIRECTORY_SEPARATOR . 'testfile-unix.tsv'],
            'tsvmac' => [$dir . DIRECTORY_SEPARATOR . 'testfile-mac.tsv'],
            'csv' => [$dir . DIRECTORY_SEPARATOR . 'testfile-coma.csv'],
            'doswin' => [$dir . DIRECTORY_SEPARATOR . 'testfile-dos-win.csv'],
            'dosunix' => [$dir . DIRECTORY_SEPARATOR . 'testfile-dos-unix.csv'],
            'dosmac' => [$dir . DIRECTORY_SEPARATOR . 'testfile-dos-mac.csv'],
            'json' => [$dir . DIRECTORY_SEPARATOR . 'testfile.json'],
        ];
    }

    protected function getFileMultipageProvider(): array
    {
        $dir = getcwd() . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'assets';
        return [
            'oxml' => [$dir . DIRECTORY_SEPARATOR . 'testfileopenxml.xlsx'],
            'ods' => [$dir . DIRECTORY_SEPARATOR . 'testfile.ods'],
            'xlsx' => [$dir . DIRECTORY_SEPARATOR . 'testfile.xlsx'],
            'xml' => [$dir . DIRECTORY_SEPARATOR . 'testsheet.xml'],
            'json' => [$dir . DIRECTORY_SEPARATOR . 'testfile.xlsx.json'],
            'yaml' => [$dir . DIRECTORY_SEPARATOR . 'testfile.xlsx.yaml'],
        ];
    }

    /**
     * Summary of testFromJsonWithObject
     * @dataProvider getFileSingleProvider
     */
    public function testFromSinglePage($file_path)
    {
        $obj = DataTransferFactory::byFile($file_path);
        //echo '<pre>' . print_r($obj, true);exit;
        $this->assertInstanceOf(ArrayAccess::class, $obj);
        $this->assertInstanceOf(Countable::class, $obj);
        $this->assertContainsOnlyInstancesOf(ArrayAccess::class, $obj);
        $this->assertCount(8, $obj);
        $this->check($obj);
    }

    /**
     * Summary of testFromJsonWithObject
     * @dataProvider getFileMultipageProvider
     */
    public function testFromMultiPage($file_path)
    {
        $obj = DataTransferFactory::byFile($file_path);
        //echo '<pre>' . print_r($obj, true);exit;
        $this->assertInstanceOf(ArrayAccess::class, $obj);
        $this->assertInstanceOf(Countable::class, $obj);
        $this->assertContainsOnlyInstancesOf(ArrayAccess::class, $obj);
        foreach ($obj as $page => $elements) {
            $this->assertIsString($page);
            $this->assertGreaterThanOrEqual(5, strlen($page));
            $this->assertInstanceOf(Countable::class, $elements);
            $this->assertInstanceOf(ArrayAccess::class, $elements);
            $this->assertCount(8, $elements);
            $this->check($elements);
        }
    }

    protected function check($elements)
    {
        foreach ($elements as $element) {
            $this->assertInstanceOf(ArrayAccess::class, $element);
            $this->assertInstanceOf(Countable::class, $element);
            foreach (['Name', 'Surname', 'date', 'number', 'email', 'text'] as $field) {
                $this->assertArrayHasKey($field, $element);
                //$this->assertObjectHasProperty($field, $element);
                $this->assertNotEmpty($element->{$field});
            }
        }
    }
}