<?php

namespace App\Services\Integrations;

class IntegrationProviderFactory
{
    public static function create($provider)
    {
        $provider = ucfirst($provider);
        $providerClass = "App\\Services\\Integrations\\IntegrationProviders\\{$provider}";
        return new $providerClass;
    }
}
