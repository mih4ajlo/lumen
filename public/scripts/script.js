//Set globals
//apiLocation = 'http://z.com/';
apiLocation = '';
lang = 'cir';
year = '2015';
id='';
compareTo = '';
searchFor = '';

$(document).ready(function() {
    console.log("ready!");
//parse URL on start
	parseUrl();
    

});


window.onpopstate = function(event) {
	parseUrl();
};

$(document).on('click','#uporediOff',function(){
    turnOffCompare();
});

$(document).on('click','#toTop',function(){
    $(document).scrollTop(0);
});


function parseUrl(){

    //some cleanup
    turnOffCompare();

	var hash = window.location.hash.replace("#", "");
    var hashVars = hash.split("-");

    if(hashVars[0]){lang = hashVars[0];}
    if(hashVars[1]){year = hashVars[1];}
    if(hashVars[2]){id = hashVars[2];}
    if(hashVars[3]){compareTo = hashVars[3];}
    if(hashVars[4]){searchFor = hashVars[4];}


    showMenu(year);
    showMainCont(year,id);
    availableYearsToCompare(); //za padajuci COMPARE meni
    loadFootNotes(year); 

    //if compare active
    if(compareTo){ compareToYear(compareTo); }
    if(searchFor){ searchForString(searchFor); }


}

//get mainMenu from DB
//year is mandatory
function showMenu(){
    $("#nav").html('');
//load nav from API
    $.getJSON( apiLocation + lang+ "/nav/"+year, function(menuRes) {
        console.log( "JSON for menu loaded..." );
        menuOut = buildMenuList(menuRes,false);
        $("#nav").html(menuOut);
		
		//show 3rd level nav FIRST - important
		showSubNav();
		//mark active
		$("#nav a[href='#"+lang+"-"+year+"-"+id+"']" ).addClass('active');
		//move active to top
		$('#content').scrollTop( 0 );
		$('#content').scrollTop( $("#nav a[href='#"+lang+"-"+year+"-"+id+"']" ).position().top );
		

    })
    .fail(function() {
        $("#nav").html('<p class="emptyHeader nav-section" >Greška prilikom učitavanja menija.</p>');
    });
}

//get content from database for requested year/section
//if no id DB will serve firs section of salorder
function showMainCont(year,id){
    $("#displayCont").html('');
//load main content from API
    $.getJSON( apiLocation + lang+ "/content/"+year+"/"+id, function(mainContRes) {
        console.log( "JSON for main content loaded..." );
        $("#displayCont").html(mainContRes[0].scont);

clearStyles("displayCont");

$("#uporediOff").hide();

    })
    .fail(function() {
        $("#displayCont").html('<p class="emptyHeader nav-section" >Greška prilikom učitavanja glavnog sadržaja.</p>');
    });
}

function compareToYear(yearToCompare){
    $('#displayContCompare').html('');

    $.getJSON( apiLocation + lang+ "/content/"+yearToCompare+"/"+id, function(yearToCompareRes) {
        console.log( "JSON for COMPARE content loaded..." );
        $('#displayContCompare').html(yearToCompareRes[0].scont);

clearStyles('displayContCompare');
$("#uporediOff").show();
loadFootNotesCompare(year);

        //prikazi compare div
        $(function() {
            $("#displayCont").animate({
                width: '49%'
            }, { duration: 200, queue: false });
            $("#displayContCompare").animate({
                width: '49%',
                opacity: 1.0
            }, { duration: 200, queue: false });
        });

		
    })
    .fail(function() {
        $("#displayContCompare").html('<p>Greška prilikom učitavanja sadržaja za uporedjivanje.</p>');
    });

}

function availableYearsToCompare(){
    $('#timelineList').html('');

    $.getJSON( apiLocation + lang+ "/timeline/"+id, function(yearsToCompareRes) {
        console.log( "JSON for COMPARE section through years loaded..." );

        if(yearsToCompareRes.length>1){

            $.each( yearsToCompareRes, function( key, value ) {
                if(value.sgodina!=year){ $('#timelineList').append('<div class="item"><a href="#'+lang+'-'+year+'-'+id+'-'+value.sgodina+'">'+value.sgodina+'</a></div>');}
            });
        } else {
             $('#timelineList').html('<div class="item">Nema podataka za poredjenje.</div>');
        }


    })
    .fail(function() {
        $("#timelineList").html('<div class="item">Greška prilikom učitavanja sadržaja za uporedjivanje.</div>');
    });

}

//highlight all keywords
function searchForString(searchFor){
        //call it after XXX ms if empty - wait for load
        if ($('#displayCont').is(':empty')){ setTimeout(function(){ searchForString(searchFor);}, 200); return;}

        console.log( "Searching for "+searchFor );
        //console.log( $("#displayCont").html() );

        key=1;count=1;

            $("#displayCont").html(function(i, valspan) {
                //http://stackoverflow.com/questions/12493128
                var re = new RegExp("(?!<span[^>]*?>)(" + searchFor + ")(?![^<]*?</span>)", "i");

                //test regex in loop and make changes
                while (re.test(valspan)) {

                    var foundSearchTerm = re.exec(valspan)[0];

                    valspan = valspan.replace(re, '<span class="filtered" id="' + key + '-' + count + '" >' + foundSearchTerm + '</span>');

                    var surroudingWords = valspan.substr(valspan.lastIndexOf('<span class="filtered" id="' + key + '-' + count + '" >' + foundSearchTerm + '</span>'), valspan.length);
                    var pretext = valspan.substr(0, valspan.lastIndexOf('<span class="filtered" id="' + key + '-' + count + '" >' + foundSearchTerm + '</span>'));
                    var surroudingWords = $($.parseHTML(pretext)).text().split(" ").splice(-5).join(" ") + " " + $($.parseHTML(surroudingWords)).text().split(" ").splice(0, 10).join(" ");;

                    //var detail = { "podaci": surroudingWords, "meta": key + '-' + count, 'position': count };
                    //stavke.push(detail);

                    count++;
                }

                return valspan;
            });

        $('html, body').animate({
            scrollTop: $('.filtered:visible:first').offset().top-50
        }, 500);

}


//load FOOTNOTES
function loadFootNotes(year){

    $.getJSON( apiLocation + lang+ "/footnotes/"+year, function(loadFootNotesRes) {
        console.log( "JSON for FOOTNOTES loaded..." );

        if(loadFootNotesRes.length>0){ $("#footnoteContent").html(loadFootNotesRes[0].fcont); }
		//disable footnote click in displayCont element and add footnotes hover
		disableFootNotesAddHover(); 		

    })
    .fail(function() {
        $("#footnoteContent").html('Greška prilikom učitavanja fusnota.');
    });
	
}

function loadFootNotesCompare(year){

    $.getJSON( apiLocation + lang+ "/footnotes/"+year, function(loadFootNotesRes) {
        console.log( "JSON for FOOTNOTES COMPARE loaded..." );

        if(loadFootNotesRes.length>0){ $("#footnoteContentCompare").html(loadFootNotesRes[0].fcont); }

		disableFootNotesAddHoverCompare();		

    })
    .fail(function() {
        $("#footnoteContentCompare").html('Greška prilikom učitavanja fusnota.');
    });
	
}


//SEARCH functions from DATABASE
$(function(){ // this will be called when the DOM is ready

        $("#filter").keyup(function(ev) {

            console.log($("#filter").val());
            //remove filtered class from document
            //if ($("#filter").val().length == 0) pr.removeResult();

            $('#rezultatiPretrage').html('');

            if ($("#filter").val().length < 3) return;
            //get data from API
            $.getJSON( apiLocation + lang+ "/search/"+$("#filter").val(), function(searchRes) {
                console.log( "JSON for SEARCH..." );

                if(searchRes.length>0){

                    //dodaj podatke sa list
                    $.each( searchRes, function( key, value ) {
                        $('#rezultatiPretrage').append('<div class="stavka-pretrage"><a href="#'+lang+'-'+value.sgodina+'-'+value.kid+'--'+$("#filter").val()+'">'+value.saltnaslov+'</a></div>');
                     });

                } else {
                     $('#rezultatiPretrage').html('<div class="stavka-pretrage">Nema rezultata.</div>');
                }


            })
            .fail(function() {
                $("#displayCont").html('<p class="emptyHeader nav-section" >Greška prilikom učitavanja sadržaja za uporedjivanje.</p>');
            });


        });


});
















//HELPER FUNCTIONS
//http://stackoverflow.com/questions/9362446
function buildMenuList(data, isSub){
    //var html = (isSub)?'<div>':''; // Wrap with div if true
    var html = '';
    html += '<ul>';
    for(item in data){
        html += '<li>';
        if(typeof(data[item].children) === 'object'){ // An array will return 'object'
                html += '<a href="#'+lang+'-'+data[item].sgodina+'-'+data[item].kid+'">'+data[item].saltnaslov+'</a>'; // Submenu found, but top level list item.
            html += buildMenuList(data[item].children, true); // Submenu found. Calling recursively same method (and wrapping it in a div)
        } else {
            html += '<a href="#'+lang+'-'+data[item].sgodina+'-'+data[item].kid+'">'+data[item].saltnaslov+'</a>'; // No submenu
        }
        html += '</li>';
    }
    html += '</ul>';
    //html += (isSub)?'</div>':'';
    return html;
}

function turnOffCompare(){
        //reset compare var
        compareTo = '';
        $('#displayContCompare').html('');
		$("#uporediOff").hide();
		
		$("#displayContCompareYear").remove();

        $(function() {
            $("#displayCont").animate({
                width: '100%'
            }, { duration: 100, queue: false });
            $("#displayContCompare").animate({
                width: '0%',
                opacity: 0.0
            }, { duration: 100, queue: false });
        });

        return ;
}



function disableFootNotesAddHover(){
		$("#displayCont a[href*='#']").click(function(e) {
		   e.preventDefault();
		 });
		
		//set text for hover
		$("#displayCont a[href*='#']").hover(function(e) {
			var textel = $(this).attr('href').slice(2);
			$(this).attr('title', $("#"+textel).text());
			$(document).tooltip();
		 });
		 
}

function disableFootNotesAddHoverCompare(){
		$("#displayContCompare a[href*='#']").click(function(e) {
		   e.preventDefault();
		 });
		
		//set text for hover
		$("#displayContCompare a[href*='#']").hover(function(e) {
			var textel = $(this).attr('href').slice(2);
			$(this).attr('title', $("#"+textel).text());
			$(document).tooltip();
		 });
		 
}

function clearStyles(elId){
	//clear word styles and attributes
	$('#'+elId+' *').removeAttr('style lang class');
	prependYear(elId);
}

function prependYear(elId){
	var hash = window.location.hash.replace("#", "");
    var hashVars = hash.split("-");

	if(elId.indexOf("Compare")<0){
		//$('#'+elId).prepend( "<span id='"+elId+"Year' >"+hashVars[1]+"</span>" );
		$("#displayContYear").remove();
		$('#mainLine').before( "<span id='"+elId+"Year' >"+hashVars[1]+"</span>" );
	}else{
		//$('#'+elId).prepend( "<span id='"+elId+"Year' >"+hashVars[3]+"</span>" );
		$("#displayContCompareYear").remove();
		$('#mainLine').before( "<span id='"+elId+"Year' >"+hashVars[3]+"</span>" );
	}
	
}
function showSubNav(){
	var hash = window.location.hash;
	
	var level = $(".navig a[href='"+hash+"']").parents('ul').length
	//console.dir( level );
	// subcats za 4 nivi preko levela ??
	
	$("#mainLine").html('');
	
	if(level==3){
			//console.dir( $(".navig a[href='"+hash+"']").parent().children("ul") );
			
		$(".navig a[href='"+hash+"']").parent().children("ul").find("li").each(function(){ 
			console.dir($(this)[0].innerHTML);
			$("#mainLine").append($(this)[0].innerHTML);
			
			} );	
	}
	
	$(".navig a[href='"+hash+"']").parents().show();
	//$(".navig a[href='"+hash+"']").closest("ul").show();
	$(".navig a[href='"+hash+"']").siblings().show();
}