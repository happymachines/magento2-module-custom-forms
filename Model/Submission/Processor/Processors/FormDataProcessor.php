<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Processor\Processors;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Model\Submission\Processor\SubmissionProcessorInterface;

/**
 * Class FormDataProcessor
 * @package HappyMachines\CustomForms\Model\Submission\Processor\Processors
 */
class FormDataProcessor implements SubmissionProcessorInterface
{
    /**
     * Builds submission data into the following structure:
     * form_builder_field_name => [
     *  'value' => submitted_value
     *  'label' => form_builder_field_label
     * ]
     * ...
     * @param FormInterface $form
     * @param $data
     * @return mixed|void
     */
    public function execute($form, $data)
    {
        $fields = $form->getFields();

        foreach ($fields as $index => $field) {
            /**
             * Skip non input fields that are assigned to the form builder structure
             * such as paragraph elements and headings
            */
            if (!$field->getName()) {
                continue;
            }

            /**
             * Maps submitted form data to the form builder field names
             */
            if ($field->getLabel() && array_key_exists($field->getName(), $data)) {
                $submissionFieldData = [];

                if (is_array($data[$field->getName()])) {
                    $submissionFieldData['value'] = implode(', ', $data[$field->getName()]);
                } else {
                    $submissionFieldData['value'] = $data[$field->getName()];
                }

                $submissionFieldData['label'] = $field->getLabel();

                $data[$field->getName()] = $submissionFieldData;
            }
        }

        return $data;
    }
}
