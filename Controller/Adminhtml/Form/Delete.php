<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Controller\Adminhtml\Form;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use HappyMachines\CustomForms\Api\Data\FormInterfaceFactory;
use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Delete form action.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Delete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'HappyMachines_CustomForms::delete_form';

    /**
     * @var FormRepositoryInterface
     */
    protected $formRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param FormRepositoryInterface $formRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        FormRepositoryInterface $formRepository,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->formRepository = $formRepository;
        $this->logger = $logger;
    }

    /**
     * @return Redirect|ResponseInterface|ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('form_id');
        if ($id) {
            try {
                $this->formRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the form.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->logger->critical(__('Unable to delete the form. %1', $e->getMessage()));
                return $resultRedirect->setPath('*/*/edit', ['form_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find the form to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
