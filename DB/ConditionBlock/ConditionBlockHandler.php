<?php

namespace FpDbTest\DB\ConditionBlock;

class ConditionBlockHandler
{
    public function proceedConditionBlocks(string $query): string
    {
        /** @var ConditionBlock[] $conditionBlocks */
        $conditionBlocks = $this->findConditionBlocks($query);
        $positionDifference = 0;

        foreach ($conditionBlocks as $conditionBlock) {
            $startPosition = $conditionBlock->startPosition + $positionDifference;
            $endPosition = $conditionBlock->endPosition + $positionDifference;

            if ($this->shouldSkipConditionBlock($conditionBlock)) {
                $query = substr_replace($query, "", $startPosition, $endPosition);

                $positionDifference = $positionDifference - strlen($conditionBlock->conditionBlockText);
            } else {
                $query = substr_replace($query, "", $startPosition, 1);
                $query = substr_replace($query, "", ($endPosition - 2), 1);

                $positionDifference -= 2;
            }
        }

        return $query;
    }

    private function findConditionBlocks(string $query): array
    {
        preg_match_all('/{[^}]*}/', $query, $matches, PREG_OFFSET_CAPTURE);

        $conditionBlocks = [];
        foreach ($matches[0] as $result) {
            $conditionBlock = new ConditionBlock();
            $conditionBlock->startPosition = (int) $result[1];
            $conditionBlock->endPosition = $conditionBlock->startPosition + strlen($result[0]);
            $conditionBlock->conditionBlockText = $result[0];

            $conditionBlocks[] = $conditionBlock;
        }

        return $conditionBlocks;
    }

    private function shouldSkipConditionBlock(ConditionBlock $conditionBlock): bool
    {
        return str_contains($conditionBlock->conditionBlockText, ConditionBlockSkip::SKIP_PLACEHOLDER);
    }
}