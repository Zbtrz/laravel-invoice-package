<?php

namespace Zbtrz\Invoice\Classes;

use Exception;
use Zbtrz\Invoice\Invoice;
use Zbtrz\Invoice\Services\InvoiceCalculationService;

class InvoicePosition
{
    public int $taxRate = 0;

    public float $quantity = 1;

    public float $pricePerUnit;

    public float $priceNet = 0;

    public float $priceGross = 0;

    public float $tax = 0;

    public string $title;

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function quantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function pricePerUnit(float $pricePerUnit): static
    {
        $this->pricePerUnit = $pricePerUnit;

        return $this;
    }

    public function taxRate(int $taxRate): static
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    public function calculate($calculationMethod, $currencyDecimals): void
    {
        if ($calculationMethod == Invoice::CALCULATION_METHOD_FROM_GROSS) {
            $this->calculateFromGross($currencyDecimals);
        } else {
            $this->calculateFromNet($currencyDecimals);
        }
    }

    private function calculateFromNet($currencyDecimals): void
    {
        $this->priceNet = InvoiceCalculationService::applyQuantity(
            $this->pricePerUnit,
            $this->quantity,
            $currencyDecimals
        );

        if ($this->taxRate) {
            $this->tax = InvoiceCalculationService::getTaxFromNet(
                $this->priceNet,
                $this->taxRate,
                $currencyDecimals
            );
        }

        $this->priceGross = $this->priceNet + $this->tax;
    }

    private function calculateFromGross($currencyDecimals): void
    {
        $this->priceGross = InvoiceCalculationService::applyQuantity(
            $this->pricePerUnit,
            $this->quantity,
            $currencyDecimals
        );

        if ($this->taxRate) {
            $this->tax = InvoiceCalculationService::getTaxFromGross(
                $this->priceGross,
                $this->taxRate,
                $currencyDecimals
            );
        }

        $this->priceNet = $this->priceGross - $this->tax;
    }

    /**
     * @throws Exception
     */
    public function validate(): void
    {
        if (!isset($this->title) || !$this->title) {
            throw new Exception('Invoice position title not defined');
        }

        if (!isset($this->pricePerUnit) || !$this->pricePerUnit) {
            throw new Exception('Invoice position price per item not defined');
        }
    }
}
