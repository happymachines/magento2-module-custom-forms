<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Ui\Component\Listing\Column;

use Magento\Cms\Block\Adminhtml\Page\Grid\Renderer\Action\UrlBuilder;
use Magento\Cms\ViewModel\Page\Grid\UrlBuilder as FrontendViewUrlBuilder;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Actions
 * @package HappyMachines\CustomForms\Ui\Component\Listing\Column
 */
class Actions extends Column
{
    /**
     * @var UrlBuilder
     */
    protected $actionUrlBuilder;

    /**
     * @var FrontendViewUrlBuilder
     */
    protected $scopeUrlBuilder;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var array
     */
    protected $actions;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlBuilder $actionUrlBuilder
     * @param UrlInterface $urlBuilder
     * @param Escaper $escaper
     * @param FrontendViewUrlBuilder $scopeUrlBuilder
     * @param array $components
     * @param array $data
     * @param array $actions
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlBuilder $actionUrlBuilder,
        UrlInterface $urlBuilder,
        Escaper $escaper,
        FrontendViewUrlBuilder $scopeUrlBuilder,
        array $components = [],
        array $data = [],
        array $actions = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->actionUrlBuilder = $actionUrlBuilder;
        $this->escaper = $escaper;
        $this->scopeUrlBuilder = $scopeUrlBuilder;
        $this->actions = $actions;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $this->prepareItemActions($item);
            }
        }

        return $dataSource;
    }

    /**
     * @param $item
     */
    protected function prepareItemActions(&$item)
    {
        /** The name of the actions ui component $actionsName */
        $actionsName = $this->getData('name');
        foreach ($this->actions as $action => $actionData) {
            if (isset($item[$actionData['idField']])) {
                $item[$actionsName][$action] = [
                    'href' => $this->urlBuilder->getUrl($actionData['actionPath'], [$actionData['param'] => $item[$actionData['param']]]),
                    'label' => __($actionData['label']),
                    '__disableTmpl' => true,
                ];
                if (isset($actionData['confirm'])) {
                    $entityName = $this->escaper->escapeHtml($item[$actionData['nameField']]);
                    $confirmData = [
                        'confirm' => [
                            'title' => isset($actionData['confirm']['title']) ? __($actionData['confirm']['title'], $entityName) : '',
                            'message' => isset($actionData['confirm']['message']) ? __($actionData['confirm']['message'], $entityName) : '',
                            '__disableTmpl' => true,
                        ],
                        'post' => true,
                        '__disableTmpl' => true,
                    ];

                    $item[$actionsName][$action] = array_merge($item[$actionsName][$action], $confirmData);
                }
            }
        }
    }
}
