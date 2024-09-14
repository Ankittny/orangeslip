<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    // protected $commands = [
        
        
    //     Commands\ClearDatabase::class
        
    // ];
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // $schedule->command('process:encashment')
        //     ->twiceMonthly(1, 16, '00:03')->withoutOverlapping()
		// 	->evenInMaintenanceMode()
		// 	->timezone('Asia/Kolkata'); 

        $schedule->command('expired_subscription_check')
            ->everyMinute()->withoutOverlapping()
			->evenInMaintenanceMode()
			->timezone('Asia/Kolkata'); 
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
