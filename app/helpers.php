<?php

function resource($uri, $controller, $app)
{
    //$verbs = array('GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE');
    global $app;
    $app->get($uri, 'App\Http\Controllers\\'.$controller.'@index');
    $app->get($uri.'/create', 'App\Http\Controllers\\'.$controller.'@create');
    $app->post($uri, 'App\Http\Controllers\\'.$controller.'@store');
    $app->get($uri.'/{id}', 'App\Http\Controllers\\'.$controller.'@show');
    $app->get($uri.'/{id}/edit', 'App\Http\Controllers\\'.$controller.'@edit');
    $app->put($uri.'/{id}', 'App\Http\Controllers\\'.$controller.'@update');
    $app->patch($uri.'/{id}', 'App\Http\Controllers\\'.$controller.'@update');
    $app->delete($uri.'/{id}', 'App\Http\Controllers\\'.$controller.'@destroy');
}