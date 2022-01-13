<?php

namespace App\Http\Controllers;

use App\Models\YearSchedule;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class MyScheduleController extends Controller
{
    public function index() {
        return view('pages.myschedule') ;
    }

    public function getData(Request $request) {

        $edit_date = $request->post('editDate') ;
        $tabOption = $request->post('tabOption') ;

        if($tabOption == "1") {

            $edit_date = new \DateTime($edit_date) ;
            $year = (int) $edit_date->format("Y");

            $yearScheduleList = YearSchedule::where("user_id" , "=" , Auth::id())->where("year" , "=" , $year)->get() ;

            return Datatables::of($yearScheduleList)
                ->addColumn('period', function ($row) {
                    return  $row->period ;
                })
                ->addColumn('content', function ($row) {
                    return $row->content ;
                })
                ->addColumn('isdone' , function($row){
                    if ( $row->isdone == 1 )
                        return "Is Done" ;
                    else return "Not Done" ;
                })
                ->addColumn('other' , function($row){
                    return $row->other ;
                })
                ->addColumn('action' , function($row){
                    return "<button type='button' class='btn btn-sm btn-primary' onclick='editYearSchedule(".$row->id.")' data-toggle='modal' data-target='#editScheduleModal'>Edit</button>&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-danger' onclick='deleteYearSchedule(".$row->id.")'>Delete</button>" ;
                })
                ->rawColumns(['period' , 'content' , 'isdone' , 'other' , 'action'])
                ->make(true);
        }
    }

    public function getOne(Request $request){
        $id = $request->post('id') ;

        $data = YearSchedule::where("id" , "=" , $id)->get()->first() ;

        return response()->json([
            "period" => $data->period ,
            "content" => $data->content ,
            "isdone" => $data->isdone ,
            "other" => $data->other
        ]);
    }

    public function saveOne(Request $request){
        $jsonBody = $request->all() ;

        $data = YearSchedule::where("id" , "=" , $jsonBody['id'])->get()->first() ;

        $data->period = $jsonBody['period'] ;
        $data->content = $jsonBody['content'] ;
        $data->isdone = $jsonBody['isdone'] ;
        $data->other = $jsonBody['other'] ;

        $data->save() ;

        return response()->json([
            "status" => "success"
        ]);
    }

    public function addOne(Request $request){
        $jsonBody = $request->all() ;

        $data = new YearSchedule ;

        $data->user_id = Auth::id() ;
        $data->year = $jsonBody['year'] ;
        $data->period = $jsonBody['period'] ;
        $data->content = $jsonBody['content'] ;
        $data->isdone = $jsonBody['isdone'] ;
        $data->other = $jsonBody['other'] ;

        $data->save() ;

        return response()->json([
            "status" => "success"
        ]);
    }


    public function deleteOne(Request $request) {
        $id = $request->post('id') ;

        YearSchedule::where("id" , "=" , $id)->delete() ;

        return response()->json([
            "status" => "success"
        ]);
    }
}
