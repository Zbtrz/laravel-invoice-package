<?php

namespace Zbtrz\Invoice\Classes;


use Exception;

class Contractor
{
    public string $name;

    public string $city;

    public string $street;

    public string $postCode;

    public string $country;

    public int $nip;

    public function name($name): static
    {
        $this->name = $name;

        return $this;
    }

    public function city($city): static
    {
        $this->city = $city;

        return $this;
    }

    public function street($street): static
    {
        $this->street = $street;

        return $this;
    }

    public function postCode($postCode): static
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function country($country): static
    {
        $this->country = $country;

        return $this;
    }

    public function nip($nip): static
    {
        $this->nip = $nip;

        return $this;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function validate(): void
    {
        if (!$this->name) {
            throw new Exception('Contractor name not defined');
        }
    }

}
