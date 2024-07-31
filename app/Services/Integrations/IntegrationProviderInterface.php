<?php

namespace App\Services\Integrations;

interface IntegrationProviderInterface
{
    public function getProducts($params);
    public function getAccounts($params);
    public function getOrders($params);
    public function getInvoices($params);

}
