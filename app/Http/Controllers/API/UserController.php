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

        $token = $user->createToken('user')->accessToken;
       return response()->json([
        'status'=> 200,
        'token'=>$token
       ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getUserDetails(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function userLogout(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   
}
