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

Route::post('register', 'Api\site\register@registerEmployee');
Route::post('register/socialite', 'Api\site\register@socialiteRegisterEmployee');

Route::post('login', 'Api\site\authentication@authenticate')->name('employee');
Route::post('login/socialite', 'Api\site\authentication@socialiteAuthenticate');

Route::post('forgetPassword', 'Api\site\resetPassword@sendEmail')->name('employee');
Route::post('checkCode', 'Api\site\resetPassword@checkCode')->name('employee');
Route::post('changePassword', 'Api\site\resetPassword@passwordResetProcess')->name('employee');

//active   
Route::post('sendActiveMail', 'Api\site\activeAccount@sendEmail')->name('employee');
Route::post('checkActiveCode', 'Api\site\activeAccount@checkCode')->name('employee');
Route::post('active', 'Api\site\activeAccount@active')->name('employee');

Route::group(['middleware' => ['auth_emp']], function() {
    Route::get('profile', 'Api\site\profileController@getProfile')->name('employee');
    Route::post('logout', 'Api\site\authentication@logout')->name('employee');
    Route::post('updateProfile', 'Api\site\profileController@updateEmployeeProfile');
    Route::post('changeEmployeePassword', 'Api\site\profileController@changeEmployeePassword');

    //mian Page
    Route::get('mainPage', 'Api\site\employee@mainPage');    

    //my jobs
    Route::get('jobDetails', 'Api\site\employee@jobDetails');

    Route::post('job/alreadyApply', 'Api\site\employee@alreadyApply');
    Route::post('job/apply', 'Api\site\employee@applyforJob');
    Route::get('availableMeetings', 'Api\site\employee@availableMeetings');
    Route::post('job/acceptOffer', 'Api\site\employee@acceptOffer');
    Route::post('job/acceptOffer/edit', 'Api\site\employee@accept_offer_with_author_meeting');
    Route::get('companyDetails', 'Api\site\employee@companyDetails');

    Route::get('myJobs', 'Api\site\employee@myJobs');
    Route::get('mySchedule', 'Api\site\employee@mySchedule');


    //my jobs meetings
    Route::get('myCandat', 'Api\site\employee@myCandat');

    //chat
    Route::get('chat/mySchedule', 'Api\site\employee@myScheduleChat');
    Route::post('chat/make', 'Api\site\employee@makeChat');

    //notification
    Route::get('notification', 'Api\site\employee@getEmplyeeNotification');
    Route::post('removeNotification', 'Api\site\employee@removeNotification');
    Route::post('makeChatNotification', 'Api\site\employee@makeChatNotification');

    //search
    Route::post('search/job', 'Api\site\employee@jobSearch');

    Route::post('search/recommended_jobs', 'Api\site\employee@recommended_jobsSearch');
    //read notify
    Route::post('readEmployeeNotify', 'Api\site\employee@readEmployeeNotify');


});

Route::group(['prefix' => 'employer'], function() {
    Route::post('login', 'Api\site\authentication@authenticate')->name('employer');
    Route::post('register', 'Api\site\register@registerEmpolyer')->name('employer');

    Route::post('forgetPassword', 'Api\site\resetPassword@sendEmail')->name('employer');
    Route::post('checkCode', 'Api\site\resetPassword@checkCode')->name('employer');
    Route::post('changePassword', 'Api\site\resetPassword@passwordResetProcess')->name('employer');

    //active
    Route::post('sendActiveMail', 'Api\site\activeAccount@sendEmail')->name('employer');
    Route::post('checkActiveCode', 'Api\site\activeAccount@checkCode')->name('employer');
    Route::post('active', 'Api\site\activeAccount@active')->name('employer');

    Route::group(['middleware' => ['auth_empr']], function() {
        Route::get('test', 'Api\site\Controller@show');
        Route::get('profile', 'Api\site\profileController@getProfile')->name('employer');
        Route::post('logout', 'Api\site\authentication@logout')->name('employer');
        Route::post('updateProfile', 'Api\site\profileController@updateEmployerProfile');
        Route::post('changeEmployerPassword', 'Api\site\profileController@changeEmployerPassword');

        //main page
        Route::get('mainPage/myJob', 'Api\site\employer@mainPage_myJob');
        Route::get('mainPage/authorJobs', 'Api\site\employer@mainPage_authorJobs');

        Route::get('jobDetails', 'Api\site\guestController@jobDetails');

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

        //chat
        Route::get('chat/mySchedule', 'Api\site\employer@myScheduleChat');

        //notification
        Route::post('makeVideo', 'Api\site\employer@makeVideoNotification');
        Route::post('makeChatNotification', 'Api\site\employer@makeChatNotification');

        //after interview
        Route::post('acceptRejectEmployee', 'Api\site\employer@acceptRejectEmployee');
        Route::get('getCandatDetails', 'Api\site\employer@getCandatDetails');

        //notification
        Route::get('notification', 'Api\site\employer@getEmplyerNotification');
        Route::post('removeNotification', 'Api\site\employer@removeNotification');

        //agora
        Route::post('agora', 'Api\site\employer@agoraToken');

        //chat
        Route::post('chat/make', 'Api\site\employer@makeChat');

        //search
        Route::post('search', 'Api\site\employer@search');
        //read notify
        Route::post('readEmployerNotify', 'Api\site\employer@readEmployerNotify');

    });
});

Route::group(['prefix' => 'guest'], function() {
    Route::get('mainPage', 'Api\site\guestController@mainPage');
    Route::get('jobDetails', 'Api\site\guestController@jobDetails');
    Route::get('categories', 'Api\site\guestController@categories');
    Route::get('countries', 'Api\site\guestController@countries');
    Route::get('getAllAds', 'Api\site\guestController@getAllAds');
    Route::get('fieldWithSpecila', 'Api\site\guestController@fieldWithSpecila');
    Route::post('FilterJob', 'Api\site\employee@FilterJob');


});

Route::get('category/job', 'Api\site\employee@jobCategory');
