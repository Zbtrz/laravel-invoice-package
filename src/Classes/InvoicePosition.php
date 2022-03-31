<?php

namespace Zbtrz\Invoice\Classes;

use Exception;

class InvoicePosition
{

    private string $title;

    private float $quantity;
    private float $tax;

    public function __construct()
    {
        $this->quantity = 1.0;
        $this->discount = 0.0;
        $this->tax      = 0.0;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function validate()
    {
    }
}
