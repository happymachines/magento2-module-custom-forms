<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Api\Data;

use HappyMachines\CustomForms\Model\Form\FieldInterface;

/**
 * Interface FormInterface
 * @package HappyMachines\CustomForms\Api\Data
 */
interface FormInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const FORM_ID                                       = 'form_id';
    const IDENTIFIER                                    = 'identifier';
    const NAME                                          = 'name';
    const IS_ACTIVE                                     = 'is_active';
    const FORM_DATA                                     = 'form_data';
    const BEFORE_FORM_CONTENT                           = 'before_form_content';
    const AFTER_FORM_CONTENT                            = 'after_form_content';
    const ENABLE_ADMIN_NOTIFICATION                     = 'enable_admin_notification';
    const ADMIN_NOTIFICATION_RECIPIENT                  = 'admin_notification_recipient';
    const ADMIN_NOTIFICATION_SENDER_EMAIL               = 'admin_notification_sender_email';
    const ADMIN_NOTIFICATION_SENDER_NAME                = 'admin_notification_sender_name';
    const ADMIN_NOTIFICATION_REPLY_TO                   = 'admin_notification_reply_to';
    const ADMIN_NOTIFICATION_EMAIL_TEMPLATE             = 'admin_notification_email_template';
    const ENABLE_ADMIN_NOTIFICATION_ATTACHMENTS         = 'enable_admin_notification_attachments';
    const ENABLE_SUBMISSION_CONFIRMATION                = 'enable_submission_confirmation';
    const SUBMISSION_CONFIRMATION_SENDER_EMAIL          = 'submission_confirmation_sender_email';
    const SUBMISSION_CONFIRMATION_SENDER_NAME           = 'submission_confirmation_sender_name';
    const SUBMISSION_CONFIRMATION_REPLY_TO              = 'submission_confirmation_reply_to';
    const SUBMISSION_CONFIRMATION_EMAIL_TEMPLATE        = 'submission_confirmation_email_template';
    const ENABLE_SUBMISSION_CONFIRMATION_ATTACHMENTS    = 'enable_submission_confirmation_attachments';
    const ENABLE_RECAPTCHA                              = 'enable_recaptcha';
    const SUBMISSION_BUTTON_TEXT                        = 'submission_button_text';
    const SUBMISSION_SUCCESS_MESSAGE                    = 'submission_success_message';
    const CREATION_TIME                                 = 'creation_time';
    const UPDATE_TIME                                   = 'update_time';
    /**#@-*/

    /**#@+
     * Non-static field properties
     */
    const FIELDS = 'fields';

    /**#@+
     * Form relation fields
     */
    const STORES            = 'stores';
    const CUSTOMER_GROUPS   = 'customer_groups';

    /**#@+
     * Form's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/

    /**
     * Get the form id
     * @return int|null
     */
    public function getFormId();

    /**
     * Get the form identifier
     * @return string
     */
    public function getIdentifier();

    /**
     * Get the form identifier formatted for framework event dispatch. [a-zA-Z0-9_]+
     * Identifier will be lower-cased and non-alphanumeric characters are replaced with underscores.
     * @return string
     */
    public function getEventIdentifier();

    /**
     * Get the form name
     * @return string
     */
    public function getName();

    /**
     * Get form status
     * @return int|bool
     */
    public function getIsActive();

    /**
     * Get form status
     * @return int|bool
     */
    public function isActive();

    /**
     * Get form recaptcha status
     * @return int|bool
     */
    public function getEnableRecaptcha();

    /**
     * Get form recaptcha status
     * @return int|bool
     */
    public function isRecaptchaEnabled();

    /**
     * Get the admin notification enable status
     * @return int|bool
     */
    public function getEnableAdminNotification();

    /**
     * Get the admin notification enable status
     * @return int|bool
     */
    public function isAdminNotificationEnabled();

    /**
     * Get the admin notification email recipient(s)
     * @return string
     */
    public function getAdminNotificationRecipient();

    /**
     * Get the admin notification sender email address
     * @return string
     */
    public function getAdminNotificationSenderEmail();

    /**
     * Get the admin notification sender name
     * @return string
     */
    public function getAdminNotificationSenderName();

    /**
     * Get the admin notification reply to address
     * @return string
     */
    public function getAdminNotificationReplyTo();

    /**
     * Get the admin notification email template
     * @return string
     */
    public function getAdminNotificationEmailTemplate();

    /**
     * Get the admin notification attachments enabled status
     * @return int|bool
     */
    public function getEnableAdminNotificationAttachments();

    /**
     * Get the admin notification attachments enabled status
     * @return int|bool
     */
    public function isAdminNotificationAttachmentsEnabled();

    /**
     * Get the submission confirmation enable status
     * @return int|bool
     */
    public function getEnableSubmissionConfirmation();

    /**
     * Get the submission confirmation enable status
     * @return int|bool
     */
    public function isSubmissionConfirmationEnabled();

    /**
     * Get the submission confirmation sender email address
     * @return string
     */
    public function getSubmissionConfirmationSenderEmail();

    /**
     * Get the submission confirmation sender name
     * @return string
     */
    public function getSubmissionConfirmationSenderName();

    /**
     * Get the submission confirmation reply to address
     * @return string
     */
    public function getSubmissionConfirmationReplyTo();

    /**
     * Get the submission confirmation email template
     * @return string
     */
    public function getSubmissionConfirmationEmailTemplate();

    /**
     * Get the submission confirmation attachments enabled status
     * @return int|bool
     */
    public function getEnableSubmissionConfirmationAttachments();

    /**
     * Get the submission confirmation attachments enabled status
     * @return int|bool
     */
    public function isSubmissionConfirmationAttachmentsEnabled();

    /**
     * Get form submission button text
     * @return string
     */
    public function getSubmissionButtonText();

    /**
     * Get form submission success message
     * @return mixed
     */
    public function getSubmissionSuccessMessage();

    /**
     * Get before form content
     * @return string
     */
    public function getBeforeFormContent();

    /**
     * Get after form content
     * @return string
     */
    public function getAfterFormContent();

    /**
     * Get form data
     * @return string
     */
    public function getFormData();

    /**
     * Get form creation time
     * @return string
     */
    public function getCreationTime();

    /**
     * Get form update time
     * @return string
     */
    public function getUpdateTime();

    /**
     * Get the form fields
     * @return FieldInterface[]
     */
    public function getFields();

    /**
     * Set the form id
     * @param int $id
     * @return FormInterface
     */
    public function setFormId($id);

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return FormInterface
     */
    public function setIdentifier($identifier);

    /**
     * Set the form name
     * @param string $name
     * @return FormInterface
     */
    public function setName($name);

    /**
     * Set form status
     * @param int|bool $isActive
     * @return FormInterface
     */
    public function setIsActive($isActive);

    /**
     * Set form recaptcha status
     * @param int|bool $isEnabled
     * @return FormInterface
     */
    public function setEnableRecaptcha($isEnabled);

    /**
     * Set the admin notification enable status
     * @param int|bool $enabled
     * @return FormInterface
     */
    public function setEnableAdminNotification($enabled);

    /**
     * Set the admin notification email recipient(s)
     * @param string $recipient
     * @return FormInterface
     */
    public function setAdminNotificationRecipient($recipient);

    /**
     * Set the admin notification sender email address
     * @param string $senderEmail
     * @return FormInterface
     */
    public function setAdminNotificationSenderEmail($senderEmail);

    /**
     * Set the admin notification sender name
     * @param string $senderName
     * @return FormInterface
     */
    public function setAdminNotificationSenderName($senderName);

    /**
     * Set the admin notification reply to address
     * @param string $replyTo
     * @return FormInterface
     */
    public function setAdminNotificationReplyTo($replyTo);

    /**
     * Set the admin notification email template
     * @param string $template
     * @return FormInterface
     */
    public function setAdminNotificationEmailTemplate($template);

    /**
     * Set the admin notification attachments enabled status
     * @param int|bool $enabled
     * @return FormInterface
     */
    public function setEnableAdminNotificationAttachments($enabled);

    /**
     * Set the submission confirmation enable status
     * @param int|bool $enabled
     * @return FormInterface
     */
    public function setEnableSubmissionConfirmation($enabled);

    /**
     * Set the submission confirmation sender email address
     * @param string $senderEmail
     * @return FormInterface
     */
    public function setSubmissionConfirmationSenderEmail($senderEmail);

    /**
     * Set the submission confirmation sender name
     * @param string $senderName
     * @return FormInterface
     */
    public function setSubmissionConfirmationSenderName($senderName);

    /**
     * Set the submission confirmation reply to address
     * @param string $replyTo
     * @return FormInterface
     */
    public function setSubmissionConfirmationReplyTo($replyTo);

    /**
     * Set the submission confirmation email template
     * @param string $template
     * @return FormInterface
     */
    public function setSubmissionConfirmationEmailTemplate($template);

    /**
     * Set the submission confirmation attachments enabled status
     * @param int|bool $enabled
     * @return FormInterface
     */
    public function setEnableSubmissionConfirmationAttachments($enabled);

    /**
     * Set form submission button text
     * @param string $text
     * @return FormInterface
     */
    public function setSubmissionButtonText($text);

    /**
     * Set form submission success message
     * @param string $message
     * @return FormInterface
     */
    public function setSubmissionSuccessMessage($message);

    /**
     * Set before form content
     * @param string $content
     * @return FormInterface
     */
    public function setBeforeFormContent($content);

    /**
     * Set after form content
     * @param string $content
     * @return FormInterface
     */
    public function setAfterFormContent($content);

    /**
     * Set form data
     * @param string $formData
     * @return FormInterface
     */
    public function setFormData($formData);

    /**
     * Set form creation time
     * @param string $createdAt
     * @return FormInterface
     */
    public function setCreationTime($createdAt);

    /**
     * Set form update time
     * @param string $updatedAt
     * @return FormInterface
     */
    public function setUpdateTime($updatedAt);

    /**
     * Set the form fields
     * @return string|array
     */
    public function setFields($fields);

    /**
     * @return FieldInterface[]
     */
    public function getCustomerEmailFields();
}
