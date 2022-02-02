<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Ui\DataProvider\Submission;

use HappyMachines\CustomForms\Model\ResourceModel\Submission\CollectionFactory;
use HappyMachines\CustomForms\Model\Submission;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $this->collection = $this->collectionFactory->create();
        $items = $this->collection->getItems();
        /** @var $submission Submission */
        foreach ($items as $submission) {
            $this->loadedData[$submission->getId()] = $submission->getData();
        }

        return $this->loadedData;
    }
}
