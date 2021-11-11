<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\BackEndController;
use App\Models\City;
use App\Models\Country;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class countryController extends BackEndController
{
    public function __construct(Country $model)
    {
        parent::__construct($model);
    }
    public function index(Request $request)
    {
        //get all data of Model
       $rows = $this->model->when($request->search,function($query) use ($request){
        $query->where('name','like','%' .$request->search . '%')
              ->where('code','like','%' .$request->search . '%');
              

    });
    $rows = $this->filter($rows,$request);
     $module_name_plural = $this->getClassNameFromModel();
     $module_name_singular = $this->getSingularModelName();
     // return $module_name_plural;
     return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
        
       
    } //end of ind

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|min:2|unique:countries,name',
            'code'          => 'required|min:2|unique:countries,code',
            'image'          => 'required|mimes:jpg,jpeg,png,svg',
        ]);
        //    return $request;
        $request_data = $request->except(['_token','image']);
        if($request->has('image')){
            $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path('public/uploads/country') , $path);
            $request_data['image']   = $path;
        }
        Country::create($request_data);
        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $country = $this->model->findOrFail($id);
        $request->validate([
            'name'          => ['required', 'min:2', Rule::unique('countries','name')->ignore($id) ],
            'code'          => ['required','min:2',  Rule::unique('countries','code')->ignore($id)],
            'image'          => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);
        $request_data = $request->except(['_token','image']);
        if($request->has('image')){
            if($country->image == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/country') , $path);
                $request_data['image']    = $path;
            } else {
                $oldImage = $country->image;

                //updat image
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/country') , $path);
                $request_data['image']    = $path;

                //delet old image
                if(file_exists(base_path('public/uploads/country/') . $oldImage)){
                    unlink(base_path('public/uploads/country/') . $oldImage);
                }   
            }
        }
        $country->update($request_data);
        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }

    public function destroy($id, Request $request)
    {
        $country = $this->model->findOrFail($id);
        if($country->image != null)
        {
            if(file_exists(base_path('public/uploads/country/') . $country->image)){
                unlink(base_path('public/uploads/country/') . $country->image);
            } 
        }
        // dd($country->employees->count());
        if($country->employees->count() >0){
            session()->flash('error', 'there are employees so delete employees first');
         return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
 
 
         }
        if($country->employers->count()>0){
            session()->flash('error', 'there are Employers so delete company first');
         return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
 
 
         }
        foreach ($country->cities as $post) {
            $post->delete();
        }
        $country->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
}
