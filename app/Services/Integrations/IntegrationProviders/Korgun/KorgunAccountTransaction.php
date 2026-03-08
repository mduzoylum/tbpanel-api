<?php

namespace App\Services\Integrations\IntegrationProviders\Korgun;

use App\Models\Account;
use App\Models\AccountTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KorgunAccountTransaction
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function getAccountTransactions($params)
    {
        $singleAccountCode = $this->toNullableString($params['account_code'] ?? null);

        if ($singleAccountCode) {
            return $this->syncByAccountCode($singleAccountCode, $params);
        }

        Account::query()
            ->whereNotNull('code')
            ->where('code', '!=', '')
            ->select(['id', 'code', 'currency'])
            ->chunkById(200, function ($accounts) use ($params) {
                foreach ($accounts as $account) {
                    $this->syncByAccountCode($account->code, $params, $account);
                }
            });

        return null;
    }

    private function syncByAccountCode(string $accountCode, array $params, ?Account $account = null)
    {
        try {
            $data = [
                'ckod' => $accountCode,
                'BasTar' => Carbon::parse($params['start_date'])->format('d.m.Y H:i:s'),
                'BitTar' => Carbon::parse($params['end_date'])->format('d.m.Y H:i:s'),
            ];

            $response = $this->client->GetCariEkstre($data);
        } catch (\Throwable $e) {
            dump("Error fetching transactions for account code {$accountCode}: " . $e->getMessage());
            Log::warning('GetCariEkstre failed', [
                'account_code' => $accountCode,
                'start_date' => $params['start_date'] ?? null,
                'end_date' => $params['end_date'] ?? null,
                'message' => $e->getMessage(),
            ]);

            return null;
        }

        $xmlString = $response->GetCariEkstreResult->any ?? null;
        if (!$xmlString) {
            return $response;
        }

        $xml = simplexml_load_string($xmlString);
        if ($xml === false) {
            Log::warning('GetCariEkstre returned invalid XML', [
                'account_code' => $accountCode,
            ]);
            return $response;
        }

        $json = json_encode($xml);
        $array = json_decode($json, true);

        $transactions = isset($array['NewDataSet']['Table'][0])
            ? $array['NewDataSet']['Table']
            : (isset($array['NewDataSet']['Table']) ? [$array['NewDataSet']['Table']] : []);

        if (!$account) {
            $account = Account::where('code', $accountCode)->first();
        }

        if (!$account) {
            return $response;
        }

        foreach ($transactions as $transaction) {
            $title = $this->toString(
                $this->pick($transaction, ['Aciklama', 'Açıklama', 'Islem', 'İşlem', 'BelgeNo', 'EvrakNo'])
            );

            if ($title === '') {
                $title = 'Cari hareket';
            }

            $debt = $this->toDecimal($this->pick($transaction, ['Borc', 'Borç', 'BorcTutar', 'DBorc', 'Tutar']));
            $credit = $this->toDecimal($this->pick($transaction, ['Alacak', 'AlacakTutar', 'DAlacak']));
            $currencyRaw = $this->toString($this->pick($transaction, ['ParaCinsi', 'Pc', 'DvzCinsi']));
            $currency = $currencyRaw !== '' ? currency_map($currencyRaw) : ($account->currency ?: 'TRY');

            $createdAt = $this->toDateTime(
                $this->pick($transaction, ['Tarih', 'Tar', 'FisTar', 'BelgeTar']),
                $params['start_date'] ?? Carbon::now()
            );

            AccountTransaction::firstOrCreate([
                'account_id' => $account->id,
                'title' => $title,
                'debt' => $debt,
                'credit' => $credit,
                'currency' => $currency,
                'created_at' => $createdAt,
            ], [
                'updated_at' => Carbon::now(),
            ]);
        }

        return $response;
    }

    private function pick(array $row, array $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row)) {
                return $row[$key];
            }
        }

        return null;
    }

    private function toString($value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_scalar($value)) {
            return trim((string) $value);
        }

        if (is_array($value)) {
            foreach ($value as $item) {
                $parsed = $this->toString($item);
                if ($parsed !== '') {
                    return $parsed;
                }
            }
        }

        return '';
    }

    private function toNullableString($value): ?string
    {
        $parsed = $this->toString($value);
        return $parsed === '' ? null : $parsed;
    }

    private function toDecimal($value): float
    {
        $parsed = $this->toString($value);
        if ($parsed === '') {
            return 0.0;
        }

        $parsed = str_replace(' ', '', $parsed);
        $parsed = str_replace('.', '', $parsed);
        $parsed = str_replace(',', '.', $parsed);

        return is_numeric($parsed) ? (float) $parsed : 0.0;
    }

    private function toDateTime($value, $fallback): Carbon
    {
        $parsed = $this->toString($value);
        if ($parsed === '') {
            return Carbon::parse($fallback);
        }

        try {
            return Carbon::parse($parsed);
        } catch (\Throwable) {
            return Carbon::parse($fallback);
        }
    }
}
