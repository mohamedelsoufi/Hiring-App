<?php

namespace App\Http\Controllers\Api\site;

use App\CustomClass\response;
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
    public function authenticate(Request $request){
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

        if($guard == 'employee'){
            // if user auth by employee guard
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

            if($employee->block == 1){
                return response()->json([
                    'status'  => false,
                    'error'   => 3,                       //1 -> data wrong, 2 -> nout active, 3 -> default
                    'message' => 'this acount is bloked',
                ], 200);
            }

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
            if (! $employer = auth('employer')->user()) {
                return response::falid('user_not_found', 404);
            }

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
        // $guard = $request->route()->getName();

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'email_id' => 'required',
            'firebase_token'=> 'required',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        $employee = Employees::where('email', '=', $request->email)->first();

        if($employee == null){
            return response::falid('this acount not found', 404);
        } else {
            if($employee->block == 1){
                return response()->json([
                    'status'  => false,
                    'message' => 'this acount is bloked',
                ], 200);
            }
        }

        $credentials = ['email' => $request->email,'password' =>  '', 'socialite_id' => $request->email_id];
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
        $guard = $request->route()->getName();

        if (! $user = auth($guard)->user()) {
            return response::falid('user_not_found', 404);
        }
        
        //remove token
        $user->token = null;
        $user->save();

        Auth::guard($guard)->logout();

        return response::suceess('logout success', 200);
    }
}
