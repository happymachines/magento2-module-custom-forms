<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\ViewModel\Adminhtml;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use HappyMachines\CustomForms\Model\Settings;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class FormBuilder
 * @package HappyMachines\CustomForms\ViewModel\Adminhtml
 */
class FormBuilder implements ArgumentInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var JsonSerializer
     */
    private $serializer;

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * FormBuilder constructor.
     * @param RequestInterface $request
     * @param JsonSerializer $serializer
     * @param Settings $settings
     * @param FormRepositoryInterface $formRepository
     */
    public function __construct(
        RequestInterface $request,
        JsonSerializer $serializer,
        Settings $settings,
        FormRepositoryInterface $formRepository
    ) {
        $this->request = $request;
        $this->serializer = $serializer;
        $this->settings = $settings;
        $this->formRepository = $formRepository;
    }

    /**
     * @param $value
     * @return bool|false|string
     */
    public function serialize($value)
    {
        return $this->serializer->serialize($value);
    }

    /**
     * @param $value
     * @return array|bool|float|int|mixed|string|null
     */
    public function unserialize($value)
    {
        return $this->serializer->unserialize($value);
    }

    /**
     * @return int
     */
    public function getEditOnAdd()
    {
        return (int)$this->settings->getEditOnAdd();
    }

    /**
     * @return int
     */
    public function getScrollOnAdd()
    {
        return (int)$this->settings->getScrollOnAdd();
    }

    /**
     * @return int
     */
    public function getConfirmFieldDelete()
    {
        return (int)$this->settings->getConfirmFieldDelete();
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $formId = $this->request->getParam('form_id');

        if ($formId) {
            $this->form = $this->formRepository->getById($formId);
        }

        return $this->form;
    }

    /**
     * @return array|string
     */
    public function getFormData()
    {
        $form = $this->getForm();
        $formData = [];

        if ($form) {
            $formData =  $form->getFormData();
        }

        return $formData;
    }

    /**
     * @return string[]
     */
    public function getDisabledFormActionButtons()
    {
        $disabledFormActions = ['save'];

        if (!$this->settings->getDeveloperEnableJsonPreview()) {
            $disabledFormActions[] = 'data';
        }

        return $disabledFormActions;
    }
}
