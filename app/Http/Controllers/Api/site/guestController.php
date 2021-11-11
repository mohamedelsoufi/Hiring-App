<?php

namespace App\Http\Controllers\Api\site;

use App\CustomClass\response;
use App\Http\Resources\categoryWithJobCountResource;
use App\Http\Resources\jobResource;
use App\Http\Resources\adResource;
use App\Models\Category;
use App\Models\Country;
use App\Models\job;
use App\Models\Ad;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class guestController
{
    public function getAllAds()
    {
        $ads=Ad::where('status','publish')->get();
        if($ads->count() <= 0){
            return response()->json([
                'status'  => false,
                'message' => 'There is No Ads',
            ],200);
        }
        return response()->json([
            'status'  => true,
            'message' => 'success',
            'ads' => adResource::collection($ads),
        ],200);
    }
    public function mainPage(Request $request){
        date_default_timezone_set('Africa/cairo');

        $validator = Validator::make($request->all(), [
            'text'    => 'nullable|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }


        $jobs = Job::where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'))->where('status', '=', 1)->where('title', 'LIKE', '%' . $request->get('text') . '%')->orderBy('id', 'desc')->orWhere('meeting_date', '>', date('Y-m-d'))->where('status', '=', 1)->where('title', 'LIKE', '%' . $request->get('text') . '%')->orderBy('id', 'desc')->paginate(6);
        
        return response()->json([
            'status'  => true,
            'message' => 'success',
            'jobs' => jobResource::collection($jobs)->response()->getData(true),
        ],200);
    }

    public function jobDetails(Request $request){

        $validator = Validator::make($request->all(), [
            'job_id'    => 'required|exists:jobs,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }
        $job = job::where('status', '=', 1)->where('id', '=', $request->get('job_id'))->first();
        
        if($job == null){
            return response::falid('this job not found', 404);
        }

        return response::suceess('success', 200, 'jobDetails',new jobResource($job));
    }

    public function categories(Request $request){
        date_default_timezone_set('Africa/cairo');
        //select all categories with job count where not closed
        $categorys = Category::withCount(['job' => function($query) {
            $query->where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'))->where('status', '=', 1)->orWhere('meeting_date', '>', date('Y-m-d'))->where('status', '=', 1);
        }])->get();


        return categoryWithJobCountResource::collection($categorys);
    }

    public function countries(){
        return Country::with('cities')->get();
    }
    public function fieldWithSpecila()
    {
        $fieldWithSpecial=Category::where('parent_id',null)->with('jobspecial')->get();
        if($fieldWithSpecial->count() <= 0){
            return response()->json([
                'status'  => false,
                'message' => 'There is No Filed',
            ],200);
        }
        return response()->json([
            'status'  => true,
            'message' => 'success',
            'fieldWithSpecial' => $fieldWithSpecial,
        ],200);
    }
}
