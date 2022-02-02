<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form\Field;

/**
 * Interface OptionInterface
 * @package HappyMachines\CustomForms\Model\Form\Field
 */
interface OptionInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const LABEL = 'label';
    const VALUE = 'value';
    const SELECTED = 'selected';
    /**#@-*/

    /**
     * Get the option label
     * @return string
     */
    public function getLabel();

    /**
     * Get the option value
     * @return string
     */
    public function getValue();

    /**
     * Get the option selected status
     * @return int|bool
     */
    public function getSelected();

    /**
     * Get the option selected status
     * @return int|bool
     */
    public function isSelected();

    /**
     * Set the option label
     * @param $label
     * @return mixed
     */
    public function setLabel($label);

    /**
     * Set the option value
     * @param $value
     * @return OptionInterface
     */
    public function setValue($value);

    /**
     * Set the option selected status
     * @param int|bool
     * @return OptionInterface
     */
    public function setSelected($isSelected);

    /**
     * Set the option selected status
     * @param int|bool
     * @return OptionInterface
     */
    public function setIsSelected($isSelected);
}
