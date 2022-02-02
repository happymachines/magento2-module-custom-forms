<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\ViewModel\Form\Field;

use HappyMachines\CustomForms\Model\Form\Field as FieldModel;
use HappyMachines\CustomForms\ViewModel\Form\Field;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Filter\ZendFactory as FilterFactory;

/**
 * Class Address
 * @package HappyMachines\CustomForms\ViewModel\Form\Field
 */
class Address extends Field
{

    const XML_CONFIG_PATH_GENERAL_REGION_DISPLAY_ALL = 'general/region/display_all';

    /**
     * @var DirectoryHelper
     */
    private $directoryHelper;

    /**
     * @var null
     */
    private $defaultCountryId;

    /**
     * Address constructor.
     * @param FieldModel $field
     * @param FilterFactory $filterFactory
     * @param Escaper $escaper
     * @param ScopeConfigInterface $scopeConfig
     * @param DirectoryHelper $directoryHelper
     * @param null $defaultCountryId
     */
    public function __construct(
        FieldModel $field,
        FilterFactory $filterFactory,
        Escaper $escaper,
        ScopeConfigInterface $scopeConfig,
        DirectoryHelper $directoryHelper,
        $defaultCountryId = null
    ) {
        parent::__construct($field, $filterFactory, $escaper, $scopeConfig);
        $this->directoryHelper = $directoryHelper;
        $this->defaultCountryId = $defaultCountryId;
    }

    /**
     * @return string
     */
    public function getOptionalRegionAllowed()
    {
        return $this->getConfig(self::XML_CONFIG_PATH_GENERAL_REGION_DISPLAY_ALL) ? true : false;
    }

    /**
     * @return string|null
     */
    public function getDefaultCountry()
    {
        $countryId = $this->defaultCountryId;

        if ($countryId === null) {
            $countryId = $this->directoryHelper->getDefaultCountry();
        }

        return $countryId;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRegionJson()
    {
        return $this->directoryHelper->getRegionJson();
    }

    /**
     * @return array|string
     */
    public function getCountriesWithOptionZip()
    {
        return $this->directoryHelper->getCountriesWithOptionalZip(true);
    }

    /**
     * @param $value
     * @return string
     */
    public function getDefaultSelectedCountry($value)
    {
        return $this->getDefaultCountry() === $value ? 'selected' : '';
    }

    /**
     * @param $inputType
     * @param $addressFieldType
     * @return string
     */
    public function getAddressInputFieldSelector($inputType, $addressFieldType)
    {
        $fieldAddressGroup = $this->field->getDataTypeAddressGroup();

        return "{$inputType}[data-type-address-field='{$addressFieldType}'][data-type-address-group='{$fieldAddressGroup}']";
    }
}
