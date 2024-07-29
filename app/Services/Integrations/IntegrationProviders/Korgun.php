<?php

namespace App\Services\Integrations\IntegrationProviders;

use App\Models\Product;
use App\Services\Integrations\IntegrationProviderAbstract;
use App\Services\Integrations\IntegrationProviderInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use SoapClient;

class Korgun extends IntegrationProviderAbstract implements IntegrationProviderInterface
{

    private $wsdl = "http://5.26.216.37:2001/KorgunWebService.asmx?WSDL";
    private $client;

    public function __construct()
    {
        $this->client = new SoapClient($this->wsdl);
    }

    public function getProducts($params)
    {

        try {

            // fill all data empty
            $data = [
                "tar1" => date('d.m.Y H:i:s', strtotime($params["start_date"])) ?? "",
                "tar2" => date('d.m.Y H:i:s', strtotime($params["end_date"])) ?? "",
                "skod1" => $params["product_code"] ?? "",
                "skod2" => $params["product_code"] ?? "",
                "FiyTip1" => "",
                "FiyTip2" => "",
                "FiyTip3" => "",
                "FiyTip4" => "",
                "FiyTip5" => "",
                "FiyTip6" => "",
                "FiyTip7" => "",
                "FiyTip8" => "",
                "FiyTip9" => "",
                "FiyTip10" => "",
                "Location" => "",
                "ParaCinsi" => "",
                "StkGrp" => "",
                "StkTip" => ""
            ];

            $response = $this->client->GetWebStkKart($data);
            $xmlString = $response->GetWebStkKartResult->any;
            $xml = simplexml_load_string($xmlString);
            $json = json_encode($xml);
            $array = json_decode($json, true);

            $products = isset($array['NewDataSet']['Table']['UrunKodu']) ? [$array['NewDataSet']['Table']] : $array['NewDataSet']['Table'] ?? [];

            foreach ($products as $product) {

                $product['box_items'] = $this->getProductBoxQuantity($product['UrunKodu']);


                if (is_array($product['box_items']) && count($product['box_items']) > 0) {

                    $product['items'] = $this->getProductItems($product['UrunKodu']);

                    $boxProducts = [];
                    foreach ($product['items'] as $item) {

                        $stockCode = $product['UrunKodu'] . '-' . $item['XKod'];

                        if (isset($boxProducts[$stockCode])) {
                            $boxProducts[$stockCode]['Miktar'] += $item['Miktar'];
                        } else {
                            $boxProducts[$stockCode] = $item;
                        }
                    }


                    foreach ($product['box_items'] as $item) {

                        $stockCode = $product['UrunKodu'] . '-' . $item['XKod'];

                        if (isset($boxProducts[$stockCode])) {
                            $boxProducts[$stockCode]['KoliMiktar'] = $item['Miktar'];
                        }
                    }

                    foreach ($boxProducts as $stockCode => $item) {

                        if ($item['Birim'] == 'Çift') {
                            $item['Birim'] = 'Koli';  // çift olan ürünler koli olarak kabul edilecek
                        }

                        $item['StokKodu'] = $stockCode;

                        $this->setProductToDb(array_merge($product, $item));


                    }
                } else {
                    $product['StokKodu'] = $product['UrunKodu'];
                    $this->setProductToDb($product);
                }
            }

            return $response;
        } catch (\SoapFault $e) {
            Log::error($e->getMessage());
            return [];
        }
    }


    private function setProductToDb($product)
    {

        unset($product['items']);
        unset($product['box_items']);

        /** @var Product $productModel */
        $productModel = Product::firstOrNew(['stock_code' => $product['StokKodu']]);
        $productModel->stock_code = $product['StokKodu'];
        $productModel->model_code = $product['UrunKodu'];

        if (is_string($product['ParaCinsi1']) && trim($product['ParaCinsi1']) != '') {
            $productModel->currency = $product['ParaCinsi1'];
        } else if (is_string($product['ParaCinsi']) && trim($product['ParaCinsi']) != '') {
            $productModel->currency = $product['ParaCinsi'];
        } else {
            $productModel->currency = 'TL';
        }

        $productModel->name = $product['UrunTanimi'];
        $productModel->description = $product['UrunTanimi'];
        $productModel->list_price = $product['Fiyat1'];
        $productModel->sale_price = $product['Fiyat1'];
        $productModel->tax_rate = (int)$product['KdvOran'];
        $productModel->type_id = $this->getProductTypeId($product['StokTip'], $product['StokTipTnm']);
        $productModel->group_id = $this->getProductGroupId($product['GRUPKOD'], $product['GRUPKODTnm']);
        $productModel->season_id = $this->getSeasonId($product['Reyon'], $product['ReyonTnm']);
        $productModel->brand_id = $this->getBrandId($product['UMarka'], $product['UMarkaTnm']);
        $productModel->supplier_id = $this->getSupplierId($product['UreticiKodu'], $product['UreticiKoduTnm']);
        $productModel->unit_id = $this->getUnitId($product['Birim']);
        $productModel->quantity = $product['KoliMiktar'] ?? 0;
        $productModel->created_at = $product['KartTarihi'] ? Carbon::parse($product['KartTarihi']) : Carbon::now();

        if (($productModel->quantity > 0 && $product['Miktar'] > 0)) {
            $productModel->box_quantity = $product['Miktar'] / $product['KoliMiktar'];
        }

        $productModel->save();


        if(isset($product['XKod']) && isset($product['XTanim'])) {
            $this->setProductAttribute($productModel, 'RENK', 'Renk', $product['XKod'], $product['XTanim']);
        }


        var_dump("Product saved: " . $product['StokKodu']);
    }

    public function getProductItems($productCode)
    {
        // fill all data empty
        $data = [
            "tar1" => "",
            "tar2" => "",
            "skod1" => $productCode,
            "skod2" => $productCode,
            "bedgrup" => "",
            "FiyTip1" => "",
            "FiyTip2" => "",
            "FiyTip3" => "",
            "FiyTip4" => "",
            "FiyTip5" => "",
            "FiyTip6" => "",
            "FiyTip7" => "",
            "FiyTip8" => "",
            "FiyTip9" => "",
            "FiyTip10" => "",
            "Location" => "",
            "ParaCinsi" => "",
            "Option" => "",
            "Barcode" => "",
            "aktifBarkod" => "",
            "MagazaMevcutRezervi" => ""
        ];

        $response = $this->client->GetWebStkMevDetail($data);
        $xmlString = $response->GetWebStkMevDetailResult->any;
        $xml = simplexml_load_string($xmlString);
        $json = json_encode($xml);
        $array = json_decode($json, true);


        return isset($array['NewDataSet']['Table']['UrunKodu']) ? [$array['NewDataSet']['Table']] : $array['NewDataSet']['Table'] ?? [];

    }


    public function getProductBoxQuantity($productCode)
    {
        // fill all data empty
        $data = [
            "skod1" => $productCode,
            "skod2" => $productCode,
            "rkod" => "",
            "koli_kod" => "",
            "Location" => "",
            "Option" => "",
        ];

        $response = $this->client->getstkKoliMik($data);
        $xmlString = $response->getstkKoliMikResult->any;
        $xml = simplexml_load_string($xmlString);
        $json = json_encode($xml);
        $array = json_decode($json, true);

        return isset($array['NewDataSet']['Table']['UrunKodu']) ? [$array['NewDataSet']['Table']] : $array['NewDataSet']['Table'] ?? [];
    }

}
