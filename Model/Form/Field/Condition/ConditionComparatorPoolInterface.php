<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form\Field\Condition;

/**
 * Interface ConditionComparatorPoolInterface
 * @package HappyMachines\CustomForms\Model\Form\Field
 */
interface ConditionComparatorPoolInterface
{
    /**
     * Compares the source value against the target value, executing
     * a comparison method based on the comparison operator
     * @param $comparisonOperator
     * @param $sourceValue
     * @param $targetValue
     */
    public function compare($comparisonOperator, $sourceValue, $targetValue);
}
