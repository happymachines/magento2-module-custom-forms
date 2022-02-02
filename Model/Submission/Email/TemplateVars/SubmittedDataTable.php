<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Email\TemplateVars;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;
use HappyMachines\CustomForms\Model\Submission\Email\TemplateVarInterface;
use Magento\Framework\Escaper;

/**
 * Class SubmittedDataTable
 * @package HappyMachines\CustomForms\Model\Submission\Email\TemplateVars
 */
class SubmittedDataTable implements TemplateVarInterface
{
    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * SubmittedDataTable constructor.
     * @param Escaper $escaper
     */
    public function __construct(
        Escaper $escaper
    ) {
        $this->escaper = $escaper;
    }

    /**
     * @inheritDoc
     */
    public function getTemplateVar(FormInterface $form, SubmissionInterface $submission)
    {
        $submittedData = $submission->getUnserializedSubmissionData();
        $submittedDataTable = "<table class='happymachines-submitted-data'>";

        foreach ($submittedData as $fieldName => $fieldData) {
            $sanitizedFieldName = $this->escaper->escapeHtml($fieldData['label']);
            $sanitizedFieldValue = $this->escaper->escapeHtml($fieldData['value']);
            $submittedDataTable .= "<tr class='happymachines-submitted-data-row'><td class='happymachines-submitted-data-name'>$sanitizedFieldName</td><td class='happymachines-submitted-data-value'>$sanitizedFieldValue</td></tr>";
        }
        $submittedDataTable .= "</table>";

        return $submittedDataTable;
    }
}
