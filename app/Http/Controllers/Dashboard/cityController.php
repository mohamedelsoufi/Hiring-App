<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\BackEndController;
use App\Models\City;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class cityController extends BackEndController
{
    public function __construct(City $model)
    {
        parent::__construct($model);
    }
    public function index(Request $request)
    {
       
        //get all data of Model
        $rows = $this->model->when($request->search,function($query) use ($request){
            $query->where('name','like','%' .$request->search . '%')
            ->orWhereHas('countries', function ($q) use ($request) {
                $q->Where('name', 'like', '%' . $request->search . '%');
                });
                  
    
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
            'name'          => 'required|min:2|unique:cities,name',
            'country_id'     => 'nullable|exists:countries,id',
        ]);
        //    return $request;
        $request_data = $request->except(['_token']);
        City::create($request_data);
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
        $city = $this->model->findOrFail($id);
        $request->validate([
            'name'          => ['required', 'min:2', Rule::unique('cities','name')->ignore($id) ],
            'country_id'     => ['nullable','exists:countries,id'],
        ]);
        $request_data = $request->except(['_token']);
        
        $city->update($request_data);
        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }

    public function destroy($id, Request $request)
    {
        $city = $this->model->findOrFail($id);
        if($city->employees->count() >0){
            session()->flash('error', 'there are employees so delete employees first');
         return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
 
 
         }
        if($city->employers->count()>0){
            session()->flash('error', 'there are Employers so delete company first');
         return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
 
 
         }
        $city->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function getcities($id){
        $data =  City::where('country_id','=',$id)->get();
        return $data;
    }
}
