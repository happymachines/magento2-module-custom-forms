<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Controller\Adminhtml\Form;

use HappyMachines\CustomForms\Controller\Adminhtml\AbstractMassDelete;
use HappyMachines\CustomForms\Model\ResourceModel\Form\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Delete form mass action.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class MassDelete extends AbstractMassDelete
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'HappyMachines_CustomForms::delete_form';

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $filter);
    }

    /**
     * @return AbstractDb
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }
}
