<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Controller\Form;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionFileInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionFileInterfaceFactory;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterfaceFactory;
use HappyMachines\CustomForms\Api\FormRepositoryInterface;
use HappyMachines\CustomForms\Api\SubmissionFileRepositoryInterface;
use HappyMachines\CustomForms\Api\SubmissionRepositoryInterface;
use HappyMachines\CustomForms\Model\Submission\Processor\SubmissionProcessorPoolInterface as SubmissionProcessor;
use HappyMachines\CustomForms\Model\Submission\Validator\ValidatorInterface as Validator;
use HappyMachines\CustomForms\Model\Uploader;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Submit
 * @package HappyMachines\CustomForms\Controller\Form
 */
class Submit extends Action implements HttpPostActionInterface
{
    /**
     * @var FormKeyValidator
     */
    private $formKeyValidator;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var SubmissionInterfaceFactory
     */
    private $submissionFactory;

    /**
     * @var SubmissionRepositoryInterface
     */
    private $submissionRepository;

    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var SubmissionProcessor
     */
    private $submissionProcessor;

    /**
     * @var Uploader
     */
    private $uploader;

    /**
     * @var SubmissionFileInterfaceFactory
     */
    private $submissionFileFactory;

    /**
     * @var SubmissionFileRepositoryInterface
     */
    private $submissionFileRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Submit constructor.
     * @param Context $context
     * @param FormKeyValidator $formKeyValidator
     * @param SerializerInterface $serializer
     * @param CustomerSession $customerSession
     * @param CheckoutSession $checkoutSession
     * @param SubmissionInterfaceFactory $submissionFactory
     * @param SubmissionRepositoryInterface $submissionRepository
     * @param FormRepositoryInterface $formRepository
     * @param Validator $validator
     * @param SubmissionProcessor $submissionProcessor
     * @param Uploader $uploader
     * @param SubmissionFileInterfaceFactory $submissionFileFactory
     * @param SubmissionFileRepositoryInterface $submissionFileRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        FormKeyValidator $formKeyValidator,
        SerializerInterface $serializer,
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        SubmissionInterfaceFactory $submissionFactory,
        SubmissionRepositoryInterface $submissionRepository,
        FormRepositoryInterface $formRepository,
        Validator $validator,
        SubmissionProcessor $submissionProcessor,
        Uploader $uploader,
        SubmissionFileInterfaceFactory $submissionFileFactory,
        SubmissionFileRepositoryInterface $submissionFileRepository,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->formKeyValidator = $formKeyValidator;
        $this->serializer = $serializer;
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
        $this->submissionFactory = $submissionFactory;
        $this->submissionRepository = $submissionRepository;
        $this->formRepository = $formRepository;
        $this->validator = $validator;
        $this->submissionProcessor = $submissionProcessor;
        $this->uploader = $uploader;
        $this->submissionFileFactory = $submissionFileFactory;
        $this->submissionFileRepository = $submissionFileRepository;
        $this->logger = $logger;
    }

    /**
     * Form submission execution
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        $formId = $this->getRequest()->getParam('form_id');

        if (isset($formId)) {
            try {
                $this->_eventManager->dispatch('happymachines_customforms_form_submission_before', ['request' => $this->getRequest()]);
                $form = $this->formRepository->getById($formId);
                $submissionData = $this->filterSubmissionDataParams($this->getRequest()->getParams());
                if ($isValid = $this->validateSubmission($form, $submissionData)) {
                    $uploadedFiles = $this->processSubmissionFiles($form);
                    if ($uploadedFiles) {
                        $this->addFileDataToSubmissionData($submissionData, $uploadedFiles);
                    }
                    $data[SubmissionInterface::FORM_ID] = $formId;
                    $data[SubmissionInterface::CUSTOMER_ID] = $this->customerSession->getCustomerId();
                    $data[SubmissionInterface::CUSTOMER_EMAIL] = $this->customerSession->getCustomer()->getEmail();
                    $data[SubmissionInterface::QUOTE_ID] = $this->checkoutSession->getQuoteId();
                    $data[SubmissionInterface::SUBMISSION_DATA] = $this->processSubmissionData($form, $submissionData);
                    $submission = $this->submissionFactory->create();
                    $submission->setData($data);
                    $this->submissionRepository->save($submission);
                    $this->saveSubmissionFiles($submission, $uploadedFiles);
                    $this->messageManager->addComplexSuccessMessage(
                        'happymachinesCustomFormsSubmissionSuccessMessage',
                        [
                            'success_message' => $form->getSubmissionSuccessMessage()
                        ]
                    );
                    $this->_eventManager->dispatch('happymachines_customforms_form_submission_after', ['form' => $form, 'submission' => $submission]);
                }
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage('An error occurred while submitting the form.');
                $this->logger->error($exception->getMessage());
            }
        } else {
            $this->messageManager->addErrorMessage(__('Unable to save form submission, the associated form was not found.'));
        }

        $resultRedirect->setUrl($this->_redirect->getRedirectUrl());
        return $resultRedirect;
    }

    /**
     * @param $submissionData
     * @return mixed
     */
    protected function filterSubmissionDataParams($submissionData)
    {
        $filteredParams = [
            'form_id',
            'form_key',
            'g-recaptcha-response',
            'recaptcha-validate-'
        ];

        foreach ($filteredParams as $param) {
            unset($submissionData[$param]);
        }

        return $submissionData;
    }

    /**
     * @param $form
     * @param $submissionData
     * @return bool
     */
    protected function validateSubmission($form, $submissionData)
    {
        $isValid = false;

        if ($submissionData) {
            $validationResults = $this->validator->validate($form, $submissionData);

            if ($validationResults->isValid()) {
                $isValid = true;
            } else {
                foreach ($validationResults->getErrors() as $error) {
                    $this->messageManager->addErrorMessage($error);
                }
            }
        }

        return $isValid;
    }

    /**
     * Processes the submission data by sending it through the
     * configured submission processor pool.
     * @param FormInterface $form
     * @param $submissionData
     * @return bool|string
     */
    protected function processSubmissionData($form, $submissionData)
    {
        $processedData = [];

        if ($submissionData) {
            $processedData = $this->submissionProcessor->process($form, $submissionData);
        }

        return $this->serializer->serialize($processedData);
    }

    /**
     * Processes the submitted files and uploads them
     * to the media directory
     * @param FormInterface $form
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function processSubmissionFiles($form)
    {
        $uploadedFiles = [];

        if ((array)$files = $this->getRequest()->getFiles()) {
            $formStructure = $form->getFormData();
            $formStructureArray = $this->serializer->unserialize($formStructure);

            foreach ($files as $fileId => $uploadData) {
                $isMultipleUpload = $this->isMultipleFileUpload($uploadData);

                if (!$this->validateFileUpload($uploadData, $isMultipleUpload)) {
                    /**
                     * File was not uploaded
                     */
                    continue;
                }

                $allowedExtensions = $this->getAllowedExtensions($formStructureArray, $fileId);

                if (!$this->uploader->getBasePath()) {
                    $this->uploader->setBasePath(Uploader::DEFAULT_BASE_PATH);
                }

                $this->uploader->setAllowedExtensions($allowedExtensions);

                if ($isMultipleUpload) {
                    foreach ($uploadData as $index => $file) {
                        $uploadedFiles[$fileId][] = $this->uploader->upload($fileId, $index);
                    }
                } else {
                    $uploadedFiles[$fileId] = $this->uploader->upload($fileId);
                }
            }
        }

        return $uploadedFiles;
    }

    /**
     * Handle saving the submitted files and associating
     * them with the submission entity
     * @param SubmissionInterface $submission
     * @param $files
     */
    protected function saveSubmissionFiles($submission, $files)
    {
        foreach ($files as $uploadedFile) {
            try {
                if ($this->isMultipleFileUpload($uploadedFile)) {
                    foreach ($uploadedFile as $file) {
                        $this->saveSubmissionFile($submission, $file);
                    }
                } else {
                    $this->saveSubmissionFile($submission, $uploadedFile);
                }
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage('An error occurred while saving the submitted files.');
                $this->logger->error('Error saving the form submission file: ' . $exception->getMessage());
            }
        }
    }

    /**
     * Persist the submission file to the database
     * @param SubmissionInterface $submission
     * @param $file
     * @return SubmissionFileInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function saveSubmissionFile($submission, $file)
    {
        /** @var SubmissionFileInterface $submissionFile */
        $submissionFile = $this->submissionFileFactory->create();
        $submissionFile->setSubmissionId($submission->getId())
            ->setFormId($submission->getFormId())
            ->setName($file['name'])
            ->setPath($file['relative_path'])
            ->setUrl($file['url'])
            ->setMimeType($file['type'])
            ->setSize($file['size']);
        return $this->submissionFileRepository->save($submissionFile);
    }

    /**
     * Adds data to submission data array of the structure FILE_FIELD_NAME => FILE_NAME(s)
     * @param $submissionData
     * @param $uploadedFiles
     */
    protected function addFileDataToSubmissionData(&$submissionData, $uploadedFiles)
    {
        foreach ($uploadedFiles as $fileId => $uploadedFile) {
            if ($this->isMultipleFileUpload($uploadedFile)) {
                $fileName = implode(', ', array_column($uploadedFile, 'name'));
            } else {
                $fileName = $uploadedFile['name'];
            }
            $submissionData[$fileId] = $fileName;
        }
    }

    /**
     * Gets the allowed extension for the file upload field.
     * If none are specified no restriction is applied.
     * @param $formData
     * @param $fieldName
     * @return array
     */
    protected function getAllowedExtensions($formData, $fieldName)
    {
        $allowedExtensions = [];

        $fileFields = array_filter($formData, function ($field) {
            return isset($field['type']) && $field['type'] === 'file';
        });

        /**
         * Reindex array starting from 0
         */
        $fileFields = array_values($fileFields);

        $fileFieldIndex = array_search($fieldName, array_column($fileFields, 'name'), false);

        if (isset($fileFieldIndex, $fileFields[$fileFieldIndex]['accept'])) {
            $allowedExtensions = $fileFields[$fileFieldIndex]['accept'];
        }

        if (!$allowedExtensions || in_array('true', $allowedExtensions, false)) {
            $allowedExtensions = [];
        } else {
            $allowedExtensions = array_unique($allowedExtensions);

            foreach ($allowedExtensions as &$allowedExtension) {
                $allowedExtension = ltrim($allowedExtension, '.');
            }
        }

        return $allowedExtensions;
    }

    /**
     * Checks if the upload data in the $_FILES request param
     * is multidimensional, indicates multiple files were uploaded
     * @param $uploadData
     * @return bool
     */
    private function isMultipleFileUpload($uploadData)
    {
        $isMultipleUpload = false;

        foreach ($uploadData as $fileData) {
            if (is_array($fileData)) {
                $isMultipleUpload = true;
                break;
            }
        }

        return $isMultipleUpload;
    }

    /**
     * @param $uploadData
     * @param $isMultiple
     * @return bool
     */
    private function validateFileUpload($uploadData, $isMultiple)
    {
        $isValid = false;

        if (isset($uploadData['size']) && $uploadData['size'] > 0) {
            $isValid = true;
        }

        if ($isMultiple) {
            foreach ($uploadData as $file) {
                if (isset($file['size']) && $file['size'] > 0) {
                    $isValid = true;
                }
            }
        }

        return $isValid;
    }
}
