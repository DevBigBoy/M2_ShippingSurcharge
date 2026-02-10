<?php

namespace Market\ShippingSurcharge\Api\Block\Product;

interface ShippingSurchargeAmountInterface
{
    public function hasSurcharge(): bool;
    public function getSurcharge(): string;
    public function getSurchargeLabel(): string;
    public function getSurchargeNote(): string;
}
