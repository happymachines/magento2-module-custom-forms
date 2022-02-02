<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Controller\Adminhtml\Submission;

use HappyMachines\CustomForms\Api\Data\SubmissionInterfaceFactory;
use HappyMachines\CustomForms\Api\SubmissionRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Edit
 * @package HappyMachines\CustomForms\Controller\Adminhtml\Submission
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'HappyMachines_CustomForms::submissions';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var SubmissionRepositoryInterface
     */
    private $submissionRepository;

    /**
     * @var SubmissionInterfaceFactory
     */
    private $submissionFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param SubmissionRepositoryInterface $submissionRepository
     * @param SubmissionInterfaceFactory $submissionFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        SubmissionRepositoryInterface $submissionRepository,
        SubmissionInterfaceFactory $submissionFactory,
        LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->submissionRepository = $submissionRepository;
        $this->submissionFactory = $submissionFactory;
        $this->logger = $logger;

        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return Page
     */
    protected function _initAction()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('HappyMachines_CustomForms::forms_content')
            ->addBreadcrumb(__('Submissions'), __('Submissions'))
            ->addBreadcrumb(__('Manage Submissions'), __('Manage Submissions'));
        return $resultPage;
    }

    /**
     * @return Page|ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $submissionId = $this->getRequest()->getParam('submission_id');
        $submissionModel = $this->submissionFactory->create();

        if ($submissionId) {
            try {
                $submissionModel = $this->submissionRepository->getById($submissionId);
            } catch (\Exception $exception) {
                $this->logger->critical(__('Unable to load/view the form submission entity. %1', $exception->getMessage()));
            }
            if (!$submissionModel->getSubmissionId()) {
                $this->messageManager->addErrorMessage(__('This submission no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $submissionId ? __('View Submission') : __('New Submission'),
            $submissionId ? __('View Submission') : __('New Submission')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Submissions'));
        $resultPage->getConfig()->getTitle()
            ->prepend($submissionModel->getSubmissionId() ? __('Submission ID %1', $submissionModel->getSubmissionId()) : __('New Submission'));

        return $resultPage;
    }
}
