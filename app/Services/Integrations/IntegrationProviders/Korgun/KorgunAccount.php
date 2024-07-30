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

            var_dump("Account: " . $account['Ckod']);
            /** @var Account $accountModel */
            $accountModel = Account::firstOrNew(['code' => $account['Ckod']]);
            $accountModel->name = $account['Cname'];
            $accountModel->company = $account['Unvan'];
            $accountModel->address = (is_string($account['Adr1']) ? $account['Adr1'] : '') . ' ' . (is_string($account['Adr2']) ? $account['Adr2'] : '');
            $accountModel->country = $account['Ulke'];
            $accountModel->city = $account['Sehir'];
            $accountModel->town = $account['ilce'];
            $accountModel->post_code = $account['Pkod'];
            $accountModel->phone = $account['Tel1'];
            $accountModel->email = $account['eMail'];
            $accountModel->tax_number = $account['VNo'];
            $accountModel->tax_office = $account['VDar'];
            $accountModel->risk_limit = $account['RiskLimit'];
            $accountModel->credit_limit = $account['KrediLimit'];
            $accountModel->discount_rate = $account['iskonto'];
            $accountModel->currency = $account['DefPc'];
            $accountModel->identity_number = $account['TCKimlik'];
            $accountModel->iban = $account['IBAN'];

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
