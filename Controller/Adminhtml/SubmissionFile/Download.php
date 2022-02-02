<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Controller\Adminhtml\SubmissionFile;

use HappyMachines\CustomForms\Api\SubmissionFileRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Response\Http\FileFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Psr\Log\LoggerInterface;

/**
 * Class Download
 * @package HappyMachines\CustomForms\Controller\Adminhtml\SubmissionFile
 */
class Download extends Action
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var SubmissionFileRepositoryInterface
     */
    protected $submissionFileRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Download constructor.
     * @param Action\Context $context
     * @param Filesystem $filesystem
     * @param FileFactory $fileFactory
     * @param SubmissionFileRepositoryInterface $submissionFileRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        Filesystem $filesystem,
        FileFactory $fileFactory,
        SubmissionFileRepositoryInterface $submissionFileRepository,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->filesystem = $filesystem;
        $this->fileFactory = $fileFactory;
        $this->submissionFileRepository = $submissionFileRepository;
        $this->logger = $logger;
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $request = $this->getRequest();
        $data = $request->getParams();

        if (isset($data['file_id'])) {
            $readDir = $this->filesystem->getDirectoryRead(DirectoryList::PUB);
            try {
                $submissionFile = $this->submissionFileRepository->getById((int)$data['file_id']);
                $filePath = DirectoryList::MEDIA . '/' . $submissionFile->getPath();
                $file = $readDir->openFile($filePath);
                $contents = $file->readAll();
                $file->close();
            } catch (FileSystemException $e) {
                $this->messageManager->addErrorMessage(__('Unable to read file. Please try again'));
                $this->logger->critical(__('Unable to download the form submission file. %1', $e->getMessage()));
                return $this->_redirect($this->getRedirect());
            }
            return $this->fileFactory->create(
                  $submissionFile->getName(),
                  $contents ?? '',
                  DirectoryList::MEDIA
                );
        }

        $this->messageManager->addErrorMessage(__('Could not download file. Please try again'));
        return $this->_redirect($this->getRedirect());
    }

    /**
     * @return string
     */
    private function getRedirect()
    {
        $urlParts = parse_url($this->_redirect->getRefererUrl());
        $urlPath = $urlParts['path'];
        $urlPath = str_replace('admin/', '', $urlPath);
        $urlPath = trim($urlPath, '/');
        /** Get action path including any parameters, excluding the url key */
        $urlPath = substr($urlPath, 0, strpos($urlPath, '/key'));
        $actionParts = explode('/', $urlPath);
        /** Extracts array values into variables */
        [$frontName, $actionPath, $action] = $actionParts;

        $redirect = "{$frontName}/{$actionPath}/{$action}";

        if (count($actionParts) > 3) {
            [$param, $value] = array_slice($actionParts, 3);
            $redirect = $this->getUrl($redirect, [$param => $value]);
        } else {
            $redirect = $this->getUrl($redirect);
        }

        return $redirect;
    }
}
