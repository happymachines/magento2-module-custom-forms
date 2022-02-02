<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Validator\Validators;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Model\Submission\Validator\ValidatorInterface;
use HappyMachines\CustomForms\Model\Form\Field\Condition\ConditionComparatorPoolInterface;
use Magento\Framework\Validation\ValidationResultFactory;

/**
 * Class RequiredValidator
 * @package HappyMachines\CustomForms\Model\Submission\Validator\Validators
 */
class RequiredValidator implements ValidatorInterface
{
    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;

    /**
     * @var ConditionComparatorPoolInterface
     */
    private $fieldConditionComparatorPool;

    /**
     * @param ValidationResultFactory $validationResultFactory
     * @param ConditionComparatorPoolInterface $fieldConditionComparatorPool
     */
    public function __construct(
        ValidationResultFactory $validationResultFactory,
        ConditionComparatorPoolInterface $fieldConditionComparatorPool
    ) {
        $this->validationResultFactory = $validationResultFactory;
        $this->fieldConditionComparatorPool = $fieldConditionComparatorPool;
    }

    /**
     * @inheritdoc
     */
    public function validate($form, array $submissionData)
    {
        $errors = [];

        $formFields = $form->getFields();
        $hiddenFields = $this->getHiddenFields($form, $submissionData);

        foreach ($formFields as $field) {
            if (!empty($field->getName()) && $field->isRequired()) {
                $requiredFieldIsMissing = !array_key_exists($field->getName(), $submissionData);
                $requiredFieldIsEmpty = isset($submissionData[$field->getName()]) && empty($submissionData[$field->getName()]);
                if ($requiredFieldIsMissing || $requiredFieldIsEmpty) {
                    $fieldIsHidden = $this->isFieldHidden($hiddenFields, $field);
                    if (!$fieldIsHidden) {
                        $errors[] = __('Submission data is missing for the required field "%field"', ['field' => $field->getLabel()]);
                    }
                }
            }
        }

        return $this->validationResultFactory->create(['errors' => $errors]);
    }

    /**
     * @param $hiddenFields
     * @param $field
     * @return bool
     */
    protected function isFieldHidden($hiddenFields, $field)
    {
        return in_array($field->getName(), $hiddenFields, false);
    }

    /**
     * @param FormInterface $form
     * @param $submissionData
     * @return array
     */
    protected function getHiddenFields($form, $submissionData)
    {
        $hiddenFields = [];

        foreach ($form->getFields() as $field) {
            if (!$field->getConditions()) {
                continue;
            }

            foreach ($field->getConditions() as $condition) {
                if ($condition->getTarget()->getFieldProperty() !== 'visibility') {
                    continue;
                }

                $sourceFieldName = $condition->getSource()->getFieldName();
                $sourceFieldValue = $condition->getSource()->getFieldValue();
                $sourceFieldComparison = $condition->getSource()->getFieldComparison();

                if (!in_array($sourceFieldName, $hiddenFields, false) && $this->fieldConditionComparatorPool->compare($sourceFieldComparison, $sourceFieldValue, $submissionData[$sourceFieldName])) {
                    $hiddenFields[] = $condition->getTarget()->getFieldName();
                }
            }
        }

        return $hiddenFields;
    }
}
