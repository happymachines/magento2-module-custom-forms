<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Ui\DataProvider\Form\Metadata;

use Magento\Customer\Api\Data\GroupInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Email\Model\ResourceModel\Template\CollectionFactory as EmailTemplateCollectionFactory;
use Magento\Email\Model\Template\Config as EmailConfig;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject;
use Psr\Log\LoggerInterface;

/**
 * Metadata provider for form edit form.
 */
class ValueProvider
{
    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var DataObject
     */
    protected $objectConverter;

    /**
     * @var EmailConfig
     */
    protected $emailConfig;

    /**
     * @var EmailTemplateCollectionFactory
     */
    protected $emailTemplateCollectionFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Initialize dependencies.
     *
     * @param GroupRepositoryInterface $groupRepository
     * @param EmailConfig $emailConfig
     * @param EmailTemplateCollectionFactory $emailTemplateCollectionFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DataObject $objectConverter
     * @param LoggerInterface $logger
     */
    public function __construct(
        GroupRepositoryInterface $groupRepository,
        EmailConfig $emailConfig,
        EmailTemplateCollectionFactory $emailTemplateCollectionFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataObject $objectConverter,
        LoggerInterface $logger
    ) {
        $this->groupRepository = $groupRepository;
        $this->emailConfig = $emailConfig;
        $this->emailTemplateCollectionFactory = $emailTemplateCollectionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->objectConverter = $objectConverter;
        $this->logger = $logger;
    }

    /**
     * Get metadata for form. It will be merged with form UI component declaration.
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getMetadataValues()
    {
        return [
            'settings' => [
                'children' => [
                    'customers' => [
                        'children' => [
                            'customer_groups' => [
                                'arguments' => [
                                    'data' => [
                                        'config' => [
                                            'options' => $this->objectConverter->toOptionArray($this->getCustomerGroups(), 'id', 'code'),
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'email_notifications' => [
                        'children' => [
                            'admin_email_notifications' => [
                                'children' => [
                                    'admin_notification_email_template' => [
                                        'arguments' => [
                                            'data' => [
                                                'config' => [
                                                    'options' => $this->getEmailTemplates(),
                                                    'default' => 'happymachines_customforms_submission_admin_notification_default',
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'submission_confirmations' => [
                                'children' => [
                                    'submission_confirmation_email_template' => [
                                        'arguments' => [
                                            'data' => [
                                                'config' => [
                                                    'options' => $this->getEmailTemplates(),
                                                    'default' => 'happymachines_customforms_submission_confirmation_default',
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array|GroupInterface[]
     */
    private function getCustomerGroups()
    {
        $customerGroups = [];

        try {
            $customerGroups = $this->groupRepository->getList($this->searchCriteriaBuilder->create())->getItems();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $customerGroups;
    }

    /**
     * @return mixed
     */
    private function getEmailTemplates()
    {
        $customTemplates = $this->getCustomEmailTemplatesArray();
        $configEmailTemplates = $this->getDefaultTemplatesArray();

        foreach ($configEmailTemplates as $emailTemplate) {
            $groupedOptions[$emailTemplate['group']][] = $emailTemplate;
        }

        ksort($groupedOptions);

        $result = [];

        foreach ($groupedOptions as $groupName => $emailTemplates) {
            $result[] = ['label' => $groupName, 'value' => $emailTemplates];
        }

        array_unshift($result, ['label' => __('Custom Email Templates'), 'value' => $customTemplates]);

        return $result;
    }

    /**
     * Get custom email templates defined in Admin > Marketing > (Communications) Email Templates
     * @return mixed
     */
    private function getCustomEmailTemplatesArray()
    {
        $collection = $this->emailTemplateCollectionFactory->create();

        try {
            $collection->load();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $collection->toOptionArray();
    }

    /**
     * Get email templates defined in email_templates.xml as a sorted array
     *
     * @return array
     */
    protected function getDefaultTemplatesArray()
    {
        $options = $this->emailConfig->getAvailableTemplates();

        uasort(
            $options,
            static function (array $firstElement, array $secondElement) {
                return strcmp((string)$firstElement['label'], (string)$secondElement['label']);
            }
        );

        return $options;
    }
}
