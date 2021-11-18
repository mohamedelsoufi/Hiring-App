<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

date_default_timezone_set('Africa/cairo');

Route::get('/clear-cache',function(){
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    // Artisan::call('jwt:secret');
    return "cache clear";
});

Route::group(['prefix' => 'employer'], function() {
    Route::post('login', 'Api\site\auth\authentication@login')->name('employer');
    Route::post('register', 'Api\site\auth\register@registerEmpolyer')->name('employer');

    Route::post('forgetPassword', 'Api\site\auth\resetPassword@sendEmail')->name('employer');
    Route::post('checkCode', 'Api\site\auth\resetPassword@checkCode')->name('employer');
    Route::post('changePassword', 'Api\site\auth\resetPassword@passwordResetProcess')->name('employer');

    //active
    Route::post('sendActiveMail', 'Api\site\auth\activeAccount@sendEmail')->name('employer');
    Route::post('checkActiveCode', 'Api\site\auth\activeAccount@checkCode')->name('employer');
    Route::post('active', 'Api\site\auth\activeAccount@active')->name('employer');

    Route::group(['middleware' => ['auth_empr']], function() {
        Route::get('test', 'Api\site\Controller@show');
        Route::get('profile', 'Api\site\auth\profileController@getProfile')->name('employer');
        Route::post('logout', 'Api\site\auth\authentication@logout')->name('employer');
        Route::post('updateProfile', 'Api\site\auth\profileController@updateEmployerProfile');
        Route::post('changeEmployerPassword', 'Api\site\auth\profileController@changeEmployerPassword');

        //main page
        Route::get('mainPage', 'Api\site\employer@mainPage');

        //myScedule
        Route::get('mySchedule', 'Api\site\employer@schedule');
        Route::get('meetingSummary', 'Api\site\employer@meetingSummary');

        //report
        Route::post('report', 'Api\site\employer@report');
        Route::get('review', 'Api\site\employer@review');

        //job
        Route::post('newJob', 'Api\site\employer@newjob');
        Route::post('jobEdit', 'Api\site\employer@jobEdit');
        Route::post('JobDelete', 'Api\site\employer@JobCanceled');
        Route::get('employee/profile', 'Api\site\employer@employeeProfile');

        //Candats
        Route::get('myCandat', 'Api\site\employer@myCandat');
        Route::get('employees', 'Api\site\employer@employees');

        Route::get('myCandat/live', 'Api\site\employer@meetingLive');
        Route::get('myCandat/accepted', 'Api\site\employer@acceptedEmployee');

        
        Route::post('candat/accept-reject', 'Api\site\employer@acceptRejectCandat');
        Route::get('availableMeetings', 'Api\site\employer@availableMeetings');

        //notification
        Route::post('makeVideo', 'Api\site\employer@makeVideoNotification');

        //after interview
        Route::post('acceptRejectEmployee', 'Api\site\employer@acceptRejectEmployee');
        Route::get('getCandatDetails', 'Api\site\employer@getCandatDetails');

        //notification
        Route::get('notification', 'Api\site\employer@getEmplyerNotification');
        Route::post('removeNotification', 'Api\site\employer@removeNotification');

        //agora
        Route::post('agora', 'Api\site\employer@agoraToken');

        //search
        Route::post('search', 'Api\site\employer@search');
        //read notify
        Route::post('readEmployerNotify', 'Api\site\employer@readEmployerNotify');
    });
});
