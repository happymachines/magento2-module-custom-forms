<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Form;

use HappyMachines\CustomForms\Model\Form\Field\ConditionInterface;

/**
 * Interface FieldInterface
 * @package HappyMachines\CustomForms\Model\Form
 */
interface FieldInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const TYPE = 'type';
    const SUBTYPE = 'subtype';
    const LABEL = 'label';
    const NAME = 'name';
    const CLASSNAME = 'className';
    const REQUIRED = 'required';
    const DISABLED = 'disabled';
    const VALUE = 'value';
    const VALUES = 'values';
    const MULTISELECT = 'multiple';
    const CONDITIONS = 'conditions';
    /**#@-*/

    /**#@+
     * Form builder type user attributes
     * @see app/code/HappyMachines/CustomForms/view/adminhtml/web/js/form/builder-options/type-user-attributes.js
     */
    const TYPE_USER_ATTRIBUTE_INPUT_CLASSNAME       = 'inputClassName';
    const TYPE_USER_ATTRIBUTE_FIELDSET_START        = 'fieldsetStart';
    const TYPE_USER_ATTRIBUTE_CUSTOMER_EMAIL        = 'dataCustomerEmail';
    const TYPE_USER_ATTRIBUTE_CONDITIONS            = 'dataConditions';
    const TYPE_USER_ATTRIBUTE_MASK                  = 'dataMask';
    const TYPE_USER_ATTRIBUTE_ADDRESS_FIELD         = 'dataTypeAddressField';
    const TYPE_USER_ATTRIBUTE_ADDRESS_GROUP         = 'dataTypeAddressGroup';
    const TYPE_USER_ATTRIBUTE_FILE_ACCEPT           = 'accept';
    /**#@-*/

    /**#@+
     * Data points and custom type user attributes defined on the entity
     * that should not be output as HTML attributes on the rendered field
     */
    const NON_RENDERABLE_ATTRIBUTE_FIELDS = [
        self::SUBTYPE,
        self::LABEL,
        self::CLASSNAME,
        self::VALUES,
        self::CONDITIONS,
        self::TYPE_USER_ATTRIBUTE_FIELDSET_START,
        self::TYPE_USER_ATTRIBUTE_INPUT_CLASSNAME
    ];

    /**
     * Get the form field type
     * @return string
     */
    public function getType();

    /**
     * Get the form field sub type
     * @return string
     */
    public function getSubtype();

    /**
     * Get the form field label
     * @return string
     */
    public function getLabel();

    /**
     * Get the form field name
     * @return string
     */
    public function getName();

    /**
     * Get the form field class name(s)
     * @return string
     */
    public function getClassName();

    /**
     * Get the form field required status
     * @return bool|int
     */
    public function getRequired();

    /**
     * Get the form field required status
     * @return bool|int
     */
    public function isRequired();

    /**
     * Get the form field disabled status
     * @return bool|int
     */
    public function getDisabled();

    /**
     * Get the form field disabled status
     * @return bool|int
     */
    public function isDisabled();

    /**
     * Get the form field value
     * @return array
     */
    public function getValue();

    /**
     * Get the form field values, this is available on select, radio, and checkbox inputs
     * @return array
     */
    public function getValues();

    /**
     * Get the form field multi-value status, this is available on select and checkbox inputs
     * @return bool|int
     */
    public function getMultiselect();

    /**
     * Get the form field multi-value status, this is available on select and checkbox inputs
     * @return bool|int
     */
    public function isMultiselect();

    /**
     * Get the form field data conditions
     * @return ConditionInterface[]|null
     */
    public function getConditions();

    /**
     * Get the list of renderable field attributes
     * @return array
     */
    public function getRenderableAttributes();

    /**
     * Get the list of non renderable field attributes
     * @return array
     */
    public function getNonRenderableAttributes();

    /**
     * Get the form field inputClassName type user attribute value, this is the class name applied to the input element
     * @return string|null
     */
    public function getInputClassName();

    /**
     * Get the form field fieldsetStart type user attribute value
     * @return bool|int
     */
    public function getFieldsetStart();

    /**
     * Get the form field fieldsetStart type user attribute value
     * @return bool|int
     */
    public function isFieldsetStart();

    /**
     * Get the form field dataTypeAddressGroup type user attribute value.
     * Indicates field is part of an address type group
     * @return string
     */
    public function getDataTypeAddressGroup();

    /**
     * Get the form field dataTypeAddressGroup type user attribute value.
     * Indicates field is part of an address type group
     * @return bool|int
     */
    public function hasAddressGroup();

    /**
     * Get the form field dataTypeAddressField type user attribute value.
     * This flag indicates the address field type (street, city, region, zipcode, region, or country)
     * @return string
     */
    public function getDataTypeAddressField();

    /**
     * Get the form field dataTypeAddressField type user attribute value.
     * This flag indicates the address field type (street, city, region, zipcode, region, or country)
     * @return bool|int
     */
    public function getAddressFieldType();

    /**
     * Set the form field type
     * @param $type
     * @return FieldInterface
     */
    public function setType($type);

    /**
     * Set the form field sub type
     * @param $type
     * @return FieldInterface
     */
    public function setSubtype($type);

    /**
     * Set the form field label
     * @param $label
     * @return FieldInterface
     */
    public function setLabel($label);

    /**
     * Set the form field name
     * @param $name
     * @return FieldInterface
     */
    public function setName($name);

    /**
     * Set the form field class name(s)
     * @param $className
     * @return string
     */
    public function setClassName($className);

    /**
     * Set the form field required status
     * @param $required
     * @return FieldInterface
     */
    public function setRequired($required);

    /**
     * Set the form field disabled status
     * @param $disabled
     * @return FieldInterface
     */
    public function setDisabled($disabled);

    /**
     * Set the form field value
     * @return array
     */
    public function setValue($value);

    /**
     * Set the form field values, this is available on select, radio, and checkbox inputs
     * @param $values
     * @return FieldInterface
     */
    public function setValues($values);

    /**
     * Set the form field multi-value status, this is available on select and checkbox inputs
     * @param $isMultiselect
     * @return FieldInterface
     */
    public function setMultiselect($isMultiselect);

    /**
     * Set the form field data conditions
     * @param $conditions
     * @return FieldInterface
     */
    public function setConditions($conditions);

    /**
     * Set the form field inputClassName type user attribute value, this is the class name applied to the input element
     * @param string $className
     * @return string|null
     */
    public function setInputClassName($className);

    /**
     * Set flag indicating field starts new fieldset
     * @param $start
     * @return FieldInterface
     */
    public function setFieldsetStart($start);

    /**
     * @return bool
     */
    public function isCustomerEmailField();

    /**
     * Set the form field dataTypeAddressGroup type user attribute value.
     * Indicates field is part of an address type group
     * @param string $addressGroup
     * @return FieldInterface
     */
    public function setDataTypeAddressGroup($addressGroup);

    /**
     * Set the form field dataTypeAddressField type user attribute value.
     * Indicates field is part of an address type group
     * @param string $addressFieldType
     * @return FieldInterface
     */
    public function setDataTypeAddressField($addressFieldType);
}
