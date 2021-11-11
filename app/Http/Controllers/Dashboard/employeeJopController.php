<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeJob;
use App\Models\Avmeeting;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class EmployeeJopController extends BackEndController
{
    public function __construct(EmployeeJob $model)
    {
        parent::__construct($model);
    }
    public function index(Request $request)
    {
        //get all data of Model
       $rows = $this->model->when($request->search,function($query) use ($request){
        $query->where('note','like','%' . $request->search .'%')
            ->orWhereHas('job',function($query)use($request){
                    $query->where('title','like','%' . $request->search .'%');
            })->orWhereHas('employee',function($query)use($request){
                    $query->where('fullName','like','%' . $request->search .'%');
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
            'job_id'                =>'required | exists:jobs,id',
            'employee_id'           =>'required | exists:employees,id',
            'candat_applay_status'  =>['nullable',Rule::in(0,1,2)],   //0->reject 1->accept 2->shoertlist
            'avmeeting_id'          =>'nullable | exists:avmeetings,id',
            'meeting_time_status'   =>['nullable',Rule::in(0,1)],     //0->reject 1->accept 
            'note'                  =>'nullable | string | min:2 |max: 700',
            'candat_status'         =>['nullable',Rule::in(0,1,2)],   //0 ->   1->   2->
        ]);
        $empjob=EmployeeJob::where('employee_id',$request->employee_id)->where('job_id',$request->job_id)->first();
        if(!empty($empjob))
        {
            return redirect()->back()->with('error','You Applay For This Job Before');
        }
        if($request->has('avmeeting_id'))
        {
            $request_data=$request->except(['avmeeting_id']);
            $avmeet=Avmeeting::find($request->avmeeting_id);
            $avmeet->update(['available'=>1]);
            $request_data['avmeeting_id']=$request->avmeeting_id;
            EmployeeJob::create($request_data);
        }
        else{
            EmployeeJob::create($request->all());
        }
        session()->flash('success',__('site.add_successfully'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function update(Request $request ,$id)
    {
        $empjob=EmployeeJob::findOrFail($id);
        $request->validate([
            'job_id'                =>'required | exists:jobs,id',
            'employee_id'           =>'required | exists:employees,id',
            'candat_applay_status'  =>['nullable',Rule::in(0,1,2)],   //0->reject 1->accept 2->shoertlist
            'avmeeting_id'          =>'nullable | exists:avmeetings,id',
            'meeting_time_status'   =>['nullable',Rule::in(0,1)],     //0->reject 1->accept 
            'note'                  =>'nullable | string | min:2 |max: 700',
            'candat_status'         =>['nullable',Rule::in(0,1,2)],   //0->reject 1->accept 2->underreview
        ]);
        if($request->has('avmeeting_id') && $request->avmeeting_id !=null)
        {
            if($empjob->avmeeting_id !=null)
            {
                $avold=Avmeeting::find($empjob->avmeeting_id);
                $avold->update(['available'=>0]);
            }
            $request_data=$request->except(['avmeeting_id']);
            $avmeet=Avmeeting::find($request->avmeeting_id);
            $avmeet->update(['available'=>1]);
            $request_data['avmeeting_id']=$request->avmeeting_id;
            $empjob->update($request_data);
        }
        else{
            $empjob->update($request->all());
        }
        session()->flash('success',__('site.updated_successfully'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function destroy($id,Request $request)
    {
        $empjob=EmployeeJob::findOrFail($id);
        if($empjob->avmeeting_id !=null && $empjob->candat_status != 1)
            {
                $avold=Avmeeting::find($empjob->avmeeting_id);
                $avold->update(['available'=>0]);
            }
        $empjob->delete();    
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
        

    }
}