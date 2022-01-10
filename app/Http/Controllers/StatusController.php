<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Registry ;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

class StatusController extends Controller
{
    //
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

    public function detail($id)
    {
        $user = User::findOrFail($id);
        $categories = Category::all() ;

        return view('pages.statusdetail', compact('id', 'user' , 'categories'));
    }

    public function userlogs(Request $request, $id)
    {
        date_default_timezone_set("Asia/Tokyo");

        $first_date =   $request->post('start_date')  ;
        $last_date =   $request->post('end_date') ;

        $first_date = date($first_date." 00:00:00") ;
        $last_date = date('Y-m-d 00:00:00' , strtoitme($last_date) + 60*60*24 ) ;

        $user = User::find($id) ;

        $registries = Registry::whereBetween('start' , [ $first_date , $last_date ] )->where('user_id' , "=" , $id)->get() ;

        return Datatables::of($registries)
            ->addColumn('status', function ($row) {
                $category = $row->category->name ;
                return $category;
            })
            ->addColumn('comment' , function($row) {
                return $row->category->comment ;
            })
            ->addColumn('endStr' , function($row){

                return $row->end ;
            })
            ->rawColumns(['status' , 'comment' , 'endStr'])
            ->make(true);
    }

    public function editstatus(Request $request) {

        $first_date =   $request->post('date_start')  ;
        $last_date =   $request->post('date_end') ;
        $id = $request->post('user-id') ;

        $user = User::find($id) ;

        $limited_date = date('Y-m-d' , strtotime($request->post('date_end')) + 60*60*24 ) ;

        $registry = Registry::where("user_id" , "=" , $id)->where('start' , '=' , $user->set_status_at)->get()->first() ;

//        var_dump( Auth::user()->set_status_at);

        $registries = Registry::where('user_id' , "=" , $id)->whereBetween('start' , [ date($first_date." 00:00:00") , date($last_date." 23:59:59") ] )->get() ;

        $categories = Category::all() ;

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

        return view('pages.editstatus' , $data) ;
    }

    public function save_registries(Request $request) {
        $registries = $request->post('eventApi') ;

        $first_date =  $request->post('first_date')  ;
        $last_date = $request->post('last_date') ;

        $first_date = date($first_date." 00:00:00") ;
        $last_date = date($last_date." 23:59:59") ;


        Registry::whereBetween('start' , [ $first_date , $last_date ] )->delete() ;

        // // var_dump($registries) ;
        foreach($registries as $registry){

            $new_registry = new Registry ;

            $new_registry->user_id = $request->post('user_id') ;
            $new_registry->start = substr( str_replace( "UTC" , " " , $registry['startStr'] ) , 0 , -6 ) ;
            $new_registry->end = substr( str_replace( "UTC" , " " , $registry['endStr'] ) , 0 , -6 ) ;
            $new_registry->category_id = Category::where('name' , '=' , $registry['title'])->get()->first()->id;

            $new_registry->save() ;
        }

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

        $date_start = $request->post('date_start') ;
        $date_end = $request->post('date_end') ;
        $user_id = $request->post('user_id') ;

        $timestamp1 = strtotime($date_start) ;
        $timestamp2 = strtotime($date_end) ;
        $min = Registry::where('user_id' , "=" ,  $user_id)->orderBy('start')->first();

        if($min->start > $timestamp1)
            $timestamp1 = $min->start;

        $totalMinutes = abs($timestamp2 - $timestamp1)/(60.0) ;

        $registries = Registry::where('user_id' , "=" ,  $user_id)->whereBetween('start' , [ $date_start , $date_end ])->get() ;


        foreach($registries as $registry) {
            $timestamp01 = strtotime($registry->start) ;
            $timestamp02 = strtotime($registry->end) ;

            if($min->start > $timestamp01)
            $timestamp01 = $min->start;

            $diffMinutes = abs($timestamp02 - $timestamp01) / (60.0) ;

            if(!isset($registry_pie_chart_data[$registry->category_id])){
                $registry_pie_chart_data[$registry->category_id] = 0 ;

            }
            $registry_pie_chart_data[$registry->category_id] += $diffMinutes ;
        }


        foreach($registry_pie_chart_data as $key => $value) {
            $registry_pie_chart_data[$key] = $value  * 100.0 / $totalMinutes ;
        }

        return response()->json([
           'pieDatas' => $registry_pie_chart_data
        ]);
    }
}
