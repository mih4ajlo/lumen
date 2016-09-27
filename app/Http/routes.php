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
   $app->get('acters',  [   'uses' => 'ActerController@list_acters']);
   $app->post('acters/edit/{acter_id}',  [   'uses' => 'ActerController@edit_acter']);
   $app->post('acters/add',  [   'uses' => 'ActerController@add_acter']);
   $app->get('acters/delete/{acter_id}',  [   'uses' => 'ActerController@delete_acter']);


     /*keywords*/
   $app->get('keywords',  [   'uses' => 'KeywordController@list_keywords']);
   $app->post('keywords/edit/{keyword_id}',  [   'uses' => 'KeywordController@edit_keyword']);
   $app->post('keywords/add',  [   'uses' => 'KeywordController@add_keyword']);
   $app->get('keywords/delete/{keyword_id}',  [   'uses' => 'KeywordController@delete_keyword']);

});



$app->group([/*'middleware' => ['auth'],*/ 'prefix' => 'auth', 'namespace' => 'App\Http\Controllers'], function () use ($app) {

    $app->get('login',  [ 'uses' => 'UserController@login']);
    $app->post('login',  [ 'uses' => 'UserController@login']);
    $app->get('logout',  [ 'uses' => 'UserController@logout']);
    $app->get('register',  [ 'uses' => 'UserController@register']);
    $app->get('error/{err}',  [ 'uses' => 'UserController@error']);

});


$app->get('login', [   'uses' => 'UserController@doLogin' ]);


$app->get('/', function () use ($app) {
      return view('index');
});

//include additional Routes
include("routes2.php");
