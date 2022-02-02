<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form\Field;

use HappyMachines\CustomForms\Model\Form\Field\Condition\ConditionFieldFactory;
use Magento\Framework\DataObject;

/**
 * Class Condition
 * @package HappyMachines\CustomForms\Model\Form\Field
 */
class Condition extends DataObject implements ConditionInterface
{
    /**
     * @var ConditionFieldFactory
     */
    private $conditionFieldFactory;

    /**
     * Condition constructor.
     * @param ConditionFieldFactory $conditionFieldFactory
     * @param array $data
     */
    public function __construct(
        ConditionFieldFactory $conditionFieldFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->conditionFieldFactory = $conditionFieldFactory;

        $this->_init();
    }

    /**
     * Initialize model
     */
    protected function _init()
    {
        $this->initializeSourceField();
        $this->initializeTargetField();
    }

    /**
     * @inheritDoc
     */
    public function getSource()
    {
        return $this->getData(self::SOURCE);
    }

    /**
     * @inheritDoc
     */
    public function getTarget()
    {
        return $this->getData(self::TARGET);
    }

    /**
     * @inheritDoc
     */
    public function setSource($source)
    {
        return $this->setData(self::SOURCE, $source);
    }

    /**
     * @inheritDoc
     */
    public function setTarget($target)
    {
        return $this->setData(self::TARGET, $target);
    }

    /**
     * Initializes condition source field model
     */
    protected function initializeSourceField()
    {
        if (is_array($this->_getData(self::SOURCE))) {
            $source = $this->conditionFieldFactory->create(['data' => $this->_getData(self::SOURCE)]);
            $this->setSource($source);
        }
    }

    /**
     * Initializes condition target field model
     */
    protected function initializeTargetField()
    {
        if (is_array($this->_getData(self::TARGET))) {
            $target = $this->conditionFieldFactory->create(['data' => $this->_getData(self::TARGET)]);
            $this->setTarget($target);
        }
    }
}
