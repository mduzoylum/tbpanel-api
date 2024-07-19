<?php

namespace App\Services\Integrations;

use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class IntegrationProviderAbstract
{

    public function korgunMocking($endpoint, $data)
    {
        $data = $endpoint;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://fabrika.toptancimburada.com/api/korgun',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('method' => $data),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($response, true);

        if($res['status']){
            $data = simplexml_load_string($res['data']['any']);

            dd($res['data']['any']);

            return json_decode(json_encode($data), true)['NewDataSet']['Table'];

        }else {
            throw new \Exception($res['message'] ?? 'Error while fetching data from Korgun API');
        }

    }

}
