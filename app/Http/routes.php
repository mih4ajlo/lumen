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


/*https://github.com/ankhuve/jobbaextra-backend/blob/master/app/Http/routes.php*/

/*
https://laracasts.com/discuss/channels/lumen/nested-route-groups-and-namespace
*/
$app->group(['middleware' => ['auth'], 'prefix' => 'dashboard', 'namespace' => 'App\Http\Controllers'], function () use ($app) {

    $app->get('/',  [   'uses' => 'DashboardController@index']);
    $app->get('sadrzaj',  [   'uses' => 'DashboardController@sadrzaj']);
    $app->get('users',  [   'uses' => 'DashboardController@users']);    
    $app->get('kategorije',  [   'uses' => 'DashboardController@kategorije']);

    /*users*/
    $app->get('user',  [   'uses' => 'UserController@list_users']);
   $app->get('user/{user_id}',  [   'uses' => 'UserController@single']);
   $app->get('user/delete/{user_id}',  [   'uses' => 'UserController@delete_user']);


    /*content*/
   $app->get('content',  [   'uses' => 'ContentController@list_content']);
   $app->get('content/{content_id}',  [   'uses' => 'ContentController@single']);
   $app->get('content/delete/{content_id}',  [   'uses' => 'ContentController@delete_content']);


   /*akteri*/
   $app->get('acters',  [   'uses' => 'ContentController@list_acters']);
   $app->post('acters/edit/{acter_id}',  [   'uses' => 'ContentController@edit_acter']);
   $app->get('acters/delete/{content_id}',  [   'uses' => 'ContentController@delete_acter']);

});



$app->group([/*'middleware' => ['auth'],*/ 'prefix' => 'auth', 'namespace' => 'App\Http\Controllers'], function () use ($app) {

    $app->get('login',  [   'uses' => 'UserController@login']);

    $app->post('login',  [   'uses' => 'UserController@login']);

    $app->get('logout',  [   'uses' => 'UserController@logout']);

    $app->get('register',  [   'uses' => 'UserController@register']);    
    $app->get('error/{err}',  [   'uses' => 'UserController@error' ]);

});


$app->get('login', [   'uses' => 'UserController@doLogin' ]);


$app->get('/', function () use ($app) {

    return $app->version();
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




//include additional Routes
include("routes2.php");
