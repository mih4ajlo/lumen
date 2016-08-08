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

	echo "string";
    return $app->version();
});


$app->get('foo/{name}', function ($name)  {
	Cache::put('drugi', $name , "12");
	
    return 'Hello World '. $name;
});

$app->get('admin/', function ()  {
	
			
	$sadrzaj = \App\Models\Sadrzaj::all();
	
    return view('admin', ['document' => "naslov dokumenta", "sadrzaj"=>$sadrzaj]);
});

$app->get('jedan_unos/', function ()  {
	
			
	$sadrzaj = app('db')->select("SELECT * FROM sadrzajs where id = 2");
	
    return view('admin', ['document' => "Jedan unos dokumenta", "sadrzaj"=>$sadrzaj]);
});



$app->get('foo/get/{name}', function ($name)  {
	$value = Cache::get('drugi');
	
    return $value;
});
