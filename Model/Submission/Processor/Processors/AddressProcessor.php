<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Processor\Processors;

use HappyMachines\CustomForms\Model\Submission\Processor\SubmissionProcessorInterface;
use Magento\Directory\Model\RegionFactory;

/**
 * Class AddressProcessor
 * @package HappyMachines\CustomForms\Model\Submission\Processor\Processors
 */
class AddressProcessor implements SubmissionProcessorInterface
{
    /**
     * @var RegionFactory
     */
    private $regionFactory;

    /**
     * Processor constructor.
     * @param RegionFactory $regionFactory
     */
    public function __construct(
        RegionFactory $regionFactory
    ) {
        $this->regionFactory = $regionFactory;
    }

    /**
     * Run additional processing on submitted form data
     *
     * @param $form
     * @param $data
     * @return null
     */
    public function execute($form, $data)
    {
        $formFields = $form->getFields();

        foreach ($formFields as $field) {
            if (empty($field->hasAddressGroup()) || $field->getAddressFieldType() !== 'region') {
                continue;
            }

            if (!empty($data[$field->getName()]) && is_numeric($data[$field->getName()])) {
                /** Region repositories not available */
                $region = $this->regionFactory->create()->load($data[$field->getName()]);
                $data[$field->getName()] = $region->getName();
            }
        }

        return $data;
    }
}
