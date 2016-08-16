<!DOCTYPE html>
<html lang="en">
<head>

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<meta charset="UTF-8">
	<title>Login</title>
</head>
<body>



	<div class="container-fluid  ">

		<form class="form-horizontal col-md-6 col-md-offset-3" action="auth/login" method="POST">
			<fieldset>
		
			<!-- Form Name -->
			<legend>Login</legend>
		
			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="textinput">Email</label>  
			  <div class="col-md-4">
			  <input id="textinput" name="email" type="text" placeholder="email" class="form-control input-md">
			    
			  </div>
			</div>
		
			<!-- Password input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="password">Password Input</label>
			  <div class="col-md-4">
			    <input id="password" name="pass" type="password" placeholder="pass" class="form-control input-md">
			    
			  </div>
			</div>
		
			<!-- Button -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="singlebutton"></label>
			  <div class="col-md-4">
			    <button id="singlebutton" name="singlebutton" class="btn btn-default">Login</button>
			  </div>
			</div>
		
			</fieldset>
		</form>
	</div>

	
</body>
</html>