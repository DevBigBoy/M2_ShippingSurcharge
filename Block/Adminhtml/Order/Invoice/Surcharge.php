<?php

namespace Market\ShippingSurcharge\Block\Adminhtml\Order\Invoice;

use Market\ShippingSurcharge\Helper\Data;

class Surcharge extends \Magento\Sales\Block\Adminhtml\Order\Invoice\Totals
{
    use \Market\ShippingSurcharge\Block\SurchargeTotal;

    private Data $configInfo;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        Data $configInfo,
        array $data = []
    ) {
        $this->configInfo = $configInfo;
        parent::__construct($context, $registry, $adminHelper, $data);
    }
}
