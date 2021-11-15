<?php

namespace App\Http\Controllers\Api\site;

use App\CustomClass\response;
use App\Http\Controllers\Controller;
use App\Http\Resources\avmeetingResource;
use App\Http\Resources\employerResource;
use App\Http\Resources\jobMeetingsResource;
use App\Http\Resources\jobResource;
use App\Http\Resources\employerChatResource;
use App\Http\Resources\notificationResource;
use App\Models\Avmeeting;
use App\Models\Chat;
use App\Models\EmployeeJob;
use App\Models\Employer;
use App\Models\EmployeeChat;
use App\Models\EmployeeNotifications;
use App\Models\EmployerNotifications;
use App\Models\job;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class employee extends Controller
{
    private $FIREBASE_SERVER_API_KEY = 'AAAAehKcgrI:APA91bFvsZKUikAV8p3c3pt6Rm0GeCCgnHQQW4mizDEVml8UFpbjFQzZZWjOtFP6oxxnNj_BqaTv8DHu5ktxwRaU95RiG2oRemvI9HI5zNCARyMpN8vV5beQE4AZGg-AWFjrqQwR9j24';

    // public function jobDetails(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'job_id'    => 'required|exists:jobs,id|integer',
    //     ]);

    //     if($validator->fails()){
    //         return response::falid($validator->errors(), 422);
    //     }
    //     $job = job::where('status', '=', 1)->where('id', '=', $request->get('job_id'))->first();

    //     if($job == null){
    //         return response::falid('this job not found', 404);
    //     }

    //     return response::suceess('success', 200, 'jobDetails', new jobResource($job));
    // }

    public function myCandat(){
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $jobMeetings = EmployeeJob::whereHas('employee', function($q) use($employee){
            $q->where('employee_id', '=', $employee->id);
        })->where('candat_applay_status', '=', 1)->orderBy('id', 'desc')->get();

        return response::suceess('success', 200, 'candats' , jobMeetingsResource::collection($jobMeetings));
    }

    public function mainPage(){
        date_default_timezone_set('Africa/cairo');

        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $jobs = Job::where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'))->where('category_id', '=', $employee->category_id)->where('status', '=', 1)->orderBy('id', 'desc')->orWhere('meeting_date', '>', date('Y-m-d'))->where('category_id', '=', $employee->category_id)->where('status', '=', 1)->orderBy('id', 'desc')->get();

        return response::suceess('success', 200, 'jobs', jobResource::collection($jobs));

    }

    public function companyDetails(Request $request){
        $validator = Validator::make($request->all(), [
            'employer_id'    => 'required|exists:employers,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        return response::suceess('success', 200, 'employer', new employerResource(Employer::find($request->get('employer_id'))));
    }

    public function alreadyApply(Request $request){
        $validator = Validator::make($request->all(), [
            'job_id'    => 'required|exists:jobs,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employee
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $employeeJob = EmployeeJob::where('job_id', '=', $request->get('job_id'))->where('employee_id','=', $employee->id)->first();

        if($employeeJob != null){
            return response::suceess('you applied for this job', 200);
        }

        return response::falid('you don\'t apply for this job', 404);
    }

    public function applyforJob(Request $request){
        date_default_timezone_set('Africa/cairo');

        //get employee
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }
        // validation
        $validator = Validator::make($request->all(), [
            'job_id'        => 'required|exists:jobs,id|integer',
            'title'         => 'required|string',
            'body'          => 'required|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }


        $job = Job::where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'))->where('status', '=', 1)->orWhere('meeting_date', '>', date('Y-m-d'))->where('status', '=', 1)->find($request->get('job_id'));

        if($job == null){
            return response::falid('job closed', 200);
        } else {
            //check if employee already apply for this job
            $EmployeeJob = EmployeeJob::where('job_id', '=', $request->get('job_id'))->where('employee_id', '=', $employee->id)->first();

            if($EmployeeJob == null){
                $employeeJob = EmployeeJob::create([
                    'job_id' => $request->get('job_id'),
                    'employee_id' => $employee->id,
                ]);
                //notificaion

                //get employer
                $employer = job::find($request->get('job_id'))->employer;

                $notification = EmployerNotifications::create([
                    'type'               => 1,
                    'title'              => $request->get('title'),
                    'body'               => $request->get('body'),
                    'employer_id'        => $employer->id,
                    'candate_id'         => $employeeJob->id,
                ]);

                $SERVER_API_KEY = $this->FIREBASE_SERVER_API_KEY;

                $token_1 = $employer->token;

                $data = [

                    "registration_ids" => [
                        $token_1
                    ],

                    "notification" => [
                        "title"         => $notification->title,
                        "body"          => $notification->body,
                        'employee_id'   => $employeeJob->employee_id,
                        'job_id'        => $employeeJob->job_id,
                        "sound"         => "default" // required for sound on ios
                    ],

                ];

                $dataString = json_encode($data);

                $headers = [

                    'Authorization: key=' . $SERVER_API_KEY,

                    'Content-Type: application/json',

                ];

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

                curl_setopt($ch, CURLOPT_POST, true);

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

                $response = curl_exec($ch);

                return response::suceess('apply for job success', 200);
            } else {
                return response::falid('you already apply for this job', 200);
            }
        }
    }

    public function acceptOffer(Request $request){
        // validation
        $validator = Validator::make($request->all(), [
            'candat_id'    => 'required|exists:employee_job,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employee
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }


        $EmployeeJob = EmployeeJob::where('employee_id', '=', $employee->id)->find($request->get('candat_id'));

        if($EmployeeJob == null){
            return response::falid('this employee not found', 404);
        } else {
            //accept
            $EmployeeJob->meeting_time_status = 1;
            $EmployeeJob->save();
            return response::suceess('accept candat success', 200);
        }

    }

    public function accept_offer_with_author_meeting(Request $request){
        //get employee
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        // validation
        $validator = Validator::make($request->all(), [
            'candat_id'    => 'required|exists:employee_job,id|integer',
            'meetings_time'   => 'required|exists:avmeetings,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        $EmployeeJob = EmployeeJob::where('employee_id', '=', $employee->id)->find($request->get('candat_id'));

        if($EmployeeJob == null){
            return response::falid('this employee not found', 404);
        } else {
            //get meeting time (and check if it is available)
            $avmeetings = Avmeeting::where('available', '=', 0)->where('job_id', '=', $EmployeeJob->job_id)->find($request->get('meetings_time'));

            if($avmeetings == null){
                return response::falid('this meeting time nout available', 404);
            } else {
                //make first avmeeting => available
                $first_avmeeting = Avmeeting::find($EmployeeJob->avmeeting_id);
                $first_avmeeting->available = 0;
                $first_avmeeting->save();

                //update for new avmeeting and accept
                $EmployeeJob->avmeeting_id = $request->get('meetings_time');
                $EmployeeJob->meeting_time_status = 1;
                $EmployeeJob->save();

                $avmeetings->available = 1;
                $avmeetings->save();

                return response::suceess('accept candat success', 200);
            }

        }
    }

    public function availableMeetings(Request $request){
        $validator = Validator::make($request->all(), [
            'job_id'         => 'required|exists:jobs,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {
            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        //check if job for this employer
        $avmeeting = avmeetingResource::collection(Avmeeting::where('available', '=', 0)->where('job_id', '=', $request->get('job_id'))->get());

        return response::suceess('success', 200, 'available_Meetings', $avmeeting);
    }

    public function myJobs(Request $request){
        date_default_timezone_set('Africa/cairo');

        $validator = Validator::make($request->all(), [
            'status'    => ['required',Rule::in(0,1,2,3)],
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        // sellect employee
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        if($request->get('status') == 3){
            $jobs =  EmployeeJob::where('employee_id', '=', $employee->id)->where('candat_status', '!=', null)->orderBy('id', 'desc')->get();
        } else {
            $jobs =  EmployeeJob::where('employee_id', '=', $employee->id)->where('candat_status', '!=', null)->where('candat_status', '=', $request->get('status'))->orderBy('id', 'desc')->get();
        }

        return response::suceess('success', 200, 'jobs', jobMeetingsResource::collection($jobs));
    }

    public function mySchedule(){
        date_default_timezone_set('Africa/cairo');

        // sellect employee
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }
        $notConfirmed = EmployeeJob::whereHas('job', function($q){
            $q->where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'))->orWhere('meeting_date', '>', date('Y-m-d'));
        })->where('employee_id', '=', $employee->id)->where('candat_applay_status', '=', 1)->where('meeting_time_status', '=', null)->orderBy('id', 'desc')->get();

        $confirmed = EmployeeJob::whereHas('job', function($q){
            $q->where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'))->orWhere('meeting_date', '>', date('Y-m-d'));
        })->where('employee_id', '=', $employee->id)->where('meeting_time_status', '=', 1)->orderBy('id', 'desc')->get();

        return response()->json([
            'status'  => true,
            'message' => 'success',
            'not_confirmed'=> jobMeetingsResource::collection($notConfirmed),
            'confirmed'=> jobMeetingsResource::collection($confirmed),
        ], 200);
    }

    public function jobCategory(Request $request){
        date_default_timezone_set('Africa/cairo');

        $validator = Validator::make($request->all(), [
            'category_id'    => 'required|exists:categories,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        $jobs = Job::where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'))->where('category_id', '=', $request->get('category_id'))->where('status', '=', 1)->orderBy('id', 'desc')->orWhere('meeting_date', '>', date('Y-m-d'))->where('category_id', '=', $request->get('category_id'))->where('status', '=', 1)->orderBy('id', 'desc')->get();

        return response::suceess('success', 200, 'jobs', jobResource::collection($jobs));
    }

    //chat
    public function myScheduleChat(Request $request){
        //get user by token
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }
        //get employees that the employer chat with thim
        $employers = Employer::whereHas('employeeChat', function($q) use($employee){
            $q->where('employee_id', '=', $employee->id);
        })->orWhereHas('employerChat', function($q) use($employee){
            $q->where('employee_id', '=', $employee->id);
        })->get();

        //pass employee id to employee
        $request->employee_id = $employee->id;

        return response::suceess('success', 200, 'employers', employerChatResource::collection($employers));
    }

    public function makeChat(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'employer_id'           => 'required|exists:employers,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get user by token
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        //check if i already make chat
        $employerChat = EmployeeChat::where('employee_id', '=', $employee->id)->where('employer_id', '=', $request->get('employer_id'))->first();

        if($employerChat == null){
            //if this employee don't chat with this employer
            EmployeeChat::create([
                'employer_id' => $request->get('employer_id'),
                'employee_id' => $employee->id,
            ]);
        } else {
            //if this employee already caht with this employee
            $employerChat->delete();
            EmployeeChat::create([
                'employer_id' => $request->get('employer_id'),
                'employee_id' => $employee->id,
            ]);
        }

        return response::suceess('success', 200);
    }

    //notification
    public function getEmplyeeNotification(){
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $notifications = EmployeeNotifications::where('employee_id', '=', $employee->id)->orderBy('id', 'desc')->get();
        return response::suceess('success', 200, 'notifications', notificationResource::collection($notifications));

    }

    public function makeChatNotification(Request $request){
        $validator = Validator::make($request->all(), [
            'employer_id'           => 'required|exists:employers,id|integer',
            'title'                 => 'required',
            'body'                  => 'required',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        $employerToken = Employer::find($request->get('employer_id'))->token;


        $SERVER_API_KEY = $this->FIREBASE_SERVER_API_KEY;

        $token_1 = $employerToken;

        $data = [

            "registration_ids" => [
                $token_1
            ],

            "notification" => [

                "title" => $request->get('title'),

                "body" => $request->get('body'),

                "sound"=> "default" // required for sound on ios

            ],

        ];

        $dataString = json_encode($data);

        $headers = [

            'Authorization: key=' . $SERVER_API_KEY,

            'Content-Type: application/json',

        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        return response::suceess('success', 200);
    }

    public function removeNotification(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'notification_id'           => 'required|exists:employeenotifications,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        $employeeNotification = EmployeeNotifications::find($request->get('notification_id'));

        if($employeeNotification == null){
            return response::falid('this notification not found', 404);
        }

        if($employeeNotification->delete()){
            return response::suceess('success', 200);
        }
    }

    //search
    public function jobSearch(Request $request){
        date_default_timezone_set('Africa/cairo');

        $validator = Validator::make($request->all(), [
            'text'    => 'nullable|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $jobs = Job::where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'))
        ->where('status', '=', 1)->where('title', 'LIKE', '%' . $request->get('text') . '%')
        ->orderBy('id', 'desc')->orWhere('meeting_date', '>', date('Y-m-d'))->where('status', '=', 1)
        ->where('title', 'LIKE', '%' . $request->get('text') . '%')->orderBy('id', 'desc')->paginate(6);

        // 'authorJobs'=> jobResource::collection($authorJobs)->response()->getData(true),

        return response()->json([
            'status'  => true,
            'message' => 'success',
            'jobs' => jobResource::collection($jobs)->response()->getData(true),
        ],200);
    }
    public function FilterJob(Request $request){
        date_default_timezone_set('Africa/cairo');

        $validator = Validator::make($request->all(), [
            'job_field'         => 'nullable|exists:categories,id',
            'job_specialize'    => 'nullable|exists:categories,id',
            'title'             =>'nullable|string',
            'salary'            =>'nullable|string',
            'experience'        =>'nullable|integer',
            'country_id'        => 'nullable|exists:countries,id',
            'city_id'           => 'nullable|exists:cities,id',
            'meeting_date'      =>'nullable | date ',
            'gender'            =>'nullable | string',
            'qualification'     =>'nullable | string',

        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        // try {
        //     if (! $employee = auth('employee')->user()) {
        //         return response::falid('user_not_found', 404);
        //     }

        // } catch (TokenExpiredException $e) {

        //     return response::falid('token_expired', 400);

        // } catch (TokenInvalidException $e) {

        //     return response::falid('token_invalid', 400);

        // } catch (JWTException $e) {

        //     return response::falid('token_absent', 400);
        // }

        $jobs = Job::where('status', '=', 1);

        if($request->has('job_field') && $request->job_field !=null){
            $jobs->where('category_id',$request->job_field);
         }
        if($request->has('job_specialize') && $request->job_specialize !=null){
            $jobs->where('job_specialize',$request->job_specialize);
        }
        if($request->has('country_id') && $request->country_id !=null){
            $jobs->where('country_id',$request->country_id);
        }
        if($request->has('city_id') && $request->city_id !=null){
            $jobs->where('city_id',$request->city_id);
        }
        if($request->has('experience') && $request->experience !=null){
            $jobs->where('experience',$request->experience);
        }
        if($request->has('salary') && $request->salary !=null){
            $jobs->where('salary',$request->salary);
        }
        if($request->has('meeting_date') && $request->meeting_date !=null){
            $jobs->where('meeting_date',$request->meeting_date);
        }
        if($request->has('title') && $request->title !=null){
            $jobs->where('title','like','%' . $request->title . '%');
        }
        if($request->has('gender') && $request->gender !=null){
            $jobs->where('gender','like','%' . $request->gender . '%');
        }
        if($request->has('qualification') && $request->qualification !=null){
            $jobs->where('qualification','like','%' . $request->qualification . '%');
        }
        $jobs=$jobs->latest()->paginate(2);
        // 'authorJobs'=> jobResource::collection($authorJobs)->response()->getData(true),

        return response()->json([
            'status'  => true,
            'message' => 'success',
            'jobs' => jobResource::collection($jobs)->response()->getData(true),
        ],200);
    }


    public function recommended_jobsSearch(Request $request){
        date_default_timezone_set('Africa/cairo');

        $validator = Validator::make($request->all(), [
            'text'    => 'nullable|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $recommended_jobs = Job::where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'))->where('status', '=', 1)->where('category_id', '=', $employee->category_id)->where('title', 'LIKE', '%' . $request->get('text') . '%')->orderBy('id', 'desc')->orWhere('meeting_date', '>', date('Y-m-d'))->where('status', '=', 1)->where('category_id', '=', $employee->category_id)->where('title', 'LIKE', '%' . $request->get('text') . '%')->orderBy('id', 'desc')->paginate(6);

        // 'authorJobs'=> jobResource::collection($authorJobs)->response()->getData(true),

        return response()->json([
            'status'  => true,
            'message' => 'success',
            'jobs' => jobResource::collection($recommended_jobs)->response()->getData(true),
        ],200);
    }
    public function readEmployeeNotify()
    {
        try {
            if (! $employee = auth('employee')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }
        $notifies=EmployeeNotifications::where('employee_id',$employee->id)->where('read_at',null)->get();
        if($notifies->count() <= 0)
        {
            return response::falid('there is no Notifications',404);
        }
        foreach($notifies as $notify)
        {
            $notify->update(['read_at'=>date('d/m/y h:m:i')]);
        }
        return response()->json([
            'successfully'=>true,
            'message'=>'all notify read successfully',
        ]);
    }
}