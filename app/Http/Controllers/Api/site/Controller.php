<?php

namespace App\Http\Controllers\Api\site;

use App\Mail\test;
use App\Models\Employees;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test(){
        Mail::To('ahmedmaher1692001@gmail.com')->send(new test);
        return 'asd';
    }
}
