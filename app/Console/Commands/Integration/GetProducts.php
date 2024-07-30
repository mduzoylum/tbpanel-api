<?php

namespace App\Console\Commands\Integration;

use App\Models\Setting;
use App\Services\Integrations\IntegrationProviderFactory;
use Illuminate\Console\Command;

class GetProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-products';

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
        $startDate = Setting::where('code', 'product_last_updated_at')->first()->value;

        if($startDate == null) {
            throw new \Exception('product_last_updated_at setting not found');
        }

        $params = [
            "start_date" => $startDate,
            "end_date" => date('Y-m-d H:i:s', strtotime($startDate. ' +1 day'))
        ];

        while (true) {
            IntegrationProviderFactory::create('korgun')->getProducts($params);

            $endDate = $params['end_date'] > date('Y-m-d H:i:s') ? date('Y-m-d H:i:s') : $params['end_date'];
            Setting::where('code', 'product_last_updated_at')->update(['value' => $endDate]);

            $params['start_date'] = $params['end_date'];
            $params['end_date'] = date('Y-m-d H:i:s', strtotime($params['end_date'] . ' +1 day'));

            if ($params['start_date'] > date('Y-m-d H:i:s')) {
                break;
            }
        }
    }

}
