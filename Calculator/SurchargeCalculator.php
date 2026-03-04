<?php

namespace Market\ShippingSurcharge\Calculator;

use Magento\Framework\Model\AbstractExtensibleModel;
use Market\ShippingSurcharge\Api\Calculator\SurchargeCalculatorInterface;

class SurchargeCalculator implements SurchargeCalculatorInterface
{
    public function calculateSurchargeForItems(AbstractExtensibleModel ...$items) : float
    {
        return (float) array_reduce($items, function ($acc, \Magento\Quote\Model\Quote\Item $item) {
            return $acc + $this->calculateSurchargeForItem($item);
        }, 0);
    }

    public function calculateSurchargeForItem(AbstractExtensibleModel $item) : float
    {
        return (float) $item->getProduct()->getData('shipping_surcharge') * ($item->getQty() ?? $item->getQtyOrdered() ?? 1);
    }
}
