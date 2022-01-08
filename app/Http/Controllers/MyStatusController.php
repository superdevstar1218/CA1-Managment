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

    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Tokyo") ;

        $data = $request->all();
        $user = User::findOrFail(Auth::id());

        $user->status = $data['status'];
        $user->set_status_at = Carbon::now() ;

        $user->save();
//        $log = Log::where('user_id', '=', $user->id)->orderByDesc('start_at')->first();
//        if($log) {
//            $log->end_at = Carbon::now();
//            $log->save();
//        }
//
//        Log::create([
//            'user_id' => $user->id,
//            'cur_status' => $data['status'],
//            'start_at' => Carbon::now(),
//            'comment' => $data['comment'],
//            'project_id' => $data['project_id']
//        ]);

        $registry = new Registry ;

        $registry->start = $user->set_status_at ;
        $registry->end = Carbon::now() ;
        $registry->user_id = Auth::id() ;
        $registry->category_id = $data['status'] ;

        $registry->save() ;


        return response()->json(array('result' => true, 'success' => 'Successfully Submitted'));
    }
}
