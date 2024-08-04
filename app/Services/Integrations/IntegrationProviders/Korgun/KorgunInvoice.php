<?php

namespace App\Services\Integrations\IntegrationProviders\Korgun;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceType;
use App\Models\Order;
use App\Models\OrderFailLog;
use App\Models\OrderProduct;
use App\Models\Product;
use Carbon\Carbon;

class KorgunInvoice
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }


    public function getInvoices($params)
    {

        $data = [
            "FatTarih1" => date('d.m.Y H:i:s', strtotime($params["start_date"])) ?? "",
            "FatTarih2" => date('d.m.Y H:i:s', strtotime($params["end_date"])) ?? "",
            "CariKod" => '',
            "Location" => '',
            "FaturaTip" => '',
            "BelgeNo1" => '',
            "BelgeNo2" => '',
            "faturaNo1" => '',
            "FaturaNo2" => '',
            "irsaliyeNo1" => '',
            "irsaliyeNo2" => '',
            "irsTarih1" => '',
            "irsTarih2" => '',
            "kay_Ozkod1" => '',
            "kay_Ozkod2" => '',
            "kay_Ozkod3" => '',
            "kay_Ozkod4" => '',
            "kay_Ozkod5" => '',
            "irsaliye" => '',
            "fatura" => '',
            "skod1" => '',
            "skod2" => '',
            "har_ozkod1" => '',
            "har_ozkod2" => '',
            "har_ozkod3" => ''
        ];


        $response = $this->client->GetFatura($data);
        $xmlString = $response->GetFaturaResult->any;
        $xml = simplexml_load_string($xmlString);
        $json = json_encode($xml);
        $array = json_decode($json, true);

        $invoices = isset($array['NewDataSet']['Table']['faturano']) ? [$array['NewDataSet']['Table']] : $array['NewDataSet']['Table'] ?? [];
        $data = [];

        foreach ($invoices as $invoice) {

            if (empty($invoice['faturano'])) {
                continue;
            }

            if (!isset($data[$invoice['faturano']])) {
                $data[$invoice['faturano']] = $invoice;

                $data[$invoice['faturano']]['amount_total'] = 0;
                $data[$invoice['faturano']]['tax_total'] = 0;
                $data[$invoice['faturano']]['discount_total'] = 0;
            }

            $data[$invoice['faturano']]['amount_total'] += $invoice['Tutar'] ?? 0;
            $data[$invoice['faturano']]['tax_total'] += $invoice['KDVTutar'] ?? 0;
            $data[$invoice['faturano']]['discount_total'] += $invoice['iskTutar'] ?? 0;
        }

        foreach ($data as $invoice) {





            var_dump("Fatura: " . $invoice['faturano']);
            var_dump($invoice);

            $invoiceType =    InvoiceType::firstOrCreate([
                'code' => $invoice['FaturaTip']
            ],[
                'name' => $invoice['FaturaTip'],
                'code' => $invoice['FaturaTip']
            ]);

            $invoiceModel = Invoice::firstOrCreate([
                'code' => $invoice['faturano']
            ], [
                'code' => $invoice['faturano'],
                'amount_total' => $invoice['amount_total'],
                'tax_total' => $invoice['tax_total'],
                'discount_total' => $invoice['discount_total'],
                'currency' => currency_map($invoice['har_ParaCinsi'] ?? 'TRY'),
                'account_id' => Account::where('code', $invoice['CariKod'])->first()->id ?? null,
                'account_code' => $invoice['CariKod'],
                'invoice_type_id' => $invoiceType->id,
                'invoice_type_code' => $invoice['FaturaTip'],
                'created_at' => Carbon::parse($invoice['fattar']),
                'store_code' => !empty($invoice['Location']) ? $invoice['Location'] : '',
                'seller_code' => !empty($invoice['Satici']) ? $invoice['Satici'] : '',
            ]);

            InvoiceDetail::firstOrCreate([
                'invoice_id' => $invoiceModel->id,
                'product_code' => $invoice['skod'] ?? ""
            ], [
                'invoice_id' => $invoiceModel->id,
                'product_code' => $invoice['skod'] ?? "",
                'quantity' => $invoice['miktar'] ?? 0,
                'unit_name' => $invoice['Birim'] ?? "",
                'price' => $invoice['fiyat'] ?? 0,
                'tax_rate' => $invoice['KDVORAN'] ?? 0,
                'currency' => currency_map($invoice['har_ParaCinsi'] ?? 'TRY'),
                'amount_total' => $invoice['Tutar'] ?? 0,
                'tax_total' => $invoice['KDVTutar'] ?? 0,
                'discount_total' => $invoice['iskTutar'] ?? 0,
            ]);

        }

        return $response;
    }

}
