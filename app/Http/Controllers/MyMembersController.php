<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

class MyMembersController extends Controller
{
    //
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $users = User::where('parent', '=', $user->id)->get();
        foreach ($users as $key => $value) {
            $category = Category::findOrFail($value->status);
            $value->cur_status = $category->name;
        }
        return view('pages.subadmin.mymembers', compact('users'));
    }
    public function detail($id)
    {
        $user = User::findOrFail($id);
        return view('pages.subadmin.mymembersdetail', compact('id', 'user'));
    }
    public function memberlogs(Request $request, $id)
    {
        $starts = $_POST['start_date'] ? (Carbon::parse($_POST['start_date'])->format('Y-m-d') . ' 00:00:00')  : null;
        $ends = $_POST['end_date'] ? (Carbon::parse($_POST['end_date'])->format('Y-m-d') . ' 23:59:59')  : null;

        if ($starts == null && $ends == null) {
            $logs = Log::where('user_id', '=', $id)->get();
        }
        if ($starts != null && $ends == null) {
            $logs = Log::where('user_id', '=', $id)->where('start_at', '>=', $starts)->get();
        }
        if ($starts == null && $ends != null) {
            $logs = Log::where('user_id', '=', $id)->where('start_at', '<=', $ends)->get();
        }
        if ($starts != null && $ends != null) {
            $logs = Log::where('user_id', '=', $id)->where('start_at', '<=', $ends)->where('start_at', '>=', $starts)->get();
        }

        return Datatables::of($logs)
            ->addColumn('status', function ($row) {
                $category = Category::findOrFail($row->cur_status)->name;
                return $category;
            })
            ->addColumn('end', function ($row) {
                if ($row->end_at == null) {
                    return 'Current';
                } else {
                    return $row->end_at;
                }
            })
            ->rawColumns(['status', 'end'])
            ->make(true);
    }
}
