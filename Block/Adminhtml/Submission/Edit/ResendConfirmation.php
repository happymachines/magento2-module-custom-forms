<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Block\Adminhtml\Submission\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ResendConfirmation
 * @package HappyMachines\CustomForms\Block\Adminhtml\Submission\Edit
 */
class ResendConfirmation extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getSubmissionId()) {
            $data = [
                'label' => __('Resend Confirmation Email'),
                'class' => '',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to resend the customer confirmation email?'
                    ) . '\', \'' . $this->getResendEmailUrl() . '\', {"data": {}})',
                'sort_order' => 30,
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
        return $this->getUrl('*/*/resendEmail', ['submission_id' => $this->getSubmissionId(), 'resend_confirmation' => true]);
    }
}
