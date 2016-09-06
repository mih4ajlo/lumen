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
				<div class="ta-right dodaj-keyword" >
					<span>Dodaj unos </span>
					<span class="glyphicon glyphicon-plus"  aria-hidden="true"></span>
				</div>
			</div>

			<table class="table table-condensed">
			<tr>
				<th>Keyword</th>
				<th>Keyword cirilica</th>
				<th>Kategorija</th>
				<th>Izmeni</th>
				<th>Obrisi</th>
			</tr>
			
			
				<?php 

					foreach ($lista_keywords as  $value) {

						$temp_id = $value->id;

						$ikonica_edit =  '<span class="glyphicon glyphicon-pencil" keyword="'.$temp_id.'" aria-hidden="true"></span><span class="glyphicon glyphicon-ok posalji-unos hidden confirm-dugme" keyword="'.$temp_id.'"   aria-hidden="true"></span>';

						$ikonica_delete =  '<span class="glyphicon glyphicon-remove" keyword="'.$temp_id.'" aria-hidden="true"></span>';


						$link_edit = "<a href='#' keyword='$temp_id' class='edit-link'>$ikonica_edit</a>";

						//ajax potvrda akcije	
						//keyword/delete/$temp_id
						$link_del = "<a href='#' keyword='$temp_id' class='delete-link' >$ikonica_delete</a>";

						print_r("<tr>"
							."<td kol='keyword'>{$value->keyword}</td> "
							."<td kol='keyword_cir'>{$value->keyword_cir}</td>"
							."<td kol='kategorija'>{$value->kategorija}</td>"
							."<td>$link_edit</td>"
							."<td>$link_del</td>"
							."</tr>");
						

					}

				?>
			</table>
			<div class="row">
				<div class="ta-right dodaj-keyword" >
					<span>Dodaj unos </span>
					<span class="glyphicon glyphicon-plus"  aria-hidden="true"></span>
				</div>
			</div>

			</div>
	</div>
	
<script type="text/javascript">
	$(".dodaj-keyword").click(function(event) {
		//dodaj red u tabeli
		$("table").find('tbody')
		    .append($('<tr>')
		        .append(
		        	'<td><input type="text" name="keyword"></td> '+
					'<td><input type="text" name="keyword_cir"></td>'+
					'<td><input type="text" name="kategorija"></td>'+
					'<td><span class="glyphicon glyphicon-ok posalji-unos" onclick="posaljiUnos(this)" aria-hidden="true"></span></td>'+
					'<td><span class="glyphicon glyphicon-remove ukloni-red" onclick="ukloniRed(this)"   aria-hidden="true"></span></td>'

		        	/*$('<td>')
		            .append($('<img>')
		                .attr('src', 'img.png')
		                .text('Image cell')
		            )*/
		        )
		    );
	});

	$(".delete-link").click(function(ev) {

		var id_keyword = $(ev.target).attr("keyword");

		$.ajax({
			url: 'keyword/delete/'+ id_keyword
		})
		.done(function() {
			console.log("success");
			ukloniRed(ev.target);

		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	})


	$(".edit-link").click(function(ev) {

		var temp_el = $(ev.target);

		var id_keyword = temp_el.attr("keyword");

		

		var row = temp_el.parent().parent().parent();
		var selectedTd = row.find("td:not(:has(*))");

		var data = {};

		selectedTd.each(function(ind, ele ){
			$(ele).attr('contenteditable',"true")
			//data[ $(ele).attr('kol')] = $(ele).html();
			//console.log( $(ele).html() )

		})

		//selektuj prvu kolonu

		temp_el.hide()
		row.find(".hidden").removeClass('hidden')



		console.log(data);
		
	})


	$(".confirm-dugme").click(function(ev) {

		var temp_el = $(ev.target);

		var id_keyword = temp_el.attr("keyword");

		

		var row = temp_el.parent().parent().parent();
		var selectedTd = row.find("td:not(:has(*))");

		var data = {};

		selectedTd.each(function(ind, ele ){
			$(ele).attr('contenteditable',"false")
			data[ $(ele).attr('kol')] = $(ele).html();
			//console.log( $(ele).html() )

		})

		//selektuj prvu kolonu

		row.find(".hidden").removeClass('hidden')
		temp_el.hide()

		$.ajax({
			url: 'keyword/edit/'+id_keyword,
			type: 'POST',			
			data: data
		})
		.done(function(msg) {
			console.log("success");
			console.log(msg);
		})
		.fail(function(msg) {
			console.error(msg);
		});

		
	})



	function posaljiUnos(el) {
		
		var row = $(el).parent().parent();
		var inputs  = row.find("input");

		var sendingObject = {};
		inputs.each(function(index, el) {
			sendingObject [ el.name] = el.value  ;			
		});

		$.ajax({
			url: 'keyword/add',
			type: 'POST',			
			data: sendingObject
		})
		.done(function(msg) {
			console.log("success");
			console.log(msg);
		})
		.fail(function(msg) {
			console.error(msg);
		});
		

	}

	function ukloniRed(el) {

		var temp = $(el).parent().parent().parent();
		temp.remove()
		
	}

	function izbrisiUnos(el) {
		
	}
</script>
	
</body>
</html>