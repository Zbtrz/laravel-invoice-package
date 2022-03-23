<?php

namespace Zbtrz\Invoice;

use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Zbtrz\Invoice\Traits\InvoiceHelper;

/**
 * Class Invoices.
 */
class Invoice
{
    use InvoiceHelper;

    public string $name;

    private string $template;

    public mixed $pdf;

    public Collection $invoicePositions;

    public string $output;

    public string $filename;

    public function __construct($name = '')
    {
        // Invoice
        $this->name = $name ?: 'Invoice';
        $this->invoicePositions = Collection::make([]);
        $this->template = 'default';

        $this->filename = 'filename';
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

        $view = View::make('invoice::templates.'. $this->template, ['invoice' => $this]);
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $this->pdf = Pdf::setOptions(['enable_php' => true])->loadHtml($html);
        $this->output = $this->pdf->download();

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
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $this->filename . '"',
            'Content-Length'      => strlen($this->output),
        ]);
    }
}
