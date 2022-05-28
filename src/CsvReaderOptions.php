<?php

namespace Saswo\CsvReader;

class CsvReaderOptions
{
    public string $delimiter = ';';
    public string $enclosure = '"';
    public ?int $headerOffset = 0;
}