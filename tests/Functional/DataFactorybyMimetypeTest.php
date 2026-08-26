<?php

namespace JuanchoSL\DataTransfer\Tests\Functional;

use ArrayAccess;
use Countable;
use PHPUnit\Framework\TestCase;
use JuanchoSL\DataTransfer\Factories\DataTransferFactory;

class DataFactorybyMimetypeTest extends TestCase
{

    public static function getFileSingleProvider(): array
    {
        $dir = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'assets';
        return [
            'mac' => [$dir . DIRECTORY_SEPARATOR . 'testfile-mac.csv', 'application/vnd.ms-excel'],
            'purewin' => [$dir . DIRECTORY_SEPARATOR . 'testfile-pure-win.csv', 'application/csv'],
            'pureunix' => [$dir . DIRECTORY_SEPARATOR . 'testfile-pure-unix.csv', 'application/csv'],
            'puremac' => [$dir . DIRECTORY_SEPARATOR . 'testfile-pure-mac.csv', 'application/csv'],
            'tsv' => [$dir . DIRECTORY_SEPARATOR . 'testfile.tsv', 'text/tab-separated-values'],
            'tsvunix' => [$dir . DIRECTORY_SEPARATOR . 'testfile-unix.tsv', 'text/tab-separated-values'],
            'tsvmac' => [$dir . DIRECTORY_SEPARATOR . 'testfile-mac.tsv', 'text/tab-separated-values'],
            'csv' => [$dir . DIRECTORY_SEPARATOR . 'testfile-coma.csv', 'application/vnd.ms-excel'],
            'doswin' => [$dir . DIRECTORY_SEPARATOR . 'testfile-dos-win.csv', 'application/vnd.ms-excel'],
            'dosunix' => [$dir . DIRECTORY_SEPARATOR . 'testfile-dos-unix.csv', 'application/vnd.ms-excel'],
            'dosmac' => [$dir . DIRECTORY_SEPARATOR . 'testfile-dos-mac.csv', 'application/vnd.ms-excel'],
            'json' => [$dir . DIRECTORY_SEPARATOR . 'testfile.json', 'application/json'],
            'dif' => [$dir . DIRECTORY_SEPARATOR . 'testfile.dif', 'application/x-dif'],
            'difexcel' => [$dir . DIRECTORY_SEPARATOR . 'testfile-converted.dif', 'application/x-dif'],
        ];
    }

    public static function getFileMultipageProvider(): array
    {
        $dir = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'assets';
        return [
            'oxml' => [$dir . DIRECTORY_SEPARATOR . 'testfileopenxml.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'ods' => [$dir . DIRECTORY_SEPARATOR . 'testfile.ods', 'application/vnd.oasis.opendocument.spreadsheet'],
            'xlsx' => [$dir . DIRECTORY_SEPARATOR . 'testfile.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'xml' => [$dir . DIRECTORY_SEPARATOR . 'testsheet.xml', 'application/xml'],
            'json' => [$dir . DIRECTORY_SEPARATOR . 'testfile.xlsx.json', 'application/json'],
            'yaml' => [$dir . DIRECTORY_SEPARATOR . 'testfile.xlsx.yaml', 'application/yaml'],
        ];
    }

    /**
     * Summary of testFromJsonWithObject
     * @dataProvider getFileSingleProvider
     */
    public function testFromSinglePage($file_path, $format)
    {
        $obj = DataTransferFactory::byMimeType($file_path, $format);
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
    public function testFromMultiPage($file_path, $format)
    {
        $obj = DataTransferFactory::byMimeType($file_path, $format);
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
            $this->assertEqualsCanonicalizing('a text with some spaces', $element['text']);
        }
    }
}