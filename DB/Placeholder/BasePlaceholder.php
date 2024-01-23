<?php

namespace FpDbTest\DB\Placeholder;
use mysqli;

abstract class BasePlaceholder
{
    const TYPE_ARRAY = 'array';
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'double';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_NULL = 'NULL';

    public function __construct(
        protected readonly mysqli $mysqli
    ) {
    }

    abstract static function getName(): string;

    abstract function getAllowedDataTypes(): array;

    abstract public function convertParam($param): string;
}