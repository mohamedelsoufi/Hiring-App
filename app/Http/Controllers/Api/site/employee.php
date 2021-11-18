<?php

namespace App\Http\Controllers\Api\site;

use App\CustomClass\response;
use App\Http\Controllers\Controller;
use App\Http\Resources\avmeetingResource;
use App\Http\Resources\employeeJobsResource;
use App\Http\Resources\jobResource;
use App\Http\Resources\employerChatResource;
use App\Http\Resources\notificationResource;
use App\Models\Avmeeting;
use App\Models\EmployeeJob;
use App\Models\EmployeeNotifications;
use App\Models\EmployerNotifications;
use App\Models\job;
use App\Services\firbaseNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class employee extends Controller
{    
    public function __construct(firbaseNotifications $firbaseNotifications)
    {
        $this->firbaseNotifications = $firbaseNotifications;
    }

    public function myCandat(){
        //get employee data
        if (! $employee = auth('employee')->user()) {
            return response::falid('user_not_found', 404);
        }

        //get emplyee job (candate) for this employee
        $jobMeetings = EmployeeJob::whereHas('employee', function($q) use($employee){
            $q->where('employee_id', '=', $employee->id);
        })->where('candat_applay_status', '=', 1)->orderBy('id', 'desc')->get();

        return response::suceess('success', 200, 'candats' , employeeJobsResource::collection($jobMeetings));
    }

    public function availableMeetings(Request $request){
        // validation
        $validator = Validator::make($request->all(), [
            'job_id'         => 'required|exists:jobs,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get all avmeeting for this job
        $avmeeting = Avmeeting::where('available', '=', 0)->where('job_id', '=', $request->get('job_id'))->get();

        return response::suceess('success', 200, 'available_Meetings', avmeetingResource::collection($avmeeting));
    }

    public function applyforJob(Request $request){
        //get employee
        if (! $employee = auth('employee')->user()) {
            return response::falid('user_not_found', 404);
        }

        // validation
        $validator = Validator::make($request->all(), [
            'job_id'                   => 'required|exists:jobs,id|integer',
            'notification_title'       => 'required|string',
            'notification_body'        => 'required|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get job
        $job = Job::NotCome()->where('status', '=', 1)->find($request->get('job_id'));

        //check if this job is valid
        if($job == null){
            return response::falid('job closed', 200);
        }


        $EmployeeJob = EmployeeJob::where('job_id', '=', $request->get('job_id'))->where('employee_id', '=', $employee->id)->first();

        //if employee already aplay for this job
        if($EmployeeJob != null){
            return response::falid('you already apply for this job', 200);
        }

        //create employee job
        $employeeJob = EmployeeJob::create([
            'job_id' => $request->get('job_id'),
            'employee_id' => $employee->id,
        ]);

        //get employer to send notification for him
        $employer = job::find($request->get('job_id'))->employer;

        //create notificaion
        EmployerNotifications::create([
            'type'               => 1,
            'title'              => $request->get('title'),
            'body'               => $request->get('body'),
            'employer_id'        => $employer->id,
            'candate_id'         => $employeeJob->id,
        ]);

        //send notificaion
        $this->firbaseNotifications->send_notification($request->get('title'), $request->get('body'), $employer->token);
        
        return response::suceess('apply for job success', 200);
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
        if (! $employee = auth('employee')->user()) {
            return response::falid('user_not_found', 404);
        }

        //get employee job (candate)
        $EmployeeJob = EmployeeJob::where('employee_id', '=', $employee->id)->find($request->get('candat_id'));

        if($EmployeeJob == null){
            return response::falid('this candate not found', 404);
        }

        //accept offer
        $EmployeeJob->meeting_time_status = 1;
        $EmployeeJob->save();

        return response::suceess('accept candat success', 200);
    }

    public function accept_offer_with_author_meeting(Request $request){
        //get employee
        if (! $employee = auth('employee')->user()) {
            return response::falid('user_not_found', 404);
        }

        // validation
        $validator = Validator::make($request->all(), [
            'candat_id'               => 'required|exists:employee_job,id|integer',
            'available_meetings_id'   => 'required|exists:avmeetings,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employee job (candate)
        $EmployeeJob = EmployeeJob::where('employee_id', '=', $employee->id)->find($request->get('candat_id'));

        //check if employee job is exist
        if($EmployeeJob == null){
            return response::falid('this candate not found', 404);
        }

        //get meeting time (and check if it is available)
        $avmeetings = Avmeeting::where('available', '=', 0)->where('job_id', '=', $EmployeeJob->job_id)->find($request->get('available_meetings_id'));

        //this meeting time is wrong(not avilable or not found or not for this job)
        if($avmeetings == null){
            return response::falid('this meeting time nout available', 404);
        }

        //make old avmeeting => available
        $old_avmeeting = Avmeeting::find($EmployeeJob->avmeeting_id);
        $old_avmeeting->available = 0;
        $old_avmeeting->save();

        //update for new avmeeting and accept
        $EmployeeJob->avmeeting_id = $request->get('available_meetings_id');
        $EmployeeJob->meeting_time_status = 1;
        $EmployeeJob->save();

        $avmeetings->available = 1;
        $avmeetings->save();

        return response::suceess('accept candat success', 200);
    }

    public function myJobs(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'status'    => ['required',Rule::in(0,1,2,3)],
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employee
        if (! $employee = auth('employee')->user()) {
            return response::falid('user_not_found', 404);
        }

        //get emplyee job
        if($request->get('status') == 3){
            $jobs =  EmployeeJob::where('employee_id', '=', $employee->id)->where('candat_status', '!=', null)->orderBy('id', 'desc')->get();
        } else {
            $jobs =  EmployeeJob::where('employee_id', '=', $employee->id)->where('candat_status', '!=', null)->where('candat_status', '=', $request->get('status'))->orderBy('id', 'desc')->get();
        }

        return response::suceess('success', 200, 'jobs', employeeJobsResource::collection($jobs));
    }

    public function mySchedule(){
        //get employee
        if (! $employee = auth('employee')->user()) {
            return response::falid('user not found', 404);
        }

        //not confirmed employee job
        $notConfirmed = EmployeeJob::whereHas('job', function($q){
            $q->NotCome()->where('status', 1);
        })->where('employee_id', '=', $employee->id)->where('candat_applay_status', '=', 1)->where('meeting_time_status', '=', null)->orderBy('id', 'desc')->get();

        //confirmed employee job
        $confirmed = EmployeeJob::whereHas('job', function($q){
            $q->NotCome()->where('status', 1);
        })->where('employee_id', '=', $employee->id)->where('meeting_time_status', '=', 1)->orderBy('id', 'desc')->get();

        return response()->json([
            'status'  => true,
            'message' => 'success',
            'not_confirmed'=> employeeJobsResource::collection($notConfirmed),
            'confirmed'=> employeeJobsResource::collection($confirmed),
        ], 200);
    }

    public function mainPage(){
        //get employee data
        if (! $employee = auth('employee')->user()) {
            return response::falid('user_not_found', 404);
        }
        
        //get all valid jobs in emplyee categories
        $jobs = Job::NotCome()->where('category_id', '=', $employee->category_id)->where('status', '=', 1)->orderBy('id', 'desc')
                ->get();

        return response::suceess('success', 200, 'jobs', jobResource::collection($jobs));
    }

    public function alreadyApply(Request $request){
        //validaion
        $validator = Validator::make($request->all(), [
            'job_id'    => 'required|exists:jobs,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employee
        if (! $employee = auth('employee')->user()) {
            return response::falid('user_not_found', 404);
        }

        //get employee job
        $employeeJob = EmployeeJob::where('job_id', '=', $request->get('job_id'))->where('employee_id','=', $employee->id)->first();

        //if you are applay for this job
        if($employeeJob != null){
            return response::suceess('you applied for this job', 200);
        }

        //if you are applay for this job
        return response::falid('you don\'t apply for this job', 404);
    }

    //notification
    public function getEmplyeeNotification(){
        if (! $employee = auth('employee')->user()) {
            return response::falid('user_not_found', 404);
        }

        $notifications = EmployeeNotifications::where('employee_id', '=', $employee->id)->orderBy('id', 'desc')->get();

        return response::suceess('success', 200, 'notifications', notificationResource::collection($notifications));
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

        //if this notificaion nout found
        if($employeeNotification == null){
            return response::falid('this notification not found', 404);
        }

        //delete notificaion
        if($employeeNotification->delete()){
            return response::suceess('success', 200);
        }
    }

    //search
    public function jobSearch(Request $request){
        //validaion
        $validator = Validator::make($request->all(), [
            'text'    => 'nullable|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employee
        if (! $employee = auth('employee')->user()) {
            return response::falid('user_not_found', 404);
        }

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

    public function recommended_jobsSearch(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'text'    => 'nullable|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employee data
        if (! $employee = auth('employee')->user()) {
            return response::falid('user_not_found', 404);
        }

        $recommended_jobs = Job::NotCome()
                                ->where('status', '=', 1)
                                ->where('category_id', '=', $employee->category_id)
                                ->where('title', 'LIKE', '%' . $request->get('text') . '%')
                                ->orderBy('id', 'desc')
                                ->paginate(6);

        return response()->json([
            'status'  => true,
            'message' => 'success',
            'jobs' => jobResource::collection($recommended_jobs)->response()->getData(true),
        ],200);
    }

    public function FilterJob(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'job_field'         =>'nullable|exists:categories,id',
            'job_specialize'    =>'nullable|exists:categories,id',
            'title'             =>'nullable|string',
            'salary'            =>'nullable|string',
            'experience'        =>'nullable|integer',
            'country_id'        =>'nullable|exists:countries,id',
            'city_id'           =>'nullable|exists:cities,id',
            'meeting_date'      =>'nullable | date ',
            'gender'            =>'nullable | string',
            'qualification'     =>'nullable | string',

        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        $jobs = Job::NotCome()->where('status', '=', 1);

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

        return response()->json([
            'status'  => true,
            'message' => 'success',
            'jobs' => jobResource::collection($jobs)->response()->getData(true),
        ],200);
    }
}