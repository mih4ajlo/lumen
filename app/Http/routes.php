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

  $app->get('content2',  [   'uses' => 'ContentController@list_content2']);

   $app->post('content/saveTree',  [   'uses' => 'ContentController@saveTree']);
   $app->get('content/saveTree',  [   'uses' => 'ContentController@saveTree']);


   $app->get('content/{content_id}',  [   'uses' => 'ContentController@single']);
   $app->post('content/edit',  [   'uses' => 'ContentController@edit_content']);
   $app->get('content/delete/{content_id}',  [   'uses' => 'ContentController@delete_content']);


   /*akteri*/
   $app->get('acters',  [   'uses' => 'ActerController@list_acters']);
   $app->post('acters/edit/{acter_id}',  [   'uses' => 'ActerController@edit_acter']);
   $app->post('acters/add',  [   'uses' => 'ActerController@add_acter']);
   $app->get('acters/delete/{acter_id}',  [   'uses' => 'ActerController@delete_acter']);


     /*keywords*/
   $app->get('keyword',  [   'uses' => 'KeywordController@list_keywords']);
   $app->post('keyword/edit/{keyword_id}',  [   'uses' => 'KeywordController@edit_keyword']);
   $app->post('keyword/add',  [   'uses' => 'KeywordController@add_keyword']);
   $app->get('keyword/delete/{keyword_id}',  [   'uses' => 'KeywordController@delete_keyword']);


      /*footnotes*/
   $app->get('footnote',  [   'uses' => 'FootnotesController@list_footnotes']);
   $app->post('footnote/edit/{footnote_id}',  [   'uses' => 'FootnotesController@edit_footnote']);
   $app->post('footnote/add',  [   'uses' => 'FootnotesController@add_footnote']);
   $app->get('footnote/delete/{footnote_id}',  [   'uses' => 'FootnotesController@delete_footnote']);

       /*kategorijas*/
   $app->get('kategorija',  [   'uses' => 'KategorijaController@list_kategorija']);
   $app->post('kategorija/edit/{kategorija_id}',  [   'uses' => 'KategorijaController@edit_kategorija']);
   $app->post('kategorija/add',  [   'uses' => 'KategorijaController@add_kategorija']);
   $app->get('kategorija/delete/{kategorija_id}',  [   'uses' => 'KategorijaController@delete_kategxorija']);


       /*referencas*/
   $app->get('referenca',  [   'uses' => 'ReferenceController@list_referenca']);
   $app->get('referenca/{referenca_id}',  [   'uses' => 'ReferenceController@one_referenca']);
   $app->post('referenca/edit/{referenca_id}',  [   'uses' => 'ReferenceController@edit_referenca']);
   $app->post('referenca/add',  [   'uses' => 'ReferenceController@add_referenca']);
   $app->get('referenca/delete/{referenca_id}',  [   'uses' => 'ReferenceController@delete_referenca']);




   /*import*/
  
   $app->get('import[/{naziv_funkcije}]',  [   'uses' => 'ImportController@glavna']);

   $app->post('import/up/showStoredSectionForYear',  [   'uses' => 'ImportController@showStoredSectionForYear']);

   $app->post('import/up/insertSectionForYear',  [   'uses' => 'ImportController@insertSectionForYear']);

    $app->post('import/up/insertNewCategory',  [   'uses' => 'ImportController@insertNewCategory']);



   $app->post('import/up/upload',  [   'uses' => 'ImportController@uploadFile']);
   $app->post('import/up/showCategories',  [   'uses' => 'ImportController@showCategories']);
   $app->post('import/up/showStoredSectionForYear',  [   'uses' => 'ImportController@showStoredSectionForYear']);
 

    $app->get('content/up/showCategoriesOrder',  [   'uses' => 'ImportController@showCategoriesOrder']);


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

      return view('index');
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
