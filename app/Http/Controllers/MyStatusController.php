<?php

namespace App\Http\Controllers;

use App\Models\Registry;
use App\Models\User;
use App\Models\Category;
use App\Models\Log;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyStatusController extends Controller
{
    //
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $categories = Category::all();
        $projects = Project::all();
        return view('pages.mystatus', compact('categories', 'user', 'projects'));
    }

    private function formatDateTime() {
        $current_minutes = intVal( date("i") ) ;

        if(  $current_minutes < 15 && $current_minutes > 0 ) {
            return date("Y-m-d H:00:00") ;
        }
        if( $current_minutes > 15 && $current_minutes < 30 ){
            return date("Y-m-d H:15:00") ;
        }
        if( $current_minutes > 30 && $current_minutes < 45 ){
            return date("Y-m-d H:30:00") ;
        }
        if( $current_minutes > 45  ){
            return date("Y-m-d H:45:00") ;
        }
        return date("Y-m-d H") + ":" + $current_minutes + ":00" ;
    }

    public function save_status(Request $request)
    {
        date_default_timezone_set("Asia/Tokyo") ;

        $data = $request->all();

        $user = User::find($data['user_id']);

        if(isset($user)) {
            if($user->status == $data['category_id'] && $user->project_id == $data['project_id']) {
                return response()->json([
                    "result" => false ,
                    'error' => "You've already in this status."
                ]);
            }
        }

        // At the first , find current last registry and update it.
        $registry = Registry::where("start" , "=" , $user->set_status_at)->get()->first() ;

        if(isset($registry)){
            if ( abs( strtotime(Carbon::now()) - strtotime($user->set_status_at) ) / 60 < 15){
                return response()->json([
                    "result" => false ,
                    "error" => "You can change status after about ".round (15 - ( abs( strtotime(Carbon::now()) - strtotime($user->set_status_at) ) / 60 ) , 2)." minutes."
                ]);
            }
            $registry->end = $this->formatDateTime() ;

            $registry->save();
        }

        // And then, update the user informations included set_status_at and status
        $user->status = $data['category_id'];

        if(isset($registry->end)) {

            $user->set_status_at = $registry->end ;
        }
        else $user->set_status_at = $this->formatDateTime() ;

        $user->save();

        // Finally , add a new registry
        $registry = new Registry ;

        $registry->start = $user->set_status_at ;
        $registry->end = date ( "Y-m-d H:i:s" , strtotime($user->set_status_at) + 60*15) ;
//        date ( "Y-m-d H:i:s" , strtotime($user->set_status_at) + 60*15)
        $registry->user_id = Auth::id() ;
        $registry->category_id = $data['category_id'] ;
        $registry->project_id = $data['project_id'] ;
        $registry->comment = $data['comment'] ;

        $registry->save() ;

        return response()->json(array('result' => true, 'success' => 'Successfully Changed'));
    }
}
