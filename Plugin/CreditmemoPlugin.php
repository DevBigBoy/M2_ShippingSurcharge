<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Market\ShippingSurcharge\Plugin;

use Market\ShippingSurcharge\Model\Surcharge as SurchargeModel;

class CreditmemoPlugin
{
    public function beforeCreateByInvoice(\Magento\Sales\Model\Order\CreditmemoFactory $context, \Magento\Sales\Model\Order\Invoice $invoice, array $data)
    {
        if (isset($data[SurchargeModel::SURCHARGE_REQUESTED_REFUND]) && $data[SurchargeModel::SURCHARGE_REQUESTED_REFUND]) {
            $invoice->setData(SurchargeModel::SURCHARGE_REQUESTED_REFUND, $data[SurchargeModel::SURCHARGE_REQUESTED_REFUND]);
        }

        return [$invoice, $data];
    }

    public function beforeCreateByOrder(\Magento\Sales\Model\Order\CreditmemoFactory $context, \Magento\Sales\Model\Order $order, array $data = [])
    {
        if (isset($data[SurchargeModel::SURCHARGE_REQUESTED_REFUND]) && $data[SurchargeModel::SURCHARGE_REQUESTED_REFUND]) {
            $order->setData(SurchargeModel::SURCHARGE_REQUESTED_REFUND, $data[SurchargeModel::SURCHARGE_REQUESTED_REFUND]);
        }

        return [$order, $data];
    }
}
