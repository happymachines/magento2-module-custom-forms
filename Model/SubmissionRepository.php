<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model;

use HappyMachines\CustomForms\Api\Data\SubmissionInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterfaceFactory;
use HappyMachines\CustomForms\Api\SubmissionRepositoryInterface;
use HappyMachines\CustomForms\Model\ResourceModel\Submission as SubmissionResource;
use HappyMachines\CustomForms\Model\ResourceModel\Submission\Collection;
use HappyMachines\CustomForms\Model\ResourceModel\Submission\CollectionFactory as SubmissionCollectionFactory;
use HappyMachines\CustomForms\Api\Data\SubmissionSearchResultsInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Class SubmissionRepository
 * @package HappyMachines\CustomForms\Model
 */
class SubmissionRepository implements SubmissionRepositoryInterface
{
    /**
     * @var SubmissionResource
     */
    private $submissionResource;

    /**
     * @var SubmissionInterfaceFactory
     */
    private $submissionFactory;

    /**
     * @var SubmissionCollectionFactory
     */
    private $submissionCollectionFactory;

    /**
     * @var SubmissionSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SubmissionRepository constructor.
     * @param SubmissionResource $submissionResource
     * @param SubmissionInterfaceFactory $submissionFactory
     * @param SubmissionCollectionFactory $submissionCollectionFactory
     * @param SubmissionSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param LoggerInterface $logger
     */
    public function __construct(
        SubmissionResource $submissionResource,
        SubmissionInterfaceFactory $submissionFactory,
        SubmissionCollectionFactory $submissionCollectionFactory,
        SubmissionSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        LoggerInterface $logger
    ) {
        $this->submissionResource = $submissionResource;
        $this->submissionFactory = $submissionFactory;
        $this->submissionCollectionFactory = $submissionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->logger = $logger;
    }

    /**
     * Save Form submission data
     *
     * @param SubmissionInterface|Submission $submission
     * @return Submission
     * @throws CouldNotSaveException
     */
    public function save(SubmissionInterface $submission)
    {
        try {
            $this->submissionResource->save($submission);
        } catch (\Exception $exception) {
            $this->logger->critical(__('Unable to save the form submission entity. %1', $exception->getMessage()));
            throw new CouldNotSaveException(
                __('Could not save the form submission: %1', $exception->getMessage()),
                $exception
            );
        }
        return $submission;
    }

    /**
     * Load Form submission data by given Submission Identity
     *
     * @param string $submissionId
     * @return SubmissionInterface
     * @throws NoSuchEntityException
     */
    public function getById($submissionId)
    {
        $submission = $this->submissionFactory->create();

        $this->submissionResource->load($submission, $submissionId);

        if (!$submission->getId()) {
            throw new NoSuchEntityException(__('The form submission with the "%1" ID doesn\'t exist.', $submissionId));
        }

        return $submission;
    }

    /**
     * Load form data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return SubmissionSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var Collection $collection */
        $collection = $this->submissionCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var SubmissionSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param SubmissionInterface $submission
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(SubmissionInterface $submission)
    {
        try {
            $this->submissionResource->delete($submission);
        } catch (\Exception $exception) {
            $this->logger->critical(__('Unable to delete the form submission entity. %1', $exception->getMessage()));
            throw new CouldNotDeleteException(
                __('Could not delete the form submission: %1', $exception->getMessage())
            );
        }
        return true;
    }

    /**
     * @param int $submissionId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $submissionId)
    {
        return $this->delete($this->getById($submissionId));
    }
}
