<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RelationController extends Controller
{
    //
    public function index()
    {
        $subadmins = User::where('role', '=', 1)->get();
        foreach ($subadmins as $value) {
            $users = User::where('parent', '=', $value->id)->get();
            $value->users = $users;
        }
        return view('pages.relation', compact('subadmins'));
    }

    public function getUser()
    {
        $users = User::where('parent', '=', 0)->get();
        return response()->json(array('users' => $users));
    }

    public function getCUser($id)
    {
        $users = User::where('parent', '=', $id)->get();
        return response()->json(array('users' => $users));
    }

    public function addUsers(Request $request, $id)
    {
        $data = $request->all();
        $addusers = $data['addusers'];
        foreach ($addusers as $key => $value) {
            $user = User::findOrFail($value);
            $user->parent = $id;
            $user->save();
        }
        return response()->json(array('result' => true, 'success' => 'User added'));
    }
    public function delUsers(Request $request, $id)
    {
        $data = $request->all();
        $delusers = $data['delusers'];
        foreach ($delusers as $key => $value) {
            $user = User::findOrFail($value);
            $user->parent = 0;
            $user->save();
        }
        return response()->json(array('result' => true, 'success' => 'User added'));
    }
}
