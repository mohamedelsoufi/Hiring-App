<?php

namespace App\Http\Controllers\Api\site;

use App\CustomClass\response;
use App\Http\Resources\acceptedEmployeeResource;
use App\Http\Resources\avmeetingResource;
use App\Http\Resources\categoryWithJobCountResource;
use App\Http\Resources\emoloyerNotificationResource;
use App\Http\Resources\employee_candat_status;
use App\Http\Resources\employeeResource;
use App\Http\Resources\jobAvMeetings;
use App\Http\Resources\employeeJobsResource;
use App\Http\Resources\jobResource;
use App\Http\Resources\employeeChatResource;
use App\Http\Resources\employerResource;
use App\Models\Avmeeting;
use App\Models\Category;
use App\Models\EmployeeJob;
use App\Models\EmployeeNotifications;
use App\Models\Employees;
use App\Models\Employer as ModelsEmployer;
use App\Models\EmployerChat;
use App\Models\EmployerNotifications;
use App\Models\job;
use App\Models\Report;
use App\Service\firbaseNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Validation\Rule;


class employer
{
    public function __construct(firbaseNotifications $firbaseNotifications)
    {
        $this->firbaseNotifications = $firbaseNotifications;
    }

    //job meeting
    public function newjob(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'field'                 => 'required|exists:categories,id|integer',
            'title'                 =>'required | string | min:2 | max:60',
            'details'               =>'required | string | min:2 | max:1000',
            'note'                  =>'required | string | min:2 | max:1000',
            'salary'                =>'required | numeric',
            'gender'                =>['required',Rule::in(0,1,2)],
            'experience'            =>'required | integer',
            'qualification'         =>'required | string | min:2 | max:80',
            'interviewer_name'      =>'required | string | min:2 | max:60',
            'interviewer_role'      =>'required | string | min:2 | max:60',
            'meeting_date'          =>'required | date',
            'meeting_from'          =>'required',
            'meeting_to'            =>'required',
            'meeting_time'          =>'required | numeric',
        ]);

        if($request->get('meeting_to') < $request->get('meeting_from')){
            return response::falid('The start time should be after the end time', 400);
        }

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //check if job date and time not come
        if(($request->get('meeting_date') < date('Y-m-d')) || ($request->get('meeting_date') == date('Y-m-d')) && ($request->get('meeting_from') <= date('H:i:s'))){
            return response::falid('the job date and time is come', 400);
        }

        //get employer
        if (! $employer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        //create job(meeting)
        $job = job::create([
            'employer_id'       => $employer->id,
            'category_id'       => $request->get('field'),
            'title'             => $request->get('title'),
            'details'           =>$request->get('details'),
            'note'              =>$request->get('note'),
            'salary'            =>$request->get('salary'),
            'gender'            =>$request->get('gender'),
            'experience'        =>$request->get('experience'),
            'qualification'     =>$request->get('qualification'),
            'interviewer_name'  =>$request->get('interviewer_name'),
            'interviewer_role'  =>$request->get('interviewer_role'),
            'meeting_date'      =>$request->get('meeting_date'),
            'meeting_from'      =>$request->get('meeting_from'),
            'meeting_to'        => $request->get('meeting_to'),
            'meeting_time'      =>$request->get('meeting_time'),
            'status'            => 1,
        ]);

        //addd meeting time to our tables
        $for =strtotime($job->meeting_from);
        $to   =strtotime($job->meeting_to);
        $dif=floor(($to - $for)/60)/$job->meeting_time;
        $startTime = date("H:i", strtotime($job->meeting_from));
        for($i=0; $i<$dif; $i++)
        {
           $endTime = date("H:i", strtotime('+'. $job->meeting_time .' minutes', strtotime($startTime)));
           Avmeeting::create([
                'job_id'    =>$job->id,
                'time_from' =>$startTime,
                'time_to'   =>$endTime,
                'available' =>0,
           ]);
           $startTime=$endTime;
        }
        //end add meeting table to our time
        return response::suceess('add new job success', 200, 'new job', new jobResource($job));
    }

    public function jobEdit(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'job_id'                => 'required|exists:jobs,id|integer',
            'job_specialize'        => 'nullable|exists:categories,id|integer',
            'country_id'            => 'nullable|exists:countries,id|integer',
            'city_id'               => 'nullable|exists:cities,id|integer',
            'title'                 =>'nullable | string | min:2 | max:60',
            'field'                 =>'nullable|exists:categories,id|integer',
            'details'               =>'nullable | string | min:2 | max:1000',
            'salary'                =>'nullable | numeric',
            'gender'                =>['nullable',Rule::in(0,1,2)],
            'experience'            =>'nullable | integer',
            'qualification'         =>'nullable | string | min:2 | max:80',
            'meeting_date'          =>'nullable | date',
            'meeting_from'          =>'required_with:meeting_to,meeting_time',
            'meeting_to'            =>'required_with:meeting_from,meeting_time',
            'meeting_time'          =>'required_with:meeting_from,meeting_to | numeric',
            'interviewer_name'      =>'nullable | string | min:2 | max:60',
            'interviewer_role'      =>'nullable | string | min:2 | max:60',
            'note'                  =>'nullable | string | min:2 | max:1000',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employer data
        if (! $employer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        $job = job::where('employer_id', '=', $employer->id)->where('status', '=', 1)->find($request->get('job_id'));

        //this job not found
        if($job == null){
            return response::falid('this job not found', 404);
        }

        $input = $request->only(
            'title','field','details','salary','gender','experience',
            'qualification','interviewer_name','interviewer_role','note',
            
        );

        //update job
        $job->update($input);

        //check if meeting time , date, meeting form, meeting to if not changed
        if(($request->get('meeting_date') != $job->meeting_date) || ($request->get('meeting_from') != $job->meeting_from) || ($request->get('meeting_to') != $job->meeting_to) || ($request->get('meeting_time') != $job->meeting_time)){

            $count=$job->avmeetings->where('available',1)->count();

            if($count != 0){
                return response::falid('you can\'t edit job time', 400);
            }

            if($request->get('meeting_to') < $request->get('meeting_from')){
                return response::falid('The start time should be after the end time', 400);
            }

            //check if job date and time not come
            if( ($request->get('meeting_date') < date('Y-m-d')) || ($request->get('meeting_date') == date('Y-m-d')) && ($request->get('meeting_from') <= date('H:i:s'))){
                return response::falid('the job date and time is come', 400);
            }

            //update time (meeting)
            if($request->has('meeting_date')){
                $job->meeting_date  = $request->get('meeting_date');
            }
            if($request->has('meeting_from')){
                $job->meeting_from  = $request->get('meeting_from');
            }
            if($request->has('meeting_to')){
                $job->meeting_to    = $request->get('meeting_to');
            }
            if($request->has('meeting_time')){
                $job->meeting_time  = $request->get('meeting_time');
            }

            if($count > 0)
            {
                $request_data=$request->except(['meeting_date','meeting_from','meeting_to','meeting_time']);
                $job->update($request_data);
            }else{
                $job->avmeetings()->delete();
                $for =strtotime($request->get('meeting_from'));
                $to   =strtotime($request->get('meeting_to'));
                $dif=floor(($to - $for)/60)/$request->get('meeting_time');
                $startTime = date("H:i", strtotime($request->get('meeting_from')));
                for($i=0; $i<$dif; $i++)
                {
                $endTime = date("H:i", strtotime('+'. $request->get('meeting_time') .' minutes', strtotime($startTime)));
                Avmeeting::create([
                        'job_id'    =>$job->id,
                        'time_from' =>$startTime,
                        'time_to'   =>$endTime,
                        'available' =>0,
                ]);
                $startTime=$endTime;
                }
            }
        }

        //save edit
        if($job->save()){
            return response::suceess('update job success', 200, 'job', new jobResource($job));
        }

        return response::falid('edit job faild', 400);
    }

    public function JobCanceled(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'job_id'           => 'required|exists:jobs,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employer data
        if (! $employer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        $job = job::where('employer_id', '=', $employer->id)->find($request->get('job_id'));

        //there are no job with this id
        if($job == null){
            return response::falid('this job not found', 404);
        }

        //if some employee has meeting
        $count=$job->avmeetings->where('available',1)->count();
        if($count != 0){
            return response::falid('you can\'t delete this job now Because employee has a meeting', 400);
        }

        //cancelle meeting
        $candats = EmployeeJob::where('job_id', '=', $job->id)->get();

        foreach($candats as $candat){
            $candat->candat_status = 0;
            $candat->save();
        }

        // Cancelle (delete) job
        $job->status = 0;
        if($job->save()){
            return response::suceess('delete success', 200);
        }
    }

    public function report(Request $request){
        $validator = Validator::make($request->all(), [
            'employee_id'    => 'required|exists:employees,id|integer',
            'note'           => 'required|min:3',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employer data
        if (! $employeer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        $report = Report::where('employee_id', '=', $request->get('employee_id'))->where('employer_id', '=', $employeer->id)->get();

        //employer already add report
        if($report->first() != null){
            return response::falid('you already make report for this employee', 400);
        }

        //block employee if there are 10 employer reported him
        if($report->count() >= 10){
            $employee = Employees::find($request->get('employee_id'));
            $employee->block = 1;
            $employee->save();
        }

        //make reoprt for this employee
        Report::create([
            'employee_id' => $request->get('employee_id'),
            'employer_id' => $employeer->id,
            'note'        => $request->get('note'),
        ]);

        return response::suceess('make report success', 200);
    }

    public function review(Request $request){
        //get employer data
        if (! $employer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        //validation
        $validator = Validator::make($request->all(), [
            'candat_id'    => 'required|exists:employee_job,id|integer',
            'review'       => 'required'
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        $EmployeeJob = EmployeeJob::find($request->get('candat_id'));
        $job = $EmployeeJob->Job;

        //if this job not for you
        if($job->employer_id != $employer->id){
            return response::falid('this job not for you', 400);
        }

        //add or edit note in EmployeeJob
        $EmployeeJob->note = $request->get('review');
        
        if($EmployeeJob->save()){
            return response::suceess('success', 200, 'note', $request->get('review'));
        }
    }

    //before meeting
    public function myCandat(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'candat_status'           => ['required',Rule::in(0,1,2,3)],  // 0->reject 1->accept 2->underreview employer who detemine this, 3->all, ,
            'job_id'                  => 'required|exists:jobs,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employer data
        if (! $employer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        //get employee job
        if($request->get('candat_status') == 3){
            //all
            $jobMeetings = EmployeeJob::where('job_id', '=', $request->get('job_id'))->whereHas('job', function($q) use($employer){
                $q->NotCome()->whereHas('employer', function($q) use($employer){
                    $q->where('employer_id', '=', $employer->id);
                })->orderBy('id', 'desc')->where('status', '=', 1);
            })->orderBy('id', 'desc')->get();
        } else {
            $jobMeetings = EmployeeJob::where('job_id', '=', $request->get('job_id'))->where('candat_applay_status', '=', $request->get('candat_status'))->whereHas('job', function($q) use($employer){
                $q->NotCome()->whereHas('employer', function($q) use($employer){
                    $q->where('employer_id', '=', $employer->id);
                })->where('status', '=', 1);
            })->orderBy('id', 'desc')->get();
        }

        return response::suceess('success', 200, 'candat', employeeJobsResource::collection($jobMeetings));
    }

    //before meeting (accept to make interview)
    public function acceptRejectCandat(Request $request){
        //validation
        if($request->get('status') == 0){
            $validator = Validator::make($request->all(), [
                'candat_id'    => 'required|exists:employee_job,id|integer',
                'status'       => ['required',Rule::in(0,1)],      //0->reject  1->accept
                'title'        => 'required|string',
                'body'         => 'required|string',
            ]);
        } else {
            //validation
            $validator = Validator::make($request->all(), [
                'avmeeting_id'      => 'required|exists:avmeetings,id|integer',
                'candat_id'         => 'required|exists:employee_job,id|integer',
                'status'            => ['required',Rule::in(0,1)],      //0->reject  1->accept
                'title'             => 'required|string',
                'body'              => 'required|string',
            ]);
        }

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employer data
        if (! $employer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        $employee_job = EmployeeJob::where('candat_applay_status', '=', null)->whereHas('job', function($q) use($employer){
            $q->where('employer_id', '=', $employer->id);
        })->find($request->get('candat_id'));


        //notification
        $employee_job = EmployeeJob::where('candat_applay_status', '=', null)->whereHas('job', function($q) use($employer){
            $q->where('employer_id', '=', $employer->id);
        })->get()->find($request->get('candat_id'));


        //employee job nor found
        if($employee_job == null){
            return response::falid('candate not found', 400);
        }

        //if emplyer enter 1 (accept)
        if($request->get('status') == 1){
            //check meeting time
            $Avmeeting = Avmeeting::where('job_id', '=', $employee_job->job_id)
                                    ->where('available', '=', 0)
                                    ->find($request->get('avmeeting_id'));

            //avmeeting is wrong
            if($Avmeeting == null){
                return response::falid('you enter wrong available meeting', 400);
            }

            //make available meeting not avilable (not tooken)
            $Avmeeting->available = 1;
            $Avmeeting->save();

            //accept candate
            $employee_job->candat_applay_status = 1;
            $employee_job->avmeeting_id = $Avmeeting->id;
            $employee_job->save();
        }
        
        //if uer enter 0 (reject candate)
        if($request->get('status') == 0){
            //rejected candate
            $employee_job->candat_applay_status = 0;
            $employee_job->save();
        }
            
        //create notificaion
        EmployeeNotifications::create([
            'type'          => 1,
            'employee_id'   => $employee_job->employee_id,
            'title'         => $request->get('title'),
            'body'          => $request->get('body'),
            'candate_id'    => $employee_job->id,
            'job_id'        => $employee_job->job_id,
        ]);

        //employee token
        $employeeToken = Employees::find($employee_job->employee_id)->token;

        //send notification
        $this->firbaseNotifications->send_notification($request->get('title'), $request->get('body'), $employeeToken);

        return response::suceess('success', 200);
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

    public function employeeProfile(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'employee_id'          => 'required|exists:employees,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employee
        $employee = Employees::find($request->get('employee_id'));

        return response::suceess('success', 200, 'employeeDetails', new employeeResource($employee));
    }

    public function meetingLive(){
        //get employer data
        if (! $employer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        $jobs = job::NotCome()->where('employer_id', '=', $employer->id)->orderBy('id', 'desc')->get();

        return response::suceess('success', 200, 'jobs', jobResource::collection($jobs));
    }

    public function meetingSummary(){
        //get employer
        if (! $employer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        //get job that finshed
        $job = job::ThatCome()
                    ->where('status', '=', 1)
                    ->where('employer_id', '=', $employer->id)
                    ->whereHas('EmployeeJob', function($q){$q->where('candat_status', '!=', null);})
                    ->orderBy('id', 'desc')
                ->get();
        
        return response::suceess('success', 200, 'schedule', jobResource::collection($job));
    }

    //get  employee by candat status (after interview)
    public function employees(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'candat_status'           => ['required',Rule::in(0,1,2,3)],  // 0->reject 1->accept 2->underreview employer who detemine this, 3->all, ,
            'job_id'                  => 'required|exists:jobs,id|integer'
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employer data
        if (! $employer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        if($request->get('candat_status') == 3){
            //all
            $employees = Employees::whereHas('EmployeeJob', function($q) use($employer, $request){
                $q->where('candat_status', '!=', null)->whereHas('job', function($query) use($employer, $request){
                    $query->where('employer_id', '=', $employer->id)->where('id', '=', $request->get('job_id'));
                });
            })->get();
        } else {
            // accept & reject & under review
            $employees = Employees::whereHas('EmployeeJob', function($q) use($employer, $request){
                $q->where('candat_status', '=', $request->get('candat_status'))->whereHas('job', function($query) use($employer, $request){
                    $query->where('employer_id', '=', $employer->id)->where('id', '=', $request->get('job_id'));
                });
            })->get();
        }

        return response::suceess('success', 200, 'employees', employee_candat_status::collection($employees));
    }




    public function acceptedEmployee(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'job_id'           => 'required|exists:jobs,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get employer
        if (! $employer = auth('employer')->user()) {
            return response::falid('user_not_found', 404);
        }

        $employees = Employees::whereHas('EmployeeJob', function($q) use($request, $employer){
            $q->where('job_id', '=', $request->job_id)->where('meeting_time_status', '=', 1)->whereHas('job', function($query) use($employer){
                $query->where('employer_id', '=', $employer->id);
            });
        })->get();

        return response::suceess('success', 200, 'accepted_employees', acceptedEmployeeResource::collection($employees));
    }

    public function mainPage_myJob(){
        date_default_timezone_set('Africa/cairo');

        try {
            if (! $employer = auth('employer')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $myJobs = Job::where('employer_id', '=', $employer->id)->where('status', '=', 1)->orderBy('id', 'desc')->where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'))->orWhere('meeting_date', '>', date('Y-m-d'))->where('employer_id', '=', $employer->id)->where('status', '=', 1)->orderBy('id', 'desc')->paginate(6);

        return response()->json([
            'status'  => true,
            'message' => 'success',
            'jobs' => jobResource::collection($myJobs)->response()->getData(true),

        ], 200);
    }

    public function mainPage_authorJobs(){
        date_default_timezone_set('Africa/cairo');

        try {
            if (! $employer = auth('employer')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $authorJobs = Job::where('employer_id', '!=', $employer->id)->where('status', '=', 1)->where('category_id', '=', $employer->business)->where('meeting_date', '=', date('Y-m-d'))->orderBy('id', 'desc')->where('meeting_from', '>', date('H:i:s'))->orWhere('meeting_date', '>', date('Y-m-d'))->where('employer_id', '!=', $employer->id)->where('status', '=', 1)->where('category_id', '=', $employer->business)->orderBy('id', 'desc')->paginate(6);

        return response()->json([
            'status'  => true,
            'message' => 'success',
            'jobs'=> jobResource::collection($authorJobs)->response()->getData(true),
            // 'authorJobs'=> jobResource::collection($authorJobs)->response()->getData(true),

        ], 200);
    }

    public function schedule(){
        date_default_timezone_set('Africa/cairo');

        //get employer
        try {
            if (! $employer = auth('employer')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }


        $job = job::where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'))->withCount('EmployeeJob')->where('status', '=', 1)->where('employer_id', '=', $employer->id)->orderBy('id', 'desc')
                    ->orWhere('meeting_date', '>', date('Y-m-d'))->withCount('EmployeeJob')->where('status', '=', 1)->where('employer_id', '=', $employer->id)->orderBy('id', 'desc')->get();

        return response::suceess('success', 200, 'schedule', jobResource::collection($job));
    }

    public function makeVideoNotification(Request $request){
        $validator = Validator::make($request->all(), [
            'employee_id'           => 'required|exists:employees,id|integer',
            'job_id'                => 'required|exists:jobs,id|integer',
            'viedo_channel_name'    => 'required|string',
            'viedo_token'           => 'required|string',
            'title'                 => 'required|string',
            'body'                  => 'required|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get user by token
        try {
            if (! $employer = auth('employer')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $employeeJob = EmployeeJob::where('employee_id', '=', $request->get('employee_id'))->where('job_id', '=', $request->get('job_id'))->first();

        if($employeeJob == null){
            return response::falid('some thing is wrong', 400);
        }

        $notification = EmployeeNotifications::create([
            'type'               => 2,
            'title'              => $request->get('title'),
            'body'               => $request->get('body'),
            'employee_id'        => $request->get('employee_id'),
            'viedo_channel_name' => $request->get('viedo_channel_name'),
            'viedo_token'        => $request->get('viedo_token'),
            'candate_id'         => $employeeJob->id,
            'job_id'             => $employeeJob->job_id,
            'employer_id'        => $employer->id,
        ]);

        $notification = EmployeeNotifications::find($notification->id);

        //make notification

        $employeeToken = Employees::find($request->get('employee_id'))->token;


        $SERVER_API_KEY = $this->FIREBASE_SERVER_API_KEY;

        $token_1 = $employeeToken;

        $data = [

            "registration_ids" => [
                $token_1
            ],

            "notification" => [
                "title"         => $notification->title,
                "body"          => $notification->body,
                "employer_id"   => $employer->id,
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

        return response::suceess('success', 200);
    }

    public function removeNotification(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'notification_id'           => 'required|exists:employernotifications,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        $employerNotification = EmployerNotifications::find($request->get('notification_id'));

        if($employerNotification == null){
            return response::falid('this notification not found', 404);
        }

        if($employerNotification->delete()){
            return response::suceess('success', 200);
        }
    }

    //accept and reject after interview
    public function acceptRejectEmployee(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'employee_id'           => 'required|exists:employees,id|integer',
            'job_id'                => 'required|exists:jobs,id|integer',
            'note'                  => 'nullable|string',
            'status'                => ['required',Rule::in(0,1,2)], //0 accept, 1 reject, 2 underreview employer who detemine this
            'title'                 => 'nullable|string',
            'body'                  => 'nullable|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get user by token
        try {
            if (! $employer = auth('employer')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        //get employeeJob
        $employeeJob = EmployeeJob::where('employee_id', '=', $request->get('employee_id'))->where('job_id', '=', $request->get('job_id'))->whereHas('job', function($q) use($employer){
            $q->where('employer_id', '=', $employer->id);
        })->first();

        if($employeeJob == null){
            return response::falid('this candate not found', 400);
        }

        $employeeJob->candat_status = $request->get('status');
        $employeeJob->note          = $request->get('note');
        $employeeJob->save();


        if($request->get('status') != 2){
            $notification = EmployeeNotifications::create([
                'type'               => 3,
                'title'              => $request->get('title'),
                'body'               => $request->get('body'),
                'employee_id'        => $request->get('employee_id'),
            ]);

            //make notificaion

            $employeeToken = $employeeJob->employee->token;

            $SERVER_API_KEY = $this->FIREBASE_SERVER_API_KEY;

            $token_1 = $employeeToken;

            $data = [

                "registration_ids" => [
                    $token_1
                ],

                "notification" => [
                    "title"         => $request->get('title'),
                    "body"          =>  $request->get('body'),
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
        }

        return response::suceess('success', 200);

    }

    public function getCandatDetails(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'employee_id'         => 'required|exists:employees,id|integer',
            'job_id'              => 'required|exists:jobs,id|integer',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get user by token
        try {
            if (! $employer = auth('employer')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $employeeJob = EmployeeJob::select('job_id','note', 'candat_status')->whereHas('job', function($q) use($employer){
            $q->where('employer_id', '=', $employer->id);
        })->where('employee_id', '=', $request->get('employee_id'))->where('job_id', '=', $request->get('job_id'))->first();

        if($employeeJob == null){
            return response::falid('this candat not found', 404);
        } else {
            return response::suceess('success', 200, 'candat', $employeeJob);
        }
    }

    public function agoraToken(){
        return app('App\Http\Controllers\Api\site\AgoraController')->generateToken();
    }

    public function getEmplyerNotification(){
        //get user by token
        try {
            if (! $employer = auth('employer')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $notifications = EmployerNotifications::where('employer_id', '=', $employer->id)->orderBy('id', 'desc')->get();

        return response::suceess('success', 200, 'notifications', emoloyerNotificationResource::collection($notifications));
    }

    //search
    public function search(Request $request){
        date_default_timezone_set('Africa/cairo');

        $validator = Validator::make($request->all(), [
            'text'    => 'nullable|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        //get user by token
        try {
            if (! $employer = auth('employer')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        //search in job for author employer in your category
        $jobs = Job::where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'))->where('status', '=', 1)->where('category_id', '=', $employer->business)->where('employer_id', '!=', $employer->id)->where('title', 'LIKE', '%' . $request->get('text') . '%')->orderBy('id', 'desc')->orWhere('meeting_date', '>', date('Y-m-d'))->where('status', '=', 1)->where('category_id', '=', $employer->business)->where('employer_id', '!=', $employer->id)->where('title', 'LIKE', '%' . $request->get('text') . '%')->orderBy('id', 'desc')->get();

        return response::suceess('success', 200, 'jobs', jobResource::collection($jobs));
    }
}