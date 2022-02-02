<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Api;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\FormSearchResultsInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface SubmissionRepositoryInterface
 * @package HappyMachines\CustomForms\Api
 */
interface SubmissionRepositoryInterface
{
    /**
     * Save form submission.
     *
     * @param SubmissionInterface $submission
     * @return SubmissionInterface
     * @throws LocalizedException
     */
    public function save(SubmissionInterface $submission);

    /**
     * Retrieve form submission.
     *
     * @param int $submissionId
     * @return SubmissionInterface
     */
    public function getById(int $submissionId);

    /**
     * Retrieve form submissions matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SubmissionSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete form submission.
     *
     * @param SubmissionInterface $submission
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(SubmissionInterface $submission);

    /**
     * Delete form submission by ID.
     *
     * @param int $submissionId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(int $submissionId);
}
