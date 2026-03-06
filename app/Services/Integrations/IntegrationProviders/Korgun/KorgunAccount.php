<?php

namespace App\Services\Integrations\IntegrationProviders\Korgun;

use App\Models\Account;
use App\Models\AccountStatus;
use App\Models\AccountType;
use App\Models\WorkingMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KorgunAccount
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }


    public function getAccounts($params)
    {
        dump("Fetching accounts with params: " . json_encode($params));
        $data = [
            "Ckod1" => $params["account_code"] ?? "",
            "Ckod2" => $params["account_code"] ?? "",
            "Unvan" => "",
            "Location" => "",
            "tcKimlik" => "",
            "Ozkod1" => "",
            "Ozkod2" => "",
            "Ozkod3" => "",
            "Ozkod4" => "",
            "Ozkod5" => "",
            "Ozkod6" => "",
            "Ozkod7" => "",
            "Ozkod8" => "",
            "Ozkod9" => "",
            "Ozkod10" => "",
            "Tar1" => Carbon::parse($params["start_date"])->format('d.m.Y H:i:s'),
            "Tar2" => Carbon::parse($params["end_date"])->format('d.m.Y H:i:s')
        ];

        $response = $this->client->GetCari($data);

        $xmlString = $response->GetCariResult->any;
        $xml = simplexml_load_string($xmlString);
        $json = json_encode($xml);
        $array = json_decode($json, true);


        $accounts = isset($array['NewDataSet']['Table']['Ckod']) ? [$array['NewDataSet']['Table']] : $array['NewDataSet']['Table'] ?? [];

        foreach ($accounts as $account) {

            $accountCode = $this->toString($account['Ckod'] ?? null);
            if ($accountCode === '') {
                continue;
            }

            var_dump("Account: " . $accountCode);
            /** @var Account $accountModel */
            $accountModel = Account::firstOrNew(['code' => $accountCode]);
            $accountModel->name = $this->toString($account['Cname'] ?? null);
            $accountModel->company = $this->toString($account['Unvan'] ?? null);
            $accountModel->address = trim($this->toString($account['Adr1'] ?? null) . ' ' . $this->toString($account['Adr2'] ?? null));
            $accountModel->country = $this->toNullableString($account['Ulke'] ?? null);
            $accountModel->city = $this->toNullableString($account['Sehir'] ?? null);
            $accountModel->town = $this->toNullableString($account['ilce'] ?? null);
            $accountModel->post_code = $this->toString($account['Pkod'] ?? null);
            $accountModel->phone = $this->toString($account['Tel1'] ?? null);
            $accountModel->email = $this->toString($account['eMail'] ?? null);
            $accountModel->tax_number = $this->toString($account['VNo'] ?? null);
            $accountModel->tax_office = $this->toString($account['VDar'] ?? null);
            $accountModel->risk_limit = $this->toInt($account['RiskLimit'] ?? null);
            $accountModel->credit_limit = $this->toInt($account['KrediLimit'] ?? null);
            $accountModel->discount_rate = $this->toInt($account['iskonto'] ?? null);

            $currency = $this->toString($account['DefPc'] ?? null);
            $accountModel->currency = $currency !== '' ? currency_map($currency) : '';
            $accountModel->identity_number = $this->toString($account['TCKimlik'] ?? null);
            $accountModel->iban = $this->toString($account['IBAN'] ?? null);

            $accountModel->working_method_id = $this->getWorkingMethodId($this->toNullableString($account['Ozkod3'] ?? null));
            $accountModel->account_type_id = $this->getAccountTypeId($this->toNullableString($account['ozkod10'] ?? null));
            $accountModel->account_status_id = $this->getAccountStatusId($this->toNullableString($account['Ozkod2'] ?? null));

            $accountModel->save();

            var_dump("Account saved: " . $accountModel->id);

        }

        return $response;
    }

    private function getWorkingMethodId(?string $name)
    {
        if (empty($name)) {
            return null;
        }

        $method = WorkingMethod::firstOrCreate([
            'code' => generate_code_from_name($name)
        ], [
            'name' => $name
        ]);

        return $method->id;
    }

    private function getAccountTypeId(?string $name)
    {
        if (empty($name)) {
            return null;
        }

        $type = AccountType::firstOrCreate([
            'code' => generate_code_from_name($name)
        ], [
            'name' => $name
        ]);

        return $type->id;
    }

    private function getAccountStatusId(?string $name)
    {
        if (empty($name)) {
            return null;
        }

        $status = AccountStatus::firstOrCreate([
            'code' => generate_code_from_name($name)
        ], [
            'name' => $name
        ]);

        return $status->id;
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

    private function toInt($value): int
    {
        $parsed = $this->toString($value);
        return is_numeric($parsed) ? (int) $parsed : 0;
    }

}
