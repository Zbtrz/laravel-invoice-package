<?php


use Illuminate\Support\Facades\Route;

Route::get('/example_invoice', function () {
    return view('invoice::example-invoice');
});
