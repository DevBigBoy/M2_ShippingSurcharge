<?php

declare(strict_types=1);

namespace Market\ShippingSurcharge\Block\Product\Cart;

use Magento\Quote\Model\Quote\Item as QuoteItem;
use Market\ShippingSurcharge\Block\ItemSurcharge;

class Item extends ItemSurcharge
{
    /**
     * @var QuoteItem
     */
    private QuoteItem $quoteItem;

    protected $surchargeLabel = 'Additional Shipping Charge';

    public function hasSurcharge(): bool
    {
        return (bool) $this->quoteItem->getProduct()->getData('shipping_surcharge');
    }

    public function getSurcharge(): string
    {
        return $this->formatSurcharge($this->quoteItem->getProduct()->getData('shipping_surcharge') * $this->quoteItem->getQty());
    }

    public function setQuoteItem(QuoteItem $quoteItem): Item
    {
        $this->quoteItem = $quoteItem;

        return $this;
    }
}
