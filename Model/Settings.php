<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Settings
 * @package HappyMachines\CustomForms\Model
 */
class Settings
{
    const CUSTOM_FORM_ENABLED_CONFIG = 'custom_forms/general/enabled';
    const CUSTOM_FORM_EMAIL_SENDER_IDENTITY_CONFIG = 'custom_forms/email/sender_identity';
    const CUSTOM_FORM_FORMBUILDER_EDIT_ON_ADD = 'custom_forms/formbuilder/edit_on_add';
    const CUSTOM_FORM_FORMBUILDER_SCROLL_TO_FIELD_ON_ADD = 'custom_forms/formbuilder/scroll_to_field_on_add';
    const CUSTOM_FORM_FORMBUILDER_CONFIRM_FIELD_DELETE = 'custom_forms/formbuilder/confirm_field_delete';
    const CUSTOM_FORM_DEVELOPER_ENABLE_JSON_PREVIEW = 'custom_forms/developer/enable_json_preview';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Settings constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get config value.
     *
     * @param string $path
     * @return string|null
     */
    public function getConfig($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->scopeConfig->getValue(
            self::CUSTOM_FORM_ENABLED_CONFIG
        );
    }

    /**
     * @return string
     */
    public function getEmailSenderIdentity()
    {
        return $this->scopeConfig->getValue(
            self::CUSTOM_FORM_EMAIL_SENDER_IDENTITY_CONFIG
        );
    }

    /**
     * @return bool
     */
    public function getEditOnAdd()
    {
        return (bool)$this->scopeConfig->getValue(
            self::CUSTOM_FORM_FORMBUILDER_EDIT_ON_ADD
        );
    }

    /**
     * @return bool
     */
    public function getScrollOnAdd()
    {
        return (bool)$this->scopeConfig->getValue(
            self::CUSTOM_FORM_FORMBUILDER_SCROLL_TO_FIELD_ON_ADD
        );
    }

    /**
     * @return bool
     */
    public function getConfirmFieldDelete()
    {
        return (bool)$this->scopeConfig->getValue(
            self::CUSTOM_FORM_FORMBUILDER_CONFIRM_FIELD_DELETE
        );
    }

    /**
     * @return bool
     */
    public function getDeveloperEnableJsonPreview()
    {
        return (bool)$this->scopeConfig->getValue(
            self::CUSTOM_FORM_DEVELOPER_ENABLE_JSON_PREVIEW
        );
    }
}
