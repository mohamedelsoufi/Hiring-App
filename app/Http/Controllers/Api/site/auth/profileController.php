<?php

namespace App\Http\Controllers\Api\site\auth;

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
        //get guared
        $guard = $request->route()->getName();

        //get user data
        if (! $user = auth($guard)->user()) {
            return response::falid('user_not_found', 404);
        }

        if($guard == 'employee'){
            return response::suceess('success', 200, 'employee', new employeeResource($user));
        } else {
            return response::suceess('success', 200, 'employee', new employerResource($user));
        }
    }

    public function updateEmployeeProfile(Request $request){

        //get user data
        if (! $employee = auth('employee')->user()) {
            return response::falid('user_not_found', 404);
        }

        // validate
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
        $employee = Employees::find($employee->id);

        $input = $request->only(
            'fullName', 'country_id', 'city_id', 'title', 'qualification', 'university', 'graduation_year',
            'experience', 'study_field', 'deriving_licence', 'birth', 'gender', 'skills', 'languages'
        );

        //update data
        $employee->update($input);

        //update cv
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

        //update audio
        if($request->has('audio')){
            if($employee->audio == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('audio')->getClientOriginalExtension();
                $request->file('audio')->move(base_path('public/uploads/employee/audio') , $path);
                $employee->audio   = $path;
            } else {
                $oldAudio = $employee->audio;

                //update audio
                $path = rand(0,1000000) . time() . '.' . $request->file('audio')->getClientOriginalExtension();
                $request->file('audio')->move(base_path('public/uploads/employee/audio') , $path);
                $employee->audio   = $path;

                //delet old audio
                if(file_exists(base_path('public/uploads/employee/audio/') . $oldAudio)){
                    unlink(base_path('public/uploads/employee/audio/') . $oldAudio);
                }   
            }
        }

        //update video
        if($request->has('video')){
            if($employee->video == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('video')->getClientOriginalExtension();
                $request->file('video')->move(base_path('public/uploads/employee/video') , $path);
                $employee->video   = $path;
            } else {
                $oldVideo = $employee->video;

                //update video
                $path = rand(0,1000000) . time() . '.' . $request->file('video')->getClientOriginalExtension();
                $request->file('video')->move(base_path('public/uploads/employee/video') , $path);
                $employee->video   = $path;

                //delet old video
                if(file_exists(base_path('public/uploads/employee/video/') . $oldVideo)){
                    unlink(base_path('public/uploads/employee/video/') . $oldVideo);
                }
            }
        }

        //update image
        if($request->has('image')){
            if($employee->image == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/employee/image') , $path);
                $employee->image   = $path;
            } else {
                $oldImage = $employee->image;

                //update image
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
        //get employer that login
        if (! $employer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        // validate
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
        $employer = Employer::find($employer->id);

        //update employer data
        $input = $request->only(
            'fullName', 'title', 'mobile_number1', 'mobile_number2','company_name',
            'country_id','city_id','business','established_at','website'
        );

        $employer->update($input);

        //update image
        if($request->has('image')){
            if($employer->image == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/employer/image') , $path);
                $employer->image   = $path;
            } else {
                $oldImage = $employer->image;

                //update image
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
        //get employee data
        if (! $employee = auth('employee')->user()) {
            return response::falid('user_not_found', 404);
        }

        // validate
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
        //get employeer data
        if (! $employer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        //validate
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
