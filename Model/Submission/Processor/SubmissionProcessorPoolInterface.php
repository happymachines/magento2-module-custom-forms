<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Processor;

use HappyMachines\CustomForms\Api\Data\FormInterface;

/**
 * Interface SubmissionProcessorPoolInterface
 * @package HappyMachines\CustomForms\Model\Submission\Processor
 */
interface SubmissionProcessorPoolInterface
{
    /**
     * @param FormInterface $form
     * @param $data
     * @return mixed
     */
    public function process($form, $data);
}
