<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Ui\DataProvider\SubmissionFile\Listing;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionFileInterface;
use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use HappyMachines\CustomForms\Model\ResourceModel\SubmissionFile\Collection;
use HappyMachines\CustomForms\Model\ResourceModel\SubmissionFile\CollectionFactory;
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
    const BYTE_UNITS = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
    const BYTE_PRECISION = [0, 0, 1, 2, 2, 3, 3, 4, 4];
    const BYTE_NEXT = 1024;

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
     * @var FormRepositoryInterface
     */
    protected $formRepository;

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
        $this->prepareUpdateUrl();
    }

    /**
     * @return void
     */
    protected function prepareUpdateUrl()
    {
        if (!isset($this->data['config']['filter_url_params'])) {
            return;
        }
        foreach ($this->data['config']['filter_url_params'] as $paramName => $paramValue) {
            if ('*' == $paramValue) {
                $paramValue = $this->request->getParam($paramName);
            }
            if ($paramValue) {
                $this->data['config']['update_url'] = sprintf(
                    '%s%s/%s/',
                    $this->data['config']['update_url'],
                    $paramName,
                    $paramValue
                );
                $this->addFilter(
                    $this->filterBuilder->setField($paramName)->setValue($paramValue)->setConditionType('eq')->create()
                );
            }
        }
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

        if ($this->request->getParam(SubmissionFileInterface::SUBMISSION_ID)) {
            $collection->addFieldToFilter(SubmissionFileInterface::SUBMISSION_ID, $this->request->getParam(SubmissionFileInterface::SUBMISSION_ID));
        }

        $data = $collection->toArray();
        $formIds = $collection->getColumnValues(SubmissionFileInterface::FORM_ID);
        $this->getForms($formIds);
        $this->getFormsHashMap($this->forms);

        foreach ($data['items'] as $key => $item) {
            if (isset($item[SubmissionFileInterface::SUBMISSION_ID])) {
                $data['items'][$key]['link'] = $this->getSubmissionLink($item[SubmissionFileInterface::SUBMISSION_ID]);
            }

            if (isset($item['form_id'])) {
                /** Reassigns the column value from the form id to the form name, used as the rendered link text */
                $data['items'][$key][SubmissionFileInterface::FORM_ID] = $this->getFormName($item[SubmissionFileInterface::FORM_ID]);
            }

            if (isset($item[SubmissionFileInterface::SIZE])) {
                $data['items'][$key]['size'] = $this->getReadableFileSize($item[SubmissionFileInterface::SIZE]);
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
     * @param $submissionId
     * @return string
     */
    protected function getSubmissionLink($submissionId)
    {
        return $this->urlBuilder->getUrl('forms/submission/edit/', [SubmissionFileInterface::SUBMISSION_ID => $submissionId]);
    }

    /**
     * @param $bytes
     * @param null $precision
     * @return string
     */
    public function getReadableFileSize($bytes, $precision = null)
    {
        for ($i = 0; ($bytes / self::BYTE_NEXT) >= 0.9 && $i < count(self::BYTE_UNITS); $i++) {
            $bytes /= self::BYTE_NEXT;
        }

        return round($bytes, is_null($precision) ? self::BYTE_PRECISION[$i] : $precision) . self::BYTE_UNITS[$i];
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
                ->setField(SubmissionFileInterface::FORM_ID)
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

    /**
     * @param $formId
     * @return mixed
     */
    protected function getFormName($formId)
    {
        return $this->formsIdMap[$formId]->getName();
    }
}
