<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form\Field;

use HappyMachines\CustomForms\Model\Form\Field\Condition\ConditionFieldInterface;

/**
 * Interface ConditionInterface
 * @package HappyMachines\CustomForms\Model\Form\Field
 */
interface ConditionInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const SOURCE = 'source';
    const TARGET = 'target';
    /**#@-*/

    /**
     * @return ConditionFieldInterface
     */
    public function getSource();

    /**
     * @return ConditionFieldInterface
     */
    public function getTarget();

    /**
     * @param array|ConditionFieldInterface $source
     * @return ConditionFieldInterface
     */
    public function setSource($source);

    /**
     * @param array|ConditionFieldInterface $target
     * @return ConditionFieldInterface
     */
    public function setTarget($target);
}
