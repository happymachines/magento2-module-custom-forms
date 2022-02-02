<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Email;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;

/**
 * Class AdminNotificationSender
 * @package HappyMachines\CustomForms\Model\Email
 */
class AdminNotificationSender extends AbstractSender
{
    /**
     * @param FormInterface $form
     * @param SubmissionInterface $submission
     * @return void
     */
    public function send($form, $submission)
    {
        $recipients = $this->getRecipients($form);
        $sender = $this->getSender($form);
        $template = $form->getAdminNotificationEmailTemplate();
        $replyTo = $form->getAdminNotificationReplyTo();
        $templateVars = $this->templateVarPool->getTemplateVars($form, $submission);
        $attachments = [];

        if ($form->isAdminNotificationAttachmentsEnabled()) {
            $attachments = $this->attachments->getSubmissionFiles($submission);
        }

        try {
            $this->emailTransport->sendMail($recipients, $sender, $template, $replyTo, $templateVars, $attachments);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    protected function getRecipients($form)
    {
        $recipients = $form->getAdminNotificationRecipient();

        return explode(',', $recipients);
    }

    /**
     * @param FormInterface $form
     * @return array|string
     */
    protected function getSender($form)
    {
        if (!$form->getAdminNotificationSenderEmail() || !$form->getAdminNotificationSenderName()) {
            return $this->settings->getEmailSenderIdentity();
        }

        return [
            'email' => $form->getAdminNotificationSenderEmail(),
            'name' => $form->getAdminNotificationSenderName()
        ];
    }
}
