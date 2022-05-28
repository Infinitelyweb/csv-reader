<?php

namespace Saswo\CsvReader\shared;

use Saswo\CsvReader\Mapping\DateTimeImmutable;
use Saswo\CsvReader\Mapping\DateTimeInterface;
use Saswo\CsvReader\Mapping\Throwable;

enum ValueConverter
{
    public static function convert(string $value, string $type) :mixed
    {
        if(self::isNullValue($value, $type)) {
            return null;
        }
        $type = ltrim($type, '?');

        switch ($type) {
            case 'int':
                return (int) $value;
            case 'bool':
                return filter_var($value, FILTER_VALIDATE_BOOL);
            case 'string':
                return (string) $value;
            case 'float':
                return (float) $value;
            case DateTimeInterface::class:
                try {
                    return new DateTimeImmutable($value);
                } catch (Throwable $ex) {
                    return (string) $value;
                }
            case \DateTime::class:
                try {
                    return new \DateTime($value);
                } catch (Throwable $ex) {
                    return (string) $value;
                }
            default:
                return null;
        }
    }

    protected static function isNullValue(string $value, string $targetType): bool
    {
        return str_starts_with($targetType, '?') && (0 === strlen($value) || 'null' === strtolower(trim($value)));
    }
}