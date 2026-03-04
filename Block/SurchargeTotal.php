<?php

namespace Market\ShippingSurcharge\Block;

use Magento\Framework\DataObject;
use Market\ShippingSurcharge\Model\Surcharge as SurchargeModel;

trait SurchargeTotal
{
    public function initTotals()
    {
        $this->initSurcharge();
        return $this;
    }

    private function initSurcharge()
    {
        if (!$this->configInfo->isFeatureEnabled() || !$this->getSource()) {
            return;
        }

        /** @var $parent \Magento\Sales\Block\Adminhtml\Order\Invoice\Totals */
        $parent = $this->getParentBlock();

        $surcharge = new DataObject(
            [
                'code' => SurchargeModel::SURCHARGE,
                'value' => $this->getSource()->getData(SurchargeModel::SURCHARGE),
                'base_value' => $this->getSource()->getData(SurchargeModel::BASE_SURCHARGE),
                'label' => __('Additional Shipping Charge'),
            ]
        );

        $parent->addTotal($surcharge, 'shipping');
    }
}
