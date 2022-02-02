<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form\Field;

use Magento\Framework\DataObject;

/**
 * Class Option
 * @package HappyMachines\CustomForms\Model\Form\Field
 */
class Option extends DataObject implements OptionInterface
{
    /**
     * @inheritDoc
     */
    public function getLabel()
    {
        return $this->getData(self::LABEL);
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    /**
     * @inheritDoc
     */
    public function getSelected()
    {
        return $this->getData(self::SELECTED);
    }

    /**
     * @inheritDoc
     */
    public function isSelected()
    {
        return $this->getSelected();
    }

    /**
     * @inheritDoc
     */
    public function setLabel($label)
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @inheritDoc
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * @inheritDoc
     */
    public function setSelected($isSelected)
    {
        return $this->setData(self::SELECTED, $isSelected);
    }

    /**
     * @inheritDoc
     */
    public function setIsSelected($isSelected)
    {
        return $this->setSelected($isSelected);
    }
}
