<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Model\Form\Field;
use HappyMachines\CustomForms\Model\Form\FieldFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class Form
 * @package HappyMachines\CustomForms\Model
 */
class Form extends AbstractModel implements FormInterface, IdentityInterface
{
    /**
     * Form page cache tag
     */
    const CACHE_TAG = 'happymachines_customforms_form';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'happymachines_form';

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var FieldFactory
     */
    protected $fieldFactory;

    /**
     * @var string
     */
    private $eventIdentifier;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Form::class);
    }

    /**
     * Form constructor.
     * @param Context $context
     * @param Registry $registry
     * @param SerializerInterface $serializer
     * @param FieldFactory $fieldFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        SerializerInterface $serializer,
        FieldFactory $fieldFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->serializer = $serializer;
        $this->fieldFactory = $fieldFactory;
    }

    /**
     * Receive form store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : (array)$this->getData('store_id');
    }

    /**
     * Prepare page's statuses, available event cms_page_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getIdentifier()];
    }

    /**
     * @inheritDoc
     */
    public function getFormId()
    {
        return $this->getData(self::FORM_ID);
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }

    /**
     * @inheritDoc
     */
    public function getEventIdentifier()
    {
        if ($this->eventIdentifier) {
            return $this->eventIdentifier;
        }

        $eventIdentifier = strtolower(trim(preg_replace('/[^a-zA-Z0-9_]+/', "_", $this->getIdentifier())));
        $this->eventIdentifier = $eventIdentifier;

        return $eventIdentifier;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function getEnableRecaptcha()
    {
        return $this->getData(self::ENABLE_RECAPTCHA);
    }

    /**
     * @inheritDoc
     */
    public function getSubmissionButtonText()
    {
        return $this->getData(self::SUBMISSION_BUTTON_TEXT);
    }

    /**
     * @inheritDoc
     */
    public function getSubmissionSuccessMessage()
    {
        return $this->getData(self::SUBMISSION_SUCCESS_MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function getBeforeFormContent()
    {
        return $this->getData(self::BEFORE_FORM_CONTENT);
    }

    /**
     * @inheritDoc
     */
    public function getAfterFormContent()
    {
        return $this->getData(self::AFTER_FORM_CONTENT);
    }

    /**
     * @inheritDoc
     */
    public function getFormData()
    {
        return $this->getData(self::FORM_DATA);
    }

    /**
     * @inheritDoc
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * @inheritDoc
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * @inheritDoc
     */
    public function getFields()
    {
        if (is_string($this->_getData(self::FORM_DATA)) && !$this->_getData(self::FIELDS)) {
            $fields = [];
            $formData = $this->serializer->unserialize($this->getFormData());

            foreach ($formData as $formFieldData) {
                /** @var Field $field */
                $field = $this->fieldFactory->create(['data' => $formFieldData]);
                $fields[] = $field;
            }

            $this->setFields($fields);
        }

        return $this->getData(self::FIELDS);
    }

    /**
     * @inheritDoc
     */
    public function setFormId($id)
    {
        return $this->setData(self::FORM_ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @inheritDoc
     */
    public function setEnableRecaptcha($isEnabled)
    {
        return $this->setData(self::ENABLE_RECAPTCHA, $isEnabled);
    }

    /**
     * @inheritDoc
     */
    public function setSubmissionButtonText($text)
    {
        return $this->setData(self::SUBMISSION_BUTTON_TEXT, $text);
    }

    /**
     * @inheritDoc
     */
    public function setSubmissionSuccessMessage($message)
    {
        return $this->setData(self::SUBMISSION_SUCCESS_MESSAGE, $message);
    }

    /**
     * @inheritDoc
     */
    public function setBeforeFormContent($content)
    {
        return $this->setData(self::BEFORE_FORM_CONTENT, $content);
    }

    /**
     * @inheritDoc
     */
    public function setAfterFormContent($content)
    {
        return $this->setData(self::AFTER_FORM_CONTENT, $content);
    }

    /**
     * @inheritDoc
     */
    public function setFormData($formData)
    {
        return $this->setData(self::FORM_DATA, $formData);
    }

    /**
     * @inheritDoc
     */
    public function setCreationTime($createdAt)
    {
        return $this->setData(self::CREATION_TIME, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function setUpdateTime($updatedAt)
    {
        return $this->setData(self::UPDATE_TIME, $updatedAt);
    }

    /**
     * @inheritDoc
     */
    public function setFields($fields)
    {
        return $this->setData(self::FIELDS, $fields);
    }

    /**
     * @inheritDoc
     */
    public function getEnableAdminNotification()
    {
        return $this->getData(self::ENABLE_ADMIN_NOTIFICATION);
    }

    /**
     * @inheritDoc
     */
    public function getAdminNotificationRecipient()
    {
        return $this->getData(self::ADMIN_NOTIFICATION_RECIPIENT);
    }

    /**
     * @inheritDoc
     */
    public function getAdminNotificationSenderEmail()
    {
        return $this->getData(self::ADMIN_NOTIFICATION_SENDER_EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function getAdminNotificationSenderName()
    {
        return $this->getData(self::ADMIN_NOTIFICATION_SENDER_NAME);
    }

    /**
     * @inheritDoc
     */
    public function getAdminNotificationReplyTo()
    {
        return $this->getData(self::ADMIN_NOTIFICATION_REPLY_TO);
    }

    /**
     * @inheritDoc
     */
    public function getAdminNotificationEmailTemplate()
    {
        return $this->getData(self::ADMIN_NOTIFICATION_EMAIL_TEMPLATE);
    }

    /**
     * @inheritDoc
     */
    public function getEnableAdminNotificationAttachments()
    {
        return $this->getData(self::ENABLE_ADMIN_NOTIFICATION_ATTACHMENTS);
    }

    /**
     * @inheritDoc
     */
    public function getEnableSubmissionConfirmation()
    {
        return $this->getData(self::ENABLE_SUBMISSION_CONFIRMATION);
    }

    /**
     * @inheritDoc
     */
    public function getSubmissionConfirmationSenderEmail()
    {
        return $this->getData(self::SUBMISSION_CONFIRMATION_SENDER_EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function getSubmissionConfirmationSenderName()
    {
        return $this->getData(self::SUBMISSION_CONFIRMATION_SENDER_NAME);
    }

    /**
     * @inheritDoc
     */
    public function getSubmissionConfirmationReplyTo()
    {
        return $this->getData(self::SUBMISSION_CONFIRMATION_REPLY_TO);
    }

    /**
     * @inheritDoc
     */
    public function getSubmissionConfirmationEmailTemplate()
    {
        return $this->getData(self::SUBMISSION_CONFIRMATION_EMAIL_TEMPLATE);
    }

    /**
     * @inheritDoc
     */
    public function getEnableSubmissionConfirmationAttachments()
    {
        return $this->getData(self::ENABLE_SUBMISSION_CONFIRMATION_ATTACHMENTS);
    }

    /**
     * @inheritDoc
     */
    public function setEnableAdminNotification($enabled)
    {
        return $this->setData(self::ENABLE_ADMIN_NOTIFICATION, $enabled);
    }

    /**
     * @inheritDoc
     */
    public function setAdminNotificationRecipient($recipient)
    {
        return $this->setData(self::ADMIN_NOTIFICATION_RECIPIENT, $recipient);
    }

    /**
     * @inheritDoc
     */
    public function setAdminNotificationSenderEmail($senderEmail)
    {
        return $this->setData(self::ADMIN_NOTIFICATION_SENDER_EMAIL, $senderEmail);
    }

    /**
     * @inheritDoc
     */
    public function setAdminNotificationSenderName($senderName)
    {
        return $this->setData(self::ADMIN_NOTIFICATION_SENDER_NAME, $senderName);
    }

    /**
     * @inheritDoc
     */
    public function setAdminNotificationReplyTo($replyTo)
    {
        return $this->setData(self::ADMIN_NOTIFICATION_REPLY_TO, $replyTo);
    }

    /**
     * @inheritDoc
     */
    public function setAdminNotificationEmailTemplate($template)
    {
        return $this->setData(self::ADMIN_NOTIFICATION_EMAIL_TEMPLATE, $template);
    }

    /**
     * @inheritDoc
     */
    public function setEnableAdminNotificationAttachments($enabled)
    {
        return $this->setData(self::ENABLE_ADMIN_NOTIFICATION_ATTACHMENTS, $enabled);
    }

    /**
     * @inheritDoc
     */
    public function setEnableSubmissionConfirmation($enabled)
    {
        return $this->setData(self::ENABLE_SUBMISSION_CONFIRMATION, $enabled);
    }

    /**
     * @inheritDoc
     */
    public function setSubmissionConfirmationSenderEmail($senderEmail)
    {
        return $this->setData(self::SUBMISSION_CONFIRMATION_SENDER_EMAIL, $senderEmail);
    }

    /**
     * @inheritDoc
     */
    public function setSubmissionConfirmationSenderName($senderName)
    {
        return $this->setData(self::SUBMISSION_CONFIRMATION_SENDER_NAME, $senderName);
    }

    /**
     * @inheritDoc
     */
    public function setSubmissionConfirmationReplyTo($replyTo)
    {
        return $this->setData(self::SUBMISSION_CONFIRMATION_REPLY_TO, $replyTo);
    }

    /**
     * @inheritDoc
     */
    public function setSubmissionConfirmationEmailTemplate($template)
    {
        return $this->setData(self::SUBMISSION_CONFIRMATION_EMAIL_TEMPLATE, $template);
    }

    /**
     * @inheritDoc
     */
    public function setEnableSubmissionConfirmationAttachments($enabled)
    {
        return $this->setData(self::ENABLE_SUBMISSION_CONFIRMATION_ATTACHMENTS, $enabled);
    }

    /**
     * @inheritDoc
     */
    public function isActive()
    {
        return $this->getIsActive();
    }

    /**
     * @inheritDoc
     */
    public function isRecaptchaEnabled()
    {
        return $this->getEnableRecaptcha();
    }

    /**
     * @inheritDoc
     */
    public function isAdminNotificationEnabled()
    {
        return $this->getEnableAdminNotification();
    }

    /**
     * @inheritDoc
     */
    public function isAdminNotificationAttachmentsEnabled()
    {
        return $this->getEnableAdminNotificationAttachments();
    }

    /**
     * @inheritDoc
     */
    public function isSubmissionConfirmationEnabled()
    {
        return $this->getEnableSubmissionConfirmation();
    }

    /**
     * @inheritDoc
     */
    public function isSubmissionConfirmationAttachmentsEnabled()
    {
        return $this->getEnableSubmissionConfirmationAttachments();
    }

    /**
     * @inheritDoc
     */
    public function getCustomerEmailFields()
    {
        $fields = $this->getFields();
        $emailFields = [];

        foreach ($fields as $field) {
            if ($field->isCustomerEmailField()) {
                $emailFields[] = $field;
            }
        }

        return $emailFields;
    }
}
