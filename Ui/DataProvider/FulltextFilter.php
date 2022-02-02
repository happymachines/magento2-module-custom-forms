<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Ui\DataProvider;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;

/**
 * Class FulltextFilter
 * @package HappyMachines\CustomForms\Ui\DataProvider
 */
class FulltextFilter implements AddFilterInterface
{
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var string
     */
    private $field;

    /**
     * @param FilterBuilder $filterBuilder
     * @param string $field
     */
    public function __construct(
        FilterBuilder $filterBuilder,
        string $field = 'name'
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->field = $field;
    }

    /**
     * @inheritdoc
     */
    public function addFilter(SearchCriteriaBuilder $searchCriteriaBuilder, Filter $filter)
    {
        $titleFilter = $this->filterBuilder->setField($this->field)
            ->setValue(sprintf('%%%s%%', $filter->getValue()))
            ->setConditionType('like')
            ->create();
        $searchCriteriaBuilder->addFilter($titleFilter);
    }
}
