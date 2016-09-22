<?php
		

$putanja = 
	($_SERVER['SERVER_NAME']=='localhost')?
			"http://localhost:8075/"	:	"http://".$_SERVER['HTTP_HOST']."/";

//"http://localhost:8075/";

$meni  = array('Home'=>'','Akteri' =>'acters','Keywords' =>'keyword' ,'Sadrzaj' =>'content' , 'Users' =>'user', 'Dodatni sadrzaj'=>'referenca', "Footnotes"=>'footnote', "Import"=>'import' , );
?>


<style type="text/css">
	.side-menu{
		margin: 20px;
	}
	.side-menu ul{
		list-style: none;
	}
	.side-menu li{   
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