<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Ui\DataProvider\Form;

use HappyMachines\CustomForms\Model\Form;
use HappyMachines\CustomForms\Ui\DataProvider\Form\Metadata\ValueProvider;
use HappyMachines\CustomForms\Model\ResourceModel\Form\CollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var ValueProvider
     */
    protected $metadataValueProvider;

    /**
     * @var
     */
    protected $collection;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param ValueProvider $metadataValueProvider
     * @param array $meta
     * @param array $data
     * @throws LocalizedException
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        ValueProvider $metadataValueProvider,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->collectionFactory = $collectionFactory;
        $this->metadataValueProvider = $metadataValueProvider;
        $meta = array_replace_recursive($this->getMetadataValues(), $meta);
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
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
        /** @var $form Form */
        foreach ($items as $form) {
            $this->loadedData[$form->getId()] = $form->getData();
        }

        return $this->loadedData;
    }

    /**
     * Get metadata values
     * @return array
     * @throws LocalizedException
     */
    protected function getMetadataValues()
    {
        return $this->metadataValueProvider->getMetadataValues();
    }
}
