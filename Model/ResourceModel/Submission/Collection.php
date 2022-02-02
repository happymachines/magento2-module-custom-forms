<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\ResourceModel\Submission;

use HappyMachines\CustomForms\Model\Submission;
use HappyMachines\CustomForms\Model\ResourceModel\Submission as SubmissionResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package HappyMachines\CustomForms\Model\ResourceModel\Form
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'submission_id';

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
    protected $_eventPrefix = 'happymachines_form_submission_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'form_submission_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Submission::class, SubmissionResource::class);
    }
}
