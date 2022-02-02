<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Email\Transport;

use HappyMachines\CustomForms\Api\Data\SubmissionFileInterface;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class EmailTransport
 * @package HappyMachines\CustomForms\Model
 */
class EmailTransport
{
    /**
     * @var TransportBuilderFactory
     */
    private $transportBuilderFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @var DriverInterface
     */
    private $fileSystemDriver;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * EmailTransport constructor.
     * @param TransportBuilderFactory $transportBuilderFactory
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param DriverInterface $fileSystemDriver
     * @param LoggerInterface $logger
     */
    public function __construct(
        TransportBuilderFactory $transportBuilderFactory,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        DriverInterface $fileSystemDriver,
        LoggerInterface $logger
    ) {
        $this->transportBuilderFactory = $transportBuilderFactory;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->fileSystemDriver = $fileSystemDriver;
        $this->logger = $logger;
    }

    /**
     * @param $recipient
     * @param $sender
     * @param $template
     * @param string $replyTo
     * @param array $templateVars
     * @param SubmissionFileInterface[] $attachments
     * @return $this
     * @throws MailException
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function sendMail($recipient, $sender, $template, $replyTo = '', $templateVars = [], $attachments = [])
    {
        $this->inlineTranslation->suspend();

        $templateVars = array_merge($templateVars, ['store' => $this->getStore()]);

        $transport = $this->transportBuilderFactory->create();

        if ($attachments) {
            foreach ($attachments as $attachment) {
                $attachmentContent = $this->fileSystemDriver->fileGetContents($attachment->getUrl());
                $transport->addAttachment($attachmentContent, $attachment->getName(), $attachment->getMimeType());
            }
        }

        $transport->setTemplateIdentifier(
            $template
        )->setTemplateOptions(
            [
                'area' => Area::AREA_FRONTEND,
                'store' => $this->getStoreId()
            ]
        )->setTemplateVars(
            $templateVars
        )->setFromByScope(
            $sender
        )->addTo(
            $recipient
        );

        if ($replyTo) {
            $transport->setReplyTo($replyTo);
        }

        try {
            $transport->getTransport()->sendMessage();
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }
        $this->inlineTranslation->resume();

        return $this;
    }

    /**
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getStore()
    {
        return $this->storeManager->getStore();
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}
