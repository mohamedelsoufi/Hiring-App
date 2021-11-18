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
