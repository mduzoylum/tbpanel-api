<?php

namespace App\Services\Integrations;

class IntegrationProviderFactory
{

    /**
     * Create a new IntegrationProvider instance.
     *
     * @param string $provider
     * @return IntegrationProviderInterface
     */
    public static function create($provider)
    {
        $provider = ucfirst($provider);
        $providerClass = "App\\Services\\Integrations\\IntegrationProviders\\{$provider}";
        return new $providerClass;
    }
}
