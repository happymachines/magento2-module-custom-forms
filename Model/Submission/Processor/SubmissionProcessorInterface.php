<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Processor;

use HappyMachines\CustomForms\Api\Data\FormInterface;

/**
 * Interface SubmissionProcessorInterface
 * @package HappyMachines\CustomForms\Model\Submission\Processor
 */
interface SubmissionProcessorInterface
{
    /**
     * @param FormInterface $form
     * @param $data
     * @return mixed
     */
    public function execute($form, $data);
}
