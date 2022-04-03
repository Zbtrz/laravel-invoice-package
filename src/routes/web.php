<?php


use Illuminate\Support\Facades\Route;
use Zbtrz\Invoice\Classes\Buyer;
use Zbtrz\Invoice\Classes\InvoicePosition;
use Zbtrz\Invoice\Classes\Seller;
use Zbtrz\Invoice\Invoice;

Route::get('/example_invoice', function () {
    $positions = [];

//        $positions[] = (new InvoicePosition())->title('Pozycja 1')->pricePerUnit(5.48)->quantity(9)->taxRate(23);
//        $positions[] = (new InvoicePosition())->title('Pozycja 2')->pricePerUnit(3.98)->quantity(4)->taxRate(8);
//        $positions[] = (new InvoicePosition())->title('Pozycja 3')->pricePerUnit(8.98)->quantity(2)->taxRate(23);
//        $positions[] = (new InvoicePosition())->title('Pozycja 4')->pricePerUnit(1.99)->quantity(5)->taxRate(23);
//        $positions[] = (new InvoicePosition())->title('Pozycja 5')->pricePerUnit(2.96)->quantity(3)->taxRate(23);
//        $positions[] = (new InvoicePosition())->title('Pozycja 6')->pricePerUnit(8.77)->quantity(1)->taxRate(8);

    $positions[] = (new InvoicePosition())->title('Pozycja 1')->pricePerUnit(6.74)->quantity(9)->taxRate(23);
    $positions[] = (new InvoicePosition())->title('Pozycja 2')->pricePerUnit(4.30)->quantity(4)->taxRate(8);
    $positions[] = (new InvoicePosition())->title('Pozycja 3')->pricePerUnit(11.05)->quantity(2)->taxRate(23);
    $positions[] = (new InvoicePosition())->title('Pozycja 4')->pricePerUnit(2.45)->quantity(5)->taxRate(23);
    $positions[] = (new InvoicePosition())->title('Pozycja 5')->pricePerUnit(3.64)->quantity(3)->taxRate(23);
    $positions[] = (new InvoicePosition())->title('Pozycja 6')->pricePerUnit(9.47)->quantity(1)->taxRate(8);

    return Invoice::create('Faktura VAT')
        ->addPositions($positions)
        ->buyer((new Buyer())->name('sprzedawca'))
        ->seller((new Seller())->name('nabywca'))
        ->stream();
});
