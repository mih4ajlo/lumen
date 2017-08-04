var sadrzaj;
var excludedSearch = [];
var oldH = 0;
var contentArr = {};
var h = $("h1,h2");
var ryear = 2016;
var rkid = 170;
var rlang = "ci"

$(function() {

    if (true) {

        loadReferencesFor();

        $('#subNavHolder').html('');

        $('#displayRefButtonReference').hide();
        $('#displayRefButtonNav').show();
        //set colors to lime
        $('#mainLine').css("border-top", "10px solid #cee66e");
        $('#displayRefButton').css("background-color", "#cee66e");

    }
});



    function loadReferencesFor(cpage) {

	var hash = window.location.hash.replace("#", "");
    var hashVars = hash.split("-");


    if (hashVars[0]) { rlang = hashVars[0]; }
    if (hashVars[1]) { ryear = hashVars[1]; }
    if (hashVars[2]) { rkid = hashVars[2]; }  
	
	
        $('#reference').html('');

        $.get(rlang+"/reference/"+ryear+"/"+rkid, { "_": $.now() }, function(response) {

            parseRefResponse(response);

        }).fail(function() {
            $('#reference').html('No references found');
            $('#displayCont').html('');
        });

    }

    function parseRefResponse(refCont) {

        var sadrzaj = refCont[0].rcont;

        strukturaSadrzaja(sadrzaj);

        //$("#sideMenu").html(struktura);

        $('#reference').html('');
        $("#displayCont").html(sadrzaj);


        $('#displayCont :header').each(function addH(i) {
            if (this.id) {
                $('#reference').append('<p class="refactive"><a href="#' + this.id + '">' + this.innerText + '</a></p>');
            }
        });


    }


    function strukturaSadrzaja(cont) {
    	h =  $(cont).filter('h1,h2');
        $(cont).filter('h1,h2').each(parseDoc);

    }

    var parseDoc = function(index, el) {
        //console.dir(el);
        //trim &nbsp
        var klasa ="";

        if (el.nodeName == "H1") {
        	if(index == 0) 
        	{
        		klasa ="active";
        	}	


            $("#sideMenu").append('<p class="emptyHeader nav-section '+klasa+'" id="showCont' + index + '">' + el.innerText.replace(/\u00a0/g, " ") + '</p>'); //return true;
            oldH = index;
            excludedSearch.push('showCont' + index);
            //set plain HTML for Chapters

        } else {
            $("#sideMenu").append('<p class="header" id="showCont' + index + '">' + el.innerText.replace(/\u00a0/g, " ") + '</p>');
        }

        //put everything between H tags into array
        contentArr['showCont' + index] = $(el).nextUntil(h[index + 1]).andSelf();

        //merge is ok as lon as you put header variable name into excluded search
        $.merge(contentArr['showCont' + oldH], contentArr['showCont' + index]);

        $('#showCont' + index).click(function() {

           /* if (index > 4 && index < 23)
                $(".navbar-nav").show();
            else
                $(".navbar-nav").hide();*/

            //remove active class
            $(this).parent().children().removeClass("active")
            $(this).addClass('active');
            $('#displayCont').html();
            $('#displayCont').html(contentArr['showCont' + index]);
            //posto se vec ucitao u #displayCont
            //parseH3subsection('showCont' + index, el.nodeName);
            //scroll clicked button to top
            $('#content').scrollTop(0);
            $('#content').scrollTop($('#showCont' + index).position().top);

            //set Global current pageX
            curPage = 'showCont' + index;
            //check if references exist
            $('#displayRefButtonReference').hide();
            //$('#displayRefButtonNav').hide();

          

        });

    }




function h4meni(argument) {
	// body...
}


function pretraga( pojam ) {
	
	var kljucevi = Object.keys(contentArr);

	for (var i = 0; i < kljucevi.length; i++) {
		var temp_cont = contentArr[kljucevi[i]];
		
		temp_cont.each(function(ind,el){
			//console.log(ind + " sadraaj :" + $(el).html() )
			
			var broj_nadjenih = $(el).text().match(new RegExp( "\s+(\w+" +pojam+"\w+)\s+" ,'ig'));


		})
	}
}




