<?php

namespace App\Services\Integrations\IntegrationProviders;

use App\Services\Integrations\IntegrationProviderAbstract;
use App\Services\Integrations\IntegrationProviderInterface;
use App\Services\Integrations\IntegrationProviders\Korgun\KorgunAccount;
use App\Services\Integrations\IntegrationProviders\Korgun\KorgunAccountTransaction;
use App\Services\Integrations\IntegrationProviders\Korgun\KorgunInvoice;
use App\Services\Integrations\IntegrationProviders\Korgun\KorgunOrder;
use App\Services\Integrations\IntegrationProviders\Korgun\KorgunProduct;
use SoapClient;

class Korgun implements IntegrationProviderInterface
{

    private $wsdlOld = "http://5.26.216.37:2001/KorgunWebService.asmx?WSDL";
    private $wsdl = "http://5.250.254.249:2001/KorgunWebService.asmx?WSDL";


    private $client;

    public function __construct()
    {
        $this->client = new SoapClient($this->wsdl);
    }

    public function getProducts($params)
    {
        return (new KorgunProduct($this->client))->getProducts($params);
    }

    public function getAccounts($params)
    {
        return (new KorgunAccount($this->client))->getAccounts($params);
    }

    public function getAccountTransactions($params)
    {
        return (new KorgunAccountTransaction($this->client))->getAccountTransactions($params);
    }

    public function getOrders($params)
    {
        return (new KorgunOrder($this->client))->getOrders($params);
    }

    public function getInvoices($params)
    {
        return (new KorgunInvoice($this->client))->getInvoices($params);
    }

}
