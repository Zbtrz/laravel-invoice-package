<?php

namespace Zbtrz\Invoice\Services;

use NumberFormatter;

class ReadableAmountService
{
    public static function amountInWords(float $amount, string $currency, string $lang): string
    {
        list($int,$dec) = explode('.', number_format($amount, 2));

        $formatter = new NumberFormatter($lang, NumberFormatter::SPELLOUT);

        if ($lang == 'pl') {
            $transChoiceInt = ReadableAmountService::getTransChoiceForPolishLang($int);
            $transChoiceDec = ReadableAmountService::getTransChoiceForPolishLang($dec);
        }

        $readableInt = $formatter->format($int) . ' ' . trans_choice("invoice::invoice.int_$currency", $transChoiceInt ?? $int);
        $readableDec = $formatter->format($dec) . ' ' . trans_choice("invoice::invoice.dec_$currency", $transChoiceDec ?? $dec);

        return $readableInt . ' ' . __('invoice::invoice.and') . ' ' . $readableDec;
    }

    private static function getTransChoiceForPolishLang($num): int
    {
        $unity = (int) substr($num,-1);
        $rest = $num % 100;

        if ($num == 1) {
            return 0;
        }
        if(($unity > 1 && $unity < 5) &! ($rest > 10 && $rest < 20)) {
            return 1;
        }

        return 2;
    }
}
