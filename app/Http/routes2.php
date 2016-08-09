<?php

//main menu
// SELECT kid, kowner, knaziv, saltnaslov, sgodina FROM sadrazajs NATURAL JOIN kategorijes order by kowner
//modularni get - year moze da postoji ali i ne mora
$app->get('nav[/{year}]', function ($year=NULL)  {

     if($year){
         //select only first 2 levels
        //$sql = "SELECT kid, kowner, knaziv, saltnaslov, sgodina, saltorder FROM sadrazajs NATURAL JOIN kategorijes WHERE sgodina={$year} order by saltorder";
        $sql = "SELECT kid, kowner, knaziv, saltnaslov, sgodina, saltorder FROM sadrazajs NATURAL JOIN kategorijes WHERE sgodina={$year} AND kowner IN(SELECT sid FROM sadrazajs NATURAL JOIN kategorijes WHERE sgodina={$year} and kowner=0) OR (sgodina={$year} and kowner=0)  order by saltorder";
     } else {
         //default
        //$sql = "SELECT kid, kowner, knaziv, saltnaslov, sgodina, saltorder FROM sadrazajs NATURAL JOIN kategorijes WHERE sgodina=2015 order by saltorder  ";
        $sql = "SELECT kid, kowner, knaziv, saltnaslov, sgodina, saltorder FROM sadrazajs NATURAL JOIN kategorijes WHERE sgodina=2015 AND kowner IN(SELECT sid FROM sadrazajs NATURAL JOIN kategorijes WHERE sgodina=2015 and kowner=0) OR (sgodina=2015 and kowner=0)  order by saltorder";
     }

    $out = frontSql($sql);

    foreach($out as $key => $value){
        if($value->kowner==0) {echo '<p>H1 ' . $value->saltnaslov . ' </p>';} else {echo '<p>H2 ' . $value->saltnaslov . '</p>';}
    }


    echo "<br><br><pre>";
    var_dump($out);
    echo "</pre>";

    return ;
});




//sub menu













function frontSql($sql){

$time_start = microtime(true);
    $md5sql = md5($sql);

    //select queries only

    //create Cache entry
    if(Cache::get($md5sql)){
        $results =  Cache::get($md5sql) ;
    } else {
        $results = DB::select($sql);
        Cache::put($md5sql, $results , "1");
    }

    echo '<br>Total execution time in miliseconds: ' . ((microtime(true) - $time_start)*1000)."<br><br>";

    return $results;

}