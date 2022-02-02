<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form\Field\Condition;

/**
 * Interface ConditionFieldInterface
 * @package HappyMachines\CustomForms\Model\Form\Field\Condition
 */
interface ConditionFieldInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const FIELD_NAME = 'fieldName';
    const FIELD_PROPERTY = 'property';
    const FIELD_COMPARISON = 'comparison';
    const FIELD_VALUE = 'value';
    /**#@-*/

    /**
     * @return string
     */
    public function getFieldName();

    /**
     * @return string
     */
    public function getFieldProperty();

    /**
     * @return string
     */
    public function getFieldComparison();

    /**
     * @return string|array
     */
    public function getFieldValue();

    /**
     * @param string $name
     * @return ConditionFieldInterface
     */
    public function setFieldName($name);

    /**
     * @param string $property
     * @return ConditionFieldInterface
     */
    public function setFieldProperty($property);

    /**
     * @param string $comparison
     * @return ConditionFieldInterface
     */
    public function setFieldComparison($comparison);

    /**
     * @param string|array $value
     * @return ConditionFieldInterface
     */
    public function setFieldValue($value);
}
