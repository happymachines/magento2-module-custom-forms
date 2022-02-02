<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Validator;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validation\ValidationResultFactory;

/**
 * Class ValidatorPool
 * @package HappyMachines\CustomForms\Model\Submission\Validator
 */
class ValidatorPool implements ValidatorInterface
{
    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;

    /**
     * @var ValidatorInterface[]
     */
    private $validators;

    /**
     * @param ValidationResultFactory $validationResultFactory
     * @param array $validators
     * @throws LocalizedException
     */
    public function __construct(
        ValidationResultFactory $validationResultFactory,
        array $validators = []
    ) {
        $this->validationResultFactory = $validationResultFactory;

        foreach ($validators as $validator) {
            if (!$validator instanceof ValidatorInterface) {
                throw new LocalizedException(
                    __('Validator must implement %interface.', ['interface' => ValidatorInterface::class])
                );
            }
        }
        $this->validators = $validators;
    }

    /**
     * @inheritdoc
     */
    public function validate($form, array $submissionData)
    {
        /* the inner empty array covers cases when no loops were made */
        $errors = [[]];
        foreach ($this->validators as $validator) {
            $validationResult = $validator->validate($form, $submissionData);

            if (!$validationResult->isValid()) {
                $errors[] = $validationResult->getErrors();
            }
        }

        return $this->validationResultFactory->create(['errors' => array_merge(...$errors)]);
    }
}
