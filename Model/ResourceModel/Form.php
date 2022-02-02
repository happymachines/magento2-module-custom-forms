<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\ResourceModel;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Form
 * @package HappyMachines\CustomForms\Model\ResourceModel
 */
class Form extends AbstractDb
{
    /**
     * Store model
     *
     * @var null|Store
     */
    protected $store = null;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param DateTime $dateTime
     * @param EntityManager $entityManager
     * @param MetadataPool $metadataPool
     * @param LoggerInterface $logger
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        DateTime $dateTime,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        LoggerInterface $logger,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->storeManager = $storeManager;
        $this->dateTime = $dateTime;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->metadataPool = $metadataPool;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('happymachines_custom_form', 'form_id');
    }

    /**
     * Perform operations before object save
     *
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if (!$this->getIsUniqueFormToStores($object)) {
            throw new LocalizedException(
                __('A form with the same identifier already exists in the selected store.')
            );
        }
        return $this;
    }

    /**
     * Load an object
     *
     * @param \HappyMachines\CustomForms\Model\Form|AbstractModel $object
     * @param mixed $value
     * @param string $field field to load by (defaults to model id)
     * @return $this
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && $field === null) {
            $field = FormInterface::IDENTIFIER;
        } elseif (!$field) {
            $field = FormInterface::FORM_ID;
        }

        return parent::load($object, $value, $field);
    }

    /**
     * @param AbstractModel $object
     * @return $this|Form
     */
    protected function _afterLoad(AbstractModel $object)
    {
        parent::_afterLoad($object);

        if (!$object->getId()) {
            return $this;
        }

        // load form available in stores
        $object->setStores($this->getStores((int)$object->getId()));

        return $this;
    }

    /**
     * @param AbstractModel $object
     * @return Form
     */
    protected function _afterSave(AbstractModel $object)
    {
        if ($object->hasStores()) {
            $storeIds = $object->getStores();
            $this->processFormRelation(
                $object,
                'happymachines_custom_form_store',
                'form_id',
                'store_id',
                $storeIds
            );
        }

        if ($object->hasCustomerGroups()) {
            $customerGroupIds = $object->getCustomerGroups();
            $this->processFormRelation(
                $object,
                'happymachines_custom_form_customer_group',
                'form_id',
                'customer_group_id',
                $customerGroupIds
            );
        }

        return parent::_afterSave($object);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param AbstractModel $object
     * @return Select
     * @throws LocalizedException
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        $linkField = FormInterface::FORM_ID;

        //@see app/code/HappyMachines/CustomForms/Model/FormRepository::getById
        if ($object->getStores()) {
            $storeIds = [
                Store::DEFAULT_STORE_ID,
                (int)$object->getStores(),
            ];
            $select->join(
                ['happymachines_custom_form_store' => $this->getTable('happymachines_custom_form_store')],
                $this->getMainTable() . '.' . $linkField . ' = happymachines_custom_form_store.' . $linkField,
                []
            )
                ->where('is_active = ?', 1)
                ->where('happymachines_custom_form_store.store_id IN (?)', $storeIds)
                ->order('happymachines_custom_form_store.store_id DESC')
                ->limit(1);
        }

        if ($object->getCustomerGroups()) {
            $customerGroupIds = [
                GroupInterface::NOT_LOGGED_IN_ID,
                (int)$object->getCustomerGroups(),
            ];
            $select->join(
                ['happymachines_custom_form_customer_group' => $this->getTable('happymachines_custom_form_customer_group')],
                $this->getMainTable() . '.' . $linkField . ' = happymachines_custom_form_customer_group.' . $linkField,
                []
            )
                ->where('is_active = ?', 1)
                ->where('happymachines_custom_form_customer_group.customer_group_id IN (?)', $customerGroupIds)
                ->order('happymachines_custom_form_customer_group.customer_group_id DESC')
                ->limit(1);
        }

        return $select;
    }

    /**
     * @param $formId
     * @return array
     */
    public function getStores($formId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getTable('happymachines_custom_form_store'),
            'store_id'
        )->where(
            'form_id = ?',
            $formId
        );
        return $this->getConnection()->fetchCol($select);
    }

    /**
     * Check for unique of identifier of form to selected store(s).
     *
     * @param AbstractModel $object
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsUniqueFormToStores(AbstractModel $object)
    {
        $linkField = FormInterface::FORM_ID;

        $stores = $this->storeManager->isSingleStoreMode()
            ? [Store::DEFAULT_STORE_ID]
            : (array)$object->getData('stores');

        $select = $this->getConnection()->select()
            ->from(['happymachines_custom_form' => $this->getMainTable()])
            ->join(
                ['happymachines_custom_form_store' => $this->getTable('happymachines_custom_form_store')],
                'happymachines_custom_form.' . $linkField . ' = happymachines_custom_form_store.' . $linkField,
                []
            )
            ->where('happymachines_custom_form.identifier = ?  ', $object->getData('identifier'))
            ->where('happymachines_custom_form_store.store_id IN (?)', $stores);

        if ($object->getId()) {
            $select->where('happymachines_custom_form.' . FormInterface::FORM_ID . ' <> ?', $object->getId());
        }

        if ($this->getConnection()->fetchRow($select)) {
            return false;
        }

        return true;
    }

    /**
     * @param AbstractModel $object
     * @param $relatedTableName
     * @param $objectIdColumn
     * @param $relationIdColumn
     * @param $relationData
     * @return $this
     */
    protected function processFormRelation(AbstractModel $object, $relatedTableName, $objectIdColumn, $relationIdColumn, $relationData)
    {
        $connection = $this->getConnection();
        $formId = (int)$object->getId();
        $relatedTable = $this->getTable($relatedTableName);
        $select = $connection->select()->from($relatedTable, [$relationIdColumn])
            ->where("$objectIdColumn = :$objectIdColumn");
        $oldData = $connection->fetchCol($select, [":$objectIdColumn" => $formId]);
        $this->deleteRelationData($objectIdColumn, $formId, $relatedTable, $relationIdColumn, array_diff($oldData, $relationData));

        $insert = [];
        foreach (array_diff($relationData, $oldData) as $newData) {
            $insert[] = [$objectIdColumn => $formId, $relationIdColumn => (int)$newData];
        }
        $this->insertRelationData($relatedTable, $insert);
        return $this;
    }

    /**
     * @param $objectIdColumn
     * @param $objectId
     * @param $relatedTable
     * @param $relationIdColumn
     * @param array $data
     */
    protected function deleteRelationData($objectIdColumn, $objectId, $relatedTable, $relationIdColumn, array $data)
    {
        if (empty($data)) {
            return;
        }
        $connection = $this->getConnection();
        $connection->beginTransaction();
        try {
            $where = ["$objectIdColumn = ?" => $objectId, "$relationIdColumn IN(?)" => $data];
            $connection->delete($relatedTable, $where);
            $connection->commit();
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $connection->rollBack();
        }
    }

    /**
     * @param $table
     * @param array $insert
     */
    protected function insertRelationData($table, array $insert)
    {
        if (empty($insert)) {
            return;
        }
        $connection = $this->getConnection();
        $connection->beginTransaction();
        try {
            $connection->insertMultiple($table, $insert);
            $connection->commit();
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $connection->rollBack();
        }
    }
}
