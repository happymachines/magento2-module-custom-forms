<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Email;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;

/**
 * Interface SenderInterface
 * @package HappyMachines\CustomForms\Model\Email
 */
interface SenderInterface
{
    /**
     * @param FormInterface $form
     * @param SubmissionInterface $submission
     * @return mixed
     */
    public function send($form, $submission);
}
