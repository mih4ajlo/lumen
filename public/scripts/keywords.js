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

		//todo selektuj prvu kolonu

		
		row.find(".hidden").removeClass('hidden')
		temp_el.addClass('hidden')
		$(selectedTd[0]).focus();


		console.log(data);
		
	})


	$(".confirm-dugme").click(function(ev) {

		var temp_el = $(ev.target);

		var id_keyword = temp_el.attr("keyword");

		

		var row = temp_el.parent().parent()/*.parent()*/;
		var selectedTd = row.find("td:not(:has(*))");

		var data = {};

		selectedTd.each(function(ind, ele ){
			$(ele).attr('contenteditable',"false")
			data[ $(ele).attr('kol')] = $(ele).html();
			//console.log( $(ele).html() )

		})

		//selektuj prvu kolonu

		row.find(".hidden").removeClass('hidden')
		temp_el.addClass('hidden')/*hide()*/

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