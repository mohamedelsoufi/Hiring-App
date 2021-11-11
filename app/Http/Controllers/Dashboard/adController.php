<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\BackEndController;
use App\Models\Ad;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class adController extends BackEndController
{
    public function __construct(Ad $model)
    {
        parent::__construct($model);
    }
    public function index(Request $request)
    {
      //get all data of Model
      $rows = $this->model->when($request->search,function($query) use ($request){
        $query->where('script','like','%' .$request->search . '%');
              

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
            'url'          => 'required|min:2|max:9000',
            'title'            => 'required|string',
            'image'          => 'required|mimes:jpg,jpeg,png,svg',
        ]);
        //    return $request;
        $request_data = $request->except(['_token','image']);
        if($request->has('image')){
            $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path('public/uploads/ads') , $path);
            $request_data['image']   = $path;
        }
        Ad::create($request_data);
        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }


    public function update(Request $request, $id)
    {
        $ad = $this->model->findOrFail($id);
        $request->validate([
            'url'          => 'required|min:2|max:9000',
            'title'            => 'required|string',
            'image'          => 'required|mimes:jpg,jpeg,png,svg',
        ]);
        $request_data = $request->except(['_token','logo']);
        if($request->has('image')){
            if($ad->image == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/ads') , $path);
                $request_data['image']    = $path;
            } else {
                $oldImage = $ad->image;

                //updat image
                $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path('public/uploads/ads') , $path);
                $request_data['image']    = $path;

                //delet old image
                if(file_exists(base_path('public/uploads/ads/') . $oldImage)){
                    unlink(base_path('public/uploads/ads/') . $oldImage);
                }   
            }
        }
        $ad->update($request_data);
        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }

    public function destroy($id, Request $request)
    {
        $ad = $this->model->findOrFail($id);
        if($ad->image != null)
        {
            if(file_exists(base_path('public/uploads/ads/') . $ad->image)){
                unlink(base_path('public/uploads/ads/') . $ad->image);
            } 
        }
        $ad->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
}
