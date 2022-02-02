<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Email\TemplateVars;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;
use HappyMachines\CustomForms\Model\Submission\Email\TemplateVarInterface;

/**
 * Class FormName
 * @package HappyMachines\CustomForms\Model\Submission\Email\TemplateVars
 */
class FormName implements TemplateVarInterface
{
    /**
     * @param FormInterface $form
     * @param SubmissionInterface $submission
     * @return mixed|string
     */
    public function getTemplateVar(FormInterface $form, SubmissionInterface $submission)
    {
        return $form->getName();
    }
}
