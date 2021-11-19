<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Intervention\Image\ImageManagerStatic as Image;

date_default_timezone_set('Africa/cairo');

Route::get('/clear-cache',function(){
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    // Artisan::call('jwt:secret');
    return "cache clear";
});

Route::post('register', 'Api\site\auth\register@registerEmployee');
Route::post('register/socialite', 'Api\site\auth\register@socialiteRegisterEmployee');

Route::post('login', 'Api\site\auth\authentication@login')->name('employee');
Route::post('login/socialite', 'Api\site\auth\authentication@socialiteAuthenticate');

Route::post('forgetPassword', 'Api\site\auth\resetPassword@sendEmail')->name('employee');
Route::post('checkCode', 'Api\site\auth\resetPassword@checkCode')->name('employee');
Route::post('changePassword', 'Api\site\auth\resetPassword@passwordResetProcess')->name('employee');

//active   
Route::post('sendActiveMail', 'Api\site\auth\activeAccount@sendEmail')->name('employee');
Route::post('checkActiveCode', 'Api\site\auth\activeAccount@checkCode')->name('employee');
Route::post('active', 'Api\site\auth\activeAccount@active')->name('employee');

Route::group(['middleware' => ['auth_emp']], function() {
    Route::get('profile', 'Api\site\auth\profileController@getProfile')->name('employee');
    Route::post('logout', 'Api\site\auth\authentication@logout')->name('employee');
    Route::post('updateProfile', 'Api\site\auth\profileController@updateEmployeeProfile');
    Route::post('changeEmployeePassword', 'Api\site\auth\profileController@changeEmployeePassword');

    //mian Page
    Route::get('mainPage', 'Api\site\employee@mainPage');    

    Route::post('job/alreadyApply', 'Api\site\employee@alreadyApply');
    Route::post('job/apply', 'Api\site\employee@applyforJob');
    Route::get('availableMeetings', 'Api\site\employee@availableMeetings');
    Route::post('job/acceptOffer', 'Api\site\employee@acceptOffer');
    Route::post('job/acceptOffer/edit', 'Api\site\employee@accept_offer_with_author_meeting');

    Route::get('myJobs', 'Api\site\employee@myJobs');
    Route::get('mySchedule', 'Api\site\employee@mySchedule');


    //my jobs meetings
    Route::get('myCandat', 'Api\site\employee@myCandat');

    //notification
    Route::get('notification', 'Api\site\employee@getEmplyeeNotification');
    Route::post('removeNotification', 'Api\site\employee@removeNotification');

    //search
    Route::post('search/job', 'Api\site\employee@jobSearch');

    Route::post('search/recommended_jobs', 'Api\site\employee@recommended_jobsSearch');
});

Route::post('asd', 'Api\site\employee@test');
