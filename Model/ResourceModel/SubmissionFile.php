<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class SubmissionFile
 * @package HappyMachines\CustomForms\Model\ResourceModel
 */
class SubmissionFile extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('happymachines_custom_form_submission_file', 'file_id');
    }
}
