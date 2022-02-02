<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Processor;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

/**
 * Class SubmissionProcessorPool
 * @package HappyMachines\CustomForms\Model\Submission\Processor
 */
class SubmissionProcessorPool implements SubmissionProcessorPoolInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SubmissionProcessorInterface[]
     */
    private $processors;

    /**
     * @param LoggerInterface $logger
     * @param SubmissionProcessorInterface[] $processors
     * @throws LocalizedException
     */
    public function __construct(
        LoggerInterface $logger,
        array $processors = []
    ) {
        foreach ($processors as $processor) {
            if (!$processor instanceof SubmissionProcessorInterface) {
                throw new LocalizedException(
                    __('Submission processor must implement SubmissionProcessorInterface.')
                );
            }
        }
        $this->processors = $processors;
        $this->logger = $logger;
    }

    /**
     * @param FormInterface $form
     * @param $data
     * @return mixed
     */
    public function process($form, $data)
    {
        foreach ($this->processors as $processor) {
            try {
                $data = $processor->execute($form, $data);
            } catch (\Exception $exception) {
                $this->logger->critical(__('An exception occurred while processing the submission data. %1', $exception->getMessage()));
            }
        }

        return $data;
    }
}
