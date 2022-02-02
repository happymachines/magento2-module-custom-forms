<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Block;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use HappyMachines\CustomForms\Model\Form\Field\FieldBlockPool;
use HappyMachines\CustomForms\ViewModel\Form\FormFactory as ViewModelFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use HappyMachines\CustomFormsReCaptcha\Model\FormReCaptchaBlockDataInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Form
 * @package HappyMachines\CustomForms\Block
 */
class Form extends Template
{
    /**
     * @var string
     */
    protected $_template = 'HappyMachines_CustomForms::form/default.phtml';

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var FormRepositoryInterface
     */
    protected $formRepository;

    /**
     * @var FieldBlockPool
     */
    protected $fieldBlockPool;

    /**
     * @var ViewModelFactory
     */
    protected $viewModelFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var \HappyMachines\CustomForms\ViewModel\Form\Form
     */
    protected $viewModel;

    /**
     * @var FormReCaptchaBlockDataInterface
     */
    protected $reCaptchaBlockData;

    /**
     * Form constructor.
     * @param Template\Context $context
     * @param CustomerSession $customerSession
     * @param StoreManagerInterface $storeManager
     * @param FormRepositoryInterface $formRepository
     * @param FieldBlockPool $fieldBlockPool
     * @param ViewModelFactory $viewModelFactory
     * @param FormReCaptchaBlockDataInterface $reCaptchaBlockData
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CustomerSession $customerSession,
        StoreManagerInterface $storeManager,
        FormRepositoryInterface $formRepository,
        FieldBlockPool $fieldBlockPool,
        ViewModelFactory $viewModelFactory,
        FormReCaptchaBlockDataInterface $reCaptchaBlockData,
        LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        $this->formRepository = $formRepository;
        $this->fieldBlockPool = $fieldBlockPool;
        $this->viewModelFactory = $viewModelFactory;
        $this->reCaptchaBlockData = $reCaptchaBlockData;
        $this->logger = $logger;
    }

    /**
     * @return $this|Form
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function _prepareLayout()
    {
        $this->buildForm($this->getForm());
        return $this;
    }

    /**
     * @return FormInterface
     * @throws NoSuchEntityException|LocalizedException
     */
    public function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        if (!$this->getData('form_id')) {
            throw new LocalizedException(
                __('Cannot render form, a form id is required.')
            );
        }

        $formId = $this->getData('form_id');
        $storeId = $this->storeManager->getStore()->getId();
        $customerGroupId = $this->customerSession->getCustomer()->getGroupId();

        try {
            $this->form = $this->formRepository->getById($formId, $storeId, $customerGroupId);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $this->form;
    }

    /**
     * @param FormInterface $form
     * @throws LocalizedException
     */
    public function buildForm($form)
    {
        if (!$form) {
            throw new LocalizedException(
                __('Unable to render the form, the form with id %1 was not found.', $this->getData('form_id'))
            );
        }

        $formFieldsContainer = $this->addChild(
            'form.fields',
            Template::class,
            [
                'template' => 'HappyMachines_CustomForms::fields/container.phtml'
            ]
        );

        $fieldsetBlock = null;
        $fieldBlockParent = $formFieldsContainer;

        foreach ($form->getFields() as $fieldIndex => $field) {
            $fieldBlockName = 'field.' . $field->getName() . '.' . $fieldIndex;
            $fieldBlock = $this->fieldBlockPool->getFieldBlock($field);
            $fieldBlock->setData('form', $form);

            if ($field->isFieldsetStart()) {
                $fieldsetName = 'fieldset.' . $fieldIndex;
                $fieldsetBlockData = [
                    'template' => 'HappyMachines_CustomForms::fields/fieldset.phtml'
                ];
                $fieldsetBlock = $this->addField($formFieldsContainer, $fieldsetName, Template::class, $fieldsetBlockData);
            }

            if ($fieldsetBlock) {
                $fieldBlockParent = $fieldsetBlock;
            }

            $this->addField($fieldBlockParent, $fieldBlockName, get_class($fieldBlock), $fieldBlock->getData());
        }

        $this->addRecaptchaBlock($fieldsetBlock ?: $formFieldsContainer);
    }

    /**
     * @param AbstractBlock $parent
     * @param string $blockName
     * @param string $block
     * @param array $blockData
     * @return mixed
     */
    public function addField($parent, $blockName, $block, $blockData)
    {
        return $parent->addChild($blockName, $block, $blockData);
    }

    /**
     * Add the ReCaptcha block as a child
     * to the custom form
     * @param $parent
     */
    protected function addRecaptchaBlock($parent)
    {
        $parent->addChild(
            $this->getRecaptchaBlockName(),
            $this->reCaptchaBlockData->getBlockClassName(),
            $this->reCaptchaBlockData->getBlockData()
        );
    }

    /**
     * @return string
     */
    public function getRecaptchaBlockName()
    {
        $formId = $this->getData('form_id');

        return 'form.' . $formId . '.recaptcha';
    }

    /**
     * @return \HappyMachines\CustomForms\ViewModel\Form\Form
     * @throws NoSuchEntityException
     */
    public function getViewModel()
    {
        if (!$this->viewModel) {
            $this->viewModel = $this->viewModelFactory->create(['form' => $this->getForm()]);
        }

        return $this->viewModel;
    }
}
