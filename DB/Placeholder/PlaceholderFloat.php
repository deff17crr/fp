<?php

namespace FpDbTest\DB\Placeholder;

class PlaceholderFloat extends BasePlaceholder
{
    static public function getName(): string
    {
        return '?f';
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
        if ($param === null) {
            return 'NULL';
        }

        return (float) $param;
    }
}