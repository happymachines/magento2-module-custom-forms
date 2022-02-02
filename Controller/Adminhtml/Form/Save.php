<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Controller\Adminhtml\Form;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\FormInterfaceFactory;
use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Save form action.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'HappyMachines_CustomForms::save_form';

    /**
     * @var FormInterfaceFactory
     */
    protected $formFactory;

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
     * @param FormInterfaceFactory $formFactory
     * @param FormRepositoryInterface $formRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        FormInterfaceFactory $formFactory,
        FormRepositoryInterface $formRepository,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->formFactory = $formFactory;
        $this->formRepository = $formRepository;
        $this->logger = $logger;
    }

    /**
     * @return Redirect|ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = FormInterface::STATUS_ENABLED;
            }
            if (empty($data['form_id'])) {
                $data['form_id'] = null;
            }

            /** @var FormInterface $model */
            $model = $this->formFactory->create();

            $id = $this->getRequest()->getParam('form_id');
            if ($id) {
                try {
                    $model = $this->formRepository->getById($id);
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(__('This form no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->_eventManager->dispatch(
                    'happymachines_customforms_form_prepare_save',
                    ['form' => $model, 'request' => $this->getRequest()]
                );

                $this->formRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the form.'));
                return $this->processResultRedirect($model, $resultRedirect, $data);
            } catch (LocalizedException $e) {
                $this->logger->critical(__('Unable to save the form entity. %1', $e->getMessage()));
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Throwable $e) {
                $this->logger->critical(__('Unable to save the form entity. %1', $e->getMessage()));
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the form.'));
            }

            return $resultRedirect->setPath('*/*/edit', ['form_id' => $this->getRequest()->getParam('form_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process result redirect
     *
     * @param FormInterface $model
     * @param Redirect $resultRedirect
     * @param array $data
     * @return Redirect
     * @throws LocalizedException
     */
    private function processResultRedirect($model, $resultRedirect, $data)
    {
        if ($this->getRequest()->getParam('back', false) === 'duplicate') {
            $newForm = $this->formFactory->create(['data' => $data]);
            $newForm->setId(null);
            $newForm->setIsActive(false);
            $this->formRepository->save($newForm);
            $this->messageManager->addSuccessMessage(__('You duplicated the form.'));
            return $resultRedirect->setPath(
                '*/*/edit',
                [
                    'form_id' => $newForm->getId(),
                    '_current' => true
                ]
            );
        }
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath('*/*/edit', ['form_id' => $model->getId(), '_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
