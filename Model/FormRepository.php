<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use HappyMachines\CustomForms\Model\ResourceModel\Form as FormResource;
use HappyMachines\CustomForms\Model\ResourceModel\Form\Collection;
use HappyMachines\CustomForms\Model\ResourceModel\Form\CollectionFactory as FormCollectionFactory;
use HappyMachines\CustomForms\Api\Data\FormInterfaceFactory as FormFactory;
use HappyMachines\CustomForms\Api\Data\FormSearchResultsInterfaceFactory;
use HappyMachines\CustomForms\Api\Data\FormSearchResultsInterface;
use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use HappyMachines\CustomForms\Api\Data\FormInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class FormRepository
 * @package HappyMachines\CustomForms\Model
 */
class FormRepository implements FormRepositoryInterface
{

    /**
     * @var FormResource
     */
    protected $formResource;

    /**
     * @var \HappyMachines\CustomForms\Model\FormFactory
     */
    protected $formFactory;

    /**
     * @var FormSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var FormCollectionFactory
     */
    protected $formCollectionFactory;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * FormRepository constructor.
     * @param FormResource $formResource
     * @param FormFactory $formFactory
     * @param FormCollectionFactory $formCollectionFactory
     * @param FormSearchResultsInterfaceFactory $searchResultsFactory
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param LoggerInterface $logger
     */
    public function __construct(
        FormResource $formResource,
        FormFactory $formFactory,
        FormCollectionFactory $formCollectionFactory,
        FormSearchResultsInterfaceFactory $searchResultsFactory,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        LoggerInterface $logger
    ) {
        $this->formResource = $formResource;
        $this->formFactory = $formFactory;
        $this->formCollectionFactory = $formCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->logger = $logger;
    }

    /**
     * Save Form data
     *
     * @param FormInterface|Form $form
     * @return Form
     * @throws CouldNotSaveException|NoSuchEntityException
     */
    public function save(FormInterface $form)
    {
        if ($form->getStores() === null) {
            $storeId = $this->storeManager->getStore()->getId();
            $stores = [$storeId];
            $form->setStores($stores);
        }
        try {
            $this->formResource->save($form);
        } catch (\Exception $exception) {
            $this->logger->critical(__('Unable to save the form entity. %1', $exception->getMessage()));
            throw new CouldNotSaveException(
                __('Could not save the form: %1', $exception->getMessage()),
                $exception
            );
        }
        return $form;
    }

    /**
     * Load Form data by given Form Identity
     *
     * @param string $formId
     * @param null $storeId
     * @param null $customerGroupId
     * @return FormInterface
     * @throws NoSuchEntityException
     */
    public function getById($formId, $storeId = null, $customerGroupId = null)
    {
        $form = $this->formFactory->create();

        if ($storeId !== null) {
            $form->setData('stores', $storeId);
        }

        if ($customerGroupId !== null) {
            $form->setData('customer_groups', $customerGroupId);
        }

        $this->formResource->load($form, $formId);

        if (!$form->getId()) {
            throw new NoSuchEntityException(__('The form with the "%1" ID doesn\'t exist.', $formId));
        }

        return $form;
    }

    /**
     * Load form data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteriaInterface $criteria
     * @return FormSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var Collection $collection */
        $collection = $this->formCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var FormSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param FormInterface $form
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(FormInterface $form)
    {
        try {
            $this->formResource->delete($form);
        } catch (\Exception $exception) {
            $this->logger->critical(__('Unable to delete the form entity. %1', $exception->getMessage()));
            throw new CouldNotDeleteException(
                __('Could not delete the form: %1', $exception->getMessage())
            );
        }
        return true;
    }

    /**
     * @param int $formId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($formId)
    {
        return $this->delete($this->getById($formId));
    }
}
