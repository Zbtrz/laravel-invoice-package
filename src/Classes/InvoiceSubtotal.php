<?php

namespace Zbtrz\Invoice\Classes;


use Illuminate\Support\Collection;
use Zbtrz\Invoice\Invoice;
use Zbtrz\Invoice\Services\InvoiceCalculationService;

class InvoiceSubtotal
{
    public float $net;

    public float $tax;

    public float $gross;

    public int $taxRate;

    public function calculate(Collection $positions, int $calculationMethod): void
    {
        if ($calculationMethod == Invoice::CALCULATION_METHOD_FROM_NET) {
            $this->calculateFromNet($positions);
        } else {
            $this->calculateFromGross($positions);
        }
    }

    public function calculateFromNet($positions): void
    {
        $this->net = $positions->sum('priceNet');
        $this->tax = InvoiceCalculationService::getTaxFromNet($this->net, $this->taxRate, 2);
        $this->gross = $this->net + $this->tax;
    }

    public function calculateFromGross($positions): void
    {
        $this->gross = $positions->sum('priceGross');
        $this->tax = InvoiceCalculationService::getTaxFromGross($this->gross, $this->taxRate, 2);
        $this->net = $this->gross - $this->tax;
    }
}
