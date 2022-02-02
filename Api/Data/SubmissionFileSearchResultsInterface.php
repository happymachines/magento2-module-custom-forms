<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface SubmissionFileSearchResultsInterface
 * @package HappyMachines\CustomForms\Api\Data
 */
interface SubmissionFileSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get form submission files list.
     *
     * @return SubmissionInterface[]
     */
    public function getItems();

    /**
     * Set form submission files list.
     *
     * @param SubmissionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
