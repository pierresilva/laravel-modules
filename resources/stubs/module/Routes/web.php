<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

# custom routes section

Route::group([
    'prefix' => 'DummySlug',
    'middleware' => 'web'
], function () {
    Route::get('/', function () {
        dd('This is the DummyName module WEB index page. Build something great!');
    });

    // Route::get('/apps', 'DummyNameController@index');
});

# end custom routes section

# generated section

# end generated section
