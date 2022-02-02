<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\ResourceModel\Form;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Model\Form;
use HappyMachines\CustomForms\Model\ResourceModel\Form as FormResource;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Collection
 * @package HappyMachines\CustomForms\Model\ResourceModel\Form
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'form_id';

    /**
     * Load data for preview flag
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'happymachines_form_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'form_collection';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Collection constructor.
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param StoreManagerInterface $storeManager
     * @param AdapterInterface|null $connection
     * @param AbstractDb|null $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null)
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->storeManager = $storeManager;
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Form::class, FormResource::class);
        $this->_map['fields']['form_id'] = 'main_table.form_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Perform operations after collection load
     *
     * @return $this
     * @throws NoSuchEntityException
     */
    protected function _afterLoad()
    {
        $this->performAfterLoad();
        $this->_previewFlag = false;

        return parent::_afterLoad();
    }

    /**
     * Perform operations before rendering filters
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $this->joinStoreRelationTable('happymachines_custom_form_store', FormInterface::FORM_ID);
    }

    /**
     * Perform operations after collection load
     *
     * @return void
     * @throws NoSuchEntityException
     */
    protected function performAfterLoad()
    {
        $this->addStoresData('happymachines_custom_form_store', FormInterface::FORM_ID);
        $this->addCustomerGroupsData('happymachines_custom_form_customer_group', FormInterface::FORM_ID);
    }

    /**
     * Add field filter to collection
     *
     * @param array|string $field
     * @param string|int|array|null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition, false);
        }
        //todo customer groups filter

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add filter by store
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
            $this->setFlag('store_filter_added', true);
        }

        return $this;
    }

    /**
     * Perform adding filter by store
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return void
     */
    protected function performAddStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = Store::DEFAULT_STORE_ID;
        }

        $this->addFilter('store', ['in' => $store], 'public');
    }

    /**
     * Join store relation table if there is store filter
     *
     * @param string $tableName
     * @param string|null $linkField
     * @return void
     */
    protected function joinStoreRelationTable($tableName, $linkField)
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable($tableName)],
                'main_table.' . $linkField . ' = store_table.' . $linkField,
                []
            )->group(
                'main_table.' . $linkField
            );
        }
        parent::_renderFiltersBefore();
    }

    /**
     * @param $tableName
     * @param $linkField
     * @throws NoSuchEntityException
     */
    protected function addStoresData($tableName, $linkField)
    {
        $linkedIds = $this->getColumnValues($linkField);
        if (count($linkedIds)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['happymachines_custom_form_store' => $this->getTable($tableName)])
                ->where('happymachines_custom_form_store.' . $linkField . ' IN (?)', $linkedIds);
            $result = $connection->fetchAll($select);
            if ($result) {
                $storesData = [];
                foreach ($result as $storeData) {
                    $storesData[$storeData[$linkField]][] = $storeData['store_id'];
                }

                foreach ($this as $item) {
                    $linkedId = $item->getData($linkField);
                    if (!isset($storesData[$linkedId])) {
                        continue;
                    }
                    $storeIdKey = array_search(Store::DEFAULT_STORE_ID, $storesData[$linkedId], true);
                    if ($storeIdKey !== false) {
                        $stores = $this->storeManager->getStores(false, true);
                        $storeId = current($stores)->getId();
                        $storeCode = key($stores);
                    } else {
                        $storeId = current($storesData[$linkedId]);
                        $storeCode = $this->storeManager->getStore($storeId)->getCode();
                    }
                    // Data consumed by @see app/code/HappyMachines/CustomForms/Ui/Component/Listing/Column/FormActions.php
                    $item->setData('_first_store_id', $storeId);
                    $item->setData('store_code', $storeCode);
                    // Assigned joined stores data to collection item, used for stores filters and multiselects
                    $item->setData('stores', $storesData[$linkedId]);
                }
            }
        }
    }

    /**
     * @param $tableName
     * @param $linkField
     */
    protected function addCustomerGroupsData($tableName, $linkField)
    {
        $linkedIds = $this->getColumnValues($linkField);
        if (count($linkedIds)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['happymachines_custom_form_customer_group' => $this->getTable($tableName)])
                ->where('happymachines_custom_form_customer_group.' . $linkField . ' IN (?)', $linkedIds);
            $result = $connection->fetchAll($select);
            if ($result) {
                $customerGroupsData = [];
                foreach ($result as $customerGroup) {
                    $customerGroupsData[$customerGroup[$linkField]][] = $customerGroup['customer_group_id'];
                }

                foreach ($this as $item) {
                    $linkedId = $item->getData($linkField);
                    if (!isset($customerGroupsData[$linkedId])) {
                        continue;
                    }
                    $item->setData('customer_groups', $customerGroupsData[$linkedId]);
                }
            }
        }
    }
}
