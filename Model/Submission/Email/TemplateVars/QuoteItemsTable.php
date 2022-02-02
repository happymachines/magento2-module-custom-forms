<?php

declare(strict_types=1);

namespace HappyMachines\CustomForms\Model\Submission\Email\TemplateVars;

use HappyMachines\CustomForms\Api\Data\FormInterface;
use HappyMachines\CustomForms\Api\Data\SubmissionInterface;
use HappyMachines\CustomForms\Model\Submission\Email\TemplateVarInterface;
use Magento\Framework\Escaper;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Catalog\Helper\Image as CatalogImageHelper;
use Psr\Log\LoggerInterface;

/**
 * Class QuoteItemsTable
 * @package HappyMachines\CustomForms\Model\Submission\Email\TemplateVars
 */
class QuoteItemsTable implements TemplateVarInterface
{
    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var CatalogImageHelper
     */
    private $catalogImageHelper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SubmittedDataTable constructor.
     * @param Escaper $escaper
     * @param CartRepositoryInterface $cartRepository
     * @param CatalogImageHelper $catalogImageHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        Escaper $escaper,
        CartRepositoryInterface $cartRepository,
        CatalogImageHelper $catalogImageHelper,
        LoggerInterface $logger
    ) {
        $this->escaper = $escaper;
        $this->cartRepository = $cartRepository;
        $this->catalogImageHelper = $catalogImageHelper;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getTemplateVar(FormInterface $form, SubmissionInterface $submission)
    {
        try {
            $quote = $this->cartRepository->get($submission->getQuoteId());
        } catch (\Exception $exception) {
            $this->logger->critical(__('Unable to load the quote while generating the quote items table template var. %1', $exception->getMessage()));
            return null;
        }

        $quoteItemsTable = "<table class='happymachines-submitted-data'>";

        if ($quote->getItemsCount() && $quoteItems = $quote->getAllVisibleItems()) {
            /** @var Item $quoteItem */
            foreach ($quoteItems as $quoteItem) {
                $product = $quoteItem->getProduct();

                if (!$product) {
                    continue;
                }
                $productName = $this->escaper->escapeHtml($product->getName());
                $productUrl = $this->escaper->escapeUrl($product->getUrlInStore());
                $productPrice = $product->getFormattedPrice();
                $parentSku = $this->escaper->escapeHtml($product->getData('parent_sku'));
                $itemQuantity = $this->escaper->escapeHtml($quoteItem->getQty());
                $productImageUrl = $this->escaper->escapeUrl($this->catalogImageHelper->init($product, 'product_thumbnail_image')
                    ->constrainOnly(true)
                    ->keepAspectRatio(true)
                    ->keepTransparency(true)
                    ->keepFrame(false)
                    ->resize(100, 100)
                    ->getUrl());
                $imageAlt = $this->escaper->escapeHtmlAttr($product->getName());

                $quoteItemsTable .= "<tr class='happymachines-submitted-data-row'>";

                if ($productName) {
                    $quoteItemsTable .= "<td class='happymachines-submitted-data-name'>$productName</td>";
                }

                $quoteItemsTable .= "<td class='happymachines-submitted-data-value'>";

                //todo less verbose
                if ($productImageUrl) {
                    $quoteItemsTable .= "<img class='admin__control-thumbnail product-thumbnail' src='$productImageUrl' alt='$imageAlt' /><br>";
                }
                if ($productUrl) {
                    $quoteItemsTable .= "<a target='_blank' href='$productUrl'>$productName</a><br>";
                }
                if ($productPrice) {
                    $quoteItemsTable .= __('Price: ') . "$productPrice <br>";
                }
                if ($parentSku) {
                    $quoteItemsTable .= __('Parent Sku: ') . "$parentSku <br>";
                }
                if ($itemQuantity) {
                    $quoteItemsTable .= __('Qty: ') . "$itemQuantity <br>";
                }

                $quoteItemsTable .= "</td></tr>";
            }
        }

        $quoteItemsTable .= "</table>";

        return $quoteItemsTable;
    }
}
