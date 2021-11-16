<?php

namespace App\Http\Controllers\Api\site\auth;

use App\CustomClass\response;
use App\Http\Controllers\Controller;
use App\Http\Resources\employeeResource;
use App\Http\Resources\employerResource;
use App\Models\Employees;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;


class register extends Controller
{
    public function registerEmployee(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'fullName'         => 'required|string',
            'email'             => 'required|string|email|max:255|unique:employees',
            'password'          => 'required|string|min:6',
            'confirmPassword'   => 'required|string|same:password',
            'country_id'        => 'required|integer|exists:countries,id|integer',
            'city_id'           => 'required|integer|exists:cities,id|integer',
            'phone'             => 'required|string|min:8|regex:/(01)[0-2]{1}[0-9]{8}/',
            'title'             => 'required|string|max:250',
            'qualification'     => 'required|string|max:250',
            'university'        => 'required|string|max:250',
            'graduation_year'   => 'required|integer|max:5000',
            'experience'        => 'required|integer',
            'study_field'       => 'required|string|max:250',
            'deriving_licence'  => 'required|boolean',
            'birth'             => 'required|string',
            'deriving_licence'  => ['required',Rule::in(0,1)],      //0->no 1->yes
            'gender'            => ['required',Rule::in(0,1,2)],    //0->male  1->female 2->other
            'skills'            => 'array|required',
            'skills.*'          => 'required|string',
            'industry'          => 'required|exists:categories,id|integer',
            'languages'         => 'array|required',
            'languages.*'       => 'required|string',
            'cv'                => 'nullable|mimes:doc,pdf,docx',
            'audio'             => 'nullable|file',
            'video'             => 'nullable|mimes:mp4,mov,ogg',
            'image'             => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //create employee
        $employee = Employees::create([
            'fullName'          => $request->get('fullName'),
            'email'             => $request->get('email'),
            'country_id'        => $request->get('country_id'),
            'city_id'           => $request->get('city_id'),
            'password'          => Hash::make($request->get('password')),
            'category_id'      => $request->get('industry'),
            'experience'       => $request->get('experience'),
            'title'            => $request->get('title'),
            'qualification'    => $request->get('qualification'),
            'university'       => $request->get('university'),
            'graduation_year'  => $request->get('graduation_year'),
            'study_field'      => $request->get('study_field'),
            'deriving_licence' => $request->get('deriving_licence') ,
            'birth'            => $request->get('birth'),
            'gender'           => $request->get('gender'),
            'languages'        => $request->get('languages'),           
            'skills'           => $request->get('skills'),
            'phone'             =>$request->get('phone'),
        ]);

        //upload cv
        if($request->has('cv')){
            $path = rand(0,1000000) . time() . '.' . $request->file('cv')->getClientOriginalExtension();
            $request->file('cv')->move(base_path('public/uploads/employee/cv') , $path);
            $employee->cv   = $path;
        }

        //upload audio
        if($request->has('audio')){
            $path = rand(0,1000000) . time() . '.' . $request->file('audio')->getClientOriginalExtension();
            $request->file('audio')->move(base_path('public/uploads/employee/audio') , $path);
            $employee->audio   = $path;
        }

        //upload video
        if($request->has('video')){
            $path = rand(0,1000000) . time() . '.' . $request->file('video')->getClientOriginalExtension();
            $request->file('video')->move(base_path('public/uploads/employee/video') , $path);
            $employee->video   = $path;
        }

        //upload image
        if($request->has('image')){
            $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path('public/uploads/employee/image') , $path);
            $employee->image   = $path;
        }

        $employee->save();

        //auth
        $token = JWTAuth::fromUser($employee);

        return response()->json([
            "status" => true,
            'message'=> 'register success',
            'employee'   => new employeeResource(Employees::find($employee->id)),
            'token'  => $token,
        ], 200);
    }

    public function socialiteRegisterEmployee(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'fullName'         => 'required|string',
            'email'             => 'required|string|email|max:255|unique:employees',
            'country_id'        => 'required|integer|exists:countries,id|integer',
            'city_id'           => 'required|integer|exists:cities,id|integer',
            
            'title'             => 'required|string|max:250',
            'qualification'     => 'required|string|max:250',
            'university'        => 'required|string|max:250',
            'graduation_year'   => 'required|integer|max:5000',
            'experience'        => 'required|integer',
            'study_field'       => 'required|string|max:250',
            'deriving_licence'  => 'required|boolean',
            'birth'             => 'required|string',
            'deriving_licence'  => ['required',Rule::in(0,1)],      //0->no 1->yes
            'gender'            => ['required',Rule::in(0,1,2)],    //0->male  1->female 2->other
            'skills'            => 'array|required',
            'skills.*'          => 'required|string',
            'industry'          => 'required|exists:categories,id|integer',
            'languages'         => 'array|required',
            'languages.*'       => 'required|string',
            'cv'                => 'nullable|mimes:doc,pdf,docx',
            'audio'             => 'nullable|file',
            'video'             => 'nullable|mimes:mp4,mov,ogg',
            'image'             => 'nullable|mimes:jpg,jpeg,png,svg',
            'token_firebase'    => 'required|string',
            'email_id'          => 'required|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //check if employee email is exist
        $employee = Employees::where('email', '=', $request->email)->first();

        if($employee == null){
            //make acount
            $employee = Employees::create([
                'fullName'          => $request->get('fullName'),
                'email'             => $request->get('email'),
                'country_id'        => $request->get('country_id'),
                'city_id'           => $request->get('city_id'),
                'password'          => Hash::make($request->get('password')),
    
                'category_id'      => $request->get('industry'),
                'experience'       => $request->get('experience'),
                'title'            => $request->get('title'),
                'qualification'    => $request->get('qualification'),
                'university'       => $request->get('university'),
                'graduation_year'  => $request->get('graduation_year'),
                'study_field'      => $request->get('study_field'),
                'deriving_licence' => $request->get('deriving_licence') ,
                'birth'            => $request->get('birth'),
                'gender'           => $request->get('gender'),
                'languages'        => $request->get('languages'),           
                'skills'           => $request->get('skills'),
                'token'            => $request->get('token_firebase'),
                'active'           => 1,
            ]);
    
            //upload cv
            if($request->has('cv')){
                $path = rand(0,1000000) . time() . '.' . $request->file('cv')->getClientOriginalExtension();
                $request->file('cv')->move(base_path('public/uploads/employee/cv') , $path);
                $employee->cv   = $path;
            }
    
    
            //upload audio
            if($request->has('audio')){
                $path = rand(0,1000000) . time() . '.' . $request->file('audio')->getClientOriginalExtension();
                $request->file('audio')->move(base_path('public/uploads/employee/audio') , $path);
                $employee->audio   = $path;
            }
    
            //upload video
            if($request->has('video')){
                $path = rand(0,1000000) . time() . '.' . $request->file('video')->getClientOriginalExtension();
                $request->file('video')->move(base_path('public/uploads/employee/video') , $path);
                $employee->video   = $path;
            }
    
            //upload image
            if($request->has('image')){
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/employee/image') , $path);
                $employee->image   = $path;
            }
    
            $employee->save();

            //auth
            $token = JWTAuth::fromUser($employee);

            return response()->json([
                "status"    => true,
                'message'   => 'register success',
                'employee'  => new employeeResource($employee),
                'token'     => $token,
            ], 200);
        }

        return response::falid('this acount already register', 400);
    }

    public function registerEmpolyer(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'fullName'          => 'required|string',
            'email'             => 'required|string|email|max:255|unique:employers',
            'password'          => 'required|string|min:6',
            'confirmPassword'   => 'required|string|same:password',
            'title'             => 'required|string',
            'mobile_number1'    => 'required|string|min:8',
            'mobile_number2'    => 'nullable|string|min:8',

            'company_name'      => 'required|string|max:250',
            'business'          => 'required|exists:categories,id|integer',
            'established_at'    => 'required|string|max:250',
            'website'           => 'required|string|max:250',
            'country_id'        => 'required|integer|exists:countries,id|integer',
            'city_id'           => 'required|integer|exists:cities,id|integer',
            'image'             => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //create employer
        $employer = Employer::create([
            'fullName'          => $request->get('fullName'),
            'email'             => $request->get('email'),
            'title'             => $request->get('title'),
            'mobile_number1'    => $request->get('mobile_number1'),
            'password'          => Hash::make($request->get('password')),

            'company_name'      => $request->get('company_name'),
            'business'          => $request->get('business'),
            'established_at'    => $request->get('established_at'),
            'website'           => $request->get('website'),
            'country_id'        => $request->get('country_id'),
            'city_id'           => $request->get('city_id'),
        ]);

        //phone 2
        if($request->has('mobile_number2')){
            $employer->mobile_number2   = $request->get('mobile_number2');
            $employer->save();
        }

        //add image
        if($request->has('image')){
            //add image
            $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path('public/uploads/employer/image') , $path);
            $employer->image   = $path;
            $employer->save();
        }

        //auth
        $token = JWTAuth::fromUser($employer);

        return response()->json([
            "status"    => true,
            'message'   => 'register success',
            'employer'   => new employerResource(Employer::find($employer->id)),
            'token'     => $token,
        ], 200);
    }
}
