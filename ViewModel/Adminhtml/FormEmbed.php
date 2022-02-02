<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\ViewModel\Adminhtml;

use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Psr\Log\LoggerInterface;

/**
 * Class FormEmbed
 * @package HappyMachines\CustomForms\ViewModel\Adminhtml
 */
class FormEmbed implements ArgumentInterface
{
    /**
     * @var RequestInterface
     */
    private $request;


    /**
     * @var \HappyMachines\CustomForms\Api\FormRepositoryInterface
     */
    private $formRepository;

    /**
     * @var \HappyMachines\CustomForms\Api\Data\FormInterface
     */
    private $form;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * FormEmbed constructor.
     *
     * @param RequestInterface                                       $request
     * @param \HappyMachines\CustomForms\Api\FormRepositoryInterface $formRepository
     * @param \Psr\Log\LoggerInterface                               $logger
     */
    public function __construct(
        RequestInterface $request,
        FormRepositoryInterface $formRepository,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->formRepository = $formRepository;
        $this->logger = $logger;
    }

    /**
     * @return null|string|int
     */
    public function getFormId()
    {
        if (!$this->form && $formId = $this->request->getParam('form_id')) {
            try {
                $this->form = $this->formRepository->getById($formId);
            } catch (\Exception $exception) {
                $this->logger->error("FormEmbed failed to fetch form: {$exception->getMessage()}");
            }
        }

        return $this->form ? $this->form->getIdentifier() : '';
    }

    /**
     * @return string
     */
    public function getLayoutEmbed()
    {
        $formId = $this->getFormId();

        return <<<EOD
            <block class="HappyMachines\CustomForms\Block\Form" name="happymachines.customforms.form.$formId">
                <arguments>
                    <argument name="form_id" xsi:type="string">$formId</argument>
                </arguments>
            </block>
            EOD;
    }

    /**
     * @return string
     */
    public function getWidgetEmbed()
    {
        $formId = $this->getFormId();

        return <<<EOD
            {{widget type="HappyMachines\CustomForms\Block\Form\Widget\Form" template="HappyMachines_CustomForms::form/default.phtml" form_id="$formId"}}
            EOD;
    }
}
