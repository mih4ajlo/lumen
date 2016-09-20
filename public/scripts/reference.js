
var sadrzaj;

 $(function() {


 	//uzmi referencu iz url-a
	
	

 	loadReferencesFor();


	/*
	
	 	$('#tabs').tab();

	 $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	  var target = $(e.target).attr("href") // activated tab
	  //alert(target);
	  if(target == "#reference"){
		
		loadReferencesFor(curPage);
		
		$('#subNavHolder').html('');
		
		$('#displayRefButtonReference').hide();
		$('#displayRefButtonNav').show();
		//set colors to lime
		$('#mainLine').css("background-color", "#cee66e");
		$('#displayRefButton').css("background-color", "#cee66e");
		
	  } else {
		  
		  $('#'+curPage).trigger('click');
		//set colors back to original
		$('#mainLine').css("background-color", "#72D0EB");
		$('#displayRefButton').css("background-color", "#72D0EB");
		
	  }
	}); */

	
	
	function loadReferencesFor(year){
		
		//treba da se prosledi samo id ka jednom elementu
		$('#reference').html('');
		lang = 'ci';
		year = '2015';
		idPojedinacnog = 5;


		$.get(lang+ "/referenca/"+year+"", { "_": $.now() }, 
			function(response) {			
				response = JSON.parse( response );
				parseRefResponse( response, idPojedinacnog);	
				loadSideMenu( response );

		}).fail(function(){ 
		  $('#reference').html('No references found');
		  $('#displayCont').html('');
		});
		
	}
	
	function parseRefResponse(refCont, idPoj){
		

		
		var temp_unos = refCont[1];

		$('#reference').html('');
		$("#displayCont").html(temp_unos.scont);

		
	    $('#displayCont :header').each(function addH(i){
			if(this.id){
				$('#reference').append('<p class="refactive"><a href="#'+this.id+'">'+this.innerText+'</a></p>');
			}
		});
		

	}	
	

	function loadSideMenu( response ) {
		//samo prikazati u meniju sa strane
		
		//uzeti sve naslove i prikazati ih
		//treba sloziti u treestrukturu
		var template = "";

		for (var i = 0; i < response.length; i++) {

			var temp_el = response[i];
			template += '<p class="side-menu-item"><a href="#'+temp_el.sid+'" sid="'+temp_el.sid+'">'+temp_el.saltnaslov+'</a></p>';

		}

		$("#sideMenu").html( template )

		$(".side-menu-item").click(function(el) {

			var el_id = $(el.target).attr('sid');
			var temp_el = response.filter(function(el) {return el.sid == el_id});
			temp_el = temp_el[0];

			
			$("#displayCont").empty();
			$("#displayCont").append( temp_el.scont );
		})
	}


	
	
})