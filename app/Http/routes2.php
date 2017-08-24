<?php

//main menu
// SELECT kid, kowner, knaziv, saltnaslov, sgodina FROM sadrzajs NATURAL JOIN kategorijes order by kowner
//modularni get - year moze da postoji ali i ne mora
$app->get('{lang}/nav[/{year}]', function ($lang,$year=NULL)  {

    $name = "nav_year".$year;

    if(file_exists($name)){
        return  response()->json( json_decode( file_get_contents($name) )) ;
    }
     if($year){
         //select only first 2 levels
        $sql = "SELECT kid, kowner, knaziv, saltnaslov, sgodina, saltorder FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina='{$year}' AND slang='{$lang}' order by korder";
        //$sql = "SELECT kid, kowner, knaziv, saltnaslov, sgodina, saltorder FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina={$year} AND kowner IN(SELECT sid FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina={$year} and kowner=0) OR (sgodina={$year} and kowner=0)  order by saltorder";
     } else {
         //default
        $sql = "SELECT kid, kowner, knaziv, saltnaslov, sgodina, saltorder FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina='2015' AND slang='{$lang}' order by korder  ";
        //$sql = "SELECT kid, kowner, knaziv, saltnaslov, sgodina, saltorder FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina=2015 AND kowner IN(SELECT sid FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina=2015 and kowner=0) OR (sgodina=2015 and kowner=0)  order by saltorder";
     }

    $out = frontSql($sql);
    $outf = buildMenuTree($out);

//    echo "<br><br><pre>";
//    var_dump($outf);
//    echo "</pre>";

    $la = json_encode($name , $outf);
    file_put_contents( $la );

    return response()->json($outf) ;
});

//SECTION CONTENT
// lang obavezan / godina obavezna / kid opcioni - defoult na ORDER BY saltorder LIMIT1
$app->get('{lang}/content/{year}[/{kid}]', function ($lang,$year,$kid=NULL)  {

     if($kid){
        $sql = "SELECT scont FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina={$year} AND kid={$kid} AND slang='{$lang}' ";
     } else {
         //default
        $sql = "SELECT scont FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina=2015 AND slang='{$lang}' order by saltorder LIMIT 1  ";
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


    return response()->json($out) ;
});


//TIMELINE CONTENT
// lang obavezan / kid obavezan
$app->get('{lang}/timeline/{kid}', function ($lang,$kid)  {

    $sql = "SELECT sgodina FROM sadrzajs NATURAL JOIN kategorijes WHERE kid={$kid}  AND slang='{$lang}' GROUP BY sgodina ";

    $out = frontSql($sql);

    return response()->json($out) ;
});

//SEARCH DATABASE
// lang obavezan / kid obavezan
$app->get('{lang}/search/{search}', function ($search, $lang)  {

$search = urldecode($search);

    $sql = "SELECT saltnaslov, sgodina,kid FROM sadrzajs NATURAL JOIN kategorijes  WHERE scont LIKE '%{$search}%'  AND slang='{$lang}'  ";

    $out = frontSql($sql);

    return response()->json($out) ;
});

//FOOTNOTES
// lang obavezan / year obavezan
$app->get('{lang}/footnotes/{year}', function ($year, $lang)  {              //dodati flang u mySQL tablu footnotes

    $sql = "SELECT fcont FROM footnotes  WHERE fyear = '{$year}'  AND flang='{$lang}'  ";

    $out = frontSql($sql);

    return response()->json($out) ;
});


//Reference - Dodatne teme
// lang obavezan / year obavezan / kid obavezan
$app->get('{lang}/reference/{year}/{kid}', function ($year,$kid,$lang)  {


    $sql = "SELECT rcont FROM refs  WHERE ryear = '{$year}' AND kid = '{$kid}'  AND rlang='{$lang}'  ";

    $out = frontSql($sql);

    return ($out) ;
});


//FIRST CHILD
// lang obavezan / godina obavezna / kid opcioni - defoult na ORDER BY saltorder LIMIT1
$app->get('{lang}/firstchild/{year}[/{kid}]', function ($lang,$year,$kid=NULL)  {

     if($kid){
        $sql = "SELECT scont FROM sadrzajs NATURAL JOIN kategorijes WHERE sgodina={$year} AND kowner={$kid}  AND slang='{$lang}' ORDER BY korder LIMIT 1  ";
     }

    $out = frontSql($sql);

    return response()->json($out) ;
});


//FIND REFERENCES - dodatne teme
// lang obavezan / godina obavezna / kid obavezan
$app->get('{lang}/findref/{year}/{kid}', function ($lang,$year,$kid)  {

    $sql = "SELECT ryear AS year, rlang AS lang, kid AS id FROM refs WHERE ryear={$year} AND kid={$kid}  AND rlang='{$lang}' ";

    $results = DB::select($sql);



    if(count($results)>0){

        $results[0]->note = '';
        return response()->json($results) ;

    } else {

        //no ref found check in owner category
        //SELECT ryear AS year, rlang AS lang, kid AS id FROM refs WHERE ryear=2015 AND kid=(SELECT kowner FROM `kategorijes` WHERE kid=454)
        $sql = "SELECT ryear AS year, rlang AS lang, kid AS id FROM refs WHERE ryear={$year}  AND rlang='{$lang}' AND kid=(SELECT kowner FROM `kategorijes` WHERE kid={$kid}) ";
        $results = DB::select($sql);

        if(count($results)>0){$results[0]->note = '<span style="color:red;">*</span>';return response()->json($results);}
    
        return response()->json($results);
    }


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