<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Processor\Processors;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Model\Submission\Processor\SubmissionProcessorInterface;

/**
 * Class EmptyFieldProcessor
 * @package HappyMachines\CustomForms\Model\Submission\Processor\Processors
 */
class EmptyFieldProcessor implements SubmissionProcessorInterface
{
    /**
     * @param FormInterface $form
     * @param $data
     * @return mixed|void
     */
    public function execute($form, $data)
    {
        $fields = $form->getFields();

        foreach ($fields as $index => $field) {
            if (empty($data[$field->getName()])) {
                unset($data[$field->getName()]);
            }
        }

        return $data;
    }
}
