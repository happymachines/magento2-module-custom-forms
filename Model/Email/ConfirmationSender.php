<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Email;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;

/**
 * Class ConfirmationSender
 * @package HappyMachines\CustomForms\Model\Email
 */
class ConfirmationSender extends AbstractSender
{
    /**
     * @param FormInterface $form
     * @param SubmissionInterface $submission
     * @return void
     */
    public function send($form, $submission)
    {
        $recipients = $this->getRecipients($form, $submission);
        $sender = $this->getSender($form);
        $template = $form->getSubmissionConfirmationEmailTemplate();
        $replyTo = $form->getSubmissionConfirmationReplyTo();
        $templateVars = $this->templateVarPool->getTemplateVars($form, $submission);
        $attachments = [];

        if ($form->isSubmissionConfirmationAttachmentsEnabled()) {
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
     * @param SubmissionInterface $submission
     * @return array
     */
    protected function getRecipients($form, $submission)
    {
        $emailFields = $form->getCustomerEmailFields();
        $recipients = [];

        $submissionData = $submission->getUnserializedSubmissionData();

        foreach ($emailFields as $emailField) {
            $fieldName = $emailField->getName();

            if (isset($submissionData[$fieldName])) {
                $recipients[] = $submissionData[$fieldName]['value'];
            }
        }

        return $recipients;
    }

    /**
     * @param FormInterface $form
     * @return array|string
     */
    protected function getSender($form)
    {
        if (!$form->getSubmissionConfirmationSenderEmail() || !$form->getSubmissionConfirmationSenderName()) {
            return $this->settings->getEmailSenderIdentity();
        }

        return [
            'email' => $form->getSubmissionConfirmationSenderEmail(),
            'name' => $form->getSubmissionConfirmationSenderName()
        ];
    }
}
