<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model;

use HappyMachines\CustomForms\Api\Data\SubmissionInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Class Submission
 * @package HappyMachines\CustomForms\Model
 */
class Submission extends AbstractModel implements SubmissionInterface
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'happymachines_form_submission';

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Submission::class);
    }

    /**
     * Submission constructor.
     * @param Context $context
     * @param Registry $registry
     * @param SerializerInterface $serializer
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        SerializerInterface $serializer,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function getSubmissionId()
    {
        return $this->getData(self::SUBMISSION_ID);
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
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function getQuoteId()
    {
        return $this->getData(self::QUOTE_ID);
    }

    /**
     * @inheritDoc
     */
    public function getSubmissionData()
    {
        return $this->getData(self::SUBMISSION_DATA);
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
    public function setSubmissionId($submissionId)
    {
        return $this->setData(self::SUBMISSION_ID, $submissionId);
    }

    /**
     * @inheritDoc
     */
    public function setFormId($formId)
    {
        return $this->setData(self::FORM_ID, $formId);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerEmail($email)
    {
        return $this->setData(self::CUSTOMER_EMAIL, $email);
    }

    /**
     * @inheritDoc
     */
    public function setQuoteId($id)
    {
        return $this->setData(self::QUOTE_ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function setSubmissionData($submissionData)
    {
        return $this->setData(self::SUBMISSION_DATA, $submissionData);
    }

    /**
     * @inheritDoc
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * @inheritDoc
     */
    public function getUnserializedSubmissionData()
    {
        return $this->serializer->unserialize($this->getSubmissionData());
    }
}
