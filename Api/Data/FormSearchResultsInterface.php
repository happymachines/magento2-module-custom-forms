<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface FormSearchResultsInterface
 * @package HappyMachines\CustomForms\Api\Data
 */
interface FormSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get forms list.
     *
     * @return FormInterface[]
     */
    public function getItems();

    /**
     * Set forms list.
     *
     * @param FormInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
