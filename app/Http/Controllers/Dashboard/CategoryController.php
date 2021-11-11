<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\BackEndController;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends BackEndController
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
    public function index(Request $request)
    {
       //get all data of Model
       $rows = $this->model->when($request->search,function($query) use ($request){
           $query->whereTranslationLike('name','%' .$request->search . '%');
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
            'name'          => 'required|min:2|unique:category_translations,name',
            'parent_id'     => 'nullable|exists:categories,id',
        ]);
        //    return $request;
        $request_data = $request->except(['_token']);
        Category::create($request_data);
        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }

    public function update(Request $request, $id)
    {
        $category = $this->model->findOrFail($id);
        $request->validate([
            //'ar.name'          => ['required', 'min:2', Rule::unique('category_translations','name')->ignore($category->id, 'category_id') ],
            'name'          => ['required', 'min:2', Rule::unique('category_translations','name')->ignore($category->id, 'category_id') ],
            'parent_id'     => 'nullable|exists:categories,id'
        ]);
        $request_data = $request->except(['_token']);
      
        $category->update($request_data);
        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }

    public function destroy($id, Request $request)
    {
        $category = $this->model->findOrFail($id);
        if($category->job->count() > 0){
           session()->flash('error', 'there are job so delete job first');
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');


        }
        if($category->employees->count()>0){
           session()->flash('error', 'there are employees so delete employees first');
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');


        }
        
        $category->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function getjobs($id){
        $data = Category::where('parent_id', $id)->get();
        return response()->json($data);
    }
}
