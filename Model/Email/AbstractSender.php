<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Email;

use HappyMachines\CustomForms\Model\Email\Transport\EmailTransport;
use HappyMachines\CustomForms\Model\Settings;
use HappyMachines\CustomForms\Model\Submission\Email\Attachments;
use HappyMachines\CustomForms\Model\Submission\Email\TemplateVarPool;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractSender
 * @package HappyMachines\CustomForms\Model\Email
 */
abstract class AbstractSender implements SenderInterface
{
    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var EmailTransport
     */
    protected $emailTransport;

    /**
     * @var TemplateVarPool
     */
    protected $templateVarPool;

    /**
     * @var Attachments
     */
    protected $attachments;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AbstractSender constructor.
     * @param Settings $settings
     * @param EmailTransport $emailTransport
     * @param TemplateVarPool $templateVarPool
     * @param Attachments $attachments
     * @param LoggerInterface $logger
     */
    public function __construct(
        Settings $settings,
        EmailTransport $emailTransport,
        TemplateVarPool $templateVarPool,
        Attachments $attachments,
        LoggerInterface $logger
    ) {
        $this->settings = $settings;
        $this->emailTransport = $emailTransport;
        $this->templateVarPool = $templateVarPool;
        $this->attachments = $attachments;
        $this->logger = $logger;
    }
}
