<?php
/**
 * @by SwiftOtter, Inc., 2/2/17
 * @website https://swiftotter.com
 **/

namespace Market\ShippingSurcharge\Block\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product as CatalogProduct;
use Magento\Framework\View\Element\Template;
use Market\ShippingSurcharge\Block\ItemSurcharge;

class Catalog extends ItemSurcharge
{
    /** @var CatalogProduct */
    private CatalogProduct $product;

    /** @var ProductRepositoryInterface */
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        Template\Context $context,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Cms\Api\BlockRepositoryInterface $blockRepository,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        array $data = []
    ) {
        parent::__construct($context, $priceCurrency, $blockRepository, $filterProvider, $data);

        $this->surchargeLabel = 'Additional Shipping Charge';
        $this->productRepository = $productRepository;
        $this->product = $this->loadProduct();
    }

    public function hasSurcharge(): bool
    {
        return (bool) $this->product->getData('shipping_surcharge');
    }

    public function getSurcharge(): string
    {
        $surcharge = $this->product->getData('shipping_surcharge');

        if ($surcharge) {
            $surcharge = $this->formatSurcharge((float)$surcharge);
        }

        return (string) $surcharge;
    }

    private function loadProduct(): \Magento\Catalog\Api\Data\ProductInterface
    {
        return $this->productRepository->getById($this->getProductIdFromRequest());
    }

    private function getProductIdFromRequest(): int
    {
        return $this->getRequest()->getParam('id');
    }
}
