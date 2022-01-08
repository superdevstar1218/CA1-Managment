<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Category;
use App\Models\Project;
use App\Models\Registry;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyLogsController extends Controller
{
    //
    public function index()
    {
        $projects = Project::all();
        $categories = Category::all();
        return view('pages.mylogs', compact('projects', 'categories'));
    }

    public function getData(Request $request)
    {

        // $projects = $_POST['projects'];
        // $categories = $_POST['categories'];
        
        $check_condition = 0;

        $first_date =   $request->post('start_date')  ;
        $last_date =   $request->post('end_date') ;

        $first_date = date($first_date." 00:00:00") ;
        $last_date = date($last_date." 23:59:59") ;

        // $logs = Registry::whereBetween('start' , [ $starts , $ends ] )->whereIn('project_id', $projects)->whereIn('category_id', $categories)->where('user_id' , "=" , Auth::id())->get() ;
        // $logs = Registry::whereBetween('start' , [ $first_date , $last_date ] )->whereIn('category_id', $categories)->where('user_id' , "=" , Auth::id())->get() ;
        $logs = Registry::whereBetween('start' , [ $first_date , $last_date ] )->where('user_id' , "=" , Auth::id())->get() ;


        return Datatables::of($logs)
            ->addColumn('status', function ($row) {
                $category = Category::findOrFail($row->category_id)->name;
                return $category;
            })
            ->addColumn('project', function ($row) {
                // $project = Project::findOrFail($row->project_id)->name;
                return 'project';
            })
            ->addColumn('end', function ($row) {
                if ($row->end == null) {
                    return 'Current';
                } else {
                    return $row->end;
                }
            })
            ->addColumn('comment', function ($row) {
                return 'Current';
            })
            ->rawColumns(['start'])
            ->make(true);
    }
}
