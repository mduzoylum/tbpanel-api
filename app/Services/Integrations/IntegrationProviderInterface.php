<?php

namespace App\Services\Integrations;

interface IntegrationProviderInterface
{
    public function getProducts($params);
    public function getAccounts($params);

}
