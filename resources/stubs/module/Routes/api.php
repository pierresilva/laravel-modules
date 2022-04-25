<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

# custom routes section

Route::group([
    'prefix' => 'DummySlug',
    'middleware' => 'api'
], function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'This is the DummyName module API index page. Build something great!'
        ]);
    });

    // Route::get('/apps', 'Api\\DummyNameController@index');
});

# end custom routes section

# generated section

# end generated section
