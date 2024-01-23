<?php

namespace FpDbTest\DB\Placeholder;

class PlaceholderId extends PlaceholderArray
{
    static public function getName(): string
    {
        return '?#';
    }

    public function getAllowedDataTypes(): array
    {
        return [
            self::TYPE_STRING,
            self::TYPE_ARRAY,
        ];
    }

    public function convertParam($param): string
    {
        if (is_array($param)) {
            $values = [];
            foreach ($param as $value) {
                $values[] = "`$value`";
            }

            $param = implode(", ", $values);
        } else {
            $param = "`$param`";
        }

        return mysqli_escape_string($this->mysqli, $param);
    }
}