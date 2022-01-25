<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Carbon\Carbon;

class MyScheduleController extends Controller
{
    public function index() {
        return view('pages.myschedule') ;
    }

    public function getDataYear(Request $request) {

        $edit_date = $request->post('editDate') ;
        $tabOption = $request->post('tabOption') ;

        if($tabOption == "1") {
            $edit_date = new \DateTime($edit_date) ;

            $ScheduleList = Schedule::where("user_id" , "=" , Auth::id())->where("project_type" , "=" , 0)->get() ;

            return Datatables::of($ScheduleList)
                ->addColumn('content', function ($row) {
                    return $row->content ;
                })
                ->addColumn('isdone' , function($row){
                    if ( $row->isdone == 1 )
                        return "Is Done" ;
                    else return "Not Done" ;
                })
                ->addColumn('comment' , function($row){
                    return $row->other ;
                })
                ->addColumn('action' , function($row){
                    return "<button type='button' class='btn btn-sm btn-primary' onclick='editYearSchedule(".$row->id.")' data-toggle='modal' data-target='#editYearScheduleModal'>Edit</button>&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-danger' onclick='deleteYearSchedule(".$row->id.")'>Delete</button>" ;
                })
                ->rawColumns(['Content' , 'Isdone' , 'Comment' , 'Action'])
                ->make(true);
        }
    }

    public function getOneYear(Request $request){
        $id = $request->post('id') ;

        $data = Schedule::where("id" , "=" , $id)->get()->first() ;

        return response()->json([
            "period" => $data->period ,
            "content" => $data->content ,
            "isdone" => $data->isdone ,
            "other" => $data->other
        ]);
    }

    public function saveOneYear(Request $request){
        $jsonBody = $request->all() ;

        $data = Schedule::where("id" , "=" , $jsonBody['id'])->get()->first() ;

        $data->period = $jsonBody['period'] ;
        $data->content = $jsonBody['content'] ;
        $data->isdone = $jsonBody['isdone'] ;
        $data->other = $jsonBody['other'] ;

        $data->save() ;

        return response()->json([
            "status" => "success"
        ]);
    }

    public function addOne(Request $request)    // Add Sch
    {
        $jsonBody = $request->all() ;
        $data = new Schedule ;

        $data->user_id = Auth::id() ;
        $year = $jsonBody['year'];
        $data->project_type = $jsonBody['type'] ;
        $data->start_date = $year;
        $data->content = $jsonBody['content'] ;
        $data->isdone = $jsonBody['isdone'] ;
        $data->comment = $jsonBody['comment'] ;

        $data->save() ;

        return response()->json([
            "status" => "success"
        ]);
    }


    public function deleteOneYear(Request $request) {
        $id = $request->post('id') ;

        Schedule::where("id" , "=" , $id)->delete() ;

        return response()->json([
            "status" => "success"
        ]);
    }
}
