<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Ui\Component;

use Magento\Ui\Component\AbstractComponent;

/**
 * Class FormBuilder
 * @package HappyMachines\CustomForms\Ui\Component
 */
class FormBuilder extends AbstractComponent
{
    /** UI Component Name */
    const NAME = 'happymachines_customforms_formbuilder_html_content';

    /**
     * Get component name
     * @return string
     */
    public function getComponentName()
    {
        return $this->getName();
    }
}
