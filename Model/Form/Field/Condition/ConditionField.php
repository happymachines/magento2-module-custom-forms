<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form\Field\Condition;

use Magento\Framework\DataObject;

/**
 * Class ConditionField
 * @package HappyMachines\CustomForms\Model\Form\Field
 */
class ConditionField extends DataObject implements ConditionFieldInterface
{
    /**
     * @inheritDoc
     */
    public function getFieldName()
    {
        return $this->getData(self::FIELD_NAME);
    }

    /**
     * @inheritDoc
     */
    public function getFieldProperty()
    {
        return $this->getData(self::FIELD_PROPERTY);
    }

    /**
     * @inheritDoc
     */
    public function getFieldComparison()
    {
        return $this->getData(self::FIELD_COMPARISON);
    }

    /**
     * @inheritDoc
     */
    public function getFieldValue()
    {
        return $this->getData(self::FIELD_VALUE);
    }

    /**
     * @inheritDoc
     */
    public function setFieldName($name)
    {
        return $this->setData(self::FIELD_NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function setFieldProperty($property)
    {
        return $this->setData(self::FIELD_PROPERTY, $property);
    }

    /**
     * @inheritDoc
     */
    public function setFieldComparison($comparison)
    {
        return $this->setData(self::FIELD_COMPARISON, $comparison);
    }

    /**
     * @inheritDoc
     */
    public function setFieldValue($value)
    {
        return $this->setData(self::FIELD_VALUE, $value);
    }
}
