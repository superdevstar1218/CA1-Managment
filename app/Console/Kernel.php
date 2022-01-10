<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DailyQuote::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        date_default_timezone_set("Asia/tokyo") ;

//        if( intVal( date("i") ) < 15 ) sleep(15 - intVal(date('i'))) ;
//
//        if( intVal(date("i") ) < 30 && intVal(date("i")) > 15) sleep( 30 - intVal(date("i"))) ;
//
//        if( intVal(date("i") ) > 30 && intVal(date("i")) < 45 ) sleep( 45 - intVal(date("i"))) ;
//
//        if( intVal(date("i")) > 45) sleep( 60 - intVal(date("i"))) ;

        $schedule->command('quote:daily')
            ->everyMinute();
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
