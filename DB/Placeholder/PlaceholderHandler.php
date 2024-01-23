<?php

namespace FpDbTest\DB\Placeholder;

use FpDbTest\DB\ConditionBlock\ConditionBlockSkip;
use FpDbTest\DB\QueryBuilderException;
use mysqli;

class PlaceholderHandler
{
    public function __construct(
        private readonly mysqli $mysqli,
    ) {
    }

    public function replaceParams(string $query, array $params): string
    {
        $positionedPlaceholders = $this->findPlaceholdersWithPositions($query);

        if (count($positionedPlaceholders) !== count($params)) {
            throw new QueryBuilderException(sprintf(
                'Provided wrong params number: %d, expected: %d',
                count($params),
                count($positionedPlaceholders)
            ));
        }

        $placeholderIndex = 0;
        $positionDifference = 0;
        foreach ($positionedPlaceholders as $position => $placeholderKey) {
            $placeholderKey = array_shift($positionedPlaceholders);
            $param = $params[$placeholderIndex];

            /* calculate parameter value based on placeholder type */
            if (!$param instanceof ConditionBlockSkip) {
                $placeholderClass = $this->getPlaceholderClass($placeholderKey);
                $paramType = gettype($param);
                $paramValue = $placeholderClass->convertParam($param);

                if (!in_array($paramType, $placeholderClass->getAllowedDataTypes(), true)) {
                    throw new QueryBuilderException(sprintf(
                        'Parameter type is not supported, allowed %s. You provided: %s',
                        implode(', ', $placeholderClass->getAllowedDataTypes()),
                        $paramType,
                    ));
                }

            } else {
                $paramValue = ConditionBlockSkip::SKIP_PLACEHOLDER;
            }

            /* replace placeholder and updated position of next placeholders */
            $placeholderLength = strlen($placeholderKey);
            $paramValueLength = strlen($paramValue);
            $currentPlaceholderPosition = $position + $positionDifference;
            $query = substr_replace($query, $paramValue, $currentPlaceholderPosition, $placeholderLength);

            $positionDifference += ($paramValueLength - $placeholderLength);
            $placeholderIndex++;
        }

        return $query;
    }

    public function findPlaceholdersWithPositions(string $query): array
    {
        $positionedPlaceholders = [];
        foreach (array_keys(self::getMappedPlaceholders()) as $placeholder) {
            $regExp = "/\\$placeholder |\\$placeholder\}|\\$placeholder\)|\\$placeholder$/";

            preg_match_all($regExp, $query, $results, PREG_OFFSET_CAPTURE);

            foreach ($results[0] as $match) {
                $positionedPlaceholders[$match[1]] = $placeholder;
            }
        }

        ksort($positionedPlaceholders);

        return $positionedPlaceholders;
    }

    private function getPlaceholderClass($placeholderKey): BasePlaceholder
    {
        $mappedPlaceholders = $this->getMappedPlaceholders();

        return new $mappedPlaceholders[$placeholderKey]($this->mysqli);
    }

    private function getMappedPlaceholders(): array
    {
        return [
            PlaceholderArray::getName()  => PlaceholderArray::class,
            PlaceholderCommon::getName() => PlaceholderCommon::class,
            PlaceholderFloat::getName()  => PlaceholderFloat::class,
            PlaceholderId::getName()     => PlaceholderId::class,
            PlaceholderInt::getName()    => PlaceholderInt::class,
        ];
    }
}