<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Block\Adminhtml\Submission\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ResendAdminNotification
 * @package HappyMachines\CustomForms\Block\Adminhtml\Submission\Edit
 */
class ResendAdminNotification extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getSubmissionId()) {
            $data = [
                'label' => __('Resend Admin Notification'),
                'class' => '',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to resend the admin notification?'
                    ) . '\', \'' . $this->getResendEmailUrl() . '\', {"data": {}})',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * Resend email action url
     *
     * @return string
     */
    public function getResendEmailUrl()
    {
        return $this->getUrl('*/*/resendEmail', ['submission_id' => $this->getSubmissionId(), 'resend_admin_notification' => true]);
    }
}
