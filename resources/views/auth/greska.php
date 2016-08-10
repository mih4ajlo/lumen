<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Greska</title>
</head>
<body>

<?php 
	//print_r($poruka);
	$err = "";	

	switch ($poruka) {
		case 'sifra':
			$err = "Proverite sifru";		
			break;

		case 'email':
			$err = "Proverite vas email";
			break;	
		
		default:
			# code...
			break;
	}

	print_r( $err );
 		
  ?>	

</body>
</html>