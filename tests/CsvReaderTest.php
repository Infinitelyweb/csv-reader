<?php

namespace Saswo\CsvReader\Tests;

use Saswo\CsvReader\CsvReader;
use PHPUnit\Framework\TestCase;
use Saswo\CsvReader\CsvReaderOptions;
use Saswo\CsvReader\shared\ArrayCollection;
use Saswo\CsvReader\Tests\data\CsvFileGenerator;
use Saswo\CsvReader\Tests\data\Foo;

class CsvReaderTest extends TestCase
{
    private static $testfiles = [
        'simple' => __DIR__.'/data/simpleCsv.csv',
        'without_header' => __DIR__.'/data/simpleCsvWithoutHeader.csv',
    ];

    public static function setUpBeforeClass(): void
    {
        if(!file_exists(self::$testfiles['simple'])) {
            CsvFileGenerator::generateSimpleTestFile(self::$testfiles['simple']);
        }

        if(!file_exists(self::$testfiles['without_header'])) {
            CsvFileGenerator::generateTetfileWithoutHeader(self::$testfiles['without_header']);
        }
    }

    public function testReadIntoCollection()
    {
        $options = new CsvReaderOptions();
        $options->delimiter = ';';
        $csvReader = new CsvReader(self::$testfiles['simple'], $options);
        $collection = $csvReader->readIntoCollection(Foo::class);
        $this->assertInstanceOf(ArrayCollection::class, $collection);
        $this->assertInstanceOf( Foo::class, $collection->getItem(0) );
        $this->assertEquals(1, $collection->getItem(0)->getId());
    }

    public function testReadIntoArray()
    {
        $options = new CsvReaderOptions();
        $options->delimiter = ';';
        $csvReader = new CsvReader(self::$testfiles['simple'], $options);
        $collection = $csvReader->readIntoArray(Foo::class);
        $this->assertIsArray($collection);
        $this->assertInstanceOf( Foo::class, $collection[0] );
        $this->assertEquals(1, $collection[0]->getId());
    }

    public function testRead()
    {
        $options = new CsvReaderOptions();
        $options->delimiter = ';';

        $csvReader = new CsvReader(self::$testfiles['simple'], $options);
        foreach( $csvReader->read(Foo::class) as $row) {
            $this->assertInstanceOf( Foo::class, $row );
            $this->assertEquals(1, $row->getId());
            break;
        }

        $options->headerOffset = null;
        $csvReader = new CsvReader(self::$testfiles['without_header'], $options);
        foreach( $csvReader->read(Foo::class) as $row) {
            $this->assertInstanceOf( Foo::class, $row );
            $this->assertEquals(1, $row->getId());
            break;
        }
    }
}
