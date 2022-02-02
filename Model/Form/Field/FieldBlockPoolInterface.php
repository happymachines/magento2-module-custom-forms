<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form\Field;

/**
 * Interface FieldBlockPoolInterface
 * @package HappyMachines\CustomForms\Model\Form\Field
 */
interface FieldBlockPoolInterface
{
    /**
     * Get the block associated with the field
     * @param $field
     * @return mixed
     */
    public function getFieldBlock($field);
}
