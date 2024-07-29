<?php

namespace App\Console\Commands\Integration\Products;

use App\Services\Integrations\IntegrationProviderFactory;
use Illuminate\Console\Command;

class GetAllProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-all-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $params = ["start_date" => "2024-02-07 00:00:00", "end_date" => "2024-02-08 00:00:00"];
        while (true) {
            IntegrationProviderFactory::create('korgun')->getProducts($params);


            $params['start_date'] = $params['end_date'];
            $params['end_date'] = date('Y-m-d H:i:s', strtotime($params['end_date'] . ' +1 day'));


            if ($params['start_date'] > date('Y-m-d H:i:s')) {
                break;
            }
        }
    }

}
