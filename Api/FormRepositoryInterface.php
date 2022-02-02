<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Api;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\FormSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface FormRepositoryInterface
 * @package HappyMachines\CustomForms\Api
 */
interface FormRepositoryInterface
{
    /**
     * Save form.
     *
     * @param FormInterface $form
     * @return FormInterface
     * @throws LocalizedException
     */
    public function save(FormInterface $form);

    /**
     * Retrieve form.
     *
     * @param int $formId
     * @param null $storeId
     * @param null $customerGroupId
     * @return FormInterface
     */
    public function getById($formId, $storeId = null, $customerGroupId = null);

    /**
     * Retrieve pages matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return FormSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete form.
     *
     * @param FormInterface $form
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(FormInterface $form);

    /**
     * Delete form by ID.
     *
     * @param int $formId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(int $formId);
}
