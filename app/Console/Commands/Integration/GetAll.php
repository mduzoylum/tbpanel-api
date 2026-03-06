<?php

namespace App\Console\Commands\Integration;

use App\Models\Setting;
use App\Services\Integrations\IntegrationProviderFactory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-all';

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
        $startDate = Setting::where('code', 'integration_last_updated_at')->first()->value;

        if($startDate == null) {
            throw new \Exception('integration_last_updated_at setting not found');
        }

        $startDateCarbon = Carbon::parse($startDate);
        $params = [
            "start_date" => $startDateCarbon->toDateTimeString(),
            "end_date" => $startDateCarbon->copy()->addDay()->toDateTimeString()
        ];


        while (true) {
            $now = Carbon::now();
            if (Carbon::parse($params['end_date'])->greaterThan($now)) {
                $params['end_date'] = $now->toDateTimeString();
            }

            IntegrationProviderFactory::create('korgun')->getProducts($params);
            IntegrationProviderFactory::create('korgun')->getAccounts($params);
            IntegrationProviderFactory::create('korgun')->getOrders($params);
            IntegrationProviderFactory::create('korgun')->getInvoices($params);

            Setting::where('code', 'integration_last_updated_at')->update(['value' => $params['end_date']]);

            $params['start_date'] = $params['end_date'];
            $params['end_date'] = Carbon::parse($params['end_date'])->addDay()->toDateTimeString();

            if (Carbon::parse($params['start_date'])->greaterThan(Carbon::now())) {
                break;
            }
        }
    }

}
