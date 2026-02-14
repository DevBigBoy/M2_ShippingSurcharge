<?php

namespace Market\ShippingSurcharge\Observer;

use Magento\Framework\Event\Observer as Event;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Quote\Model\Quote;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Market\ShippingSurcharge\Api\Calculator\SurchargeCalculatorInterface;
use Market\ShippingSurcharge\Helper\Data;
use Market\ShippingSurcharge\Model\Surcharge;

class QuoteSubmit implements ObserverInterface
{
    private OrderRepositoryInterface $orderRepository;
    private Data $configInfo;
    private SurchargeCalculatorInterface $surchargeCalculator;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        Data $configInfo,
        SurchargeCalculatorInterface $surchargeCalculator
    ) {
        $this->orderRepository = $orderRepository;
        $this->configInfo = $configInfo;
        $this->surchargeCalculator = $surchargeCalculator;
    }

    public function execute(Event $observer): void
    {
        if ($this->configInfo->isFeatureEnabled()) {
            $quote = $observer->getData('quote');
            $order = $observer->getData('order');

            $this->calculateQuoteTotals($quote);
            $this->calculateOrderTotals($order);
        }
    }

    private function calculateTotalsFromItems(AbstractExtensibleModel ...$items) : float
    {
        return array_reduce($items, function (float $acc, AbstractExtensibleModel $item) {
            $itemTotal = $this->surchargeCalculator->calculateSurchargeForItem($item);

            $item->setData(Surcharge::BASE_SURCHARGE, $itemTotal);
            $item->setData(Surcharge::SURCHARGE, $itemTotal);

            return $acc + $itemTotal;
        }, 0);
    }

    private function calculateQuoteTotals(Quote $quote): void
    {
        $shippingSurcharge = $this->surchargeCalculator->calculateSurchargeForItems(...$quote->getAllItems());

        $quote->setData(Surcharge::SURCHARGE, $shippingSurcharge);
    }

    private function calculateOrderTotals(Order $order): void
    {
        $shippingSurcharge = $this->calculateTotalsFromItems(...$order->getAllItems());

        $order->setData(Surcharge::BASE_SURCHARGE, $shippingSurcharge);
        $order->setData(Surcharge::SURCHARGE, $shippingSurcharge);

        $this->orderRepository->save($order);
    }
}
