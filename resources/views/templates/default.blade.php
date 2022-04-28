<html lang="{{ $invoice->lang }}">
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->getName() }}</title>

    <style>
        .positions table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        .positions table th {
            padding: 5px 20px;
            border-bottom: 1px solid #C1CED9;
        }

        .positions table td {
            padding: 5px;
            text-align: right;
            border: 1px solid #C1CED9;
        }

        .summary {
            width: 50%;
            float: right;
            margin-top: 10px;
            font-size: 12px;
        }

        .summary td, th {
            border: 1px solid #C1CED9;
            padding: 0 5px 0 5px;
        }

        .summary tfoot {
            font-weight: bold;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 15px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            text-align: right;
            font-size: 8px;
        }

        .signatures {
            margin-top: 20px;
        }

        .signatures td {
            font-size: 12px;
            text-align: center;
        }

        .signatures p {
            font-weight: bold;
            margin-bottom: 30px;
        }

        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        .header-table td {
            width: 35%;
            width: 35%;
        }

        .header-table td:nth-child(2) {
            text-align: center;
            font-weight: bold;
            font-size: 25px;
        }

        .header-table td:first-child {
            vertical-align: top;
        }

        .header-table td:last-child {
            text-align: right;
            vertical-align: bottom;
        }

        .bg-gray-100 {
            background-color: #f8f9fa;
        }

        .contractor-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            width: 50%;
        }

    </style>
</head>
<body>


<header class="clearfix">

    <table class="header-table">
        <tr>
            <td><img src="{{ $invoice->getLogo() }}" alt="logo" height="170"></td>
            <td>{{ $invoice->getName() }}</td>
            <td class="header-dates">
                <b>{{ __('invoice::invoice.issue_date') }}:</b> 2022-01-01 <br/>
                <b>{{ __('invoice::invoice.payment_due_date') }}:</b> 2022-01-01
            </td>
        </tr>
    </table>

    <table class="contractor-table bg-gray-100">
        <tr>
            <td>
                <b>{{ __('invoice::invoice.seller') }}</b><br/><br/>
                {{ $invoice->getSeller()->name }} <br/>

                @if(isset($invoice->getSeller()->street))
                    {{ $invoice->getSeller()->street }}<br/>
                @endif
                @if(isset($invoice->getSeller()->city))
                    {{ $invoice->getSeller()->postCode }} {{ $invoice->getSeller()->city }}<br/>
                @endif
                @if(isset($invoice->getSeller()->nip))
                    NIP: {{ $invoice->getSeller()->nip }}
                @endif
            </td>
            <td>
                <b>{{ __('invoice::invoice.buyer') }}</b><br/><br/>
                {{ $invoice->getBuyer()->name }} <br/>

                @if(isset($invoice->getBuyer()->street))
                    {{ $invoice->getBuyer()->street }}<br/>
                @endif
                @if(isset($invoice->getBuyer()->city))
                    {{ $invoice->getBuyer()->postCode }} {{ $invoice->getBuyer()->city }}<br/>
                @endif
                @if(isset($invoice->getBuyer()->nip))
                    NIP: {{ $invoice->getBuyer()->nip }}
                @endif
            </td>
        </tr>
    </table>

    <table class="bg-gray-100" style="border: 1px solid #C1CED9; padding: 5px">
        <tr>
            <td>
                Metoda płatności: Przelew <br/>
                Santander nr rachunku: PL09191010482260000000000000
            </td>
        </tr>
    </table>
</header>
<main>
    <div class="positions">
        <table style="width: 100%; font-size: 10px" class="positions-table">
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

    <table class="summary">
        <thead>
        <tr style="background: #f1f1f1">
            <th>Stawka vat</th>
            <th>Netto</th>
            <th>Vat</th>
            <th>Brutto</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->getSubtotals() as $subtotal)
            <tr>
                <td style="text-align: center;">{{$subtotal->taxRate}} %</td>
                <td style="text-align: right;">{{$subtotal->net}}</td>
                <td style="text-align: right;">{{$subtotal->tax}}</td>
                <td style="text-align: right;">{{$subtotal->gross}}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td style="border: none; text-align: right;"></td>
            <td style="text-align: right;">{{ $invoice->getTotalNet() }}</td>
            <td style="text-align: right;">{{ $invoice->getTotalTax() }}</td>
            <td style="text-align: right;">{{ $invoice->getTotalGross() }}</td>
        </tr>
        </tfoot>
    </table>
    <div style="
    clear: both;
    display: table;">

    </div>

    <table style="float: left;">
        <tr>
            <td>
                <b>{{ __('invoice::invoice.amount_to_pay') }}:</b>
                {{ \Zbtrz\Invoice\Services\ReadableAmountService::amountInWords($invoice->getTotalGross(), 'pln', $invoice->lang) }}
            </td>
        </tr>
    </table>
    <div style="
    clear: both;
    display: table;">
    </div>
    <table class="signatures">
        <tr>
            <td>
                <p>Podpis sprzedawcy </p>
                ......................................................................
            </td>

            <td>
                <p>Podpis nabywcy</p>
                ......................................................................
            </td>
        </tr>
    </table>
</main>
<footer>
    <script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "strona {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }



    </script>
</footer>

</body>
</html>






