<?php

namespace FpDbTest\DB\Placeholder;

class PlaceholderCommon extends BasePlaceholder
{
    static public function getName(): string
    {
        return '?';
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
        switch (gettype($param)) {
            case self::TYPE_BOOLEAN:
                return $param ? '1' : '0';
            case self::TYPE_NULL:
                return 'NULL';
            case self::TYPE_STRING:
                $param = mysqli_escape_string($this->mysqli, $param);

                return "'$param'";
            default:
                return $param;
        }
    }
}