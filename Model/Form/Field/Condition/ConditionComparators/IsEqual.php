<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form\Field\Condition\ConditionComparators;

/**
 * Class IsEqual
 * @package HappyMachines\CustomForms\Model\Form\Field\Condition\ConditionComparators
 */
class IsEqual implements ConditionComparatorInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const COMPARISON_EQUAL = 'eq';
    /**#@-*/

    /**
     * @param $sourceValue
     * @param $targetValue
     * @return bool
     */
    public function compare($sourceValue, $targetValue)
    {
        if (is_array($sourceValue) && is_array($targetValue)) {
            sort($sourceValue);
            sort($targetValue);
        }

        return $sourceValue === $targetValue;
    }
}
