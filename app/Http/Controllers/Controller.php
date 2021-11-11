<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\EmployeeJob as ModelsEmployeeJob;
use App\Models\Employees;
use App\Models\Employer;
use App\Models\Employer_employee;
use App\Models\job;
use App\Models\Report;
use EmployeeJob;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test(){
        return Employees::with('report')->get();
    }
}
