<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Observer\Frontend;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use HappyMachines\CustomForms\Model\Email\ConfirmationSender;
use HappyMachines\CustomForms\Model\Email\AdminNotificationSender;

/**
 * Class SendNewSubmissionEmail
 * @package HappyMachines\CustomForms\Observer\Frontend
 */
class SendNewSubmissionEmail implements ObserverInterface
{
    /**
     * @var ConfirmationSender
     */
    private $confirmationSender;

    /**
     * @var AdminNotificationSender
     */
    private $adminNotificationSender;

    /**
     * SendNewSubmissionEmail constructor.
     * @param ConfirmationSender $confirmationSender
     * @param AdminNotificationSender $adminNotificationSender
     */
    public function __construct(
        ConfirmationSender $confirmationSender,
        AdminNotificationSender $adminNotificationSender
    ) {
        $this->confirmationSender = $confirmationSender;
        $this->adminNotificationSender = $adminNotificationSender;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        /** @var FormInterface $form */
        $form = $observer->getForm();
        $submission = $observer->getSubmission();

        if ($form->isSubmissionConfirmationEnabled()) {
            $this->confirmationSender->send($form, $submission);
        }

        if ($form->isAdminNotificationEnabled()) {
            $this->adminNotificationSender->send($form, $submission);
        }

        return $this;
    }
}
