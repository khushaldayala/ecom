<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ActivateProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:activate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate products based on release date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return 0;
       $originalDate = Carbon::now()->toDateString();
       $today = \Carbon\Carbon::parse($originalDate)->format('Y/m/d');

        // Retrieve product IDs from product_release_schedules with matching release date
        $productIdsToActivate = DB::table('product_release_schedules')
            ->where('release_date', $today)
            ->pluck('product_id')
            ->toArray();

        if (empty($productIdsToActivate)) {
            $this->info('No products to activate today.');
            return;
        }

        // Update the corresponding products' status in the products table
        DB::table('products')
            ->whereIn('id', $productIdsToActivate)
            ->update(['status' => 'active']);

            // Update the is_done field in product_release_schedules
        DB::table('product_release_schedules')
            ->whereIn('product_id', $productIdsToActivate)
            ->update(['is_done' => 'done']);

        $this->info('Products activated successfully.');
    }
}
