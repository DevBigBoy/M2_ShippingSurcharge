<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Market\ShippingSurcharge\Plugin;

use Market\ShippingSurcharge\Model\Surcharge as SurchargeModel;

class ItemPlugin
{
    public function afterCalcRowTotal(\Magento\Sales\Model\Order\Creditmemo\Item $item)
    {
        $orderItem = $item->getOrderItem();
        $itemQty = $item->getQty();

        if ($orderItem->getData(SurchargeModel::SURCHARGE) && $itemQty > 0) {
            $item->setData(SurchargeModel::SURCHARGE, $this->calculateSurchargeFrom($orderItem, $itemQty));
            $item->setData(SurchargeModel::BASE_SURCHARGE, $this->calculateSurchargeFrom($orderItem, $itemQty, SurchargeModel::BASE_SURCHARGE));
        }
    }

    private function calculateSurchargeFrom(\Magento\Sales\Model\Order\Item $orderItem, $itemQty, $key = SurchargeModel::SURCHARGE)
    {
        return (int) ceil(($orderItem->getData($key) / $orderItem->getQtyOrdered()) * $itemQty);
    }
}
