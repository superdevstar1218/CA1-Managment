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

        $users = User::all() ;

        foreach($users as $user){

            $initial_datetime = new \DateTime( $user->set_status_at ) ;
            $current_datetime = new \DateTime(date('Y-m-d H:i:s'));

            $diff = date_diff($initial_datetime , $current_datetime) ;


            if( $initial_datetime->format("Y-m-d") == $current_datetime->format("Y-m-d") ){ //calculate days between two datetime
//
                $registry = Registry::where("user_id" , "=" , $user['id'] )->where("start" , "=" , $initial_datetime)->get()->first() ;

                if(isset($registry['id'])){
                    $registry['end'] = date('Y-m-d H:i:s') ;
                } else {
                    $registry = new Registry ;

                    $registry['start'] = $initial_datetime ;
                    $registry['end'] = date('Y-m-d H:i:s');
                    $registry['category_id'] = $user['status'] ;
                    $registry['user_id'] = $user['id'] ;
                }
                $registry->save() ;
            } else {

                $registry = Registry::where("user_id" , "=" , $user->id )->where("start" , "=" , $initial_datetime)->get()->first() ;

                if(isset($registry['id'])){
                    $registry->end = date("Y-m-d 00:00:00") ;

                    $registry->save();

                    $new_registry = new Registry ;

                    $new_registry->category_id = $user->status ;
                    $new_registry->user_id = $user->id ;
                    $new_registry->start = date('Y-m-d 00:00:00');
                    $new_registry->end = date('Y-m-d H:i:s') ;

                    $new_registry->save();

                    $user->set_status_at = date('Y-m-d 00:00:00') ;

                    $user->save() ;
                }
            }

        }
        return Command::SUCCESS;
    }
}
