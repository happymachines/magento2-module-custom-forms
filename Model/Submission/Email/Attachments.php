<?php

namespace HappyMachines\CustomForms\Model\Submission\Email;

use HappyMachines\CustomForms\Api\Data\SubmissionInterface;
use HappyMachines\CustomForms\Api\SubmissionFileRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

/**
 * Class Attachments
 * @package HappyMachines\CustomForms\Model\Submission\Email
 */
class Attachments
{
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var FilterGroupBuilder
     */
    private $filterGroupBuilder;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SubmissionFileRepositoryInterface
     */
    private $submissionFileRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Attachments constructor.
     * @param FilterBuilder $filterBuilder
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SubmissionFileRepositoryInterface $submissionFileRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SubmissionFileRepositoryInterface $submissionFileRepository,
        LoggerInterface $logger
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->submissionFileRepository = $submissionFileRepository;
        $this->logger = $logger;
    }

    /**
     * @param SubmissionInterface $submission
     * @return array
     */
    public function getSubmissionFiles($submission)
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $submissionIdFilter = $this->filterBuilder
            ->setField(SubmissionInterface::SUBMISSION_ID)
            ->setValue($submission->getSubmissionId())
            ->setConditionType('eq')
            ->create();
        $submissionIdFilterGroup = $this->filterGroupBuilder->addFilter($submissionIdFilter)->create();

        $searchCriteria->setFilterGroups([$submissionIdFilterGroup]);

        try {
            $submissionFiles = $this->submissionFileRepository->getList($searchCriteria);
            return $submissionFiles->getItems() ?? [];
        } catch (LocalizedException $exception) {
            $this->logger->error('Error loading submission files: ' . $exception->getMessage());
            return [];
        }
    }
}
