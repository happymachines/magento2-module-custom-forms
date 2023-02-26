<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Setup\Patch\Data;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\FormInterfaceFactory;
use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Filesystem\Driver\File as FileSystemDriver;

/**
 * Class CreateDefaultForms
 * @package HappyMachines\CustomForms\Setup\Patch\Data
 */
class CreateDefaultForms implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var FileSystemDriver
     */
    private $fileSystemDriver;

    /**
     * @var FormInterfaceFactory
     */
    private $formFactory;

    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * @var string
     */
    private $sampleContactFormData;

    /**
     * CreateDefaultForms constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param FileSystemDriver $fileSystemDriver
     * @param FormInterfaceFactory $formFactory
     * @param FormRepositoryInterface $formRepository
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        FileSystemDriver $fileSystemDriver,
        FormInterfaceFactory $formFactory,
        FormRepositoryInterface $formRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->fileSystemDriver = $fileSystemDriver;
        $this->formFactory = $formFactory;
        $this->formRepository = $formRepository;
        $this->sampleContactFormData = __DIR__ . '/resources/sample-contact-form.json';
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $formData = [
            FormInterface::NAME => 'Sample Contact Form',
            FormInterface::IDENTIFIER => 'sample-contact-form',
            FormInterface::IS_ACTIVE => 1,
            FormInterface::STORES => [0],
            FormInterface::CUSTOMER_GROUPS => [0,1,2,3],
            FormInterface::ENABLE_RECAPTCHA => 0,
            FormInterface::ADMIN_NOTIFICATION_RECIPIENT => 'support@example.com',
            FormInterface::ADMIN_NOTIFICATION_EMAIL_TEMPLATE => 'happymachines_customforms_submission_admin_notification_default',
            FormInterface::SUBMISSION_CONFIRMATION_EMAIL_TEMPLATE => 'happymachines_customforms_submission_confirmation_default',
            FormInterface::SUBMISSION_SUCCESS_MESSAGE => 'Thank you for contacting us! Your question will be reviewed and we will respond shortly.',
            FormInterface::FORM_DATA => $this->fileSystemDriver->fileGetContents($this->sampleContactFormData)
        ];

        /** @var FormInterface $formModel */
        $formModel = $this->formFactory->create()->setData($formData);
        $this->formRepository->save($formModel);

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
