<!DOCTYPE html>
<html lang="en">

<head>

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
	<script>
	//filter atributa
	//https://www.tinymce.com/docs/configure/content-filtering/
	tinymce.init({ 
		selector:'textarea',
		 visual: false,
		 valid_elements : '*[*]',
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
		 //extended_valid_elements : 'p[class|akter]' 
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
		
		<form class="form-horizontal" method="post" action="edit">
		<fieldset>

		<!-- Form Name -->
		<legend>Sadrzaj</legend>



		<!-- Text input-->
		<div class="form-group">

			<button  class="btn btn-default">Sacuvaj</button>
		  <label class="col-md-4 control-label" for="textNaslov">Naslov</label>  
		  <div class="col-md-4">
		  <input id="textNaslov" name="textNaslov" type="text" placeholder="naslov" class="form-control input-md"  value="<?php echo $sadrzaj_unos[0]->saltnaslov; ?>">
		    
		  </div>
		</div>

		<input type="hidden" name="sid" value="<?php echo $sadrzaj_unos[0]->sid; ?>">
		<div>
	

		id ,tip ,kid ,saltnaslov ,saltorder ,surl ,sordernatural  ,s_related_to 

			<!-- Select Basic -->
			<div class="form-group ">
			  <label class="col-md-4 control-label" for="selectJezik">Jezik</label>
			  <div class="col-md-4">
			    <select id="selectJezik" name="selectJezik" class="form-control">
			      <option value="rs-ci">Cirilica</option>
			      <option value="rs-lat">Latinica</option>
			      <option value="en">Engleski</option>
			    </select>
			  </div>
			</div>
			
			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="textGodina">Godina</label>  
			  <div class="col-md-4">
			  <input id="textGodina" name="textGodina" type="text" placeholder="godina" class="form-control input-md" value="<?php echo $sadrzaj_unos[0]->sgodina; ?>">
			    
			  </div>
			</div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="textKeywords">Keywords</label>  
		  <div class="col-md-4">
		  <input id="textKeywords" name="textKeywords" type="text" placeholder="keywords" class="form-control input-md" value="<?php echo $sadrzaj_unos[0]->skeywords; ?>">
		    
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="textKategorija">Kategorija</label>  
		  <div class="col-md-4">
		  <input id="textKategorija" name="textKategorija" type="text" placeholder="kategorija" class="form-control input-md" value="<?php echo $sadrzaj_unos[0]->kid; ?>">
		    
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