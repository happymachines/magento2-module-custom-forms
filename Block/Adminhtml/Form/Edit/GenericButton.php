<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Block\Adminhtml\Form\Edit;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * GenericButton constructor.
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        RequestInterface $request,
        UrlInterface $urlBuilder
    ) {
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }

    /**
     * Get form id
     *
     * @return int|null
     */
    public function getFormId()
    {
        return $this->request->getParam('form_id');
    }
}
