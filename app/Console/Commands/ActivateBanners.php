<?php

namespace App\Console\Commands;

use App\Models\Banner;
use Illuminate\Console\Command;

class ActivateBanners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'banners:activate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate banners based on release date';

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
        $today = now()->toDateString();

        $this->makeActiveBanner($today);

        $this->makeInactiveBanner($today);

    }
    
    public function makeActiveBanner($today)
    {
        $records = Banner::where('schedule_start_date', $today)->get();
    
        foreach ($records as $record) {
            $record->status = 'active';
            $record->save();
        }
    
        $this->info('Checked schedule dates and activated banners.');
    }

    public function makeInactiveBanner($today)
    {
        $records = Banner::where('schedule_end_date', $today)->get();

        foreach ($records as $record) {
            $record->status = 'inactive';
            $record->save();
        }

        $this->info('Checked schedule dates and Inactivated banners.');
    }
}
