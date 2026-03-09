<?php

namespace Market\ShippingSurcharge\Block\Order;


use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Block\Order\Totals;
use Market\ShippingSurcharge\Block\SurchargeTotal;
use Market\ShippingSurcharge\Helper\Data;

class Surcharge extends Totals
{
    use SurchargeTotal;

    private Data $configInfo;

    public function __construct(
        Context $context,
        Registry $registry,
        Data $configInfo,
        array $data = []
    ) {
        parent::__construct($context, $registry, $data);
        $this->configInfo = $configInfo;
    }
}
