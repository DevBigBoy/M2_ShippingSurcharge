<?php

namespace Market\ShippingSurcharge\Model\Quote\Address\Total;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Market\ShippingSurcharge\Api\Calculator\SurchargeCalculatorInterface;
use Market\ShippingSurcharge\Model\Surcharge as SurchargeModel;

class Surcharge extends AbstractTotal
{
    /**
     * @var PriceCurrencyInterface
     */
    private PriceCurrencyInterface $priceCurrency;

    /**
     * @var SurchargeCalculatorInterface
     */
    private SurchargeCalculatorInterface $surchargeCalculator;

    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        SurchargeCalculatorInterface $surchargeCalculator,
    ) {
        $this->setCode(SurchargeModel::SURCHARGE);

        $this->priceCurrency = $priceCurrency;
        $this->surchargeCalculator = $surchargeCalculator;
    }

    public function collect(Quote $quote, ShippingAssignmentInterface $shippingAssignment, Quote\Address\Total $total)
    {
        parent::collect($quote, $shippingAssignment, $total);

        $totalSurcharge = 0;

        foreach ($quote->getAllItems() as $item) {
            $itemSurcharge = $this->surchargeCalculator->calculateSurchargeForItem($item);
            $item->setData(SurchargeModel::SURCHARGE, $itemSurcharge);
            $totalSurcharge += $itemSurcharge;
        }

        $quote->setData(SurchargeModel::SURCHARGE, $totalSurcharge);

        $total->setTotalAmount(SurchargeModel::SURCHARGE, $this->priceCurrency->convert($totalSurcharge, $quote->getStore()));
        $total->setBaseTotalAmount(SurchargeModel::SURCHARGE, $totalSurcharge);

        return $this;
    }

    public function fetch(Quote $quote, Quote\Address\Total $total)
    {
        return [
            'code' => $this->getCode(),
            'title' => $this->getLabel(),
            'value' => $this->loadSurchargeAmount($quote, $total)
        ];
    }

    private function loadSurchargeAmount(Quote $quote, Quote\Address\Total $total)
    {
        if ($total->getTotalAmount(SurchargeModel::SURCHARGE)) {
            $surcharge = $total->getTotalAmount(SurchargeModel::SURCHARGE);
        } elseif ($quote->getData(SurchargeModel::SURCHARGE)) {
            $surcharge = $quote->getData(SurchargeModel::SURCHARGE);
        } else {
            $surcharge = $this->calculateSurcharge($quote);
        }

        return $surcharge;
    }

    private function calculateSurcharge(Quote $quote): float
    {
        return $this->surchargeCalculator->calculateSurchargeForItems(...$quote->getAllItems());
    }

    public function getLabel(): \Magento\Framework\Phrase|string
    {
        return __('Shipping Surcharge');
    }

}
