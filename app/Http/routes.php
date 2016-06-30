<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$app->group(['prefix' => 'api/'.getenv('API_VERSION'), 'namespace' => 'App\Http\Controllers'], function() use ($app){

    $app->post('auth/login', 'AuthController@postLogin');

    $app->group(['prefix' => 'api/'.getenv('API_VERSION'), 'middleware' => 'auth'], function($app){

        resource('leads', 'LeadsController', $app);

        resource('lead-lists', 'LeadListsController', $app);

        resource('users', 'UsersController', $app);

    });

});
