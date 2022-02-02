<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form;

use HappyMachines\CustomForms\Model\Form\Field\ConditionFactory;
use HappyMachines\CustomForms\Model\Form\Field\OptionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class Field
 * @package HappyMachines\CustomForms\Model\Form
 */
class Field extends DataObject implements FieldInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ConditionFactory
     */
    private $conditionFactory;

    /**
     * @var OptionFactory
     */
    private $optionFactory;

    /**
     * Field constructor.
     * @param SerializerInterface $serializer
     * @param ConditionFactory $conditionFactory
     * @param OptionFactory $optionFactory
     * @param array $data
     */
    public function __construct(
        SerializerInterface $serializer,
        ConditionFactory $conditionFactory,
        OptionFactory $optionFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->serializer = $serializer;
        $this->conditionFactory = $conditionFactory;
        $this->optionFactory = $optionFactory;

        $this->_init();
    }

    /**
     * Model initialization
     */
    protected function _init()
    {
        $this->intializeFieldOptions();
        $this->initializeConditions();
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @inheritDoc
     */
    public function getSubtype()
    {
        return $this->getData(self::SUBTYPE);
    }

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
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function getClassName()
    {
        return $this->getData(self::CLASSNAME);
    }

    /**
     * @inheritDoc
     */
    public function getRequired()
    {
        return $this->getData(self::REQUIRED);
    }

    /**
     * @inheritDoc
     */
    public function isRequired()
    {
        return $this->getRequired();
    }

    /**
     * @inheritDoc
     */
    public function getDisabled()
    {
        return $this->getData(self::DISABLED);
    }

    /**
     * @inheritDoc
     */
    public function isDisabled()
    {
        return $this->getDisabled();
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
    public function getValues()
    {
        return $this->getData(self::VALUES);
    }

    /**
     * @inheritDoc
     */
    public function getMultiselect()
    {
        return $this->getData(self::MULTISELECT);
    }

    /**
     * @inheritDoc
     */
    public function isMultiselect()
    {
        return $this->getMultiselect();
    }

    /**
     * @inheritDoc
     */
    public function getConditions()
    {
        return $this->getData(self::CONDITIONS);
    }

    /**
     * @inheritDoc
     */
    public function getRenderableAttributes()
    {
        return array_diff_key($this->getData(), array_flip($this->getNonRenderableAttributes()));
    }

    /**
     * @inheritDoc
     */
    public function getNonRenderableAttributes()
    {
        return self::NON_RENDERABLE_ATTRIBUTE_FIELDS;
    }

    /**
     * @inheritDoc
     */
    public function getInputClassName()
    {
        return $this->getData(self::TYPE_USER_ATTRIBUTE_INPUT_CLASSNAME);
    }

    /**
     * Get the form field fieldsetStart type user attribute value
     * @return bool|int
     */
    public function getFieldsetStart()
    {
        return $this->getData(self::TYPE_USER_ATTRIBUTE_FIELDSET_START);
    }

    /**
     * Get the form field fieldsetStart type user attribute value
     * @return bool|int
     */
    public function isFieldsetStart()
    {
        return $this->getFieldsetStart();
    }

    /**
     * @inheritDoc
     */
    public function getDataTypeAddressGroup()
    {
        return $this->getData(self::TYPE_USER_ATTRIBUTE_ADDRESS_GROUP);
    }

    /**
     * @inheritDoc
     */
    public function hasAddressGroup()
    {
        return $this->getDataTypeAddressGroup();
    }

    /**
     * @inheritDoc
     */
    public function getDataTypeAddressField()
    {
        return $this->getData(self::TYPE_USER_ATTRIBUTE_ADDRESS_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getAddressFieldType()
    {
        return $this->getDataTypeAddressField();
    }

    /**
     * @inheritDoc
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @inheritDoc
     */
    public function setSubtype($type)
    {
        return $this->setData(self::SUBTYPE, $type);
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
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function setClassName($className)
    {
        return $this->setData(self::CLASSNAME, $className);
    }

    /**
     * @inheritDoc
     */
    public function setRequired($required)
    {
        return $this->setData(self::REQUIRED, $required);
    }

    /**
     * @inheritDoc
     */
    public function setDisabled($disabled)
    {
        return $this->setData(self::DISABLED, $disabled);
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
    public function setValues($values)
    {
        return $this->setData(self::VALUES, $values);
    }

    /**
     * @inheritDoc
     */
    public function setMultiselect($isMultiselect)
    {
        return $this->setData(self::MULTISELECT, $isMultiselect);
    }

    /**
     * @inheritDoc
     */
    public function setConditions($conditions)
    {
        return $this->setData(self::CONDITIONS, $conditions);
    }

    /**
     * @inheritDoc
     */
    public function setInputClassName($className)
    {
        return $this->setData(self::TYPE_USER_ATTRIBUTE_INPUT_CLASSNAME, $className);
    }

    /**
     * @inheritDoc
     */
    public function setFieldsetStart($start)
    {
        return $this->setData(self::TYPE_USER_ATTRIBUTE_FIELDSET_START, $start);
    }

    /**
     * @inheritDoc
     */
    public function setDataTypeAddressGroup($addressGroup)
    {
        return $this->setData(self::TYPE_USER_ATTRIBUTE_ADDRESS_GROUP, $addressGroup);
    }

    /**
     * @inheritDoc
     */
    public function setDataTypeAddressField($addressFieldType)
    {
        return $this->setData(self::TYPE_USER_ATTRIBUTE_ADDRESS_FIELD, $addressFieldType);
    }

    /**
     * @inheritDoc
     */
    public function isCustomerEmailField()
    {
        return $this->getData(self::TYPE_USER_ATTRIBUTE_CUSTOMER_EMAIL);
    }

    /**
     * Initializes field option models
     */
    protected function intializeFieldOptions()
    {
        if ($this->_getData(self::VALUES)) {
            $options = [];

            foreach ($this->_getData(self::VALUES) as $optionData) {
                $option = $this->optionFactory->create(['data' => $optionData]);
                $options[] = $option;
            }

            $this->setValues($options);
        }
    }

    /**
     * Initializes condition field models from the serialized
     * conditions data string
     */
    protected function initializeConditions()
    {
        if (is_string($this->_getData(self::TYPE_USER_ATTRIBUTE_CONDITIONS))) {
            $conditions = [];
            $conditionsData = $this->serializer->unserialize($this->_getData(self::TYPE_USER_ATTRIBUTE_CONDITIONS));

            foreach ($conditionsData as $conditionData) {
                $condition = $this->conditionFactory->create(['data' => $conditionData]);
                $conditions[] = $condition;
            }

            $this->setConditions($conditions);
        }
    }
}
