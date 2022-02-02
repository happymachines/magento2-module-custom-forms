<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Controller\Adminhtml\SubmissionFile;

use HappyMachines\CustomForms\Api\Data\SubmissionFileInterface;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Index action.
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'HappyMachines_CustomForms::submissions_files';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('HappyMachines_CustomForms::forms_content');
        $resultPage->addBreadcrumb(__('Form Submissions'), __('Form Submission Files'));
        $resultPage->addBreadcrumb(__('Manage Submission Files'), __('Manage Submission Files'));
        $resultPage->getConfig()->getTitle()->prepend(__($this->getTitle()));

        return $resultPage;
    }

    /**
     * @return string
     */
    protected function getTitle()
    {
        $title = 'Submission Files';

        if ($this->getRequest()->getParam(SubmissionFileInterface::SUBMISSION_ID)) {
            $title = 'Files For Submission ID: ' . $this->getRequest()->getParam(SubmissionFileInterface::SUBMISSION_ID);
        }

        return $title;
    }
}
