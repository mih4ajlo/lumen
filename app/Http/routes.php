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

$app->get('/', function () use ($app) {

    return $app->version();
});


$app->get('foo/{name}', function ($name)  {
	Cache::put('drugi', $name , "12");
	
    return 'Hello World '. $name;
});


$app->get('foo/get/{name}', function ($name)  {
	$value = Cache::get('drugi');
	
    return $value;
});
