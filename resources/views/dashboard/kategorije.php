<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Kategorije</title>
</head>
<body>
	


	<?php 
		foreach ($kategorije as  $value) {
			echo $value->kid ." ". $value->knaziv . "<br/>";
		}
	?>

</body>
</html>