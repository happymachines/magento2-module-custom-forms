<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model;

use HappyMachines\CustomForms\Api\Data\SubmissionFileInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionFileInterfaceFactory;
use HappyMachines\CustomForms\Api\SubmissionFileRepositoryInterface;
use HappyMachines\CustomForms\Model\ResourceModel\SubmissionFile as SubmissionFileResource;
use HappyMachines\CustomForms\Model\ResourceModel\SubmissionFile\Collection;
use HappyMachines\CustomForms\Model\ResourceModel\SubmissionFile\CollectionFactory as SubmissionFileCollectionFactory;
use HappyMachines\CustomForms\Api\Data\SubmissionFileSearchResultsInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionFileSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Class SubmissionFileRepository
 * @package HappyMachines\CustomForms\Model
 */
class SubmissionFileRepository implements SubmissionFileRepositoryInterface
{
    /**
     * @var SubmissionFileResource
     */
    private $submissionFileResource;

    /**
     * @var SubmissionFileInterfaceFactory
     */
    private $submissionFileFactory;

    /**
     * @var SubmissionFileCollectionFactory
     */
    private $submissionFileCollectionFactory;

    /**
     * @var SubmissionFileSearchResultsInterfaceFactory
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
     * @param SubmissionFileResource $submissionFileResource
     * @param SubmissionFileInterfaceFactory $submissionFileFactory
     * @param SubmissionFileCollectionFactory $submissionFileCollectionFactory
     * @param SubmissionFileSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param LoggerInterface $logger
     */
    public function __construct(
        SubmissionFileResource $submissionFileResource,
        SubmissionFileInterfaceFactory $submissionFileFactory,
        SubmissionFileCollectionFactory $submissionFileCollectionFactory,
        SubmissionFileSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        LoggerInterface $logger
    ) {

        $this->submissionFileResource = $submissionFileResource;
        $this->submissionFileFactory = $submissionFileFactory;
        $this->submissionFileCollectionFactory = $submissionFileCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->logger = $logger;
    }

    /**
     * Save form submission file data
     *
     * @param SubmissionFileInterface|SubmissionFile $file
     * @return SubmissionFile
     * @throws CouldNotSaveException
     */
    public function save(SubmissionFileInterface $file)
    {
        try {
            $this->submissionFileResource->save($file);
        } catch (\Exception $exception) {
            $this->logger->critical(__('Unable to save the submission file entity. %1', $exception->getMessage()));
            throw new CouldNotSaveException(
                __('Could not save the file: %1', $exception->getMessage()),
                $exception
            );
        }
        return $file;
    }

    /**
     * Load form submission file data by given id
     *
     * @param int $fileId
     * @return SubmissionFileInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $fileId)
    {
        $submissionFile = $this->submissionFileFactory->create();

        $this->submissionFileResource->load($submissionFile, $fileId);

        if (!$submissionFile->getId()) {
            throw new NoSuchEntityException(__('The form submission file with the "%1" ID doesn\'t exist.', $fileId));
        }

        return $submissionFile;
    }

    /**
     * Load form data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return SubmissionFileSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var Collection $collection */
        $collection = $this->submissionFileCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var SubmissionFileSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param SubmissionFileInterface $file
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(SubmissionFileInterface $file)
    {
        try {
            $this->submissionFileResource->delete($file);
        } catch (\Exception $exception) {
            $this->logger->critical(__('Unable to delete the submission file entity. %1', $exception->getMessage()));
            throw new CouldNotDeleteException(
                __('Could not delete the form submission file: %1', $exception->getMessage())
            );
        }
        return true;
    }

    /**
     * @param int $fileId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $fileId)
    {
        return $this->delete($this->getById($fileId));
    }
}
