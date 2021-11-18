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

Route::group(['prefix' => 'guest'], function() {
    Route::get('mainPage', 'Api\site\guestController@mainPage');
    Route::get('jobDetails', 'Api\site\guestController@jobDetails');
    Route::get('companyDetails', 'Api\site\guestController@companyDetails');

    Route::get('category/job', 'Api\site\guestController@jobCategory');

    Route::get('categories', 'Api\site\guestController@categories');
    Route::get('countries', 'Api\site\guestController@countries');
    Route::get('getAllAds', 'Api\site\guestController@getAllAds');
    Route::get('fieldWithSpecila', 'Api\site\guestController@fieldWithSpecila');
    Route::post('FilterJob', 'Api\site\employee@FilterJob');
});