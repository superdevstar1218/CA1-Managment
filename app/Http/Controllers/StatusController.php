<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Registry ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Models\Project ;

class StatusController extends Controller
{
    //
    private $max ;

    public function setMax($max) {
        $this->max = $max ;

        return ;
    }
    public function getMax() {
        return $this->max ;
    }
    public function index()
    {
        $users = User::where('parent', '!=', -2)->get();
        foreach ($users as $key => $value) {
            $value->userType = $value->role == 1 ? 'Sub Admin' : 'User';
            $category = Category::findOrFail($value->status);
            $value->cur_status = $category->name;
        }
        return view('pages.status', compact('users'));
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

    public function detail($id)
    {
        $user = User::findOrFail($id);
        $categories = Category::all() ;
        $projects = Project::all() ;

        return view('pages.statusdetail', compact('id', 'user' , 'categories' , 'projects'));
    }

    public function userlogs(Request $request, $id)
    {
        date_default_timezone_set("Asia/Tokyo");

        $first_date =   $request->post('start_date')  ;
        $last_date =   $request->post('end_date') ;

        $first_date = date($first_date." 00:00:00") ;
        $last_date = date($last_date." 23:59:59") ;

        $user = User::find($id) ;

        $registries = Registry::whereBetween('start' , [ $first_date , $last_date ] )->where('user_id' , "=" , $id)->orderBy('end' , 'asc')->get() ;

        $registry = Registry::whereBetween('start' , [$first_date , $last_date ])->where('user_id' , "=" ,$id)->orderBy('end' , 'desc')->get()->first() ;

        if(isset($registry)){
            $this->setMax($registry->end) ;
        }

//        var_dump($this->getMax());

        return Datatables::of($registries)
            ->addColumn('status', function ($row) {
                $category = $row->category->name ;
                return $category;
            })
            ->addColumn('project', function ($row) {
                $project = $row->project->name ;
                return $project;
            })
            ->addColumn('endStr' , function($row){
                if($row->end == $this->getMax()){
                    return "Current" ;
                }
                return $row->end ;
            })
            ->rawColumns(['status' , 'project' , 'endStr' , 'comment'])
            ->make(true);
    }

    public function editstatus(Request $request) {

        $first_date =   $request->post('date_start')  ;
        $last_date =   $request->post('date_end') ;
        $id = $request->post('user-id') ;

        $user = User::find($id) ;

        $limited_date = date('Y-m-d' , strtotime($request->post('date_end')) + 60*60*24 ) ;

        $registry = Registry::where("user_id" , "=" , $id)->whereBetween('start' , [ date($first_date." 00:00:00") , date($last_date." 23:59:59") ] )->orderBy('end' , 'desc')->get()->first() ;

//        var_dump( Auth::user()->set_status_at);

        $registries = Registry::where('user_id' , "=" , $id)->whereBetween('start' , [ date($first_date." 00:00:00") , date($last_date." 23:59:59") ] )->get() ;

        $categories = Category::all() ;
        $projects = Project::all() ;

        $data = [] ;

        $data['date_start'] = $first_date ;
        $data['date_end'] = $last_date ;

        $data['user_created_at'] = $user->created_at ;
        $data['limited_date'] = $limited_date ;
        $data['last_dateTime'] = $registry->end ;

        $data['registries'] = $registries ;
        $data['user_id'] = $id ;
        $data['user_name'] = User::find($id)->name ;
        $data['categories'] = $categories ;
        $data['projects'] = $projects ;

        return view('pages.editstatus' , $data) ;
    }

    public function save_registries(Request $request) {
        $registries = $request->post('eventApi') ;

        $first_date =  $request->post('first_date')  ;
        $last_date = $request->post('last_date') ;

        $first_date = date($first_date." 00:00:00") ;
        $last_date = date($last_date." 23:59:59") ;

        $user = User::find($request->post('user_id') );

        $max_datetime = 0 ;

//        var_dump($registries) ;
        Registry::whereBetween('start' , [ $first_date , $last_date ] )->delete() ;

        // // var_dump($registries) ;
        foreach($registries as $registry){

            $new_registry = new Registry ;

            $new_registry->user_id = $request->post('user_id') ;
            $new_registry->start = substr( str_replace( "UTC" , " " , $registry['startStr'] ) , 0 , -6 ) ;
            $new_registry->end = substr( str_replace( "UTC" , " " , $registry['endStr'] ) , 0 , -6 ) ;
            $new_registry->category_id = $registry["category_id"] ;
            $new_registry->project_id = $registry["project_id"] ;
            $new_registry->comment = $registry["comment"] ;

            $new_registry->save() ;

            if ( $max_datetime < strtotime($new_registry->start) ){
                $max_datetime = strtotime($new_registry->start) ;
            }
        }

        $user->set_status_at = date("Y-m-d H:i:s" , $max_datetime) ;
        $user->save() ;

        return response()->json([
            "status" => "Save Successfully!"
        ]);
    }

    public function delete_registry(Request $request){
        $id = $request->post('id') ;

        Registry::find($id)->delete() ;

        return response()->json([
            "status" => "Deleted Successfully!"
        ]);
    }

    public function analysis_registry(Request $request) {

        $registry_pie_chart_data = [] ;
        $timeInfo = [] ;

        $date_start = $request->post('date_start') ;
        $date_end = $request->post('date_end') ;
        $user_id = $request->post('user_id') ;

        $registry = Registry::where("user_id" , "=" , $user_id)->whereBetween('start' , [ date($date_start." 00:00:00") , date($date_end." 23:59:59") ] )->orderBy('start' , 'desc')->get()->first() ;

        $timestamp1 = strtotime($registry->end) ;

        $registry = Registry::where("user_id" , "=" , $user_id)->whereBetween('start' , [ date($date_start." 00:00:00") , date($date_end." 23:59:59") ] )->orderBy('start' , 'asc')->get()->first() ;

        $timestamp2 = strtotime($registry->start) ;

        $totalMinutes = abs($timestamp1 - $timestamp2) ;

        $registries = Registry::where("user_id" , "=" , $user_id)->whereBetween('start' , [ date($date_start." 00:00:00") , date($date_end." 23:59:59") ] )->orderBy("start" , "asc")->get() ;

        foreach($registries as $registry) {
            $timestamp01 = strtotime($registry->start) ;
            $timestamp02 = strtotime($registry->end) ;

            $diffMinutes = abs($timestamp02 - $timestamp01)  ;

            if(!isset($registry_pie_chart_data[$registry->category_id])){
                $registry_pie_chart_data[$registry->category_id] = 0 ;
                $timeInfo[$registry->category_id] = 0;
            }
            $registry_pie_chart_data[$registry->category_id] += $diffMinutes ;
            $timeInfo[$registry->category_id] += $diffMinutes ;
        }

        foreach($registry_pie_chart_data as $key => $value) {
            $registry_pie_chart_data[$key] = round( $value  * 100.0 / $totalMinutes , 2 ) ;
        }

        return response()->json([
           'pieDatas' => $registry_pie_chart_data ,
            'timeInfo' => $timeInfo ,
        ]);
    }
}
