<?php

namespace App\Http\Controllers\Api\site;

use App\CustomClass\response;
use App\Http\Resources\categoryWithJobCountResource;
use App\Http\Resources\jobResource;
use App\Http\Resources\adResource;
use App\Http\Resources\employerResource;
use App\Models\Category;
use App\Models\Country;
use App\Models\job;
use App\Models\Ad;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class guestController
{
    public function mainPage(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'text'    => 'nullable|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get all active jobs if user don't pass text(search)
       //and search if user pass text 

        $jobs = Job::NotCome()
                    ->where('status', '=', 1)
                    ->where('title', 'LIKE', '%' . $request->get('text') . '%')
                    ->orderBy('id', 'desc')
                    ->paginate(6);
        
        return response()->json([
            'status'  => true,
            'message' => 'success',
            'jobs' => jobResource::collection($jobs)->response()->getData(true),
        ],200);
    }

    public function jobDetails(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'job_id'    => 'required|exists:jobs,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //select job if it's active
        $job = job::where('status', '=', 1)->where('id', '=', $request->get('job_id'))->first();

        if($job == null){
            return response::falid('this job not found', 404);
        }

        return response::suceess('success', 200, 'jobDetails',new jobResource($job));
    }

    public function categories(Request $request){
        //select all categories with job count where not closed
        $categorys = Category::withCount(['job' => function($query) {
            $query->NotCome()->where('status', '=', 1);
        }])->get();

        return categoryWithJobCountResource::collection($categorys);
    }

    public function countries(){
        return Country::with('cities')->get();
    }

    public function companyDetails(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'employer_id'    => 'required|exists:employers,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employer
        $employer = Employer::find($request->get('employer_id'));

        return response::suceess('success', 200, 'employer', new employerResource($employer));
    }

    public function jobCategory(Request $request){
        //validaion
        $validator = Validator::make($request->all(), [
            'category_id'    => 'required|exists:categories,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        $jobs = Job::NotCome()
                    ->where('category_id', '=', $request->get('category_id'))
                    ->where('status', '=', 1)
                    ->orderBy('id', 'desc')
                    ->get();

        return response::suceess('success', 200, 'jobs', jobResource::collection($jobs));
    }
}
