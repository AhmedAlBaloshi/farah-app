<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        return view('users.list', compact('users'));
    }

    public function create()
    {
        $roles = Role::pluck('role_name', 'id')->toArray();
        return view('users.form', compact('roles'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role_id' => 'required',
        ]);

        if ($request->mobile_no) {
            $request->validate([
                'mobile_no'    => 'required|digits:8',
            ]);
        }
        $user =  User::add($request->all());
        return redirect()->route('users.index')->with('success', 'User Added successfully.');
    }
    public function edit($id)
    {
        $roles = Role::pluck('role_name', 'id')->toArray();
        $user = User::findOrFail($id);
        return view('users.form', compact('user', 'roles'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'firstname' => 'required|min:3|string',
            'lastname' => 'required|min:3|string',
            'mobile_no' => 'required|digits:8',
            'role_id' => 'required',
        ]);
        $user =  User::updateRecords($id, $request->all());
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        if ((int)$id > 0) {

            User::findOrFail($id)->delete();

            return response()->json([
                "code" => 200,
                "response_status" => "success",
                "message"         => "Record deleted successfully",
                "data"            => []
            ]);
        }

        return response()->json([
            "code" => 500,
            "response_status" => "error",
            "message"         => "Something went wrong"
        ]);
    }
}
