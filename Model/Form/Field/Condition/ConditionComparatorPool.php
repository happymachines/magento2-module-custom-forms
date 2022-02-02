<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form\Field\Condition;

use HappyMachines\CustomForms\Model\Form\Field\Condition\ConditionComparators\ConditionComparatorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ConditionComparatorPool
 * @package HappyMachines\CustomForms\Model\Form\Field
 */
class ConditionComparatorPool implements ConditionComparatorPoolInterface
{
    /**
     * @var ConditionComparatorInterface[]
     */
    private $comparators;

    /**
     * @param ConditionComparatorInterface[] $comparators
     * @throws LocalizedException
     */
    public function __construct(
        array $comparators = []
    ) {
        foreach ($comparators as $comparator) {
            if (!$comparator instanceof ConditionComparatorInterface) {
                throw new LocalizedException(
                    __('Condition comparator must implement ConditionComparatorInterface.')
                );
            }
        }
        $this->comparators = $comparators;
    }

    /**
     * @param $comparisonOperator
     * @param $sourceValue
     * @param $targetValue
     * @return bool
     * @throws LocalizedException
     */
    public function compare($comparisonOperator, $sourceValue, $targetValue)
    {
        foreach ($this->comparators as $operator => $comparator) {
            if ($operator === $comparisonOperator) {
                return $comparator->compare($sourceValue, $targetValue);
            }
        }

        throw new LocalizedException(
            __('No condition comparator found for comparison type "%1".', $comparisonOperator)
        );
    }
}
