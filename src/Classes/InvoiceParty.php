<?php

namespace Zbtrz\Invoice\Classes;


use Exception;

class InvoiceParty
{
    public string $name;

    private string $city;

    private string $street;

    private string $postalCode;

    private string $country;

    private int $nip;

    public function name($name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function validate(): void
    {
        if (!$this->name) {
            throw new Exception('Invoice position title not defined');
        }
    }
}
