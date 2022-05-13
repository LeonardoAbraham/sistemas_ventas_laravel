<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function login(Request $request){
        /*
            user: leonardo_01@gmail.com
            password:12345678
        */
        $user = User::whereEmail($request->email)->first();

        if(!is_null($user) && Hash::check($request->password, $user->password)){
            $user->api_token = Str::random(150);
            $user->save();
            return \response()->json(['res' => true, 'token'=>$user->api_token, 'message'=>'Bienvenido al sistema'], 200);
        }
        else{
            return \response()->json(['res' => false, 'message'=>'Cuenta o password incorrectos'], 200);
        }
    }
}
