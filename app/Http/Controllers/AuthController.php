<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

date_default_timezone_set('Asia/Jakarta');

class AuthController extends Controller
{
    public function register(Request $req)
    {
        //validator
        $rules=[
            'name'=>'required|string',
            'email'=>'required|string|unique:users',
            'password'=>'required|string|min:8'
        ];
        $validator = Validator::make($req->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        //create new user in users table
        $user = User::create([
            'name'=>$req->name,
            'email'=>$req->email,
            'password'=>Hash::make($req->password),
        ]);
        //create new account in account table
        $account = new Member;
        $account->id = $user->id;
        $account->save();

        $response = ['user'=> $user];
        return response()->json($response, 200);
    }



    
    public function login(Request $req)
    {
        // validate input
        $rules = [
            'email' => 'required',
            'password' => 'required|string'
        ];
        $req->validate($rules);

        //find user email in users table
        $user = User::where('email', $req->email)->first();
        
        //if user email found and password correct
        if($user && Hash::check($req->password, $user->password)){
            $token = $user->api_token = strtolower(Str::random(60));
            $user->save();
            $response=['user'=>$user, 'token'=>$token, 'id'=>$user->id];
            return response()->json($response, 200);
        }
        $response = ['massage'=>'incorrect mail and password'];
        return response()->json($response, 400);
    }
}
