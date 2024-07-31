<?php

use App\Models\Currency;
use App\Models\CurrencyHistory;
use Illuminate\Support\Facades\Cache;

if (!function_exists('generate_code_from_name')) {
    /**
     * Generate code from a given name.
     *
     * @param string $name
     * @return string
     */
    function generate_code_from_name($name)
    {
        $code = strtolower($name);
        $code = str_replace([' ', 'ç', 'ğ', 'ı', 'ö', 'ş', 'ü', 'Ç', 'Ğ', 'İ', 'Ö', 'Ş', 'Ü'], ['-', 'c', 'g', 'i', 'o', 's', 'u', 'c', 'g', 'i', 'o', 's', 'u'], $code);
        $code = preg_replace('/[^A-Za-z0-9\-]/', '', $code);
        $code = preg_replace('/-+/', '-', $code);
        return $code;
    }

}


if (!function_exists('exchange_rate')) {
    function exchange_rate($amount, $from, $to = null, $date = null)
    {
        // günlük cache yapılabilir
        $cacheKey = 'exchange_rate_' . $from . '_' . $to . '_' . $date;
        $rate = Cache::get($cacheKey);
        if ($rate) {
            return $amount * $rate;
        }

        if ($from == $to) {
            return $amount;
        }

        if (!$date) {
            $date = date('Y-m-d');
        }

        $currency = Currency::where('code', $from)->first();
        $defaultCurrency = Currency::where('is_default', true)->first();

        if (!$currency) {
            throw new \Exception('Currency not found');
        }

        if (!$defaultCurrency) {
            throw new \Exception('Default currency not found');
        }

        $currencyHistory = CurrencyHistory::where('currency_id', $currency->id)
            ->where('default_currency_id', $defaultCurrency->id)
            ->where('date', $date)
            ->first();

        if (!$currencyHistory) {
            $currencyHistory = save_currency_history($currency, $defaultCurrency, $date);
        }

        $defaultAmount = $amount * $currencyHistory->rate;

        if(!$to || $to == $defaultCurrency->code) {
            Cache::put($cacheKey, $defaultAmount / $amount, 60 * 24);
            return $defaultAmount;
        }

        $currency = Currency::where('code', $to)->first();

        $currencyHistory = CurrencyHistory::where('currency_id', $currency->id)
            ->where('default_currency_id', $defaultCurrency->id)
            ->where('date', $date)
            ->first();

        if (!$currencyHistory) {
            $currencyHistory = save_currency_history($currency, $defaultCurrency, $date);
        }

        $amountWithNewCurrency = $defaultAmount / $currencyHistory->rate;

        Cache::put($cacheKey, $amountWithNewCurrency / $amount, 60 * 24);

        return $amountWithNewCurrency;
    }
}


if (!function_exists('save_currency_history')) {
    function save_currency_history($currency, $defaultCurrency, $date)
    {
        $formattedDate = date('dmY', strtotime($date));
        $yearMonth = date('Ym', strtotime($date));


        $response = file_get_contents("https://www.tcmb.gov.tr/kurlar/" . $yearMonth . "/" . $formattedDate . ".xml?");
        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        $array = json_decode($json, true);
        $currencyHistory = new CurrencyHistory();
        $currencyHistory->currency_id = $currency->id;
        $currencyHistory->default_currency_id = $defaultCurrency->id;
        $currencyHistory->date = $date;

        $currencyRate = 1;
        $defaultCurrencyRate = 1;

        foreach ($array['Currency'] as $arr) {
            if ($arr['@attributes']['CurrencyCode'] == $currency->code) {
                $currencyRate = $arr['ForexSelling'];
            }
            if ($arr['@attributes']['CurrencyCode'] == $defaultCurrency->code) {
                $defaultCurrency = $arr['ForexSelling'];
            }
        }

        $currencyHistory->rate = $currencyRate / $defaultCurrencyRate;
        $currencyHistory->save();

        return $currencyHistory;
    }

    if(!function_exists('currency_map')) {
        function currency_map($currency) {
            $map = [
                'TL' => 'TRY',
                'US' => 'USD',
                'EU' => 'EUR',
            ];

            return $map[$currency] ?? $currency;
        }

    }
}
