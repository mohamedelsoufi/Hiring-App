<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\job;
use App\Models\Avmeeting;
use App\Models\EmployeeJob;

use Illuminate\Validation\Rule;
class JobController extends BackEndController
{
    public function __construct(job $model)
    {
        parent::__construct($model);
    }
    public function index(Request $request)
    {
        //get all data of Model
       $rows = $this->model->when($request->search,function($query) use ($request){
        $query->where('title','like','%' .$request->search . '%')
              ->orWhere('details','like','%' .$request->search . '%')
              ->orWhere('note','like','%' .$request->search . '%')
              ->orWhere('salary','like','%' .$request->search . '%')
              ->orWhere('gender','like','%' .$request->search . '%')
              ->orWhere('experience','like','%' .$request->search . '%')
              ->orWhere('qualification','like','%' .$request->search . '%')
              ->orWhere('interviewer_name','like','%' .$request->search . '%')
              ->orWhere('interviewer_role','like','%' .$request->search . '%')
              ->orWhereHas('category',function($query)use($request){
                    $query->orWhereTranslationLike('name',$request->search);
                })->orWhereHas('employer',function($query)use($request){
                $query->where('fullName','like','%' .$request->search . '%');
            });


    });
    $rows = $this->filter($rows,$request);
     $module_name_plural = $this->getClassNameFromModel();
     $module_name_singular = $this->getSingularModelName();
     // return $module_name_plural;
     return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));


    } //end of ind
    public function store(Request $request)
    {

        $request->validate([
            'employer_id'           =>'required | exists:employers,id',
            'category_id'              => 'required|exists:categories,id',
            'title'                 =>'required | string | min:2 | max:60',
            'details'               =>'required | string | min:2 | max:1000',
            'note'                  =>'nullable | string | min:2 | max:1000',
            'salary'                =>'required | numeric',
            'gender'                =>['nullable',Rule::in(0,1,2)],
            'experience'            =>'nullable | integer',
            'qualification'         =>'nullable | string | min:2 | max:80',
            'interviewer_name'      =>'required | string | min:2 | max:60',
            'interviewer_role'      =>'required | string | min:2 | max:60',
            'meeting_date'          =>'required | date',
            'meeting_from'          =>'required',
            'meeting_to'            =>'required',
            'meeting_time'          =>'required | numeric',
            'status'                =>['required',Rule::in(0,1,2)],
        ]);
        $job=job::create($request->all());
        //addd meeting time to our tables
            $for =strtotime($request->meeting_from);
            $to   =strtotime($request->meeting_to);
            $dif=floor(($to - $for)/60)/$request->meeting_time;
            $startTime = date("H:i", strtotime($request->meeting_from));
            for($i=0; $i<$dif; $i++)
            {
               $endTime = date("H:i", strtotime('+'. $request->meeting_time .' minutes', strtotime($startTime)));
               Avmeeting::create([
                    'job_id'    =>$job->id,
                    'time_from' =>$startTime,
                    'time_to'   =>$endTime,
                    'available' =>0,
               ]);
               $startTime=$endTime;
            }
        //end add meeting table to our time
        session()->flash('success',__('site.add_successfully'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function update(Request $request, $id)
    {   $job=job::findOrFail($id);
        $request->validate([
            'employer_id'           =>'required | exists:employers,id',
            'category_id'           => 'required|exists:categories,id',
            'title'                 =>'required | string | min:2 | max:60',
            'details'               =>'required | string | min:2 | max:1000',
            'note'                  =>'nullable | string | min:2 | max:1000',
            'salary'                =>'required | numeric',
            'gender'                =>['nullable',Rule::in(0,1,2)],
            'experience'            =>'nullable | integer',
            'qualification'         =>'nullable | string | min:2 | max:80',
            'interviewer_name'      =>'required | string | min:2 | max:60',
            'interviewer_role'      =>'required | string | min:2 | max:60',
            'meeting_date'          =>'nullable | date',
            'meeting_from'          =>'nullable',
            'meeting_to'            =>'nullable',
            'meeting_time'          =>'nullable | numeric',
            'status'                =>['required',Rule::in(0,1,2)],
        ]);

        $count=$job->avmeetings->where('available',1)->count();
        if($count > 0)
        {
            $request_data=$request->except(['meeting_date','meeting_from','meeting_to','meeting_time']);
            $job->update($request_data);
        }else{

            $job->update($request->all());
            $job->avmeetings()->delete();
            $for =strtotime($request->meeting_from);
            $to   =strtotime($request->meeting_to);
            $dif=floor(($to - $for)/60)/$request->meeting_time;
            $startTime = date("H:i", strtotime($request->meeting_from));
            for($i=0; $i<$dif; $i++)
            {
               $endTime = date("H:i", strtotime('+'. $request->meeting_time .' minutes', strtotime($startTime)));
               Avmeeting::create([
                    'job_id'    =>$job->id,
                    'time_from' =>$startTime,
                    'time_to'   =>$endTime,
                    'available' =>0,
               ]);
               $startTime=$endTime;
            }
        }
        session()->flash('success',__('site.updated_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');

    }
    public function destroy($id,Request $request)
    {
        $job=job::findOrFail($id);
        $job->avmeetings()->delete();
        if($job->EmployeeJob->count()>0){
            session()->flash('error', 'there are Applicants so delete Applicants first');
         return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');


         }
        $job->delete();
        session()->flash('success',__('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');


    }
    public function getAvMeeing($id)
    {
        $data=Avmeeting::where('job_id',$id)->where('available',0)->get();
        return $data;
    }
    public function MyEnrollJobs($id)
    {
       //get all data of Model
        $Mysavejobs=EmployeeJob::where('employee_id',$id)->pluck('job_id');
        $rows=Job::whereIn('id',$Mysavejobs)->paginate();
        $module_name_plural = $this->getClassNameFromModel();
        $module_name_singular = $this->getSingularModelName();
        // return $module_name_plural;
        return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
    } //end of index
    public function getAllEmployerJobs($employer_id)
    {
        $rows=job::where('employer_id',$employer_id)->paginate();
        $module_name_plural = $this->getClassNameFromModel();
        $module_name_singular = $this->getSingularModelName();
        // return $module_name_plural;
        return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
    } //
}