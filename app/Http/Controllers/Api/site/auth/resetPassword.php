<?php

namespace App\Http\Controllers\Api\site\auth;

use App\CustomClass\response as CustomClassResponse;
use App\Mail\resetPassword as MailResetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class resetPassword
{
    ////////sent email /////////////

    public function sendEmail(Request $request)  // this is most important function to send mail and inside of that there are another function
    {
        $table = $request->route()->getName();

        if (!$this->validateEmail($request->email, $table)) {  // this is validate to fail send mail or true
            return $this->failedResponse();
        }
        
        $request->table = $table;

        // token is important in send mail 
        $token = $this->createToken($request->email, $request);
        Mail::to($request->email)->send(new MailResetPassword($token, $request->email));

        return $this->successResponse();
    }


    public function createToken($email, $request)  // this is a function to get your request email that there are or not to send mail
    {
        $table = $request->table . '_password_resets';

        $oldToken = DB::table($table)->where('email', $email)->first();

        if ($oldToken) {
            return $oldToken->token;
        }

        $token = rand(1000000,9999999);
        $this->saveToken($token, $email, $request);
        return $token;
    }


    public function saveToken($token, $email, $request)  // this function save new password
    {
        $table = $request->table . '_password_resets';

        DB::table($table)->insert([
            'email' => $email,
            'token' => $token,
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
            'status'  => false,
            'message' => 'Email does\'t found on our database'
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponse()
    {
        return response()->json([
            'status'  => true,
            'message' => 'Reset Email is send successfully, please check your inbox.'
        ], Response::HTTP_OK);
    }

    //////////////////////// change password ////////////

    public function passwordResetProcess(Request $request){
        $validator = Validator::make($request->all(), [
            'password'          => 'required|string|min:6',
            'confirmPassword'   => 'required|string|same:password',
            'email'             => 'required',
            'code'              => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                "status"    => false,
                'message'   => $validator->errors(),
            ], 422);
        }

        $table = $request->route()->getName();
        $request->table = $table;

        return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this->tokenNotFoundError();

    }
  
    // Verify if token is valid
    private function updatePasswordRow($request){
        $table = $request->table . '_password_resets';

        return DB::table($table)->where([
            'email' => $request->email,
            'token' => $request->code
        ]);
    }
  
    // code not found response  
    private function tokenNotFoundError() {
        return response()->json([
            'status'  => false,
            'message' => 'Either your email or code is wrong.'
        ],Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    //check code
    public function checkCode(Request $request){
        $table = $request->route()->getName() . '_password_resets';


        $validator = Validator::make($request->all(), [
            'email'           => 'required',
            'code'            => 'required',
        ]);
        
        if($validator->fails()){
            return CustomClassResponse::falid($validator->errors(), 422);
        }

        $row = DB::table($table)
                ->where('email', '=', $request->get('email'))
                ->where('token', '=', $request->get('code'))->get();

        if($row->count() > 0){
            return CustomClassResponse::suceess('success', 200);
        } else {
            return CustomClassResponse::falid('not found', 404);
        }
    }

    // Reset password
    private function resetPassword($request) {
        $table = $request->table . 's';
        // update password
        DB::table($table)
        ->where('email', $request->email)
        ->update(['password' => bcrypt($request->password)]);

        // remove verification data from db
        $this->updatePasswordRow($request)->delete();

        // reset password response
        return response()->json([
            'status'  => true,
            'message'=>'Password has been updated.'
        ],Response::HTTP_CREATED);
    } 
}
