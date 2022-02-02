<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Validator;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use Magento\Framework\Validation\ValidationResult;

/**
 * Interface ValidatorInterface
 * @package HappyMachines\CustomForms\Model\Submission\Validator
 */
interface ValidatorInterface
{
    /**
     * @param FormInterface $form
     * @param array $submissionData
     * @return ValidationResult
     */
    public function validate(FormInterface $form, array $submissionData);
}
