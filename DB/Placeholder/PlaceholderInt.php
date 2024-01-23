<?php

namespace FpDbTest\DB\Placeholder;

class PlaceholderInt extends BasePlaceholder
{
    static public function getName(): string
    {
        return '?d';
    }

    public function getAllowedDataTypes(): array
    {
        return [
            self::TYPE_STRING,
            self::TYPE_BOOLEAN,
            self::TYPE_INTEGER,
            self::TYPE_FLOAT,
            self::TYPE_NULL,
        ];
    }

    public function convertParam($param): string
    {
        return (int) $param;
    }
}