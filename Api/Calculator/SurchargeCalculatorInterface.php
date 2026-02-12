<?php

namespace Market\ShippingSurcharge\Api\Calculator;

use Magento\Framework\Model\AbstractExtensibleModel;

interface SurchargeCalculatorInterface
{
    public function calculateSurchargeForItems(AbstractExtensibleModel ...$items) : float;

    public function calculateSurchargeForItem(AbstractExtensibleModel $item) : float;
}
