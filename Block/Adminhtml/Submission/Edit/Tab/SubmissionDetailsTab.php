<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Block\Adminhtml\Submission\Edit\Tab;

use Magento\Backend\Block\Template;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

/**
 * Class SubmissionDetailsTab
 * @package HappyMachines\CustomForms\Block\Adminhtml\Submission\Edit\Tab
 */
class SubmissionDetailsTab extends Template implements TabInterface
{
    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getTabLabel()
    {
        return __('Submission Details');
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getTabTitle()
    {
        return __('Submission Details');
    }

    /**
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}
