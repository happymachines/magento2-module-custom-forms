<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface SubmissionSearchResultsInterface
 * @package HappyMachines\CustomForms\Api\Data
 */
interface SubmissionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get form submission list.
     *
     * @return SubmissionInterface[]
     */
    public function getItems();

    /**
     * Set form submission list.
     *
     * @param SubmissionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
