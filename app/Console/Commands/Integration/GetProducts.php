<?php

namespace App\Console\Commands\Integration;

use App\Models\Setting;
use App\Services\Integrations\IntegrationProviderFactory;
use Carbon\Carbon;
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

        $startDateCarbon = Carbon::parse($startDate);
        $syncUntil = Carbon::now()->startOfSecond();
        $params = [
            "start_date" => $startDateCarbon->copy(),
            "end_date" => $startDateCarbon->copy()->addDay()
        ];

        while ($params['start_date']->lessThan($syncUntil)) {
            if ($params['end_date']->greaterThan($syncUntil)) {
                $params['end_date'] = $syncUntil->copy();
            }

            var_dump("Start Date : " . $params['start_date']->toDateTimeString());

            IntegrationProviderFactory::create('korgun')->getProducts($params);
            Setting::where('code', 'product_last_updated_at')->update(['value' => $params['end_date']->toDateTimeString()]);

            $params['start_date'] = $params['end_date']->copy();
            $params['end_date'] = $params['end_date']->copy()->addDay();
        }
    }

}
