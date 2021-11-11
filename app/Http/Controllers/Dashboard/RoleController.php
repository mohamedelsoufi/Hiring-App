<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends BackEndController
{

    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function index(Request $request)
    {
       //get all data of Model
       $rows = $this->model->when($request->search,function($query) use ($request){
           $query->where('name','like','%' .$request->search . '%')
                 ->orWhere('description', 'like','%' . $request->search . '%');

       });
       $rows = $this->filter($rows,$request);
        $module_name_plural = $this->getClassNameFromModel();
        $module_name_singular = $this->getSingularModelName();
        // return $module_name_plural;
        return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
    } //end of ind
    public function store(Request $request)
    {
        $newRole = new Role();
        $newRole->name         =  $request->name;
        $newRole->display_name = ucfirst($request->name);
        $newRole->description  =  $request->description;
        $newRole->save();

        $newRole->attachPermissions($request->permissions);
        session()->flash('success', __('site.add_successfuly'));

        return redirect()->route('dashboard.roles.index');

    }

    public function update(Request $request, $id)
    {

        $updateRole = $this->model->findOrFail($id);
        $updateRole->name         =  $request->name;
        $updateRole->display_name = ucfirst($request->name);
        $updateRole->description  =  $request->description;
        $updateRole->save();


        $updateRole->syncPermissions($request->permissions);

        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function destroy($id, Request $request)
    {
        $category = $this->model->findOrFail($id);
        $category->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
}
