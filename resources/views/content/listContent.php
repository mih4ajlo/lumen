<!DOCTYPE html>
<html lang="en">

<head>

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<style type="text/css">
	.ta-right{
		text-align: right;
	}
	</style>

	<meta charset="UTF-8">
	<title>List of content</title>
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
		<div class="row">
				<div class="ta-right" >
					<span>Dodaj unos </span>
					<span class="glyphicon glyphicon-plus"  aria-hidden="true"></span>
				</div>
			</div>

			<table class="table table-condensed">
			<tr>
				<th>Naslov</th>
				<th>Redosled</th>
				<th>Izmeni</th>
				<th>Obrisi</th>
			</tr>
			
			
				<?php 

					foreach ($lista_unosa as  $value) {

						$temp_id = $value->sid;

						$ikonica_edit =  '<span class="glyphicon glyphicon-pencil" content="'.$temp_id.'" aria-hidden="true"></span>';

						$ikonica_delete =  '<span class="glyphicon glyphicon-remove" content="'.$temp_id.'" aria-hidden="true"></span>';


						$link_edit = "<a href='content/$temp_id'>$ikonica_edit</a>";

						//ajax potvrda akcije	
						$link_del = "<a href='content/delete/$temp_id'>$ikonica_delete</a>";

						print_r("<tr>"
							."<td>{$value->saltnaslov}</td> "
							."<td>{$value->saltorder}</td>"
							."<td>$link_edit</td>"
							."<td>$link_del</td>"
							."</tr>");
						

					}

				?>
			</table>
			<div class="row">
				<div class="ta-right" >
					<span>Dodaj unos </span>
					<span class="glyphicon glyphicon-plus"  aria-hidden="true"></span>
				</div>
			</div>

			</div>
	</div>
	

	
</body>
</html>