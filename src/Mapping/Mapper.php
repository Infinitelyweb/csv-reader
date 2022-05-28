<?php

namespace Saswo\CsvReader\Mapping;

use ReflectionAttribute;
use Saswo\CsvReader\Attributes\AbstractMappingAttributes;
use Saswo\CsvReader\Attributes\IndexMapping;
use Saswo\CsvReader\Attributes\TitleMapping;
use Saswo\CsvReader\shared\ValueConverter;

class Mapper
{
    private ?string $targetType = null;
    private ?int $sourceColumnIndex = null;
    private ?string $methodName = null;
    private ?string $propertyName = null;

    public function __construct(private readonly \ReflectionMethod|\ReflectionProperty $reflection, private readonly ?array $header)
    {
    }

    public function getMapping(): ?Mapper
    {
        $index = $this->getColumnIndex();
        if ($index === null) {
            return null;
        }
        $this->sourceColumnIndex = $index;

        if ($this->reflection instanceof \ReflectionMethod) {
            $this->methodName = $this->reflection->getName();
            $this->targetType = (string)$this->reflection->getParameters()[0]->getType();
        } else if ($this->reflection instanceof \ReflectionProperty) {
            $this->propertyName = $this->reflection->getName();
            $this->targetType = (string)$this->reflection->getType();
        }
        return $this;
    }

    public static function map(array $row, string $mapInto, array $rowMappings): object
    {
        $object = new $mapInto();

        /** @var Mapper $mapping */
        foreach ($rowMappings as $mapping) {
            $object = $mapping->mapRowInto($row, $object);
        }

        return $object;
    }

    private function mapRowInto(array $row, object $object): object
    {
        $row = array_values($row);
        $value = ValueConverter::convert($row[$this->sourceColumnIndex], $this->targetType);

        if ($this->methodName) {
            $object->{$this->methodName}($value);
        } else if ($this->propertyName) {
            $object->{$this->propertyName} = $value;
        }

        return $object;
    }

    private function getColumnIndex(): ?int
    {
        // we check all attributes - and use the first suitable attribute
        foreach ($this->getAttributes() ?? [] as $attribute) {
            $instance = $attribute->newInstance();
            if ($instance instanceof TitleMapping) {
                $index = $this->header[$instance->getTitle()] ?? null;
                if ($index === null) {
                    continue;
                }
                return $index;
            }

            if ($instance instanceof IndexMapping) {
                return $instance->getIndex();
            }
        }

        return null;
    }

    private function getAttributes(): ?array
    {
        $attributes = $this->reflection->getAttributes(AbstractMappingAttributes::class, ReflectionAttribute::IS_INSTANCEOF);
        if (!$attributes) {
            return null;
        }

        return $attributes;
    }
}