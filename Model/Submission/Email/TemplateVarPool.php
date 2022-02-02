<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Email;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

/**
 * Class TemplateVarPool
 * @package HappyMachines\CustomForms\Model\Submission\Email
 */
class TemplateVarPool implements TemplateVarPoolInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $templateVars;

    /**
     * TemplateVarsPool constructor.
     * @param LoggerInterface $logger
     * @param array $templateVars
     * @throws LocalizedException
     */
    public function __construct(
        LoggerInterface $logger,
        array $templateVars = []
    ) {
        foreach ($templateVars as $templateVar) {
            if (!$templateVar instanceof TemplateVarInterface) {
                throw new LocalizedException(
                    __('TemplateVar must implement %interface.', ['interface' => TemplateVarInterface::class])
                );
            }
        }

        $this->logger = $logger;
        $this->templateVars = $templateVars;
    }

    /**
     * @param FormInterface $form
     * @param SubmissionInterface $submission
     * @return array|mixed
     */
    public function getTemplateVars(FormInterface $form, SubmissionInterface $submission)
    {
        $templateVars = [];

        /** @var TemplateVarInterface $templateVar */
        foreach ($this->templateVars as $templateVarName => $templateVar) {
            try {
                $templateVars[$templateVarName] = $templateVar->getTemplateVar($form, $submission);
            } catch (\Exception $exception) {
                $this->logger->critical(__('Failed to get template var for submission email. %1', $exception->getMessage()));
            }
        }

        return $templateVars;
    }
}
