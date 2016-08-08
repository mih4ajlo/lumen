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

$app->get('admin/', function ()  {
	
			
	$sadrzaj = \App\Models\Sadrzaj::all();
	
    return view('admin', ['document' => "naslov dokumenta", "sadrzaj"=>$sadrzaj]);
});

$app->get('jedan_unos/', function ()  {
	
			
	$sadrzaj = app('db')->select("SELECT * FROM sadrzajs where id = ? and length(content) > ? ", [2, 20]);

	
	
    return view('admin', ['document' => "Jedan unos dokumenta", "sadrzaj"=>$sadrzaj]);
});



$app->get('foo/get/{name}', function ($name)  {
	$value = Cache::get('drugi');

    return $value;
});

$app->get('dbtest', function ()  {

$out = "DB test SQL NO CACHE / No Lumen Cache";
$time_start = microtime(true);
$results = DB::select("SELECT SQL_NO_CACHE  own_kat, ponder, iid,  count(1) as total, count(opit) as popunjeno, (count(opit)/count(1)*100) as proc FROM `csr_pitanja` left join csr_odgovori ON opit=pid and okor=1 and tip=1 left join csr_indikatori on own_ind = iid WHERE tip=1 AND own_ank=1 GROUP BY own_ind");
Cache::put('sql', $results , "12");

$out .= '<br>Total execution time in miliseconds: ' . (microtime(true) - $time_start)*1000;
$out .= "<pre>".print_r($results,true). "</pre>";

    return $out;
});

$app->get('dbtestcache', function ()  {

// how TF you tell Lumen to use cache in query
$out = "DB test SQL NO CACHE / Lumen Cache ON";
$time_start = microtime(true);
        $results = Cache::get('sql');
//if()
//$results = DB::select("SELECT SQL_NO_CACHE  own_kat, ponder, iid,  count(1) as total, count(opit) as popunjeno, (count(opit)/count(1)*100) as proc FROM `csr_pitanja` left join csr_odgovori ON opit=pid and okor=1 and tip=1 left join csr_indikatori on own_ind = iid WHERE tip=1 AND own_ank=1 GROUP BY own_ind");

$out .= '<br>Total execution time in miliseconds: ' . (microtime(true) - $time_start)*1000;
$out .= "<pre>".print_r($results,true). "</pre>";

    return $out;
});
