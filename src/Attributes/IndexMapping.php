<?php
declare(strict_types=1);

namespace Saswo\CsvReader\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class IndexMapping extends AbstractMappingAttributes
{
    public function __construct(private int $index)
    {

    }

    public function getIndex(): int
    {
        return $this->index;
    }
}