<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Uploader
 * @package HappyMachines\CustomForms\Model
 */
class Uploader
{
    const DEFAULT_BASE_PATH = 'customforms/submission/upload';

    /** @var Database $coreFileStorageDatabase */
    protected $coreFileStorageDatabase;

    /** @var Filesystem\Directory\WriteInterface $mediaDirectory */
    protected $mediaDirectory;

    /** @var UploaderFactory $uploaderFactory */
    protected $uploaderFactory;

    /** @var StoreManagerInterface $storeManager */
    protected $storeManager;

    /** @var LoggerInterface $logger */
    protected $logger;

    /** @var string $basePath */
    protected $basePath;

    /** @var string[] $allowedExtensions */
    protected $allowedExtensions;

    /** @var Mime */
    protected $fileMime;

    /**
     * ImageUploader constructor.
     * @param Database $coreFileStorageDatabase
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param Mime $fileMime
     * @param LoggerInterface $logger
     * @param $basePath
     * @param $allowedExtensions
     * @throws FileSystemException
     */
    public function __construct(
        Database $coreFileStorageDatabase,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        Mime $fileMime,
        LoggerInterface $logger,
        $basePath,
        $allowedExtensions
    ) {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->fileMime = $fileMime;
        $this->logger = $logger;
        $this->basePath = $basePath;
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * @param string $basePath
     * @return Uploader
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * @param string[] $allowedExtensions
     * @return Uploader
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;

        return $this;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @return string[]
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    /**
     * @param $path
     * @param $imageName
     * @return string
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    /**
     * @param $fileId
     * @param null $fileIndex
     * @return string[]
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function upload($fileId, $fileIndex = null)
    {
        $basePath = $this->getBasePath();

        /**
         * Used for multi-file uploads
         */
        if (isset($fileIndex)) {
            $fileId .= "[$fileIndex]";
        }

        /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);

        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($basePath));

        if (!$result) {
            throw new LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }

        /**
         * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
         */
        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['path'] = str_replace('\\', '/', $result['path']);
        $result['url'] = $this->storeManager
                ->getStore()
                ->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . $this->getFilePath($basePath, $result['file']);

        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($basePath, '/') . '/' . ltrim($result['file'], '/');
                $result['relative_path'] = $relativePath;
                $file = $result['path'] . $result['file'];
                $result['type'] = $this->fileMime->getMimeType($file);
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedException(
                    __('Something went wrong while saving the file(s).')
                );
            }
        }

        return $result;
    }
}
