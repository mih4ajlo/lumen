<?php
$putanja = 
	($_SERVER['SERVER_NAME']=='localhost')?
			"http://localhost:8075/"	:	"http://".$_SERVER['SERVER_NAME']."/";

//"http://localhost:8075/";

$meni  = array('Akteri' =>'acters','Keywords' =>'keyword' ,'Sadrzaj' =>'content' , 'Users' =>'user' , );
?>

<div>
	<ul>
	<?php foreach ($meni as $key => $value) {
		echo "<li><a href='".$putanja."dashboard/$value'>$key</a></li>";
	} ?>

		
	</ul>

</div>