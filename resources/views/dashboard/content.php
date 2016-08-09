<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Content</title>
</head>
<body>


	<?php 
			
		include_once 'side-menu.php';

		foreach ($lista as  $value) {
			echo $value->sid. " " .$value->saltnaslov . "<br/>";
		}


	?>
</body>
</html>