<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form\Field\Condition\ConditionComparators;

/**
 * Interface ConditionComparatorInterface
 * @package HappyMachines\CustomForms\Model\Form\Field\Condition\ConditionComparators
 */
interface ConditionComparatorInterface
{
    /**
     * Compares the source value against the target value, executing
     * a comparison method based on the comparison operator
     * @param $sourceValue
     * @param $targetValue
     */
    public function compare($sourceValue, $targetValue);
}
