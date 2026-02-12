<?php

namespace Market\ShippingSurcharge\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public const SYSTEM_CONFIG_BASE_PATH = 'shipping_surcharge';

    public function getModuleConfig(string $section, string $field) : string
    {
        return (string) $this->scopeConfig->getValue(
            sprintf('%s/%s/%s', self::SYSTEM_CONFIG_BASE_PATH, $section, $field),
            ScopeInterface::SCOPE_STORE
        );
    }

    public function isFeatureEnabled() : bool
    {
        return (bool) $this->getModuleConfig('general', 'enabled');
    }
}
