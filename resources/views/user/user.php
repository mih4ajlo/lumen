<!DOCTYPE html>
<html lang="en">

<head>

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

	
	<meta charset="UTF-8">
	<title>Single user</title>
</head>
<body>

	<div class="container">
		<div class="row">Header neki</div>
	</div>


	<div class="navigation col-xs-12 col-sm-6 col-md-2">
		side menu content
	</div>

	<div class="main col-xs-12 col-sm-6 col-md-8">
		
	<div class="container-fluid">
		
		<form class="form-horizontal" method="post" action="auth/login">
		<fieldset>

	
		<?php  

		$user_unos = $user_unos[0];/*
				print_r("<pre>");
				var_dump();
				print_r("</pre>");
				die();*/
		
		?>


		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="textImePrezime">Ime i prezime</label>  
		  <div class="col-md-4">
		  <input id="textImePrezime" name="textImePrezime" type="text" placeholder="naslov" class="form-control input-md" value="<?php echo $user_unos->ime_prezime; ?>">
		    
		  </div>
		</div>

		
		<!-- Select Basic -->
		<div class="form-group ">
		  <label class="col-md-4 control-label" for="selectJezik">Privilegija</label>
		  <div class="col-md-4">
		    <select id="selectJezik" name="selectJezik" class="form-control">
		      <option value="admin">Admin</option>
		      <option value="regular">Regular</option>
		    </select>
		  </div>
		</div>
		
		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="textUName">Username</label>  
		  <div class="col-md-4">
		  <input id="textUName" name="textUName" type="text" placeholder="godina" class="form-control input-md" value="<?php echo $user_unos->uname; ?>">
		    
		  </div>
		</div>
		

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="textMail">Mail</label>  
		  <div class="col-md-4">
		  	<input id="textMail" name="textMail" type="text" placeholder="keywords" class="form-control input-md" value="<?php echo $user_unos->email; ?>">
		    
		  </div>
		</div>

		<div class="form-group">
			<button type="button">Nova sifra</button>	
		</div>
		
				<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="textSifra">Sifra</label>  
		  <div class="col-md-4">
		  <input id="textSifra" name="textSifra" type="text" placeholder="sifra" class="form-control input-md" >
		    
		  </div>
		</div>
		

		</fieldset>
		<input type="submit" value="Izmeni">
	</form>

	</div>
	</div>
</body>
</html>