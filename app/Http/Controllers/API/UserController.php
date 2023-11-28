<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginUser(Request $request)
    {
        $input = $request->all();
       
        Auth::attempt($input);
        $user = Auth::user();

        $token = $user->createToken('user')->accessToken; //o auth

        return response()->json([
            'message'=>"You login",
        'status'=> 200,
        'token'=>$token
       ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getUserDetails(Request $request)
    {
        
        if(Auth::guard('api')->check()){
            $user = Auth::guard('api')->user();
         return response(['data'=> $user]);
           } 
            return response(['data' =>'Not found']);
            
   }

    /**
     * Display the specified resource.
     */
    public function userLogout(Request $request)
    {
        $usertoken = Auth::guard('api')->user()->token(); 
    $usertoken->revoke();
    $response = ['message' => 'You have been successfully logged out!'];
    return response($response, 200);
    }

    /**
     * Update the specified resource in storage.
     */
   
}
