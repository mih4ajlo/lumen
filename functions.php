<?php
include("config.php");

function getMainCats (){
    global $db;

$sql = "SELECT knaziv FROM KATEGORIJES ";
$result = $db->query($sql);

$topmenu = '<ul>';

while($row = mysqli_fetch_object($result)){

 $topmenu .=  '<li>'. $row->knaziv .'</li>';

}

$topmenu .=  '</ul>';

return $topmenu;

}



?>