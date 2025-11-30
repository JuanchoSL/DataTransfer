<?php

namespace JuanchoSL\DataTransfer\Tests\Functional;

use ArrayAccess;
use Countable;
use JuanchoSL\DataTransfer\Enums\Format;
use PHPUnit\Framework\TestCase;
use JuanchoSL\DataTransfer\Factories\DataTransferFactory;

class DataFactoryWithFormatReaderTest extends TestCase
{

    protected function getFileSingleProvider(): array
    {
        $dir = getcwd() . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'assets';
        return [
            'mac' => [$dir . DIRECTORY_SEPARATOR . 'testfile-mac.csv', Format::EXCEL_CSV],
            'purewin' => [$dir . DIRECTORY_SEPARATOR . 'testfile-pure-win.csv', Format::CSV],
            'pureunix' => [$dir . DIRECTORY_SEPARATOR . 'testfile-pure-unix.csv', Format::CSV],
            'puremac' => [$dir . DIRECTORY_SEPARATOR . 'testfile-pure-mac.csv', Format::CSV],
            'tsv' => [$dir . DIRECTORY_SEPARATOR . 'testfile.tsv', Format::TAB],
            'tsvunix' => [$dir . DIRECTORY_SEPARATOR . 'testfile-unix.tsv', Format::TAB],
            'tsvmac' => [$dir . DIRECTORY_SEPARATOR . 'testfile-mac.tsv', Format::TAB],
            'csv' => [$dir . DIRECTORY_SEPARATOR . 'testfile-coma.csv', Format::EXCEL_CSV],
            'doswin' => [$dir . DIRECTORY_SEPARATOR . 'testfile-dos-win.csv', Format::EXCEL_CSV],
            'dosunix' => [$dir . DIRECTORY_SEPARATOR . 'testfile-dos-unix.csv', Format::EXCEL_CSV],
            'dosmac' => [$dir . DIRECTORY_SEPARATOR . 'testfile-dos-mac.csv', Format::EXCEL_CSV],
            'json' => [$dir . DIRECTORY_SEPARATOR . 'testfile.json', Format::JSON],
        ];
    }

    protected function getFileMultipageProvider(): array
    {
        $dir = getcwd() . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'assets';
        return [
            'oxml' => [$dir . DIRECTORY_SEPARATOR . 'testfileopenxml.xlsx', Format::EXCEL_XLSX],
            'ods' => [$dir . DIRECTORY_SEPARATOR . 'testfile.ods', Format::ODS],
            'xlsx' => [$dir . DIRECTORY_SEPARATOR . 'testfile.xlsx', Format::EXCEL_XLSX],
            'xml' => [$dir . DIRECTORY_SEPARATOR . 'testsheet.xml', Format::EXCEL_XML],
            'json' => [$dir . DIRECTORY_SEPARATOR . 'testfile.xlsx.json', Format::JSON],
            'yaml' => [$dir . DIRECTORY_SEPARATOR . 'testfile.xlsx.yaml', Format::YAML],
        ];
    }

    /**
     * Summary of testFromJsonWithObject
     * @dataProvider getFileSingleProvider
     */
    public function testFromSinglePage($file_path, $format)
    {
        $obj = DataTransferFactory::byFile($file_path, $format);
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
    public function testFromMultiPage($file_path, $format)
    {
        $obj = DataTransferFactory::byFile($file_path, $format);
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