<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    public function index() {
        $all_data = Role::all();
        $user_data = User::with('role')->get();
        return view('users', compact('all_data', 'user_data'));
    }

    public function save(Request $req){
        $exists =User::where('email',$req->email)->exists();
        if($exists == 1){
            return 2;
        }else{
            $data = new User();
            if($req->hasFile('image')){
                $imageName = time().'.'.$req->image->extension();
                $folderPath = public_path('images');
                if($req->image->move($folderPath, $imageName)){
                $data->image = $imageName;
                }
            }
            $data->name = $req->name;
            $data->email = $req->email;
            $data->role_id = $req->role_id;
            $data->phone = $req->phone;
            $data->description = $req->description;
            if($data->save()){
                $all_data = User::with('role')->get();
                return $all_data;
            }
        }
    }
}
