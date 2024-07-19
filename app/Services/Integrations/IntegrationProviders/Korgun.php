<?php

namespace App\Services\Integrations\IntegrationProviders;

use App\Services\Integrations\IntegrationProviderAbstract;
use App\Services\Integrations\IntegrationProviderInterface;

class Korgun extends IntegrationProviderAbstract implements IntegrationProviderInterface
{
    public function getProducts()
    {

        $products = $this->korgunMocking('GetCari', []);

        return $products;


    }
}
