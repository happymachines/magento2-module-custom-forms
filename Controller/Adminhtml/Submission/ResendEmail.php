<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Controller\Adminhtml\Submission;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use HappyMachines\CustomForms\Model\Email\ConfirmationSender;
use HappyMachines\CustomForms\Model\Email\AdminNotificationSender;
use HappyMachines\CustomForms\Api\SubmissionRepositoryInterface;
use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ResendEmail
 * @package HappyMachines\CustomForms\Controller\Adminhtml\Submission
 */
class ResendEmail extends Action implements HttpPostActionInterface
{
    /**
     * @var SubmissionRepositoryInterface
     */
    private $submissionRepository;

    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * @var ConfirmationSender
     */
    private $confirmationSender;

    /**
     * @var AdminNotificationSender
     */
    private $adminNotificationSender;

    /**
     * @var SubmissionInterface
     */
    private $submission;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ResendEmail constructor.
     * @param Action\Context $context
     * @param SubmissionRepositoryInterface $submissionRepository
     * @param FormRepositoryInterface $formRepository
     * @param ConfirmationSender $confirmationSender
     * @param AdminNotificationSender $adminNotificationSender
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        SubmissionRepositoryInterface $submissionRepository,
        FormRepositoryInterface $formRepository,
        ConfirmationSender $confirmationSender,
        AdminNotificationSender $adminNotificationSender,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->submissionRepository = $submissionRepository;
        $this->formRepository = $formRepository;
        $this->confirmationSender = $confirmationSender;
        $this->adminNotificationSender = $adminNotificationSender;
        $this->logger = $logger;
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $submission = $this->getSubmission();
        $form = $this->getForm();

        if ($form->isSubmissionConfirmationEnabled() && $this->getRequest()->getParam('resend_confirmation')) {
            try {
                $this->confirmationSender->send($form, $submission);
                $this->messageManager->addSuccessMessage(__('Confirmation email successfully sent.'));
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                $this->logger->critical(__('Unable to send form submission confirmation. %1', $exception->getMessage()));
            }
        }

        if ($form->isAdminNotificationEnabled() && $this->getRequest()->getParam('resend_admin_notification')) {
            try {
                $this->adminNotificationSender->send($form, $submission);
                $this->messageManager->addSuccessMessage(__('Admin notification successfully sent.'));
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                $this->logger->critical(__('Unable to send admin form submission notification. %1', $exception->getMessage()));
            }
        }

        return $this->resultRedirectFactory->create()->setPath(
            '*/*/edit',
            [
                'submission_id' => $submission->getSubmissionId()
            ]
        );
    }

    /**
     * @return SubmissionInterface
     */
    protected function getSubmission()
    {
        $submissionId = $this->getRequest()->getParam('submission_id');

        if ($submissionId) {
            try {
                $this->submission = $this->submissionRepository->getById($submissionId);
            } catch (\Exception $exception) {
                $this->logger->critical(__('Unable to send admin form submission notification, failed to load submission. %1', $exception->getMessage()));
            }
        }

        return $this->submission;
    }

    /**
     * @return FormInterface
     */
    protected function getForm()
    {
        $submission = $this->getSubmission();

        if ($submission) {
            try {
                $this->form = $this->formRepository->getById($submission->getFormId());
            } catch (\Exception $exception) {
                $this->logger->critical(__('Unable to send admin form submission notification, failed to load form. %1', $exception->getMessage()));
            }
        }

        return $this->form;
    }
}
