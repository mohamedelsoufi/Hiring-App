<?php

namespace App\Http\Controllers\Api\site\auth;

use App\CustomClass\response;
use App\Http\Controllers\Controller;
use App\Http\Resources\employeeResource;
use App\Http\Resources\employerResource;
use App\Models\Employees;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class authentication extends Controller
{
    public function login(Request $request){
        //get guard
        $guard = $request->route()->getName();

        //validation
        $validator = Validator::make($request->all(), [
            'email'             => 'required',
            'password'          => 'required',
            'token_firebase'    => 'required',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }
        
        //check password and email
        $credentials = ['email' => $request->email, 'password' => $request->password];

        try {
            if (! $token = auth($guard)->attempt($credentials)) {
                return response()->json([
                    'status'  => false,
                    'error'   => 1,                       //1 -> data wrong, 2 -> nout active, 3 -> default
                    'message' => 'passwored or email is wrong',
                ], 400);
            }
        } catch (JWTException $e) {
            return response::falid('some thing is wrong', 500);
        }

        //check user
        if($guard == 'employee'){
            // if user auth by employee guard
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

            //check if user blocked
            if($employee->block == 1){
                return response()->json([
                    'status'  => false,
                    'error'   => 3,                       //1 -> data wrong, 2 -> nout active, 3 -> default
                    'message' => 'this acount is bloked',
                ], 200);
            }

            //check if user activation
            if($employee->active == null){
                return response()->json([
                    'status'  => false,
                    'error'   => 2,                       //1 -> data wrong, 2 -> nout active, 3 -> default
                    'message' => 'your acount need to verification',
                ], 200);
            }

            //update firbase token
            $employee = Employees::find($employee->id);
            $employee->token = $request->get('token_firebase');
            $employee->save();

            return response()->json([
                'status'  => true,
                'message' => 'succeess',
                'employee'=> new employeeResource($employee),
                'token'   => $token,
            ], 200);
            
        } else {
            // if user auth by employer guard

            //get employer data
            if (! $employer = auth('employer')->user()) {
                return response::falid('user_not_found', 404);
            }

            //check employer active
            if($employer->active == null){
                return response()->json([
                    'status'  => false,
                    'error'   => 2,                       //1 -> data wrong, 2 -> nout active, 3 -> default
                    'message' => 'your acount need to verification',
                ], 200);
            }

            //update firbase token
            $employer = Employer::find($employer->id);
            $employer->token = $request->get('token_firebase');
            $employer->save();

            return response()->json([
                'status'  => true,
                'message' => 'succeess',
                'employer'=> new employerResource($employer),
                'token'   => $token,
            ], 200);
        }
    }

    public function socialiteAuthenticate(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'email_id' => 'required',
            'firebase_token'=> 'required',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //check if employee emial is exist
        $employee = Employees::where('email', '=', $request->email)->first();

        if($employee == null){
            return response::falid('this acount not found', 404);
        } else {
            //if employee block
            if($employee->block == 1){
                return response()->json([
                    'status'  => false,
                    'message' => 'this acount is bloked',
                ], 200);
            }
        }

        //employee auth
        $credentials = ['email' => $request->email,'password' =>  ''];
        try {
            if (! $token = auth('employee')->attempt($credentials)) {
                return response::falid('try again this email not for you', 404);
            }
        } catch (JWTException $e) {
            return response::falid('some thing is wrong', 500);
        }

        // update employee firbas token
        $employee->token = $request->firebase_token;
        $employee->save();

        return response()->json([
            "status" => true,
            'message'=> 'login success',
            'employee'   => new employeeResource($employee),
            'token'  => $token,
        ], 200);
        
    }
    
    public function logout(Request $request){
        //get guard
        $guard = $request->route()->getName();

        //get user data
        if (! $user = auth($guard)->user()) {
            return response::falid('user_not_found', 404);
        }
        
        //remove token
        $user->token = null;
        $user->save();

        //logout
        Auth::guard($guard)->logout();

        return response::suceess('logout success', 200);
    }
}
