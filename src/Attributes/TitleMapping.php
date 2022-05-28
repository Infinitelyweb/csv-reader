<?php
declare(strict_types=1);

namespace Saswo\CsvReader\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class TitleMapping extends AbstractMappingAttributes
{
    public function __construct(private string $title)
    {

    }

    public function getTitle(): string
    {
        return $this->title;
    }
}