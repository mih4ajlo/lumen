<!DOCTYPE html>
<html lang="en">

<head>

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>


	<meta charset="UTF-8">
	<title>Referenca</title>
</head>
<body>


	<div class="container-fluid">
		<?php 
			   
		include '../resources/views/dashboard/header.php'; 
		?>
	</div>


	<div class="navigation col-xs-12 col-sm-6 col-md-2">
		<?php 
			   
		include '../resources/views/dashboard/side-menu.php';
		?>
	</div>
	<div class="main col-xs-12 col-sm-6 col-md-8">
		<div class="container-fluid">
		
			<?php 
				print_r("<pre>");
				var_dump(
					array_keys( get_object_vars(  $unos[0] ) )  
					);
				print_r("</pre>");
				die();
				

			?>
			

			
			

			</div>
	</div>
	

	
</body>
</html>