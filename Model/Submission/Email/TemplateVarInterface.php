<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Email;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;

/**
 * Interface TemplateVarInterface
 * @package HappyMachines\CustomForms\Model\Submission\Email
 */
interface TemplateVarInterface
{
    /**
     * @param FormInterface $form
     * @param SubmissionInterface $submission
     * @return mixed
     */
    public function getTemplateVar(FormInterface $form, SubmissionInterface $submission);
}
