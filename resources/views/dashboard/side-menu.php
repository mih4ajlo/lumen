<?php
$putanja = 
	($_SERVER['SERVER_NAME']=='localhost')?
			"http://localhost:8075/"	:	"http://".$_SERVER['SERVER_NAME']."/";

//"http://localhost:8075/";

$meni  = array('Akteri' =>'acters','Keywords' =>'keyword' ,'Sadrzaj' =>'content' , 'Users' =>'user' , );
?>


<style type="text/css">
	.side-menu{
		margin: 20px;
	}
	ul{
		list-style: none;
	}
	li{   
		display: block;
	    text-align: center;
	    position: relative;
	    margin: 10px 0;
	}
</style>

<div class="side-menu">
	<ul>
	<?php foreach ($meni as $key => $value) {
		echo "<li><a href='".$putanja."dashboard/$value'>$key</a></li>";
	} ?>

		
	</ul>

</div>