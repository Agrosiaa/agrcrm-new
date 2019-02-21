<?php

namespace App\Console;

//use App\Console\Commands\SiteMap;
use App\Console\Commands\ChangeTaxOfProducts;
use App\Console\Commands\NewTCSLogicForVendorSettlement;
use App\Console\Commands\OrderCalculation;
use App\Console\Commands\OrderRoundOffScript;
use App\Console\Commands\ProductCalculation;
use App\Console\Commands\SettlementAmountCalculation;
use App\Console\Commands\OrderStatusCloseToConfirmed;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Psy\Command\Command;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\ExpireOtp::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->daily();
        $schedule->command('custom:expire-otp')->daily();
        $schedule->command('custom:rmacomplete')->daily();
        $schedule->command('custom:alertVendor')->daily();
        $schedule->command('custom:disableProduct')->daily();
        $schedule->command('custom:generate-sitemap')
                ->weekly()
                ->mondays()
                ->at('00:01');
    }
}
