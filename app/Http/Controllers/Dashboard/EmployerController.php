<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class EmployerController extends BackEndController
{
    
 public function __construct(Employer $model)
 {
     parent::__construct($model);
 }
 public function index(Request $request)
 {
     //get all data of Model
    $rows = $this->model->when($request->search,function($query) use ($request){
     $query->where('fullName','like','%' .$request->search . '%')
           ->orWhere('email','like','%' .$request->search . '%')
           ->orWhere('title','like','%' .$request->search . '%')
           ->orWhere('mobile_number1','like','%' .$request->search . '%')
           ->orWhere('mobile_number2','like','%' .$request->search . '%')
           ->orWhere('company_name','like','%' .$request->search . '%')
           ->orWhere('established_at','like','%' .$request->search . '%')
           ->orWhere('website','like','%' .$request->search . '%')
           ->orWhereHas('countries',function($query) use($request){
               $query->where('name','like','%' .$request->search . '%');
           })->orWhereHas('cities',function($query)use($request){
             $query->where('name','like','%' .$request->search . '%');
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
            'email'             => 'required|string|email|max:255|unique:employers|unique:employers',
            'password'          => 'required|string|min:6|confirmed',
            'title'             => 'required|string',
            'mobile_number1'    => 'required|string|min:8',
            'mobile_number2'    => 'nullable|string|min:8',
            'company_name'      => 'required|string|max:250',
            'business'          => 'required|exists:categories,id',
            'established_at'    => 'required|before:tomorrow|max:250',
            'website'           => 'required|string|url|max:700',
            'country_id'                => 'required|exists:countries,id',
            'city_id'                   => 'required|exists:cities,id',
            'image'             => 'nullable|image',
        ]);

      

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
        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');

    }

    
    public function update(Request $request, $id)
    {
        $employer = Employer::findOrFail($id);
        $request->validate([
            'fullName'          => 'nullable|string|max:250',
            'title'             => 'nullable|string|max:250',
            'email'             => 'nullable|email|max:255|unique:employers,email,'. $employer->id,
            'password'          => 'nullable|string|min:6|confirmed',
            'mobile_number1'    => 'nullable|digits:11|regex:/(01)[0-2]{1}[0-9]{8}/',
            'mobile_number2'    => 'nullable|digits:11|regex:/(01)[0-2]{1}[0-9]{8}/',
            'company_name'      => 'nullable|string|max:250',
            'country_id'                => 'required|exists:countries,id',
            'city_id'                   => 'required|exists:cities,id',
            'business'          => 'nullable|exists:categories,id',
            'established_at'    => 'nullable|before:tomorrow|max:250',
            'website'           => 'nullable|string|max:250',
            'image'             => 'nullable|file',
            'active'            =>['nullable',Rule::in(0,1)],
        ]);


        //update data
        if($request->has('password') && $request->password !=null){
            $employer->password  = Hash::make($request->get('password'));
        }

        if($request->has('fullName')){
            $employer->fullName      = $request->get('fullName');
        }
        if($request->has('email')){
            $employer->email         = $request->get('email');
        }

        if($request->has('mobile_number1')){
            $employer->mobile_number1       = $request->get('mobile_number1');
        }

        if($request->has('mobile_number2')){
            $employer->mobile_number2       = $request->get('mobile_number2');
        }

        if($request->has('title')){
            $employer->title          = $request->get('title');
        }

        if($request->has('company_name')){
            $employer->company_name       = $request->get('company_name');
        }

        if($request->has('country_id')){
            $employer->country_id       = $request->get('country_id');
        }

        if($request->has('city_id')){
            $employer->city_id       = $request->get('city_id');
        }

        if($request->has('business')){
            $employer->business       = $request->get('business');
        }

        if($request->has('established_at')){
            $employer->established_at         = $request->established_at;
        }

        if($request->has('website')){
            $employer->website       = $request->get('website');
        }
        if($request->has('active')){
            $employer->active       = $request->get('active');
        }

        //updat image
        if($request->has('image')){
            if($employer->image == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/employer/image') , $path);
                $employer->image   = $path;
            } else {
                $oldImage = $employer->image;
                //updat image
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/employer/image') , $path);
                $employer->image   = $path;

                //delet old image
                if(file_exists(base_path('public/uploads/employer/image/') . $oldImage)){
                    unlink(base_path('public/uploads/employer/image/') . $oldImage);
                }   
            }
        }


        $employer->save();
        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }

    
    public function destroy($id,Request $request)
    {
        $employer=Employer::findOrFail($id);
            if($employer->image != null){
                $oldImage = $employer->image;
                if(file_exists(base_path('public/uploads/employer/image/') . $oldImage)){
                    unlink(base_path('public/uploads/employer/image/') . $oldImage);
                }   
            }
            if($employer->employerChat->count()>0){
                session()->flash('error', 'there are employerChat so delete employerChat first');
             return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
     
     
             }
            if($employer->jobs->count()>0){
                session()->flash('error', 'there are jobs so delete jobs first');
             return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
     
     
             }
            if($employer->EmployerNotifications != null){
                session()->flash('error', 'there are EmployerNotifications so delete EmployerNotifications first');
             return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
     
     
             }
        $employer->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
        

    }
}