<?php

namespace App\Services\Integrations\IntegrationProviders\Korgun;

use App\Models\PriceField;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Services\Integrations\IntegrationProviderAbstract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KorgunProduct extends IntegrationProviderAbstract
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }


    public function getProducts($params)
    {
        $data = [
            "tar1" => Carbon::parse($params["start_date"])->format('d.m.Y H:i:s'),
            "tar2" => Carbon::parse($params["end_date"])->format('d.m.Y H:i:s'),
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

            var_dump("Product: " . $product['UrunKodu']);
            var_dump($product);

            $product['box_items'] = $this->getProductBoxQuantity($product['UrunKodu']);
            $product['items'] = $this->getProductItems($product['UrunKodu']);

            $product['prices'] = $this->getPricesFromProduct($product);

            if (is_array($product['items']) && count($product['items']) > 0) {

                $boxProducts = [];
                foreach ($product['items'] as $item) {

                    $stockCode = $product['UrunKodu'] . '-' . $item['XKod'];

                    if (isset($boxProducts[$stockCode])) {
                        $boxProducts[$stockCode]['Miktar'] += $item['Miktar'];
                        $boxProducts[$stockCode]['store_stocks'] = [];
                    } else {
                        $boxProducts[$stockCode] = $item;
                    }

                    $boxProducts[$stockCode]['KoliMiktar'] = $boxProducts[$stockCode]['Miktar'];

                    if (isset($boxProducts[$stockCode]['store_stocks'][$item['Location']])) {
                        $boxProducts[$stockCode]['store_stocks'][$item['Location']] += $item['Miktar'];
                    } else {
                        $boxProducts[$stockCode]['store_stocks'][$item['Location']] = $item['Miktar'];
                    }
                }


                foreach ($product['box_items'] as $item) {

                    $stockCode = $product['UrunKodu'] . '-' . $item['XKod'];

                    if ($item['Birim'] == 'Çift') {
                        $item['Birim'] = 'Koli';  // çift olan ürünler koli olarak kabul edilecek
                    }

                    if (isset($boxProducts[$stockCode])) {
                        $boxProducts[$stockCode]['KoliMiktar'] = $item['Miktar'];
                    }
                }

                foreach ($boxProducts as $stockCode => $item) {

                    $item['StokKodu'] = $stockCode;
                    $item['prices'] = $this->getPricesFromItem($product, $item);

                    $this->setProductToDb(array_merge($product, $item));


                }
            } else {
                $product['StokKodu'] = $product['UrunKodu'];
                $this->setProductToDb($product);
            }
        }

        return $response;
    }


    private function setProductToDb($product)
    {

        unset($product['items']);
        unset($product['box_items']);

        /** @var Product $productModel */
        $productModel = Product::firstOrNew(['stock_code' => $product['StokKodu']]);
        $productModel->stock_code = $product['StokKodu'];
        $productModel->model_code = $product['UrunKodu'];

        if (isset($product['ParaCinsi']) && is_string($product['ParaCinsi']) && trim($product['ParaCinsi']) != '') {
            $productModel->currency = currency_map($product['ParaCinsi']);
        } else if (isset($product['ParaCinsi1']) && is_string($product['ParaCinsi1']) && trim($product['ParaCinsi1']) != '') {
            $productModel->currency = currency_map($product['ParaCinsi1']);
        } else {
            $productModel->currency = currency_map('TL');
        }

        $productModel->name = $product['UrunTanimi'];
        $productModel->description = $product['UrunTanimi'];
        $productModel->list_price = $product['Fiyat1'] ?? 0;
        $productModel->sale_price = $product['Fiyat1'] ?? 0;
        $productModel->tax_rate = (int)$product['KdvOran'];
        $productModel->type_id = $this->getProductTypeId($product['StokTip'], $product['StokTipTnm']);
        $productModel->group_id = $this->getProductGroupId($product['GRUPKOD'], $product['GRUPKODTnm']);
        $productModel->season_id = $this->getSeasonId($product['Reyon'], $product['ReyonTnm']);
        $productModel->brand_id = $this->getBrandId($product['UMarka'], $product['UMarkaTnm'] ?? $product['UMarka']);
        $productModel->supplier_id = $this->getSupplierId($product['UreticiKodu'], $product['UreticiKoduTnm']);
        $productModel->unit_id = $this->getUnitId($product['Birim']);
        $productModel->quantity = $product['KoliMiktar'] ?? 0;
        $productModel->created_at = $product['KartTarihi'] ? Carbon::parse($product['KartTarihi']) : Carbon::now();

        if ($product['Miktar'] > 0 && $product['KoliMiktar'] > 0) {
            $productModel->box_quantity = $product['Miktar'] / $product['KoliMiktar'];
        } else if($product['KoliMiktar'] == 0 && isset($product['XKod']) && !empty($product['XKod'])) {
            $row = DB::table('box_quantities')
                ->where('stock_code', $product['StokKodu'])
                ->where('color_code', $product['XKod'])
                ->where('box_quantity', '>', 0)
                ->first();

            if($row) {
                $productModel->box_quantity = $row->box_quantity;
            }
        }

        $productModel->save();


        if (isset($product['XKod']) && isset($product['XTanim'])) {
            $this->setProductAttribute($productModel, 'renk', 'Renk', $product['XKod'], $product['XTanim']);
        }

        $storeId = $this->getStoreId($product['Location'], $product['Location_Tanim']);

        if (isset($product['store_stocks']) && is_array($product['store_stocks'])) {
            foreach ($product['store_stocks'] as $storeCode => $quantity) {
                $productModel->storeStocks()->syncWithoutDetaching([$storeId => ['quantity' => $quantity]]);
            }
        }
        if (isset($product['prices']) && is_array($product['prices'])) {
            foreach ($product['prices'] as $priceType => $price) {
                $priceFieldId = PriceField::where('code', $priceType)->first()->id;
                ProductPrice::updateOrCreate([
                    'product_id' => $productModel->id,
                    'price_field_id' => $priceFieldId,
                ], [
                    'list_price' => $price['price'],
                    'sale_price' => $price['price'],
                    'currency' => $price['currency'],
                ]);
            }
        }


        var_dump("Product saved: " . $product['StokKodu']);
    }

    public function getProductItems($productCode)
    {
        // fill all data empty
        $data = [
            "skod" => $productCode,
            "xkod" => "",
            "ykod" => "",
        ];

        $response = $this->client->GetWebStkLocMevDetail($data);
        $xmlString = $response->GetWebStkLocMevDetailResult->any;
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

    private function getPricesFromProduct($product)
    {
        $prices = [
            'online_sale_price' => [
                'price' => $product['Fiyat1'] ?? 0,
                'currency' => currency_map($product['ParaCinsi1'] && is_string($product['ParaCinsi1']) ? $product['ParaCinsi1'] : 'TL'),
            ],
            'discounted_online_sale_price' => [
                'price' => $product['Fiyat2'] ?? 0,
                'currency' => currency_map($product['ParaCinsi2'] && is_string($product['ParaCinsi2']) ? $product['ParaCinsi2'] : 'TL'),
            ],
            'credit_card_sale_price' => [
                'price' => $product['Fiyat4'] ?? 0,
                'currency' => currency_map($product['ParaCinsi4'] && is_string($product['ParaCinsi4']) ? $product['ParaCinsi4'] : 'TL'),
            ],
            'market_cost_price' => [
                'price' => $product['Fiyat5'] ?? 0,
                'currency' => currency_map($product['ParaCinsi5'] && is_string($product['ParaCinsi5']) ? $product['ParaCinsi5'] : 'TL'),
            ],
            'cost_price' => [
                'price' => $product['Fiyat6'] ?? 0,
                'currency' => currency_map($product['ParaCinsi6'] && is_string($product['ParaCinsi6']) ? $product['ParaCinsi6'] : 'TL'),
            ],
            'store_sale_price' => [
                'price' => $product['Fiyat7'] ?? 0,
                'currency' => currency_map($product['ParaCinsi7'] && is_string($product['ParaCinsi7']) ? $product['ParaCinsi7'] : 'TL'),
            ],
        ];


        return $prices;
    }


    private function getPricesFromItem($product, $item)
    {
        $currency = currency_map($item['ParaCinsi'] && is_string($item['ParaCinsi']) ? $item['ParaCinsi'] : 'TL');
        $prices = [
            'online_sale_price' => [
                'price' => $item['Fiyat1'] ?? 0,
                'currency' => $currency,
            ],
            'discounted_online_sale_price' => [
                'price' => $item['Fiyat2'] ?? 0,
                'currency' => $currency
            ],
            'credit_card_sale_price' => [
                'price' => $item['Fiyat4'] ?? 0,
                'currency' => $currency
            ],
            'market_cost_price' => [
                'price' => $item['Fiyat5'] ?? 0,
                'currency' => $currency
            ],
            'cost_price' => [
                'price' => $product['Fiyat6'] ?? 0,
                'currency' => $currency
            ],
            'store_sale_price' => [
                'price' => $product['Fiyat7'] ?? 0,
                'currency' => $currency
            ],
        ];


        return $prices;
    }

}
