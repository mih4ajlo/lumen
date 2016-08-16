<!DOCTYPE html>
<html lang="en">

<head>

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
	<script>tinymce.init({ 
		selector:'textarea',
		 visual: false,
		plugins : [
		  'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
		  'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
		  'save table contextmenu directionality emoticons template paste  '
		],
		external_plugins: {
	    	'meta': location.origin +'/scripts/tinymce/plugins/meta/plugin.min.js',
	    	'textcolor':'https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.4.1/plugins/textcolor/plugin.min.js'
	  	},
	 	menubar: ' edit insert view ',
		toolbar:[
		    'undo redo | styleselect | bold italic | link image',
		    'alignleft aligncenter alignright meta  code'
		  ]
	 });

	//newdocument, bold, italic, underline, strikethrough, alignleft, aligncenter, alignright, alignjustify, styleselect, formatselect, fontselect, fontsizeselect, cut, copy, paste, bullist, numlist, outdent, indent, blockquote, undo, redo, removeformat, subscript, superscript
	</script>
	<meta charset="UTF-8">
	<title>Single content</title>
</head>
<body>

	Single content

	<?php 

	/*	print_r("<pre>");
		var_dump("naslov: ". $sadrzaj_unos[0]->saltnaslov);
		var_dump("godina: ". $sadrzaj_unos[0]->sgodina);
		var_dump("keywords: ". $sadrzaj_unos[0]->skeywords);
		var_dump("lang: ". $sadrzaj_unos[0]->slang);
		print_r("</pre>");*/
		//die();	

	?>

	<div class="container-fluid">
		
		<form class="form-horizontal" method="post" action="auth/login">
		<fieldset>

		<!-- Form Name -->
		<legend>Sadrzaj</legend>



		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="textNaslov">Naslov</label>  
		  <div class="col-md-4">
		  <input id="textNaslov" name="textNaslov" type="text" placeholder="naslov" class="form-control input-md">
		    
		  </div>
		</div>

		<div>
			<!-- Select Basic -->
			<div class="form-group ">
			  <label class="col-md-4 control-label" for="selectJezik">Jezik</label>
			  <div class="col-md-4">
			    <select id="selectJezik" name="selectJezik" class="form-control">
			      <option value="RS-cir">Cirilica</option>
			      <option value="RS-lat">Latinica</option>
			      <option value="en">Engleski</option>
			    </select>
			  </div>
			</div>
			
			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="textGodina">Godina</label>  
			  <div class="col-md-4">
			  <input id="textGodina" name="textGodina" type="text" placeholder="godina" class="form-control input-md">
			    
			  </div>
			</div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="textKeywords">Keywords</label>  
		  <div class="col-md-4">
		  <input id="textKeywords" name="textKeywords" type="text" placeholder="keywords" class="form-control input-md">
		    
		  </div>
		</div>

		<!-- Textarea -->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="textSadrzaj">Sadrzaj</label>
		  <div class="col-md-10 col-md-offset-1">  

		    <textarea class="form-control" id="textSadrzaj" name="textSadrzaj" rows="20"><?php echo $sadrzaj_unos[0]->scont; ?></textarea>
		  </div>
		</div>

		</fieldset>
	</form>

	</div>
</body>
</html>