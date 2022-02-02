<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form\Field;

use HappyMachines\CustomForms\Model\Form\FieldInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;

/**
 * Class FieldBlockPool
 * @package HappyMachines\CustomForms\Model\Form\Field
 */
class FieldBlockPool implements FieldBlockPoolInterface
{
    /**
     * @var array
     */
    private $blocks;

    /**
     * FieldBlockPool constructor.
     * @param array $blocks
     * @throws LocalizedException
     */
    public function __construct(
        array $blocks = []
    ) {
        foreach ($blocks as $block) {
            if (!$block instanceof BlockInterface) {
                throw new LocalizedException(
                    __('Field block must implement BlockInterface.')
                );
            }
        }
        $this->blocks = $blocks;
    }

    /**
     * @param $field
     * @return mixed
     * @throws LocalizedException
     */
    public function getFieldBlock($field)
    {
        $fieldBlocks = [];

        foreach ($this->blocks as $block) {
            if ($this->getFieldBlockMatch($field, $block)) {
                $block->setViewModel($block->getViewModelFactory()->create(['field' => $field]));
                $fieldBlocks[] = $block;
            }
        }

        $fieldBlock = array_pop($fieldBlocks);

        if (!$fieldBlock) {
            $errorMessage = 'No matching block definition found for field %1 of type %2';

            if ($field->getSubtype()) {
                $errorMessage .= ' and subtype %3';
            }

            throw new LocalizedException(
                __($errorMessage, $field->getName(), $field->getType(), $field->getSubtype())
            );
        }

        return $fieldBlock;
    }

    /**
     * Determines if the field type and subtype match the block definition in the block pool
     * @param FieldInterface $field
     * @param $block
     * @return string|null
     */
    protected function getFieldBlockMatch($field, $block)
    {
        $fieldTypeMatch = $field->getType() === $block->getFieldType();
        $fieldSubtypeMatch = $field->getSubtype() === $block->getFieldSubtype();
        $additionalAttributeCheck = $this->checkFieldAttributes($field, $block);

        return $fieldTypeMatch && $fieldSubtypeMatch && $additionalAttributeCheck;
    }

    /**
     * Check additional field attributes, useful for assigning a custom block to an existing
     * field type. ie. Assign a custom block to a select field that matches unique field attributes
     * @param $field
     * @param $block
     * @return bool
     */
    protected function checkFieldAttributes($field, $block)
    {
        if (!$block->getFieldAttributes()) {
            return true;
        }

        $attributeMatch = false;

        foreach ($block->getFieldAttributes() as $attributeName => $attributeValue) {
            $attributeMatch = $field->getData($attributeName) === $attributeValue;
        }

        return $attributeMatch;
    }
}
