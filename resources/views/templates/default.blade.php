<html lang="en"><head>
    <meta charset="utf-8">
    <title>{{ $invoice->getName() }}</title>

    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        body {
            position: relative;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-family: Arial;
        }
        #seller {
            float: left;
            width: 50%;
        }
        #buyer {
            float: left;
            width: 50%;
        }
        header {
            padding: 10px 0;
            margin-bottom: 30px;
        }
        h1 {
            border-top: 1px solid  #5D6975;
            border-bottom: 1px solid  #5D6975;
            color: #5D6975;
            text-align: center;
            margin: 0 0 20px 0;
        }
        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 20px;
        }
        .positions table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }
        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
        }
        table td {
            padding: 10px;
            text-align: right;
        }
        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: right;
        }
    </style>
</head>
<body>
<header class="clearfix">

    <h1>{{ $invoice->getName() }}</h1>
    <div id="seller">
        <h2>Sprzedawca</h2>
        <div>{{ $invoice->getSeller()->name }}</div>
    </div>

    <div id="buyer">
        <h2>Nabywca</h2>
        <div>{{ $invoice->getBuyer()->name }}</div>
    </div>
</header>
<main>

    <div class="positions">
        <table style="width: 100%;">
            <thead>
            <tr>
                <th>Nazwa pozycji</th>
                <th>Cena jednostkowa</th>
                <th>Stawka Vat</th>
                <th>Wartosc netto</th>
                <th>Vat</th>
                <th>Wartosc brutto</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoice->getInvoicePositions() as $position)
                <tr>
                    <td>{{ $position->title }}</td>
                    <td style="text-align: right">{{ $position->pricePerUnit }}</td>
                    <td>{{ $position->taxRate }} %</td>
                    <td style="text-align: right">{{ $position->priceNet }}</td>
                    <td style="text-align: right">{{ $position->tax }}</td>
                    <td style="text-align: right">{{ $position->priceGross }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <table style="width: 50%; float: right; margin-top: 20px">
        <thead>
        <tr>
            <th>Stawka vat</th>
            <th>Netto</th>
            <th>Vat</th>
            <th>Brutto</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->getSubtotals() as $subtotal)
            <tr>
                <td>{{$subtotal->taxRate}} %</td>
                <td style="text-align: right;">{{$subtotal->net}}</td>
                <td style="text-align: right;">{{$subtotal->tax}}</td>
                <td style="text-align: right;">{{$subtotal->gross}}</td>
            </tr>
        @endforeach
        <tr>
            <td style="border: none; text-align: right;"> Razem:</td>
            <td style="text-align: right;">{{ $invoice->getTotalNet() }}</td>
            <td style="text-align: right;">{{ $invoice->getTotalTax() }}</td>
            <td style="text-align: right;">{{ $invoice->getTotalGross() }}</td>
        </tr>
        </tbody>
    </table>

</main>

<footer>
    strona 1/1
</footer>

</body></html>

{{--<!DOCTYPE html>--}}
{{--<html lang="pl">--}}
{{--<head>--}}
{{--    <title>{{ $invoice->getName() }}</title>--}}
{{--    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>--}}

{{--    <style>--}}
{{--        table, th, td {--}}
{{--            border: 1px solid;--}}
{{--        }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body>--}}
{{--<h1 style="text-align: center">{{ $invoice->getName() }}</h1>--}}


{{--<h2 style="text-align: left">Pozycje</h2>--}}

{{--<div>--}}
{{--    <div style="width: 50%;">--}}
{{--        <h4>Sprzedawca</h4>--}}
{{--        <table style="width: 100%">--}}

{{--            <tr>--}}
{{--                <td>--}}
{{--                    bb--}}
{{--                </td>--}}
{{--            </tr>--}}

{{--        </table>--}}
{{--    </div>--}}
{{--    <div style="width: 50%;">--}}
{{--        <h4>Nabywca</h4>--}}
{{--        <table style="width: 100%">--}}
{{--            <tr>--}}
{{--                <td>--}}

{{--                    aa--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        </table>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div>--}}
{{--    <table style="width: 100%;">--}}
{{--        <thead>--}}
{{--        <tr>--}}
{{--            <th>Nazwa pozycji</th>--}}
{{--            <th>Cena jednostkowa</th>--}}
{{--            <th>Stawka Vat</th>--}}
{{--            <th>Wartosc netto</th>--}}
{{--            <th>Vat</th>--}}
{{--            <th>Wartosc brutto</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--        @foreach($invoice->getInvoicePositions() as $position)--}}
{{--            <tr>--}}
{{--                <td>{{ $position->title }}</td>--}}
{{--                <td style="text-align: right">{{ $position->pricePerUnit }}</td>--}}
{{--                <td>{{ $position->taxRate }} %</td>--}}
{{--                <td style="text-align: right">{{ $position->priceNet }}</td>--}}
{{--                <td style="text-align: right">{{ $position->tax }}</td>--}}
{{--                <td style="text-align: right">{{ $position->priceGross }}</td>--}}
{{--            </tr>--}}
{{--        @endforeach--}}
{{--        </tbody>--}}
{{--    </table>--}}

{{--    <table style="width: 50%; float: right; margin-top: 20px">--}}
{{--        <thead>--}}
{{--        <tr>--}}
{{--            <th>Stawka vat</th>--}}
{{--            <th>Netto</th>--}}
{{--            <th>Vat</th>--}}
{{--            <th>Brutto</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--        @foreach($invoice->getSubtotals() as $subtotal)--}}
{{--            <tr>--}}
{{--                <td>{{$subtotal->taxRate}} %</td>--}}
{{--                <td style="text-align: right;">{{$subtotal->net}}</td>--}}
{{--                <td style="text-align: right;">{{$subtotal->tax}}</td>--}}
{{--                <td style="text-align: right;">{{$subtotal->gross}}</td>--}}
{{--            </tr>--}}
{{--        @endforeach--}}
{{--        <tr>--}}
{{--            <td style="border: none; text-align: right;"> Razem:</td>--}}
{{--            <td style="text-align: right;">{{ $invoice->getTotalNet() }}</td>--}}
{{--            <td style="text-align: right;">{{ $invoice->getTotalTax() }}</td>--}}
{{--            <td style="text-align: right;">{{ $invoice->getTotalGross() }}</td>--}}
{{--        </tr>--}}
{{--        </tbody>--}}
{{--    </table>--}}
{{--</div>--}}
{{--</body>--}}
{{--</html>--}}





