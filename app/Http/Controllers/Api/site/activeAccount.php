<?php

namespace App\Http\Controllers\Api\site;

use App\Mail\ActivateAcount;
use App\Models\Employees;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\CustomClass\response as CustomClassResponse;
use App\Http\Resources\employeeResource;
use App\Http\Resources\employerResource;
use App\Models\Employer;
use Tymon\JWTAuth\Facades\JWTAuth;

class activeAccount
{
    ////////sent email /////////////

    public function sendEmail(Request $request)  // this is most important function to send mail and inside of that there are another function
    {
        $table = $request->route()->getName();

        if (!$this->validateEmail($request->email, $table)) {  // this is validate to fail send mail or true
            return $this->failedResponse();
        }
        
        $request->table = $table;

        // code is important in send mail 
        $code = $this->createCode($request->email, $request);
        Mail::to($request->email)->send(new ActivateAcount($code));

        return $this->successResponse();
    }


    public function createCode($email, $request)  // this is a function to get your request email that there are or not to send mail
    {
        $table = $request->table . '_active';

        $oldCode = DB::table($table)->where('email', $email)->first();

        if ($oldCode) {
            return $oldCode->code;
        }

        $code = rand(1000000,9999999);
        $this->saveCode($code, $email, $request);
        return $code;
    }

    public function saveCode($code, $email, $request)  // this function save new code
    {
        $table = $request->table . '_active';

        DB::table($table)->insert([
            'email' => $email,
            'code' => $code,
            'created_at' => Carbon::now()
        ]);
    }

    public function validateEmail($email, $table)  //this is a function to get your email from database
    {
        return !!DB::table($table . 's')->where('email', $email)->first();
    }

    public function failedResponse()
    {
        return response()->json([
            'status' => false,
            'message'=>'Email does\'t found on our database',
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponse()
    {
        return response()->json([
            'status' => true,
            'message'=>'Reset Email is send successfully, please check your inbox.',
        ], Response::HTTP_OK);
    }

    
    //////////////////////// change code ////////////

    public function active(Request $request){
        $table = $request->route()->getName();
        $request->table = $table;

        $validator = Validator::make($request->all(), [
            'email'           => 'required',
            'code'            => 'required',
        ]);
        
        if($validator->fails()){
            return CustomClassResponse::falid($validator->errors(), 422);
        }

        return $this->updateStateRow($request)->count() > 0 ? $this->changeState($request) : $this->codeNotFoundError();
    }
  
    // Verify if code is valid
    private function updateStateRow($request){
        $table = $request->table . '_active';

        return DB::table($table)->where([
            'email' => $request->email,
            'code' => $request->code
        ]);
    }
  
    // code not found response  
    private function codeNotFoundError() {
        return response()->json([
        'status' => false,
        'message'=>'Either your email or code is wrong.',
        ],Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    //check code
    public function checkCode(Request $request){
        $table = $request->route()->getName() . '_active';


        $validator = Validator::make($request->all(), [
            'email'           => 'required',
            'code'            => 'required',
        ]);
        
        if($validator->fails()){
            return CustomClassResponse::falid($validator->errors(), 422);
        }

        $row = DB::table($table)
                ->where('email', '=', $request->get('email'))
                ->where('code', '=', $request->get('code'))->get();

        if($row->count() > 0){
            return CustomClassResponse::suceess('success', 200);
        } else {
            return CustomClassResponse::falid('not found', 404);
        }
    }
  
    // change State
    private function changeState($request) {
        $table = $request->table . 's';
        // active
        DB::table($table)
        ->where('email', $request->email)
        ->update(['active' => 1]);

        // remove verification data from db
        $this->updateStateRow($request)->delete();
        
        if($request->table == 'employee'){
            $employee = Employees::where('email', $request->email)->first();
            $token = JWTAuth::fromUser($employee);
            //response
            return response()->json([
                'status' => true,
                'employee'=> new employeeResource($employee),
                'token'   => $token,
                'message'=>'activation success',
            ],Response::HTTP_CREATED);
        } else {
            $employer = Employer::where('email', $request->email)->first();
            $token = JWTAuth::fromUser($employer);
            //response
            return response()->json([
                'status' => true,
                'employer'  => new employerResource($employer),
                'token'   => $token,
                'message'=>'activation success',
            ],Response::HTTP_CREATED);
        }
    }
}
