<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Email;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;

/**
 * Interface TemplateVarPoolInterface
 * @package HappyMachines\CustomForms\Model\Submission\Email
 */
interface TemplateVarPoolInterface
{
    /**
     * @param FormInterface $form
     * @param SubmissionInterface $submission
     * @return mixed
     */
    public function getTemplateVars(FormInterface $form, SubmissionInterface $submission);
}
