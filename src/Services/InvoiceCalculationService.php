<?php

namespace Zbtrz\Invoice\Services;

class InvoiceCalculationService
{
    public static function applyQuantity(float $unitPrice, float $quantity, int $decimals): float
    {
        return round($unitPrice * $quantity, $decimals);
    }

    public static function getTaxFromNet(float $amount, int $taxRate, int $decimals): float
    {
        return round($amount * $taxRate / 100, 2);
    }

    public static function getTaxFromGross(float $amount, int $taxRate, int $decimals): float
    {
        return round($amount * $taxRate / (100 + $taxRate), $decimals);
    }
}
