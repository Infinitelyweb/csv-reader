<?php
declare(strict_types=1);

namespace Saswo\CsvReader\Mapping;

use ReflectionAttribute;
use ReflectionClass;
use Saswo\CsvReader\Attributes\AbstractMappingAttributes;
use Saswo\CsvReader\Attributes\IndexMapping;
use Saswo\CsvReader\Attributes\TitleMapping;

final class MappingBuilder
{
    public static function buildMap(?array $header, string $entity): array
    {
        try {
            $reflection = new ReflectionClass($entity);
        } catch (\ReflectionException $e) {
            return [];
        }

        if ($header) {
            $header = array_flip($header);
        }

        $mappings = [];
        foreach (array_merge(
                     $reflection->getProperties(\ReflectionProperty::IS_PUBLIC),
                     $reflection->getMethods(\ReflectionMethod::IS_PUBLIC)
                 ) as $propertyMethod) {
            $mapping = (new Mapper($propertyMethod, $header))->getMapping();
            if ($mapping === null) {
                continue;
            }
            $mappings[] = $mapping;
        }

        return $mappings;
    }
}