<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Log;
use App\Models\Registry;
use Illuminate\Console\Command;
use App\Models\User ;

class DailyQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save User Status Automatically!';

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
        date_default_timezone_set("Asia/Tokyo");

//        Registry::where("id" , "=" , "138")->delete();

        $current_minutes = date("i") ;

        if( intVal($current_minutes) % 15 != 0) return Command::SUCCESS ;

        $current_datetime = date("Y-m-d H").":".$current_minutes.":00" ;

        $users = User::all() ;

        foreach($users as $user){
            $initial = new \DateTime( $user['set_status_at'] ) ;
            $current = new \DateTime( $current_datetime );

            if( $initial->format("Y-m-d") == $current->format("Y-m-d")){

//                        Registry::where("id" , "=" , "139")->delete();

                $registry = Registry::where("user_id" , "=" , $user['id'])->where("start" , "=" , $user->set_status_at)->get()->first() ;

                if(isset($registry))  {
                    $registry->end = $current_datetime ;
                    $registry->save() ;
                }
            } else {
                $registry = Registry::where("user_id" , "=" , $user->id )->where("start" , "=" , $user->set_status_at)->get()->first() ;

                if(isset($registry)){
                    $registry->end = date("Y-m-d 00:00:00") ;
                    $registry->save() ;

                }

                $user->set_status_at =  date("Y-m-d 00:00:00") ;

                $user->save() ;

                $new_registry = new Registry ;

                $new_registry->start = date("Y-m-d 00:00:00") ;
                $new_registry->end = $current_datetime ;
                $new_registry->category_id = $user->status ;
                $new_registry->user_id = $user->id ;

                $new_registry->save() ;
            }
        }
        return Command::SUCCESS;
    }
}
