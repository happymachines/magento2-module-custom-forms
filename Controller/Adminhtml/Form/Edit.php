<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Controller\Adminhtml\Form;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use HappyMachines\CustomForms\Api\Data\FormInterfaceFactory;
use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Edit
 * @package HappyMachines\CustomForms\Controller\Adminhtml\Form
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'HappyMachines_CustomForms::save_form';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var FormInterfaceFactory
     */
    protected $formFactory;

    /**
     * @var FormRepositoryInterface
     */
    protected $formRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param FormInterfaceFactory $formFactory
     * @param FormRepositoryInterface $formRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        FormInterfaceFactory $formFactory,
        FormRepositoryInterface $formRepository,
        LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->formFactory = $formFactory;
        $this->formRepository = $formRepository;
        parent::__construct($context);
        $this->logger = $logger;
    }

    /**
     * Init actions
     *
     * @return Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('HappyMachines_CustomForms::forms')
            ->addBreadcrumb(__('Forms'), __('Forms'))
            ->addBreadcrumb(__('Manage Forms'), __('Manage Forms'));
        return $resultPage;
    }

    /**
     * @return Page|ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $formId = $this->getRequest()->getParam('form_id');
        $formModel = $this->formFactory->create();

        if ($formId) {
            try {
                $formModel = $this->formRepository->getById($formId);
            } catch (\Exception $exception) {
                $this->logger->critical(__('Unable to edit the form. %1', $exception->getMessage()));
            }
            if (!$formModel->getFormId()) {
                $this->messageManager->addErrorMessage(__('This form no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $formId ? __('Edit Form') : __('New Form'),
            $formId ? __('Edit Form') : __('New Form')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Forms'));
        $resultPage->getConfig()->getTitle()
            ->prepend($formModel->getFormId() ? $formModel->getName() : __('New Form'));

        return $resultPage;
    }
}
