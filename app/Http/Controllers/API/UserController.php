<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Mail;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function registerUser(Request $request)
     {
        
        try {
            $validator = Validator::make($request->all(),[
                'name' =>'required|string',
                'email' => 'required|string',
                'password' => 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'error' =>$validator->errors()
                ], 200);
            }
            
            // $input = $request->password;
            // $password = bcrypt($input); 
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password =  Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => 200,
                "user created successfully"
            ]);


        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => "error",
                'message' => 'Something went wrong'
            ]);
        }
     }




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
    public function forgotPassword(Request $request){ 
        $input = $request->only('email');
        $validator = Validator::make($input, [
            'email' => "required|email"
        ]);
       
        if ($validator->fails()) {
            return response()->json($validator->errors());
        } 
        $response = Password::sendResetLink($input);
      // dd($response);
        $message = $response == Password::RESET_LINK_SENT ? 'Mail send successfully' : GLOBAL_SOMETHING_WANTS_TO_WRONG; 
        return response()->json($message);
    }

    public function passwordReset(Request $request){
    $input = $request->only('email','token', 'password', 'password_confirmation');
    $validator = Validator::make($input, [
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);
    if ($validator->fails()) {
        return response()->json($validator->errors());
    }
    $response = Password::reset($input, function ($user, $password) {
        $user->password = Hash::make($password);
        $user->save();
    });
    $message = $response == Password::PASSWORD_RESET ? 'Password reset successfully' : GLOBAL_SOMETHING_WANTS_TO_WRONG;
    return response()->json($message);
}
   
}
