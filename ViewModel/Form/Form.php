<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\ViewModel\Form;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Form
 * @package HappyMachines\CustomForms\ViewModel
 */
class Form implements ArgumentInterface
{
    const FORM_SUBMIT_ACTION_URL = 'forms/form/submit';

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var FilterProvider
     */
    private $filterProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Form constructor.
     * @param FormInterface $form
     * @param UrlInterface $urlBuilder
     * @param FilterProvider $filterProvider
     * @param LoggerInterface $logger
     */
    public function __construct(
        FormInterface $form,
        UrlInterface $urlBuilder,
        FilterProvider $filterProvider,
        LoggerInterface $logger
    ) {
        $this->form = $form;
        $this->urlBuilder = $urlBuilder;
        $this->filterProvider = $filterProvider;
        $this->logger = $logger;
    }

    /**
     * Pass method calls through the view model to the Form class,
     * allows access to the Form class methods.
     *
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->form->$method(...$args);
    }

    /**
     * @return string
     */
    public function getFormActionUrl()
    {
        return $this->urlBuilder->getUrl(self::FORM_SUBMIT_ACTION_URL);
    }

    /**
     * @param $content
     * @return string
     * @throws \Exception
     */
    public function filterContent($content)
    {
        return $this->filterProvider->getBlockFilter()->filter($content);
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getFormSubmitButtonText()
    {
        $defaultText = 'Submit';
        $buttonText = $this->form->getSubmissionButtonText() ?? $defaultText;

        return __($buttonText);
    }
}
