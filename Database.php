<?php

namespace FpDbTest;

use Exception;
use FpDbTest\DB\ConditionBlock\ConditionBlockSkip;
use FpDbTest\DB\QueryBuilder;
use mysqli;

class Database implements DatabaseInterface
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function buildQuery(string $query, array $args = []): string
    {
        $queryBuilder = new QueryBuilder($this->mysqli);

        return $queryBuilder->buildQuery($query, $args);
    }

    public function skip(): ConditionBlockSkip
    {
        return new ConditionBlockSkip();
    }
}
