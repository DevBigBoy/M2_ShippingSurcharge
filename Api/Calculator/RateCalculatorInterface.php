<?php

namespace Market\ShippingSurcharge\Api\Calculator;

use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Shipping\Model\Rate;

interface RateCalculatorInterface
{
    public function calculateRates(Rate\Result $rateRequestResults, AbstractExtensibleModel ...$requestItems) : Rate\Result;
}
