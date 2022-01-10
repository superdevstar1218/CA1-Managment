<?php

namespace App\Http\Controllers;

use App\Models\Registry;
use App\Models\User;
use App\Models\Log;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        $users = User::where('role', '!=', 0)->get();
        return view('users.index', compact('users'));
    }

    public function formatDateTime() {
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

    public function store(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return response()->json(array('result' => false, 'errors' => $validator->getMessageBag()->toArray()));
        }
        $role = -1;
        if ($data['userType'] == 'Sub Admin') {
            $role = 1;
        } else {
            $role = 2;
        }


        date_default_timezone_set("Asia/Tokyo");

        $user = new User ;

        $user->name = $data['name'] ;
        $user->email = $data['email'] ;
        $user->password = Hash::make($data['password']) ;
        $user->role  = $role ;
        $user->parent = ($role == 1 ? -1 : 0) ;
        $user->project_id = 1 ;
        $user->status = 1 ;
        $user->set_status_at = $this->formatDateTime() ;
        $user->created_at = $this->formatDateTime() ;

        $user->save() ;

        $registry = new Registry ;

        $registry->user_id = $user['id'] ;
        $registry->category_id = 1 ;
        $registry->start = $this->formatDateTime() ;
        $registry->end = date ( "Y-m-d H:i:s" , strtotime($this->formatDateTime()) + 60*15) ;


        $registry->save();

        return response()->json(array('result' => true, 'success' => 'User created'));
    }

    public function update(Request $request, $id)
    {
        //
        $data = $request->all();
        $user = User::find($id);
        $role = -1;
        if ($data['userType'] == 'Sub Admin') {
            $role = 1;
        } else {
            $role = 2;
        }
        if ($user->name != $data['name']) {
            $temp = User::where('name', '=', $data['name'])->first();
            if ($temp) {
                return response()->json(array('result' => false, 'errors' => 'Name Duplicated'));
            }
        }
        if ($user->email != $data['email']) {
            $temp = User::where('email', '=', $data['email'])->first();
            if ($temp) {
                return response()->json(array('result' => false, 'errors' => 'Email Duplicated'));
            }
        }
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $role;
        $user->parent =  $role == 1 ? -1 : 0;
        $user->save();
        if ($role == 2) {
            User::where('parent', '=', $user->id)->update(['parent' => 0]);
        }
        return response()->json(array('result' => true, 'success' => 'User updated'));
    }
}
