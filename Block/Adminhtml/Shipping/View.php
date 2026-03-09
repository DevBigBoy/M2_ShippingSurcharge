<?php
declare(strict_types=1);

namespace Market\ShippingSurcharge\Block\Adminhtml\Shipping;

use Magento\Sales\Block\Adminhtml\Order\AbstractOrder;
use Market\ShippingSurcharge\Helper\Data as ConfigInfo;

class View extends AbstractOrder
{
    private ConfigInfo $configInfo;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        ConfigInfo $configInfo,
        array $data = []
    ) {
        $this->configInfo = $configInfo;

        parent::__construct($context, $registry, $adminHelper, $data);
    }

    public function isShippingSurchargeEnabled() : bool
    {
        return $this->configInfo->isFeatureEnabled();
    }
}
