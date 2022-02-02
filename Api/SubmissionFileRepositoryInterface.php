<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Api;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\FormSearchResultsInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionFileInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionFileSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface SubmissionFileRepositoryInterface
 * @package HappyMachines\CustomForms\Api
 */
interface SubmissionFileRepositoryInterface
{
    /**
     * Save form submission file.
     *
     * @param SubmissionFileInterface $file
     * @return SubmissionFileInterface
     * @throws LocalizedException
     */
    public function save(SubmissionFileInterface $file);

    /**
     * Retrieve form submission file.
     *
     * @param int $fileId
     * @return SubmissionFileInterface
     */
    public function getById(int $fileId);

    /**
     * Retrieve form submission files matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SubmissionFileSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete form submission file.
     *
     * @param SubmissionFileInterface $file
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(SubmissionFileInterface $file);

    /**
     * Delete form submission file by ID.
     *
     * @param int $fileId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(int $fileId);
}
