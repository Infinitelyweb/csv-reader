<?php
declare(strict_types=1);

namespace Saswo\CsvReader;

use League\Csv\Reader;
use Saswo\CsvReader\Mapping\Mapper;
use Saswo\CsvReader\Mapping\MappingBuilder;
use Saswo\CsvReader\shared\ArrayCollection;

class CsvReader
{
    private Reader $reader;

    public function __construct(
        private readonly string   $filepath,
        private ?CsvReaderOptions $options = null
    )
    {
        if (!file_exists($this->filepath)) {
            throw new \Exception('Argument #1 must be an valid filepath - file does not exist');
        }

        $this->setupReader();
    }

    /**
     * @throws \League\Csv\InvalidArgument
     * @throws \League\Csv\Exception
     */
    private function setupReader(): void
    {
        $this->reader = Reader::createFromPath($this->filepath);
        if (!$this->options) {
            $this->options = new CsvReaderOptions();
        }
        if ($this->options->headerOffset !== null) {
            $this->reader->setHeaderOffset($this->options->headerOffset);
        }
        $this->reader->setDelimiter($this->options->delimiter);
        $this->reader->setEnclosure($this->options->enclosure);
    }

    private function getRowMappings($mapInto): array
    {
        $header = $this->reader->getHeaderOffset() !== null
            ? $this->reader->getHeader() : null;

        return MappingBuilder::buildMap($header, $mapInto);
    }

    public function read(string $mapInto): iterable
    {
        $rowMappings = $this->getRowMappings($mapInto);

        foreach ($this->reader->getIterator() as $row) {
            yield Mapper::map($row, $mapInto, $rowMappings);
        }
    }

    public function readIntoArray(string $mapInto): array
    {
        $rowMappings = $this->getRowMappings($mapInto);

        $collection = [];
        foreach ($this->reader->getIterator() as $row) {
            $collection[] = Mapper::map($row, $mapInto, $rowMappings);
        }
        return $collection;
    }

    public function readIntoCollection(string $mapInto): ArrayCollection
    {
        $rowMappings = $this->getRowMappings($mapInto);

        $collection = new ArrayCollection();
        foreach ($this->reader->getIterator() as $row) {
            $collection->addItem(Mapper::map($row, $mapInto, $rowMappings));
        }
        return $collection;
    }
}