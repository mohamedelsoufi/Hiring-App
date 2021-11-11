<?php

use App\Http\Controllers\Dashboard\CategoryController;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Auth::routes();



Route::group(['middleware' => ['guest']], function () {
    Route::get('/', function () {
        return view('auth.login');
    });


});


Route::group(
    [
        'prefix'     => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ],
    function () {


        Route::get('/dashboard/home','HomeController@index')->name('dashboard.home');
        Route::prefix('dashboard')->namespace('Dashboard')->middleware(['auth'])->name('dashboard.')->group(function () {

            Route::resource('categories', 'CategoryController');
            Route::resource('roles', 'RoleController');
            Route::resource('users', 'UserController');
            Route::resource('employees', 'EmployeeController');
            Route::resource('employers', 'EmployerController');
            Route::resource('jobs', 'JobController');
            Route::resource('employeejobs', 'EmployeeJopController');
            Route::resource('countries', 'countryController');
            Route::resource('cities', 'cityController');
            Route::resource('ads', 'adController');

            Route::resource('employeejobs', 'employeeJopController');
            Route::get('getcities/{id}','cityController@getcities')->name('getcities');
            Route::get('getjobs/{id}','CategoryController@getjobs')->name('getjobs');
            Route::get('getAvMeeing/{id}','JobController@getAvMeeing')->name('getAvMeeing');
            Route::get('MyEnrollJobs/{id}',"JobController@MyEnrollJobs")->name('MyEnrollJobs');
            Route::get('myjobs/{employer_id}', 'JobController@getAllEmployerJobs')->name('myComapnyJbos');
            //start candiatis
            Route::get('allCandits/{job_id}', 'EmployeeController@getAllCandits')->name('getAllCandits');
            Route::get('allAcceptCandits/{job_id}', 'EmployeeController@getAcceptCandits')->name('getAcceptCandits');
            Route::get('allRejectCandits/{job_id}', 'EmployeeController@getRejectCandits')->name('getRejectCandits');
            Route::get('allNotConfirmCandits/{job_id}', 'EmployeeController@getNotConfirmCandits')->name('getNotConfirmCandits');
            //end candiats
        });

    });

Route::get('/home', 'HomeController@index')->name('home');
