<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\RouteInfo;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $baseConfig = config('scramble');

        Scramble::configure()->expose(false);

        Scramble::resolveTagsUsing(function (RouteInfo $routeInfo, Operation $operation): array {
            return str_starts_with($routeInfo->route->uri(), 'admin/')
                ? ['Admin Auth']
                : ['Supplier Auth'];
        });

        Scramble::registerApi('admin', $baseConfig)
            ->routes(function (Route $route): bool {
                return in_array('api', $route->gatherMiddleware(), true)
                    && str_starts_with($route->uri(), 'admin/');
            })
            ->expose('docs/admin', 'docs/admin.json');

        Scramble::registerApi('supplier', $baseConfig)
            ->routes(function (Route $route): bool {
                return in_array('api', $route->gatherMiddleware(), true)
                    && str_starts_with($route->uri(), 'supplier/');
            })
            ->expose('docs/supplier', 'docs/supplier.json');
    }
}
