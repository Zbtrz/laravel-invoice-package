<?php

namespace Zbtrz\Invoice;

use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Zbtrz\Invoice\Classes\Buyer;
use Zbtrz\Invoice\Classes\InvoicePosition;
use Zbtrz\Invoice\Classes\InvoiceSubtotal;
use Zbtrz\Invoice\Classes\Seller;
use Zbtrz\Invoice\Traits\InvoiceFields;

/**
 * Class Invoices.
 */
class Invoice
{
    use InvoiceFields;

    const CALCULATION_METHOD_FROM_NET = 1;
    const CALCULATION_METHOD_FROM_GROSS = 2;

    const CALCULATION_MODE_PER_POSITION = 1;
    const CALCULATION_MODE_PER_VAT_RATE = 2;

    private Collection $invoicePositions;

    private Collection $invoiceTotals;

    private Seller $seller;

    private Buyer $buyer;

    private mixed $pdf;

    private string $name;

    private string $template = 'default';

    private string $output;

    private string $filename = 'filename';

    private float $totalNet;

    private float $totalTax;

    private float $totalGross;

    private int $currencyDecimals = 2;

    private int $calculationMethod = self::CALCULATION_METHOD_FROM_NET;

    private int $calculationMode = self::CALCULATION_MODE_PER_POSITION;

    public function __construct($name)
    {
        $this->name = $name ?? 'Invoice';
        $this->invoicePositions = Collection::make([]);
        $this->invoiceTotals = Collection::make([]);
    }

    public static function create($name = ''): static
    {
        return new static($name);
    }

    /**
     * @throws Exception
     */
    public function render(): static
    {
        if (isset($this->pdf)) {
            return $this;
        }

        $this->beforeRender();

        $view = View::make('invoice::templates.' . $this->template, ['invoice' => $this]);
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $this->pdf = Pdf::setOptions(['enable_php' => true])->loadHtml($html);
        $this->output = $this->pdf->download();

        return $this;
    }

    /**
     * @throws Exception
     */
    public function addPosition(InvoicePosition $position): static
    {
        $position->validate();

        $this->invoicePositions->push($position);

        return $this;
    }

    public function seller($seller): static
    {
        $this->seller = $seller;

        return $this;
    }

    public function buyer($buyer): static
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSeller(): Seller
    {
        return $this->seller;
    }

    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    public function getInvoicePositions(): Collection
    {
        return $this->invoicePositions;
    }

    public function getSubtotals(): Collection
    {
        return $this->invoiceTotals;
    }

    public function getTotalNet(): float
    {
        return $this->totalNet;
    }

    public function getTotalTax(): float
    {
        return $this->totalTax;
    }

    public function getTotalGross(): float
    {
        return $this->totalGross;
    }

    /**
     * @throws Exception
     */
    public function addPositions($positions): static
    {
        foreach ($positions as $position) {
            $this->addPosition($position);
        }

        return $this;
    }

    public function calculateFromNet(): static
    {
        $this->calculationMethod = self::CALCULATION_METHOD_FROM_NET;

        return $this;
    }

    public function calculateFromGross(): static
    {
        $this->calculationMethod = self::CALCULATION_METHOD_FROM_GROSS;

        return $this;
    }

    public function calculatePerPosition(): static
    {

        $this->calculationMode = self::CALCULATION_MODE_PER_POSITION;

        return $this;
    }

    public function calculatePerVatRate(): static
    {
        $this->calculationMode = self::CALCULATION_MODE_PER_VAT_RATE;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function stream(): Response
    {
        $this->render();

        return new Response($this->output, Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $this->filename . '"',
        ]);
    }

    /**
     * @throws Exception
     */
    public function download(): Response
    {
        $this->render();

        return new Response($this->output, Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $this->filename . '"',
            'Content-Length' => strlen($this->output),
        ]);
    }

    /**
     * @throws Exception
     */
    protected function beforeRender(): void
    {
        $this->validate();
        $this->calculate();
    }

    /**
     * @throws Exception
     */
    protected function validate(): void
    {
        if (!count($this->invoicePositions)) {
            throw new Exception('No invoice positions defined.');
        }

        if (!isset($this->buyer)) {
            throw new Exception('Buyer not defined.');
        }

        if (!isset($this->seller)) {
            throw new Exception('Seller not defined.');
        }

        $this->buyer->validate();
        $this->seller->validate();
    }

    private function calculate(): void
    {
        $this->calculatePositions();
        $this->calculateTotals();
    }

    private function calculatePositions(): void
    {
        $this->invoicePositions->each(function (InvoicePosition $position) {
            $position->calculate($this->calculationMethod, $this->currencyDecimals);
        });
    }

    private function calculateTotals(): void
    {
        foreach ($this->invoicePositions->groupBy('taxRate') as $taxRate => $positions) {
            $invoiceSubtotal = new InvoiceSubtotal();
            $invoiceSubtotal->taxRate = $taxRate;

            if ($this->calculationMode == self::CALCULATION_MODE_PER_POSITION) {
                $invoiceSubtotal->net = $positions->sum('priceNet');
                $invoiceSubtotal->tax = $positions->sum('tax');
                $invoiceSubtotal->gross = $positions->sum('priceGross');
            } else {
                $invoiceSubtotal->calculate($positions, $this->calculationMethod);
            }

            $this->invoiceTotals->push($invoiceSubtotal);
        }

        $this->totalNet = $this->invoiceTotals->sum('net');
        $this->totalTax = $this->invoiceTotals->sum('tax');
        $this->totalGross = $this->invoiceTotals->sum('gross');
    }
}
