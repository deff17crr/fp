<?php

namespace FpDbTest\DB\Placeholder;

class PlaceholderArray extends PlaceholderCommon
{
    private const SEPARATOR = ', ';

    static public function getName(): string
    {
        return '?a';
    }

    public function getAllowedDataTypes(): array
    {
        return [
            self::TYPE_ARRAY,
        ];
    }

    public function convertParam($param): string
    {
        $isAssoc = false;
        $paramValues = [];
        foreach ($param as $key => $value) {
            if (!$isAssoc && gettype($key) === 'string') {
                $isAssoc = true;
            }

            $convertedValue = parent::convertParam($value);

            $paramValues[] = $isAssoc ?
                sprintf('`%s` = %s', $key, $convertedValue) :
                $convertedValue;
        }

        return implode(self::SEPARATOR, $paramValues);
    }
}