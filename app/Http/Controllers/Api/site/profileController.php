<?php

namespace App\Http\Controllers\Api\site;

use App\CustomClass\response;
use App\Http\Resources\employeeResource;
use App\Http\Resources\employerResource;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\Employees;
use App\Models\Employer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use Illuminate\Support\Facades\DB;

class profileController
{
    public function getProfile(Request $request){
        $guard = $request->route()->getName();
        try {
            if (! $user = auth($guard)->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);
            
        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        if($guard == 'employee'){
            return response::suceess('success', 200, 'employee', new employeeResource($user));
        } else {
            return response::suceess('success', 200, 'employee', new employerResource($user));
        }
    }

    public function updateEmployeeProfile(Request $request){

        // return $request;
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 401);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 401);
            
        } catch (JWTException $e) {

            return response::falid('token_absent', 401);
        }

        // validate registeration request
        $validator = Validator::make($request->all(), [
            'fullName'          => 'nullable|string|max:250',
            'email'             => 'nullable|email|max:255|unique:employees,email,'. $employee->id,
            'country_id'        => 'nullable|integer|exists:countries,id|integer',
            'city_id'           => 'nullable|integer|exists:cities,id|integer',
            'title'             => 'nullable|string|max:250',
            'qualification'     => 'nullable|string|max:250',
            'university'        => 'nullable|string|max:250',
            'graduation_year'   => 'nullable|integer|max:5000',
            'experience'        => 'nullable|integer',
            'study_field'       => 'nullable|string|max:250',
            'deriving_licence'  => ['nullable',Rule::in(0,1)],      //0->no 1->yes
            'birth'             => 'nullable|string',
            'gender'            => ['nullable',Rule::in(0,1,2)],    //0->male  1->female 2->other
            'skills'            => 'array|nullable',
            'skills.*'          => 'nullable|string',
            'industry'          => 'nullable|exists:categories,id|integer',
            'languages'         => 'array|nullable',
            'languages.*'       => 'nullable|string',
            'cv'                => 'nullable|mimes:doc,pdf,docx',
            'audio'             => 'nullable|file',
            'video'             => 'nullable|mimes:mp4,mov,ogg',
            'image'             => 'nullable|mimes:jpg,jpeg,png,svg',
            'phone'             => 'nullable|string|min:8|regex:/(01)[0-2]{1}[0-9]{8}/',

        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }


        //selet employee
        $employee = Employees::find($employee->id)->makeVisible(['password' ,'title',
        'qualification','university','graduation_year','study_field','deriving_licence',
        'state','skills','languages','cv','audio','video','birth','gender', 'image','phone']);

        if($request->has('fullName')){
            $employee->fullName      = $request->get('fullName');
        }
        if($request->has('email')){
            $employee->email         = $request->get('email');
        }
        if($request->has('phone')){
            $employee->phone         = $request->get('phone');
        }

        if($request->has('country_id')){
            $employee->country_id       = $request->get('country_id');
        }

        if($request->has('city_id')){
            $employee->city_id          = $request->get('city_id');
        }

        if($request->has('industry')){
            $employee->category_id          = $request->get('industry');
        }

        if($request->has('title')){
            $employee->title          = $request->get('title');
        }

        if($request->has('qualification')){
            $employee->qualification          = $request->get('qualification');
        }

        if($request->has('university')){
            $employee->university          = $request->get('university');
        }

        if($request->has('graduation_year')){
            $employee->graduation_year          = $request->get('graduation_year');
        }

        if($request->has('experience')){
            $employee->experience          = $request->get('experience');
        }

        if($request->has('study_field')){
            $employee->study_field          = $request->get('study_field');
        }

        if($request->has('deriving_licence')){
            $employee->deriving_licence          = $request->get('deriving_licence');
        }

        if($request->has('birth')){
            $employee->birth          = $request->get('birth');
        }

        if($request->has('gender')){
            $employee->gender          = $request->get('gender');
        }

        if($request->has('languages')){
            $employee->languages          = $request->get('languages');
        }

        if($request->has('skills')){
            $employee->skills          = $request->get('skills');
        }

        //updat cv
        if($request->has('cv')){
            if($employee->cv == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('cv')->getClientOriginalExtension();
                $request->file('cv')->move(base_path('public/uploads/employee/cv') , $path);
                $employee->cv   = $path;
            } else {
                $oldCv = $employee->cv;

                //updat cv
                $path = rand(0,1000000) . time() . '.' . $request->file('cv')->getClientOriginalExtension();
                $request->file('cv')->move(base_path('public/uploads/employee/cv') , $path);
                $employee->cv   = $path;

                //delet old cv
                if(file_exists(base_path('public/uploads/employee/cv/') . $oldCv)){
                    unlink(base_path('public/uploads/employee/cv/') . $oldCv);
                }   
            }
        }

        //updat audio
        if($request->has('audio')){
            if($employee->audio == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('audio')->getClientOriginalExtension();
                $request->file('audio')->move(base_path('public/uploads/employee/audio') , $path);
                $employee->audio   = $path;
            } else {
                $oldAudio = $employee->audio;

                //updat audio
                $path = rand(0,1000000) . time() . '.' . $request->file('audio')->getClientOriginalExtension();
                $request->file('audio')->move(base_path('public/uploads/employee/audio') , $path);
                $employee->audio   = $path;

                //delet old audio
                if(file_exists(base_path('public/uploads/employee/audio/') . $oldAudio)){
                    unlink(base_path('public/uploads/employee/audio/') . $oldAudio);
                }   
            }
        }

        //updat video
        if($request->has('video')){
            if($employee->video == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('video')->getClientOriginalExtension();
                $request->file('video')->move(base_path('public/uploads/employee/video') , $path);
                $employee->video   = $path;
            } else {
                $oldVideo = $employee->video;

                //updat video
                $path = rand(0,1000000) . time() . '.' . $request->file('video')->getClientOriginalExtension();
                $request->file('video')->move(base_path('public/uploads/employee/video') , $path);
                $employee->video   = $path;

                //delet old video
                if(file_exists(base_path('public/uploads/employee/video/') . $oldVideo)){
                    unlink(base_path('public/uploads/employee/video/') . $oldVideo);
                }
            }
        }

        //updat image
        if($request->has('image')){
            if($employee->image == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/employee/image') , $path);
                $employee->image   = $path;
            } else {
                $oldImage = $employee->image;

                //updat image
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/employee/image') , $path);
                $employee->image   = $path;

                //delet old image
                if(file_exists(base_path('public/uploads/employee/image/') . $oldImage)){
                    unlink(base_path('public/uploads/employee/image/') . $oldImage);
                }   
            }
        }           

        if($employee->save()){
            return response::suceess('update profile success', 200, 'employee', new employeeResource($employee));
        } else {
            return response::falid('update profile falid', 400);
        }
    }

    public function updateEmployerProfile(Request $request){
        try {
            if (! $employer = auth('employer')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 401);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 401);
            
        } catch (JWTException $e) {

            return response::falid('token_absent', 401);
        }

        // validate registeration request
        $validator = Validator::make($request->all(), [
            'fullName'          => 'nullable|string|max:250',
            'title'             => 'nullable|string|max:250',
            'email'             => 'nullable|email|max:255|unique:employers,email,'. $employer->id,
            'mobile_number1'    => 'nullable|string|min:8',
            'mobile_number2'    => 'nullable|string|min:8',
            'company_name'      => 'nullable|string|max:250',
            'country_id'        => 'nullable|integer|exists:countries,id|integer',
            'city_id'           => 'nullable|integer|exists:cities,id|integer',
            'business'          => 'nullable|exists:categories,id|integer',
            'established_at'    => 'nullable|string|max:250',
            'website'           => 'nullable|string|max:250',
            'image'             => 'nullable|file',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }


        //sellect employer
        $employer = Employer::find($employer->id)->makeVisible(['company_name',
        'country', 'city', 'business', 'established_at', 'website', 'image', 'mobile_number2']);

        if($request->has('fullName')){
            $employer->fullName      = $request->get('fullName');
        }
        if($request->has('email')){
            $employer->email         = $request->get('email');
        }

        if($request->has('mobile_number1')){
            $employer->mobile_number1       = $request->get('mobile_number1');
        }

        if($request->has('mobile_number')){
            $employer->mobile_number2       = $request->get('mobile_number2');
        }

        if($request->has('title')){
            $employer->city          = $request->get('title');
        }

        if($request->has('company_name')){
            $employer->company_name       = $request->get('company_name');
        }

        if($request->has('country_id')){
            $employer->country_id       = $request->get('country_id');
        }

        if($request->has('city_id')){
            $employer->city_id       = $request->get('city_id');
        }

        if($request->has('business')){
            $employer->business       = $request->get('business');
        }

        if($request->has('established_at')){
            $employer->established_at       = $request->get('established_at');
        }

        if($request->has('website')){
            $employer->website       = $request->get('website');
        }

        //updat image
        if($request->has('image')){
            if($employer->image == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/employer/image') , $path);
                $employer->image   = $path;
            } else {
                $oldImage = $employer->image;

                //updat image
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/employer/image') , $path);
                $employer->image   = $path;

                //delet old image
                if(file_exists(base_path('public/uploads/employer/image/') . $oldImage)){
                    unlink(base_path('public/uploads/employer/image/') . $oldImage);
                }   
            }
        }


        if($employer->save()){
            return response::suceess('update profile success', 200, 'employer',  new employerResource($employer));
        } else {
            return response::falid('update profile falid', 400);
        }
    }

    public function changeEmployeePassword(Request $request){
        // return $request;
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 401);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 401);
            
        } catch (JWTException $e) {

            return response::falid('token_absent', 401);
        }

        // validate registeration request
        $validator = Validator::make($request->all(), [
            'password'          => 'required|string|min:6',
            'confirmPassword'   => 'required_with:password|string|same:password',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //update data
        if($request->has('password')){
            if(Hash::check($request->oldPassword, $employee->password)){
                $employee->password  = Hash::make($request->get('password'));
                
            } else {
                return response::falid('old password is wrong', 400);
            }
        }

        if($employee->save()){
            return response::suceess('change password success', 200);
        } else {
            return response::falid('update profile falid', 400);
        }
    }

    public function changeEmployerPassword(Request $request){
        try {
            if (! $employer = auth('employer')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 401);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 401);
            
        } catch (JWTException $e) {

            return response::falid('token_absent', 401);
        }

        // validate registeration request
        $validator = Validator::make($request->all(), [
            'password'          => 'nullable|string|min:6',
            'confirmPassword'   => 'required_with:password|string|same:password',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }
        
        //update data
        if($request->has('password')){
            if(Hash::check($request->oldPassword, $employer->password)){
                $employer->password  = Hash::make($request->get('password'));
                
            } else {
                return response::falid('old password is wrong', 405);
            }
        }

        if($employer->save()){
            return response::suceess('change password success', 200);
        } else {
            return response::falid('update profile falid', 400);
        }
    }
}
