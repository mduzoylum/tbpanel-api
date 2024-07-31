<?php

namespace App\Services\Integrations\IntegrationProviders\Korgun;

use App\Models\Account;
use App\Models\Order;
use App\Models\OrderFailLog;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class KorgunOrder
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }


    public function getOrders($params)
    {
        $data = [
            "SipNo" => "",
            "carikod" => "",
            "Durumu" => "",
            "tarih1" => date('d.m.Y H:i:s', strtotime($params["start_date"])) ?? "",
            "tarih2" => date('d.m.Y H:i:s', strtotime($params["end_date"])) ?? "",
            "OzelKod1" => "",
            "OzelKod2" => "",
            "OzelKod3" => "",
            "OzelKod4" => "",
            "OzelKod5" => "",
            "Ozelkod6" => "",
            "Ozelkod7" => "",
            "OzelKod8" => "",
            "OzelKod9" => "",
            "OzelKod10" => ""
        ];

        $response = $this->client->GetSipOzellik($data);

        $xmlString = $response->GetSipOzellikResult->any;
        $xml = simplexml_load_string($xmlString);
        $json = json_encode($xml);
        $array = json_decode($json, true);

        $orders = isset($array['NewDataSet']['Table']['SipNo']) ? [$array['NewDataSet']['Table']] : $array['NewDataSet']['Table'] ?? [];

        foreach ($orders as $order) {

            var_dump("Order: " . $order['SipNo']);

            $orderModel = Order::where('code', $order['SipNo'])->first();

            if ($orderModel) {
                var_dump("Order already exists : " . $order['SipNo']);
                continue;
            }

            $account = Account::where('code', $order['CariKod'])->first();

            if (!$account) {
                $this->logOrder("Account not found : " . $order['CariKod'], $order);
                continue;
            }

            $orderProducts = $this->getOrderProducts($order['SipNo']);

            if (empty($orderProducts)) {
                throw new \Exception("Order products not found : " . $order['SipNo']);
            }

            $invoiceItems = $this->getInvoice($orderProducts, $order);

            if (empty($invoiceItems)) {
                $this->logOrder("Invoice not found : " . $order['SipNo'], $order);
                continue;
            }

            /** @var Order $orderModel */
            $orderModel = Order::firstOrNew(['code' => $order['SipNo']]);
            $orderModel->code = $order['SipNo'];
            $orderModel->account_id = $account->id;

            $orderModel->order_total = 0;
            $orderModel->tax_total = 0;
            $orderModel->discount_total = 0;

            $invoiceProducts = [];

            foreach ($invoiceItems as $invoiceItem) {
                $orderModel->order_total += $invoiceItem['Tutar'];
                $orderModel->tax_total += $invoiceItem['KDVTutar'];
                $orderModel->discount_total += $invoiceItem['iskTutar'] ?? 0;
                $orderModel->currency = currency_map($invoiceItem['har_ParaCinsi']);

                if ($invoiceItem['skod'] == 'XXXXUlasim') {
                    $orderModel->cargo_total = $invoiceItem['Tutar'];
                }

                if (!isset($invoiceProducts[$invoiceItem['skod']])) {
                    $invoiceProducts[$invoiceItem['skod']] = [
                        'tax_rate' => $invoiceItem['KDVORAN'] ?? 0,
                        'price' => $invoiceItem['fiyat'],
                        'currency' => currency_map($invoiceItem['har_ParaCinsi'])
                    ];
                }
            }

            $mixedProducts = [];
            $productError = false;

            foreach ($orderProducts as $orderProduct) {
                $productCode = (isset($orderProduct['xkod']) && !empty($orderProduct['xkod'])) ? ($orderProduct['skod'] . '-' . $orderProduct['xkod']) : $orderProduct['skod'];

                if ($productCode == 'XXXXUlasim') {
                    continue;
                }
                if (!isset($orderProduct['Miktar'])) {
                    continue;
                }

                if(!isset($invoiceProducts[$orderProduct['skod']])) {
                    $productError = true;
                    $this->logOrder("Product not found in invoice : " . $productCode, $order);
                    break;
                }

                $product = Product::where('stock_code', $productCode)->first();

                if (!$product) {
                    $productError = true;
                    $this->logOrder("Product not found : " . $productCode, $order);
                    break;
                }

                if (isset($orderProduct['xkod']) && !empty($orderProduct['xkod']) && $product->box_quantity == 0) {
                    $productError = true;
                    $this->logOrder("Product box quantity is 0 : " . $productCode, $order);
                    break;
                }

                if (!isset($mixedProducts[$productCode])) {
                    $mixedProducts[$productCode] = [
                        'product_id' => $product->id,
                        'product_price' => $product->sale_price,
                        'product_currency' => $product->currency,
                        'box_quantity' => $product->box_quantity == 0 ? 1 : $product->box_quantity,
                        'price' => $invoiceProducts[$orderProduct['skod']]['price'],
                        'tax_rate' => $invoiceProducts[$orderProduct['skod']]['tax_rate'],
                        'currency' => $invoiceProducts[$orderProduct['skod']]['currency'],
                        'quantity' => 0
                    ];
                }

                $mixedProducts[$productCode]['quantity'] += $orderProduct['Miktar'];
            }

            if ($productError) {
                continue;
            }

            $orderModel->save();

            try {
                foreach ($mixedProducts as $mixedProduct) {

                    OrderProduct::create([
                        'order_id' => $orderModel->id,
                        'product_id' => $mixedProduct['product_id'],
                        'quantity' => $mixedProduct['quantity'] / $mixedProduct['box_quantity'],
                        'list_price' => round(exchange_rate($mixedProduct['product_price'], $mixedProduct['product_currency'], $mixedProduct['currency']),2),
                        'sale_price' => $mixedProduct['price'],
                        'tax_rate' => $mixedProduct['tax_rate']
                    ]);
                }
            } catch (\Exception $e) {
                OrderProduct::where('order_id', $orderModel->id)->delete();
                $orderModel->delete();
                throw $e;
            }

            var_dump("Order saved : " . $order['SipNo']);


        }

        return $response;
    }

    private function getOrderProducts($orderCode)
    {
        $data = [
            "SipNo" => $orderCode
        ];

        $response = $this->client->GetSipDurum($data);
        $xmlString = $response->GetSipDurumResult->any;
        $xml = simplexml_load_string($xmlString);
        $json = json_encode($xml);
        $array = json_decode($json, true);

        return isset($array['NewDataSet']['Table']['skod']) ? [$array['NewDataSet']['Table']] : $array['NewDataSet']['Table'] ?? [];

    }

    private function getInvoice($orderProducts, $order)
    {
        $invoices = [];

        foreach ($orderProducts as $orderProduct) {

            if (!isset($orderProduct['FaturaNo'])) {
                $this->logOrder("Invoice not found : " . $order['SipNo'], $order);
                return [];
            }

            $invoiceCode = $orderProduct['FaturaNo'];

            if (!in_array($invoiceCode, $invoices)) {
                $invoices[] = $invoiceCode;
            }
        }

        $returnData = [];

        foreach ($invoices as $invoiceCode) {

            $data = [
                "FatTarih1" => '',
                "FatTarih2" => '',
                "CariKod" => '',
                "Location" => '',
                "FaturaTip" => 'fsa',
                "BelgeNo1" => '',
                "BelgeNo2" => '',
                "faturaNo1" => $invoiceCode,
                "FaturaNo2" => $invoiceCode,
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

            $response =  isset($array['NewDataSet']['Table']['belgeno']) ? [$array['NewDataSet']['Table']] : $array['NewDataSet']['Table'] ?? [];

            $returnData = array_merge($returnData, $response);
        }

        return $returnData;

    }

    private function logOrder($error, $order)
    {
        var_dump("ERROR : " . $error);

        OrderFailLog::updateOrCreate([
            'code' => $order['SipNo']
        ], [
            'message' => $error
        ]);
    }


}
