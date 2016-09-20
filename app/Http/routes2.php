<?php

//main menu
// SELECT kid, kowner, knaziv, saltnaslov, sgodina FROM sadrzajs NATURAL JOIN kategorijes order by kowner
//modularni get - year moze da postoji ali i ne mora
$app->get('{lang}/nav[/{year}]', function ($lang,$year=NULL)  {


    if($lang =="cir" || $lang =="ci"){
        $lang = "rs-ci";
    }

    if($lang =="lat" ){
        $lang = "rs-lat";
    }

    //jezik i tip sadrzaja

     if($year){
         //select only first 2 levels
        $sql = /*"SELECT kid, kowner, knaziv, saltnaslov, sgodina, saltorder FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina={$year} and tip = 'sadrzaj' order by korder";  */
        "SELECT k.kid, kowner, knaziv, saltnaslov, kgodina as godina, sgodina  , saltorder FROM kategorijes k INNER JOIN sadrzajs s on k.kid=s.kid WHERE kgodina={$year} and klang='{$lang}' and k.tip = 'sadrzaj' order by korder";
        //$sql = "SELECT kid, kowner, knaziv, saltnaslov, sgodina, saltorder FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina={$year} AND kowner IN(SELECT sid FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina={$year} and kowner=0) OR (sgodina={$year} and kowner=0)  order by saltorder";
     } else {
         //default
        $sql = "SELECT k.kid, kowner, knaziv, saltnaslov,sgodina , kgodina as godina, saltorder FROM kategorijes k INNER JOIN sadrzajs s on k.kid=s.kid WHERE kgodina=2015 and  klang='{$lang}' and k.tip = 'sadrzaj' order by korder";
        /*"SELECT kid, kowner, knaziv, saltnaslov, sgodina, saltorder FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina=2015 and tip = 'sadrzaj' order by korder  ";*/
        //$sql = "SELECT kid, kowner, knaziv, saltnaslov, sgodina, saltorder FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina=2015 AND kowner IN(SELECT sid FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina=2015 and kowner=0) OR (sgodina=2015 and kowner=0)  order by saltorder";
     }
     

    $out = frontSql($sql);
    $outf = buildMenuTree($out);

//    echo "<br><br><pre>";
//    var_dump($outf);
//    echo "</pre>";


    return json_encode($outf) ;
});

//SECTION CONTENT
// lang obavezan / godina obavezna / kid opcioni - defoult na ORDER BY saltorder LIMIT1
$app->get('{lang}/content/{year}[/{kid}]', function ($lang,$year,$kid=NULL)  {

    if($lang =="cir" || $lang =="ci"){
        $lang = "rs-ci";
    }

    if($lang =="lat" ){
        $lang = "rs-lat";
    }


     if($kid){
        $sql = "SELECT scont FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina={$year} AND kid={$kid} AND slang='{$lang}' AND tip = 'sadrzaj'  ";
     } else {
         //default
        $sql = "SELECT scont FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina=2015 AND slang='{$lang}' AND tip = 'sadrzaj'  order by saltorder LIMIT 1  ";
     }

    $out = frontSql($sql);

    //clear span lang and class
    //$out[0]->scont = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $out[0]->scont);
    //$out[0]->scont = preg_replace('/(<[^>]+) lang=".*?"/i', '$1', $out[0]->scont);
    //$out[0]->scont = preg_replace('/(<[^>]+) class=".*?"/i', '$1', $out[0]->scont);
    //$out[0]->scont = preg_replace('/(<[^>]+) width=".*?"/i', '$1', $out[0]->scont);


//    echo "<br><br><pre>";
//    var_dump($out);
//    echo "</pre>";


    return json_encode($out) ;
});


//TIMELINE CONTENT
// lang obavezan / kid obavezan
$app->get('{lang}/timeline/{kid}', function ($lang,$kid)  {

    if($lang =="cir" || $lang =="ci"){
        $lang = "rs-ci";
    }

    if($lang =="lat" ){
        $lang = "rs-lat";
    }

    $sql = "SELECT sgodina FROM sadrzajs NATURAL JOIN kategorijes WHERE kid={$kid} AND tip = 'sadrzaj'  GROUP BY sgodina ";

    $out = frontSql($sql);

    return json_encode($out) ;
});

//SEARCH DATABASE
// lang obavezan / kid obavezan
$app->get('{lang}/search/{search}', function ($lang,$search)  {

    $search = urldecode($search);

    if($lang =="cir" || $lang =="ci"){
        $lang = "rs-ci";
    }

    if($lang =="lat" ){
        $lang = "rs-lat";
    }

    //lang
    //scont_notag
    //and sgodina=2015
    //tip      
    //AND slang = '{$lang}'
          


    $sql = "SELECT saltnaslov, sgodina,kid,sid FROM sadrzajs NATURAL JOIN kategorijes  WHERE scont LIKE '%{$search}%' AND tip = '{$_GET["tip"]}' AND sgodina = '{$_GET["god"]}' AND slang = '{$lang}' ";

    $out = frontSql($sql);

    return json_encode($out) ;
});

//FOOTNOTES
// lang obavezan / kid obavezan
$app->get('{lang}/footnotes/{year}', function ($year)  {

    $sql = "SELECT fcont FROM footnotes  WHERE fyear = '{$year}'  AND tip = 'sadrzaj' ";

    $out = frontSql($sql);

    return json_encode($out) ;
});





///////////////////////////////
//HELPER FUNCTIONS
///////////////////////////////

//build hierachical tree from flat mySQL parent/owner result
//http://stackoverflow.com/questions/13877656/
function buildMenuTree(array $elements, $root = 0) {
    $branch = array();

    foreach ($elements as $element) {
        if ($element->kowner == $root) {
            $children = buildMenuTree($elements, $element->kid);
            if ($children) {
                $element->children = $children;
            }
            $branch[] = $element;
        }
    }

    return $branch;
}


//exec sql and return result for fron end - caching included :)
function frontSql($sql){

$time_start = microtime(true);
    $md5sql = md5($sql);

    //select queries only

    //No CACHE for now
    $results = DB::select($sql);

    //create Cache entry
//    if(Cache::get($md5sql)){
//        $results =  Cache::get($md5sql) ;
//    } else {
//        $results = DB::select($sql);
//        Cache::put($md5sql, $results , "1");
//    }

    //echo '<br>Total execution time in miliseconds: ' . ((microtime(true) - $time_start)*1000)."<br>";

    return $results;

}