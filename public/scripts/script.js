

//Set globals
//apiLocation = 'http://z.com/';
apiLocation = '';
lang = 'lat';
year = '2016';
id = '40';
compareTo = '';
searchFor = '';
tip="sadrzaj";

$(document).ready(function() {
    console.log("ready!");
    //parse URL on start
    parseUrl();


   $(document).scroll( function(el) {
        if($(document).scrollTop() > 120 ){
            //top position je 40px
            $("#content").css('top', '40px');
        }
        else {
            $("#content").css('top', '');
        }
    })

});


window.onpopstate = function(event) {
    parseUrl();
};

$(document).on('click', '#uporediOff', function() {
    turnOffCompare();
});

$(document).on('click', '#toTop', function() {
    $(document).scrollTop(0);
});





function parseUrl() {

    //some cleanup
    turnOffCompare();

    var hash = window.location.hash.replace("#", "");
    var hashVars = hash.split("-");

    if(window.location.pathname == "/dodatne.html")
        tip = "referenca";

    if (hashVars[0]) { lang = hashVars[0]; }
    if (hashVars[1]) { year = hashVars[1]; }
    if (hashVars[2]) { id = hashVars[2]; }  //korder
    if (hashVars[3]) { compareTo = hashVars[3]; }
    if (hashVars[4]) { searchFor = hashVars[4]; }


    showMenu(year);
    showMainCont(year, id);
    availableYearsToCompare(); //za padajuci COMPARE meni
    loadFootNotes(year);
	loadReferences();

    //if compare active
    if (compareTo) { compareToYear(compareTo); }
    if (searchFor) { searchForString(searchFor); }

}


//get mainMenu from DB
//year is mandatory
function showMenu( yearPo ) {
    $("#nav").html('');
    //load nav from API
    
    lang = vratiJezik();

    $.getJSON(apiLocation + lang + "/nav/" + yearPo,{tip:tip}, function(menuRes) {
            console.log("JSON for menu loaded...");
            menuOut = buildMenuList(menuRes, false);
            $("#nav").html(menuOut);

            //show 3rd level nav FIRST - important
            showSubNavAndActivate();

            //move active to top
            var selektovana = $("#nav a[class='active']");
            selektovana = selektovana.position() == undefined ? 0 : selektovana.position().top;
            $('#content').scrollTop(0);
            $('#content').scrollTop();


           
            //OTKRIVANJE MENIJA
            
            $("#nav>ul>li>a").each(function(index, el) {
                   if( $(el).siblings('ul').length > 0 )
                    $(el).append( '<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>' )
              });
              
            $("#nav>ul>li>ul").hide()
            
            
            //:not(
            //$("#nav>ul>li>a.active+ul, #nav>ul>li>ul>a.active+ul, #nav>ul>li>ul>li>a.active+ul").show()
            var temp_selektor = 
                $("#nav>ul>li>a.activ,#nav>ul>li>a.active+ul,"+
                " #nav>ul>li>ul>a.active, #nav>ul>li>ul>a.active+ul,"+
                " #nav>ul>li>ul>li>a.active, #nav>ul>li>ul>li>a.active+ul,"+
                " #nav>ul>li>ul>li>ul>li>a.active, #nav>ul>li>ul>li>ul>li>a.active+ul");
            temp_selektor.parents().filter('ul').show();//svi parenti 
            temp_selektor.show();

            $("#nav>ul>li>a>span").click(function(e) {                

                e.preventDefault();
                //otkrij celo podstablo
                $(this).parent().siblings().toggle('slow');
            });

            var maxScrollTop = $("#nav").prop('scrollHeight') - $("#nav").outerHeight();

            // if maxScrollTop < $("#nav a.active").position().top, radi offset nekako

            // SCROLL MENIJA
            $("#nav").scrollTop($("#nav").scrollTop() + $("#nav a.active").position().top - 8 );
            //if() ako je h1 koristit ovo; meni se prikaze 95% i onda je moguci skrol jako mali
            //
            //$("#nav").offset({top:-300})
            //$("#nav").prop('scrollHeight')
            

        })
        .fail(function() {
            var poruka = 'Greška prilikom učitavanja menija.';
                poruka = 'Грешка приликом учитавања менија.';
            $("#nav").html('<p class="emptyHeader nav-section" >'+poruka +'</p>');
        });
}

//get content from database for requested year/section
//if no id DB will serve firs section of salorder
function showMainCont(yearPo, id) {
    $("#displayCont").html('');
    //load main content from API

    lang = vratiJezik();
    
    //TODO treba poslati tip dokumenta
    $.getJSON(apiLocation + lang + "/content/" + yearPo + "/" + id,{tip:tip}, function(mainContRes) {
            console.log("JSON for main content loaded...");

            if (mainContRes[0] == undefined) return;

            $("#displayCont").html(
                mainContRes[0].scont
            );

            clearStyles("displayCont");

            $("#uporediOff").hide();


            //PRIKAZ filter kontrola    
            if($("#displayCont [akter]").length > 0)
                $(".nav.navbar-nav").show() ; 
            else 
                $(".nav.navbar-nav").hide() ; 
        })
        .fail(function() {
            var poruka = 'Greška prilikom učitavanja glavnog sadržaja.';
                poruka = 'Грешка приликом учитавања главног садржаја.';
            $("#displayCont").html('<p class="emptyHeader nav-section" >'+poruka+'</p>');
        });
		
}

function compareToYear(yearToCompare) {
    $('#displayContCompare').html('');

    lang = vratiJezik();


    $.getJSON(apiLocation + lang + "/content/" + yearToCompare + "/" + id, function(yearToCompareRes) {
            console.log("JSON for COMPARE content loaded...");
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
            var poruka = 'Greška prilikom učitavanja sadržaja za uporedjivanje.';
                poruka = 'Грешка приликом учитавања садржаја за упоређивање.';
            $("#displayContCompare").html('<p>'+ poruka +'</p>');
        });

}


function availableYearsToCompare() {
    $('#timelineList').html('');

    lang = vratiJezik();

    $.getJSON(apiLocation + lang + "/timeline/" + id, function(yearsToCompareRes) {
            console.log("JSON for COMPARE section through years loaded...");

            if (yearsToCompareRes.length > 1) {

                $.each(yearsToCompareRes, function(key, value) {
                    if (value.sgodina != year) { 
                        $('#timelineList').append(
                            '<div class="item"><a href="#' + lang + '-' + year + '-' + id + '-' + value.sgodina + '">' + value.sgodina + '</a></div>');
                         }
                });
            } else {
               // u zavisnosti od jezika     
               var  poruka = "Nema podataka za poredjenje.";
                    poruka = "Нема података за поређење.";
                $('#timelineList').html('<div class="item">' + poruka + '</div>');
            }


        })
        .fail(function() {
            var poruka = 'Greška prilikom učitavanja sadržaja za uporedjivanje.';
                poruka = 'Грешка приликом учитавања садржаја за упоређивање.';
            $("#timelineList").html('<div class="item">' + poruka + '</div>');
        });

}

//highlight all keywords
function searchForString(searchFor) {
    //call it after XXX ms if empty - wait for load
    if ($('#displayCont').is(':empty')) { setTimeout(function() { searchForString(searchFor); }, 200);
        return; }



    console.log("Searching for " + searchFor);
    //console.log( $("#displayCont").html() );

    key = 1;
    count = 1;

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
        scrollTop: $('.filtered:visible:first').offset().top - 50
    }, 500);

//    $("#displayContYear").html('')
    $
}


//load FOOTNOTES
function loadFootNotes(year) {

    lang = vratiJezik();

    $.getJSON(apiLocation + lang + "/footnotes/" + year, function(loadFootNotesRes) {
            console.log("JSON for FOOTNOTES loaded...");

            if (loadFootNotesRes.length > 0) { $("#footnoteContent").html(loadFootNotesRes[0].fcont); }
            //disable footnote click in displayCont element and add footnotes hover
            disableFootNotesAddHover();

        })
        .fail(function() {
            var poruka = 'Greška prilikom učitavanja fusnota.';
                poruka = 'Грешка приликом учитавања фуснота.';
            $("#footnoteContent").html(poruka);
        });

}

function loadFootNotesCompare(year) {

    lang = vratiJezik();

    $.getJSON(apiLocation + lang + "/footnotes/" + year, function(loadFootNotesRes) {
            console.log("JSON for FOOTNOTES COMPARE loaded...");

            if (loadFootNotesRes.length > 0) { $("#footnoteContentCompare").html(loadFootNotesRes[0].fcont); }

            disableFootNotesAddHoverCompare();

        })
        .fail(function() {
            var poruka = 'Greška prilikom učitavanja fusnota.';
                poruka = 'Грешка приликом учитавања фуснота.';
            $("#footnoteContentCompare").html( poruka );
        });

}


//SEARCH functions from DATABASE
$(function() { // this will be called when the DOM is ready

    $("#filter").keyup(function(ev) {

        var temp_tip = "sadrzaj";
        if(window.location.pathname == "/dodatne.html")
           temp_tip = "referenca";     

        console.log($("#filter").val());
        //remove filtered class from document
        //if ($("#filter").val().length == 0) pr.removeResult();

        $('#rezultatiPretrage').html('');

        if ($("#filter").val().length < 3) return;
        //TODO uzeti vrednosti, da ne budu zakucane vrednosti
        //get data from API
        $.getJSON(apiLocation + lang + "/search/" + $("#filter").val(), { god: year, tip: temp_tip }, function(searchRes) {
                console.log("JSON for SEARCH...");

                if (searchRes.length > 0) {

                    //dodaj podatke sa list
                    $.each(searchRes, function(key, value) {
                        $('#rezultatiPretrage').append('<div class="stavka-pretrage"><a href="#' + lang + '-' + value.sgodina + '-' + value.kid + '--' + $("#filter").val() + '">' + value.saltnaslov + '</a></div>');
                    });

                } else {
                    $('#rezultatiPretrage').html('<div class="stavka-pretrage">Nema rezultata.</div>');
                }

                $("#rezultat-pretrage").show();

                $("#hideSearch").click(function(ev) {
                    hideResult();
                })

                $("#closeSearch").click(function(ev) {
                    removeResult();
                })

            })
            .fail(function() {
                var poruka = 'Greška prilikom učitavanja sadržaja za uporedjivanje.';
                    poruka = 'Грешка приликом учитавања садржаја за упоређивање.';
                $("#displayCont").html('<p class="emptyHeader nav-section" >'+poruka+'</p>');
            });


    });


});



//HELPER FUNCTIONS
//http://stackoverflow.com/questions/9362446
function buildMenuList(data, isSub) {
    //var html = (isSub)?'<div>':''; // Wrap with div if true
    var html = '';
    html += '<ul>';
    for (item in data) {
        html += '<li>';
        //TODO zameniti kid sa korder
        if (typeof(data[item].children) === 'object') { // An array will return 'object'
            var strelica = ''; ///'<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>'
            html += '<a href="#' + lang + '-' + year + '-' + data[item].kid + '">' + data[item].saltnaslov + " " + strelica + '</a>'; // Submenu found, but top level list item.
            html += buildMenuList(data[item].children, true); // Submenu found. Calling recursively same method (and wrapping it in a div)
        } else {
            html += '<a href="#' + lang + '-' + year + '-' + data[item].kid + '">' + data[item].saltnaslov + '</a>'; // No submenu
        }
        html += '</li>';
    }
    html += '</ul>';
    //html += (isSub)?'</div>':'';
    return html;
}

function turnOffCompare() {
    //reset compare var
    compareTo = '';
    $('#displayContCompare').html('');
    $("#uporediOff").hide();

    $("#displayContCompareYear").remove();

    $('#displayContYear').addClass('margin-45-left') ;
    $('#displayContYear').removeClass('margin-20-left') ;

    $(function() {
        $("#displayCont").animate({
            width: '100%'
        }, { duration: 100, queue: false });
        $("#displayContCompare").animate({
            width: '0%',
            opacity: 0.0
        }, { duration: 100, queue: false });
    });

    return;
}



function disableFootNotesAddHover() {
	console.log('fixing content clicks');
    $("#displayCont a[href*='#']").click(function(e) {
        e.preventDefault();
    });

    //set text for hover
    $("#displayCont a[href*='#']").hover(function(e) {
        var textel = $(this).attr('href').slice(2);
        $(this).attr('title', $("#" + textel).text());
        $(document).tooltip();
    });

}

function disableFootNotesAddHoverCompare() {
    $("#displayContCompare a[href*='#']").click(function(e) {
        e.preventDefault();
    });

    //set text for hover
    $("#displayContCompare a[href*='#']").hover(function(e) {
        var textel = $(this).attr('href').slice(2);
        $(this).attr('title', $("#" + textel).text());
        $(document).tooltip();
    });

}

function clearStyles(elId) {
    //clear word styles and attributes
    $('#' + elId + ' *').removeAttr('style lang class');
    prependYear(elId);
}

function prependYear(elId) {
    var hash = window.location.hash.replace("#", "");
    var hashVars = hash.split("-");
    var god = hashVars[1];
    var stil = "margin-45-left";
    
    if(god == undefined) 
        god =2015;


    
    var meni =   
        '<span id="' + elId + 'Year" class="ui header '+ stil +'">'+
          '<div class="ui inline dropdown">'+
              '<div class="text">'+god+'</div>'+
              '<i class="dropdown icon"></i>'+
              '<div class="menu">'+
                '<div class="item" data-text="2015">2015</div>'+
                '<div class="item" data-text="2014">2014</div>'+
                '<div class="item" data-text="2013">2013</div>'+
              '</div>'+
            '</div>'+
        '</span>';


        //ako nema compare 
    if (elId.indexOf("Compare") < 0) {
        //$('#'+elId).prepend( "<span id='"+elId+"Year' >"+hashVars[1]+"</span>" );
       
        if(god == undefined) 
            god = 2015;

        $("#displayContYear").remove();
        $('#mainLine').before( meni );
        var godina = $('.ui.inline.dropdown').dropdown('get value');


        
        $('.ui.inline.dropdown').dropdown('setting', 'onChange', function(ev) {
            var godina  = $('.ui.inline.dropdown').dropdown('get value');
            window.location = "#"+lang+"-"+godina;
            //parseUrl();
        });
    
    } else {
        //$('#'+elId).prepend( "<span id='"+elId+"Year' >"+hashVars[3]+"</span>" );
        $("#displayContCompareYear").remove();
        god = hashVars[3];
        $('#mainLine').before("<span id='" + elId + "Year'  >" + god + "</span>");

        $('#displayContYear').removeClass('margin-45-left') ;
        $('#displayContYear').addClass('margin-20-left') ;
    }
    //DISPLAY CONT YEAR
    
   

}

function showSubNavAndActivate() {
    var hash = window.location.hash;

    var level = $(".navig a[href='" + hash + "']").parents('ul').length
    //console.dir(level);
    // subcats za 4 nivi preko levela ??

    //mark NAv link active
    $("#nav a[href='#" + lang + "-" + year + "-" + id + "']").addClass('active');

    $("#mainLine").html('');

    if (level == 2) {
        $(".navig a[href='" + hash + "']").siblings().show();
    }

    if (level == 3) {
        //console.dir($(".navig a[href='" + hash + "']").parent().children("ul"));

        $(".navig a[href='" + hash + "']").parent().children("ul").find("li").each(function() {
            //console.dir($(this)[0].innerHTML);
            $("#mainLine").append($(this)[0].innerHTML);

        });
        $(".navig a[href='" + hash + "']").parents().show();
		

		
		loadFirstChild();

    }


    if (level == 4) {
        //console.dir( $(".navig a[href='"+hash+"']").parent().parent().parent() );

        $(".navig a[href='" + hash + "']").parent().parent().find("li").each(function() {
            //console.dir($(this)[0].innerHTML);
            $("#mainLine").append($(this)[0].innerHTML);

        });
        $(".navig a[href='" + hash + "']").parent().parents().show();
        $(".navig a[href='" + hash + "']").parent().parent().parent().children().addClass('active');
        $(".navig a[href='" + hash + "']").parent().parent().hide();

    }

}


function loadFirstChild(){

    $.getJSON(apiLocation + lang + "/firstchild/" + year + "/"+id, function(loadFirstChildRes) {
            console.log("first child loaded");
            if (loadFirstChildRes.length > 0) { $("#displayCont").append(loadFirstChildRes[0].scont); disableFootNotesAddHover(); }
        })
	}	
	
function loadReferences(){
	
    $.getJSON(apiLocation + lang + "/findref/" + year + "/"+id, function(loadReferencesRes) {
            console.log("find ref loaded");
            //console.dir(loadReferencesRes);
            if (loadReferencesRes.length > 0) { $("#displayRefButton").html(' <a class=" cir" id="displayRefButtonReference" target="_blank" href="dodatne.html#'+loadReferencesRes[0].lang+'-'+loadReferencesRes[0].year+'-'+loadReferencesRes[0].id+'">Додатне теме'+loadReferencesRes[0].note+'</a>  '); }else 
				{$("#displayRefButton").html(' ');}
        })	
	
}	


function vratiJezik() {
    langTmp = window.location.hash;
    langTmp = langTmp.replace("#","");
    langTmp = langTmp.split("-")[0];

    lang = langTmp || lang;
    
    if(lang =="")
        lang ='ci';
    return lang;
}



var hideResult = function(arg) {
        $(".sadrzaj-pretrage").hide();
        //setuj opciju show
        $("#hideSearch").attr("show", true);
        $("#hideSearch").html("show");
        //return arg;
    }

    var removeResult = function(arg) {
        $(".control").hide()
        $('#filter').val('')
        $('.filtered').removeClass('filtered')
            //ukloni sve dugmice

        //zasad skrivanje, treba da se ukloni lista pretrage
        $(".sadrzaj-pretrage").hide();


    }