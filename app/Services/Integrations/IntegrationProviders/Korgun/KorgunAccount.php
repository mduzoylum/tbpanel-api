<?php

namespace App\Services\Integrations\IntegrationProviders\Korgun;

use App\Models\Account;
use App\Models\AccountStatus;
use App\Models\AccountType;
use App\Models\WorkingMethod;
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
            "Tar1" => date('d.m.Y H:i:s', strtotime($params["start_date"])) ?? "",
            "Tar2" => date('d.m.Y H:i:s', strtotime($params["end_date"])) ?? ""
        ];

        $response = $this->client->GetCari($data);
        $xmlString = $response->GetCariResult->any;
        $xml = simplexml_load_string($xmlString);
        $json = json_encode($xml);
        $array = json_decode($json, true);

        $accounts = isset($array['NewDataSet']['Table']['Ckod']) ? [$array['NewDataSet']['Table']] : $array['NewDataSet']['Table'] ?? [];

        foreach ($accounts as $account) {

            var_dump($account);

            var_dump("Account: " . $account['Ckod']);
            /** @var Account $accountModel */
            $accountModel = Account::firstOrNew(['code' => $account['Ckod']]);
            $accountModel->name = is_string($account['Cname']) ? $account['Cname'] : '';
            $accountModel->company = is_string($account['Unvan']) ? $account['Unvan'] : '';
            $accountModel->address = (is_string($account['Adr1']) ? $account['Adr1'] : '') . ' ' . (is_string($account['Adr2']) ? $account['Adr2'] : '');
            $accountModel->country = is_string($account['Ulke']) ? $account['Ulke'] : null;
            $accountModel->city = is_string($account['Sehir']) ? $account['Sehir'] : null;
            $accountModel->town = is_string($account['ilce']) ? $account['ilce'] : null;
            $accountModel->post_code = is_string($account['Pkod']) ? $account['Pkod'] : '';
            $accountModel->phone = is_string($account['Tel1']) ? $account['Tel1'] : '';
            $accountModel->email = is_string($account['eMail']) ? $account['eMail'] : '';
            $accountModel->tax_number = is_string($account['VNo']) ? $account['VNo'] : '';
            $accountModel->tax_office =  is_string($account['VDar']) ? $account['VDar'] : '';
            $accountModel->risk_limit = (int) $account['RiskLimit'];
            $accountModel->credit_limit = (int) $account['KrediLimit'];
            $accountModel->discount_rate = (int) $account['iskonto'];
            $accountModel->currency = is_string($account['DefPc']) ? $account['DefPc'] : '';
            $accountModel->identity_number = is_string($account['TCKimlik']) ? $account['TCKimlik'] : '';
            $accountModel->iban = is_string($account['IBAN']) ? $account['IBAN'] : '';

            $accountModel->working_method_id = $this->getWorkingMethodId($account['Ozkod3']);
            $accountModel->account_type_id = $this->getAccountTypeId($account['ozkod10']);
            $accountModel->account_status_id = $this->getAccountStatusId($account['Ozkod2']);

            $accountModel->save();

            var_dump("Account saved: " . $accountModel->id);

        }

        return $response;
    }

    private function getWorkingMethodId($name)
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

    private function getAccountTypeId($name)
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

    private function getAccountStatusId($name)
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

}
