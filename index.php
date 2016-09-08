<?php
include("config.php");
include("functions.php");

//process file upload
if($_POST["action"]=="upload"){

$target_file = "imported/" . basename($_FILES['fileuplaod']['name']);
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

if($fileType!="html" AND $fileType!="htm"){die("Mozete postaviti samo HTML fajlove");}
if (file_exists($target_file)) {die("Fajl sa tim imenom vec postoji");}

if (move_uploaded_file($_FILES["fileuplaod"]["tmp_name"], $target_file)) {
        echo "Fajl je USPESNO postavljen.";
    } else {
        echo "Doslo je do greske. Fajl NIJE postavljen";
    }
}


$files = scandir("imported");

echo "<h1>Izaberite fajl za import</h1>";
foreach($files as $key=>$value){
    if($value!="." && $value!=".." )
    echo "<p><a href='import.php?file=".$value."' >".$value."</a></p>";
}




?>
<br><br><br><br><br>

<h1>Ili postavite novi fajl.</h1>
<form action="index.php" method="post" enctype="multipart/form-data">
    Select NEW file to upload: <br>
    <input type="file" name="fileuplaod" id="fileuplaod">
    <input type="submit" value="Upload file" name="submit">
    <input type="hidden" name="action" value="upload">

    
</form>









list uploaded files
upload new file
select file for import.php?file=qqq&year=2015