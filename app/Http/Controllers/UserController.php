<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
    	$user_details = User::where('type','!=','admin')->get();

    	return view('admin.user.listing',compact('user_details'));
    }

    public function edit_user($id)
    {
       $user = User::find($id);

      return view('admin.user.edit', compact('user'));
    }

    public function delete(Request $request)
    {
        $user_id    = $request->user_id;
        $deletedRows = User::find($user_id)->delete();
        return redirect('/admin/users')->with('success','Deleted Successfully');

    }

    public function updateuser(Request $request)
    {
        $user_id    = $request->user_id;
        $name       = $request->name;
        $dob        = $request->dob;
        $gender     = $request->gender;

        $user = User::find($user_id);

        $user->name = $name;
        $user->dob = $dob;
        $user->gender = $gender;

        $user->save();

        return redirect('/admin/users')->with('success','User Update Successfully');
    }

   

}
