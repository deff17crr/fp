<?php

namespace FpDbTest\DB;

use FpDbTest\DB\ConditionBlock\ConditionBlockHandler;
use FpDbTest\DB\Placeholder\PlaceholderHandler;
use mysqli;

class QueryBuilder
{
    private PlaceholderHandler $placeholderHandler;
    private ConditionBlockHandler $conditionBlockHandler;

    public function __construct(
        mysqli $mysqli
    ) {
        /* @TODO it's better to use Dependency Injection instead of composition */
        $this->placeholderHandler = new PlaceholderHandler($mysqli);
        $this->conditionBlockHandler = new ConditionBlockHandler();
    }

    public function buildQuery(string $query, array $params): string
    {
        $newQuery = $this->placeholderHandler->replaceParams($query, $params);

        $newQuery = $this->conditionBlockHandler->proceedConditionBlocks($newQuery);

        return $newQuery;
    }
}