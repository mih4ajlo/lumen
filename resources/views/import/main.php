<?php 

	$files = scandir("../storage/app");

	echo "<h1>Izaberite fajl za import</h1>";
	foreach($files as $key=>$value){

	    if($value!="." && $value!=".."  && strpos($value, ".htm"  ))
	    echo "<p><a href='import/order?file=".$value."' >".$value."</a></p>";
	} 
?>


lista uploadovanih fajlova

<br><br><br><br><br>

<h1>Ili postavite novi fajl.</h1>
<form action="import/up/upload" method="post" enctype="multipart/form-data">
    Select NEW file to upload: <br>
    <input type="file" name="fileuplaod" id="fileuplaod">
    <input type="submit" value="Upload file" name="submit">
    <input type="hidden" name="action" value="upload">

    
</form>

<a href="import/order">Link ka necem</a>

list uploaded files
upload new file
select file for import/order?file=qqq&year=2015