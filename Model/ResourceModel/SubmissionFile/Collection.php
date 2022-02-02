<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\ResourceModel\SubmissionFile;

use HappyMachines\CustomForms\Model\SubmissionFile;
use HappyMachines\CustomForms\Model\ResourceModel\SubmissionFile as SubmissionFileResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package HappyMachines\CustomForms\Model\ResourceModel\SubmissionFile
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'file_id';

    /**
     * Load data for preview flag
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'happymachines_form_submission_file_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'form_submission_file_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(SubmissionFile::class, SubmissionFileResource::class);
    }
}
