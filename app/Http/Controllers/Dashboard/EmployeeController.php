<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\job;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends BackendController
{
    public function __construct(Employees $model)
    {
        parent::__construct($model);
    }
    public function index(Request $request)
    {
        //get all data of Model
       $rows = $this->model->when($request->search,function($query) use ($request){
        $query->where('fullName','like','%' .$request->search . '%')
              ->orWhere('email','like','%' .$request->search . '%')
              ->orWhere('qualification','like','%' .$request->search . '%')
              ->orWhere('graduation_year','like','%' .$request->search . '%')
              ->orWhere('university','like','%' .$request->search . '%')
              ->orWhere('gender','like','%' .$request->search . '%')
              ->orWhere('skills','like','%' .$request->search . '%')
              ->orWhere('languages','like','%' .$request->search . '%')
              ->orWhereHas('countries',function($query) use($request){
                  $query->where('name','like','%' .$request->search . '%');
              })->orWhereHas('cities',function($query)use($request){
                $query->where('name','like','%' .$request->search . '%');
              })->orWhereHas('category',function($query)use($request){
                    $query->orWhereTranslationLike('name',$request->search);
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
            'fullName'          => 'required|string',
            'email'             => 'required|string|email|max:255|unique:employees',
            'password'          => 'required|string|min:5|confirmed',
            'password_confirmation'   => 'required|string|same:password',
            'country_id'           => 'required|exists:countries,id',
            'city_id'              => 'required|exists:cities,id',
            'title'             => 'required|string|max:250',
            'qualification'     => 'required|string|max:250',
            'university'        => 'required|string|max:250',
            'graduation_year'   => 'required|integer|max:5000',
            'study_field'       => 'required|string|max:250',
            'deriving_licence'  => ['required',Rule::in(0,1)],      //0->no 1->yes
            'birth'             => 'required|string',
            'gender'            => ['required',Rule::in(0,1,2)],    //0->male  1->female 2->other
            'block'             => ['required',Rule::in(0,1)],      //0->unblocked 1->blocked
            'cv'                => 'nullable|file',
            'audio'             => 'nullable|file',
            'video'             => 'nullable|mimes:mp4,mov,ogg',
            'image'             => 'nullable|mimes:jpg,jpeg,png,svg',
            'category_id'       =>'required |exists:categories,id',
            'skills'            =>'nullable|string|max:250', 
            'languages'         =>'nullable|string|max:250',

        ]);
        
        $request_data=$request->except(['cv','audio','video','image','password','password_confirmation']);

        if($request->has('cv')){
            $path = rand(0,1000000) . time() . '.' . $request->file('cv')->getClientOriginalExtension();
            $request->file('cv')->move(base_path('public/uploads/employee/cv') , $path);
            $request_data['cv'] = $path;
        }

        //updat audio
        if($request->has('audio')){
            $path = rand(0,1000000) . time() . '.' . $request->file('audio')->getClientOriginalExtension();
            $request->file('audio')->move(base_path('public/uploads/employee/audio') , $path);
            $request_data['audio'] = $path;
        }

        //updat video
        if($request->has('video')){
            $path = rand(0,1000000) . time() . '.' . $request->file('video')->getClientOriginalExtension();
            $request->file('video')->move(base_path('public/uploads/employee/video') , $path);
            $request_data['video'] = $path;
        }

        if($request->has('image')){
            $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path('public/uploads/employee/image') , $path);
            $request_data['image']   = $path;
        }
        if($request->password)
        {
            $request_data['password']=bcrypt($request->password);
        }
       
        Employees::create($request_data);
        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
  
    }


    public function update(Request $request, $id)
    {
        $employee=Employees::findOrFail($id);
        if(!$employee)
        {
            return "not found by this id";
        }
       $request->validate([
            'fullName'                  => 'nullable|string',
            'email'                     => ['nullable','string','email','max:255',Rule::unique('employees','email')->ignore($id)],
            'password'                  => 'nullable|string|min:5|confirmed',
            'password_confirmation'     => 'same:password',
            'country_id'                => 'required|exists:countries,id',
            'city_id'                   => 'required|exists:cities,id',
            'title'                     => 'nullable|string|max:250',
            'qualification'             => 'nullable|string|max:250',
            'university'                => 'nullable|string|max:250',
            'graduation_year'           => 'nullable|integer|max:5000',
            'study_field'               => 'nullable|string|max:250',
            'deriving_licence'          => ['nullable',Rule::in(0,1)],
            'birth'                     => 'nullable|string',
            'gender'                    => ['nullable',Rule::in(0,1,2)],
            'block'                     => ['nullable',Rule::in(0,1)],
            'cv'                        => 'nullable|file',
            'audio'                     => 'nullable|file',
            'video'                     => 'nullable|mimes:mp4,mov,ogg',//mimes:mp4,mov,ogg
            'image'                     => 'nullable|file|mimes:jpg,jpeg,png,svg',
            'category_id'               => 'nullable |exists:categories,id',
            'skills'                    =>'nullable|string|max:250', 
            'languages'                 =>'nullable|string|max:250',
        ]);
       
        $request_data=$request->except(['cv','audio','video','image','password','password_confirmation']);

        if($request->has('cv')){
            if($employee->cv == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('cv')->getClientOriginalExtension();
                $request->file('cv')->move(base_path('public/uploads/employee/cv') , $path);
                $request_data['cv']   = $path;
            } else {
                $oldCv = $employee->cv;
                //updat cv
                $path = rand(0,1000000) . time() . '.' . $request->file('cv')->getClientOriginalExtension();
                $request->file('cv')->move(base_path('public/uploads/employee/cv') , $path);
                $request_data['cv']  = $path;

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
                $request_data['audio']   = $path;
            } else {
                $oldAudio = $employee->audio;

                //updat audio
                $path = rand(0,1000000) . time() . '.' . $request->file('audio')->getClientOriginalExtension();
                $request->file('audio')->move(base_path('public/uploads/employee/audio') , $path);
                $request_data['audio']   = $path;

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
                $request_data['video']   = $path;
            } else {
                $oldVideo = $employee->video;

                //updat video
                $path = rand(0,1000000) . time() . '.' . $request->file('video')->getClientOriginalExtension();
                $request->file('video')->move(base_path('public/uploads/employee/video') , $path);
                $request_data['video']   = $path;

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
                $request_data['image']    = $path;
            } else {
                $oldImage = $employee->image;

                //updat image
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/employee/image') , $path);
                $request_data['image']    = $path;

                //delet old image
                if(file_exists(base_path('public/uploads/employee/image/') . $oldImage)){
                    unlink(base_path('public/uploads/employee/image/') . $oldImage);
                }   
            }
        }

        if($request->has('password') && $request->password !=null)
        {
            $request_data['password']=bcrypt($request->password);
        }
       
        $employee->update($request_data);
        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }

    public function destroy($id,Request $request)
    {
        $employee=Employees::findOrFail($id);
        if(!$employee)
        {
            return "Not Found This Empoyee By This ID";
        }
        if($employee->image !=null)
        {
            $image = $employee->image;
            if(file_exists(base_path('public/uploads/employee/image/') . $image)){
                unlink(base_path('public/uploads/employee/image/') . $image);
            }   
        }
        if($employee->cv !=null)
        {
            $cv = $employee->cv;
            if(file_exists(base_path('public/uploads/employee/cv/') . $cv)){
                unlink(base_path('public/uploads/employee/cv/') . $cv);
            }   
        }
        if($employee->audio !=null)
        {
            $audio = $employee->audio;
            if(file_exists(base_path('public/uploads/employee/audio/') . $audio)){
                unlink(base_path('public/uploads/employee/audio/') . $audio);
            }   
        }
        if($employee->video !=null)
        {
            $video = $employee->video;
            if(file_exists(base_path('public/uploads/employee/video/') . $video)){
                unlink(base_path('public/uploads/employee/video/') . $video);
            }   
        }
        if($employee->employerChat->count()>0){
            session()->flash('error', 'there are employerChat so delete employerChat first');
         return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
 
 
         }
         
        if($employee->EmployeeJob->count()>0){
            session()->flash('error', 'there are EmployeeJob so delete EmployeeJob first');
         return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
 
 
         }
        if($employee->employeeChat->count()>0){
            session()->flash('error', 'there are employeeChat so delete employeeChat first');
         return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
 
 
         }
         if($employee->EmployerNotifications != null){
            session()->flash('error', 'there are EmployerNotifications so delete EmployerNotifications first');
         return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
 
 
         }
        
        $employee->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
       
    }
    
    public function getAllCandits($job_id)
    {
        $job=job::find($job_id);
        $arr=$job->EmployeeJob->pluck('employee_id');
        $rows=Employees::whereIn('id',$arr)->paginate();
        $module_name_plural = $this->getClassNameFromModel();
        $module_name_singular = $this->getSingularModelName();
        // return $module_name_plural;
        return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
    }

    public function getAcceptCandits($job_id)
    {
        $job=job::find($job_id);
        $arr=$job->EmployeeJob->where('candat_applay_status',1)->pluck('employee_id');
        $rows=Employees::whereIn('id',$arr)->paginate();
        $module_name_plural = $this->getClassNameFromModel();
        $module_name_singular = $this->getSingularModelName();
        // return $module_name_plural;
        return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
    }
    public function getRejectCandits($job_id)
    {
        $job=job::find($job_id);
        $arr=$job->EmployeeJob->where('candat_applay_status',0)->pluck('employee_id');
        $rows=Employees::whereIn('id',$arr)->paginate();
        $module_name_plural = $this->getClassNameFromModel();
        $module_name_singular = $this->getSingularModelName();
        // return $module_name_plural;
        return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
    }
    public function getNotConfirmCandits($job_id)
    {
        $job=job::find($job_id);
        $arr=$job->EmployeeJob->where('candat_applay_status',2)->pluck('employee_id');
        $rows=Employees::whereIn('id',$arr)->paginate();
        $module_name_plural = $this->getClassNameFromModel();
        $module_name_singular = $this->getSingularModelName();
        // return $module_name_plural;
        return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
        
    }
}
