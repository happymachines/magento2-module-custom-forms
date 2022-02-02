<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model;

use HappyMachines\CustomForms\Api\Data\SubmissionFileInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class SubmissionFile
 * @package HappyMachines\CustomForms\Model
 */
class SubmissionFile extends AbstractModel implements SubmissionFileInterface
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'happymachines_form_submission_file';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\SubmissionFile::class);
    }

    /**
     * @inheritDoc
     */
    public function getFileId()
    {
        return $this->getData(self::FILE_ID);
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
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return $this->getData(self::PATH);
    }

    /**
     * @inheritDoc
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * @inheritDoc
     */
    public function getMimeType()
    {
        return $this->getData(self::MIME_TYPE);
    }

    /**
     * @return string|void
     */
    public function getSize()
    {
        return $this->getData(self::SIZE);
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
    public function setFileId($fileId)
    {
        return $this->setData(self::FILE_ID, $fileId);
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
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function setPath($path)
    {
        return $this->setData(self::PATH, $path);
    }

    /**
     * @inheritDoc
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @inheritDoc
     */
    public function setMimeType($mimeType)
    {
        return $this->setData(self::MIME_TYPE, $mimeType);
    }

    /**
     * @inheritDoc
     */
    public function setSize($size)
    {
        return $this->setData(self::SIZE, $size);
    }

    /**
     * @inheritDoc
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }
}
