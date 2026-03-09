<?php

namespace Market\ShippingSurcharge\Block\Adminhtml\Order;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Helper\Admin;
use Market\ShippingSurcharge\Block\SurchargeTotal;
use Market\ShippingSurcharge\Helper\Data;

class Surcharge extends \Magento\Sales\Block\Adminhtml\Order\Totals
{
    use SurchargeTotal;

    private Data $configInfo;

    public function __construct(
        Context $context,
        Registry $registry,
        Admin $adminHelper,
        Data $configInfo,
        array $data = []
    ) {
        $this->configInfo = $configInfo;
        parent::__construct($context, $registry, $adminHelper, $data);
    }
}
