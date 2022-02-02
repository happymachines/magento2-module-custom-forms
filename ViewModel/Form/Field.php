<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\ViewModel\Form;

use HappyMachines\CustomForms\Model\Form\Field as FieldModel;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Filter\ZendFactory as FilterFactory;
use Magento\Framework\Escaper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Zend_Filter_Interface;

/**
 * Class Field
 * @package HappyMachines\CustomForms\ViewModel\Form
 */
class Field implements ArgumentInterface
{
    /**
     * @var FieldModel
     */
    protected $field;

    /**
     * @var FilterFactory
     */
    protected $filterFactory;

    /**
     * @var Zend_Filter_Interface
     */
    protected $camelCaseToDashFilter;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Field constructor.
     * @param FieldModel $field
     * @param FilterFactory $filterFactory
     * @param Escaper $escaper
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        FieldModel $field,
        FilterFactory $filterFactory,
        Escaper $escaper,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->field = $field;
        $this->filterFactory = $filterFactory;
        $this->escaper = $escaper;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Pass method calls through the view model to the Field class,
     * allows access to the Field class methods.
     *
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->field->$method(...$args);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getConfig($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return Zend_Filter_Interface
     */
    protected function getCamelToDashFilter()
    {
        if ($this->camelCaseToDashFilter) {
            return $this->camelCaseToDashFilter;
        }

        if ($this->filterFactory->canCreateFilter('camelCaseToDash')) {
            $this->camelCaseToDashFilter = $this->filterFactory->createFilter('camelCaseToDash');
        }

        return $this->camelCaseToDashFilter;
    }

    /**
     * @return Escaper
     */
    protected function getEscaper()
    {
        return $this->escaper;
    }

    /**
     * Gets the renderable field attributes as a string in the
     * format <attribute>="<attributeValue>"
     * @return string
     */
    public function getFieldAttributes()
    {
        $attributesData = $this->field->getRenderableAttributes();
        $attributeNameFilter = $this->getCamelToDashFilter();
        $attributeEscaper = $this->getEscaper();

        return array_reduce(
            array_keys($attributesData),
            static function ($attributesString, $key) use ($attributesData, $attributeNameFilter, $attributeEscaper) {
                if ($attributesData[$key]) {
                    try {
                        $attributeName = $attributeNameFilter->filter($key);
                    } catch (\Exception $exception) {
                        $attributeName = $key;
                    }

                    if (is_array($attributesData[$key])) {
                        $attributeValue = implode(',', $attributesData[$key]);
                    } else {
                        $attributeValue = $attributesData[$key];
                    }

                    $attributeValue = $attributeEscaper->escapeHtmlAttr($attributeValue);
                    $attributesString .= ' ' . "{$attributeName}='{$attributeValue}'";
                }
                return $attributesString;
            },
            ''
        );
    }

    /**
     * @return string
     */
    public function getRequiredFieldClass()
    {
        return $this->field->isRequired() ? 'required' : '';
    }
}
