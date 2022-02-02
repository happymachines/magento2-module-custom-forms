<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Ui\DataProvider\Submission\Listing;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;
use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use HappyMachines\CustomForms\Model\ResourceModel\Submission\Collection;
use HappyMachines\CustomForms\Model\ResourceModel\Submission\CollectionFactory;
use HappyMachines\CustomForms\Ui\DataProvider\AddFilterInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var FormRepositoryInterface
     */
    protected $formRepository;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var AddFilterInterface[]
     */
    protected $additionalFilterPool;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var FormInterface[]
     */
    protected $forms;

    /**
     * @var array [form_id => FormInterface]
     */
    protected $formsIdMap;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     * @param FormRepositoryInterface $formRepository
     * @param FilterBuilder $filterBuilder
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $meta
     * @param array $data
     * @param array $additionalFilterPool
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        UrlInterface $urlBuilder,
        FormRepositoryInterface $formRepository,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $meta = [],
        array $data = [],
        array $additionalFilterPool = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collection = $this->collectionFactory->create();
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
        $this->formRepository = $formRepository;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->additionalFilterPool = $additionalFilterPool;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $collection = $this->getCollection();
        $data['items'] = [];

        if ($this->request->getParam(SubmissionInterface::FORM_ID)) {
            $collection->addFieldToFilter(SubmissionInterface::FORM_ID, $this->request->getParam(SubmissionInterface::FORM_ID));
        }

        $data = $collection->toArray();
        $formIds = $collection->getColumnValues(SubmissionInterface::FORM_ID);
        $this->getForms($formIds);
        $this->getFormsHashMap($this->forms);

        foreach ($data['items'] as $key => $item) {
            if (isset($item['form_id'])) {
                /** Reassigns the column value from the form id to the form name, used as the rendered link text */
                $data['items'][$key][SubmissionInterface::FORM_ID] = $this->getFormName($item[SubmissionInterface::FORM_ID]);
                $data['items'][$key]['link'] = $this->getFormLink($item[SubmissionInterface::FORM_ID]);
            }

            if (!isset($item['customer_email'])) {
                $data['items'][$key]['customer_email'] = __('Guest');
            }
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function addFilter(Filter $filter)
    {
        if (!empty($this->additionalFilterPool[$filter->getField()])) {
            $this->additionalFilterPool[$filter->getField()]->addFilter($this->searchCriteriaBuilder, $filter);
        } else {
            parent::addFilter($filter);
        }
    }

    /**
     * @param $formId
     * @return mixed
     */
    protected function getFormName($formId)
    {
        return $this->formsIdMap[$formId]->getName();
    }

    /**
     * @param $formId
     * @return string
     */
    protected function getFormLink($formId)
    {
        return $this->urlBuilder->getUrl('forms/form/edit/', [SubmissionInterface::FORM_ID => $formId]);
    }

    /**
     * @param $formIds
     * @return array|FormInterface[]
     */
    protected function getForms($formIds)
    {
        if (!$this->forms) {
            $searchCriteria = $this->searchCriteriaBuilder->create();

            $formIdFilter = $this->filterBuilder
                ->setField(SubmissionInterface::FORM_ID)
                ->setValue($formIds)
                ->setConditionType('in')
                ->create();

            $formIdFilterGroup = $this->filterGroupBuilder->addFilter($formIdFilter)->create();

            $searchCriteria->setFilterGroups([$formIdFilterGroup]);

            try {
                $forms = $this->formRepository->getList($searchCriteria);
                $this->forms = $forms->getItems() ?? [];
            } catch (\Exception $exception) {
                $this->forms = [];
            }
        }

        return $this->forms;
    }

    /**
     * Builds a form hash map of the structure
     * [
     *  form_id => FormInterface,
     *  form_id => FormInterface,
     *  form_id => FormInterface,
     *  ...
     * ]
     * @param FormInterface[] $forms
     * @return array
     */
    protected function getFormsHashMap($forms)
    {
        if (!$this->formsIdMap) {
            $map = [];

            foreach ($forms as $form) {
                $map[$form->getFormId()] = $form;
            }

            $this->formsIdMap = $map;
        }

        return $this->formsIdMap;
    }
}
