<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Api\SearchCriteria\CollectionProcessor\FilterProcessor;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use HappyMachines\CustomForms\Model\ResourceModel\Form\Collection;

/**
 * Class FormStoreFilter
 * @package HappyMachines\CustomForms\Model\Api\SearchCriteria\CollectionProcessor\FilterProcessor
 */
class FormStoreFilter implements CustomFilterInterface
{
    /**
     * Apply custom store filter to collection
     *
     * @param Filter $filter
     * @param AbstractDb $collection
     * @return bool
     */
    public function apply(Filter $filter, AbstractDb $collection)
    {
        /** @var Collection $collection */
        $collection->addStoreFilter($filter->getValue(), false);

        return true;
    }
}
