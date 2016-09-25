//Set globals
//apiLocation = 'http://z.com/';
apiLocation = '';
lang = 'cir';
year = '2015';
id = '3';
compareTo = '';
searchFor = '';

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

   $(".lang").click(function(ev) {
        var lan = $(ev.target).attr("href")
        if(lan == "#ci"){
            $(".lat,.en").addClass('hidden');//cirilica
            $(".cir").removeClass('hidden')
        }
        else if(lan == "#lat"){
            $(".cir,.en").addClass('hidden');//cirilica
            $(".lat").removeClass('hidden')
        }
        else if(lan == "#en"){
            $(".cir,.lat").addClass('hidden');//cirilica
            $(".en").removeClass('hidden')
        }

        //console.log(  );
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

    if (hashVars[0]) { lang = hashVars[0]; }
    if (hashVars[1]) { year = hashVars[1]; }
    if (hashVars[2]) { id = hashVars[2]; }  //korder
    if (hashVars[3]) { compareTo = hashVars[3]; }
    if (hashVars[4]) { searchFor = hashVars[4]; }


    showMenu(year);
    showMainCont(year, id);
    availableYearsToCompare(); //za padajuci COMPARE meni
    loadFootNotes(year);

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

    $.getJSON(apiLocation + lang + "/nav/" + yearPo,{tip:"sadrzaj"}, function(menuRes) {
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


            //sakrij sve h2-ove koji nisu na tom podstablu
            //dodaj svim h1-ma glyph strelicu na dole 
            
            
            $("#nav>ul>li>a+ul").append( '<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>' )
            $("#nav>ul>li>ul").hide()
            
            //:not(
            //$("#nav>ul>li>a.active+ul, #nav>ul>li>ul>a.active+ul, #nav>ul>li>ul>li>a.active+ul").show()
            var temp_selektor = $("#nav>ul>li>a.activ,#nav>ul>li>a.active+ul, #nav>ul>li>ul>a.active, #nav>ul>li>ul>a.active+ul, #nav>ul>li>ul>li>a.active, #nav>ul>li>ul>li>a.active+ul");
            temp_selektor.parents().filter('ul').show();//svi parenti 
            temp_selektor.show();

            $("#nav>ul>li>a>span").click(function(e) {                

                e.preventDefault();
                //otkrij celo podstablo
                $(this).parent().siblings().toggle('slow');
            });


            // SCROLL MENIJA
            $("#nav").scrollTop($("#nav").scrollTop() + $("#nav a.active").position().top - 8 );

        })
        .fail(function() {
            $("#nav").html('<p class="emptyHeader nav-section" >Greška prilikom učitavanja menija.</p>');
        });
}

//get content from database for requested year/section
//if no id DB will serve firs section of salorder
function showMainCont(yearPo, id) {
    $("#displayCont").html('');
    //load main content from API

    lang = vratiJezik();
    
    $.getJSON(apiLocation + lang + "/content/" + yearPo + "/" + id, function(mainContRes) {
            console.log("JSON for main content loaded...");

            if (mainContRes[0] == undefined) return;

            $("#displayCont").html(
                mainContRes[0].scont
            );

            clearStyles("displayCont");

            $("#uporediOff").hide();
        })
        .fail(function() {
            $("#displayCont").html('<p class="emptyHeader nav-section" >Greška prilikom učitavanja glavnog sadržaja.</p>');
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
            $("#displayContCompare").html('<p>Greška prilikom učitavanja sadržaja za uporedjivanje.</p>');
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
            $("#timelineList").html('<div class="item">Greška prilikom učitavanja sadržaja za uporedjivanje.</div>');
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
            $("#footnoteContent").html('Greška prilikom učitavanja fusnota.');
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
            $("#footnoteContentCompare").html('Greška prilikom učitavanja fusnota.');
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
        $.getJSON(apiLocation + lang + "/search/" + $("#filter").val(), { god: "2015", tip: temp_tip }, function(searchRes) {
                console.log("JSON for SEARCH...");

                if (searchRes.length > 0) {

                    //dodaj podatke sa list
                    $.each(searchRes, function(key, value) {
                        $('#rezultatiPretrage').append('<div class="stavka-pretrage"><a href="#' + lang + '-' + value.sgodina + '-' + value.kid + '--' + $("#filter").val() + '">' + value.saltnaslov + '</a></div>');
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
function buildMenuList(data, isSub) {
    //var html = (isSub)?'<div>':''; // Wrap with div if true
    var html = '';
    html += '<ul>';
    for (item in data) {
        html += '<li>';
        //TODO zameniti kid sa korder
        if (typeof(data[item].children) === 'object') { // An array will return 'object'
            var strelica = ''; ///'<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>'
            html += '<a href="#' + lang + '-' + data[item].godina + '-' + data[item].korder + '">' + data[item].saltnaslov + " " + strelica + '</a>'; // Submenu found, but top level list item.
            html += buildMenuList(data[item].children, true); // Submenu found. Calling recursively same method (and wrapping it in a div)
        } else {
            html += '<a href="#' + lang + '-' + data[item].godina + '-' + data[item].korder + '">' + data[item].saltnaslov + '</a>'; // No submenu
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



    if (elId.indexOf("Compare") < 0) {
        //$('#'+elId).prepend( "<span id='"+elId+"Year' >"+hashVars[1]+"</span>" );
        var god = hashVars[1];
        if(god == undefined) 
            god = 2015;

        $("#displayContYear").remove();
        $('#mainLine').before("<span id='" + elId + "Year' >" + god + "</span>");
    } else {
        //$('#'+elId).prepend( "<span id='"+elId+"Year' >"+hashVars[3]+"</span>" );
        $("#displayContCompareYear").remove();
        $('#mainLine').before("<span id='" + elId + "Year' >" + hashVars[3] + "</span>");
    }

}

function showSubNavAndActivate() {
    var hash = window.location.hash;

    var level = $(".navig a[href='" + hash + "']").parents('ul').length
    console.dir(level);
    // subcats za 4 nivi preko levela ??

    //mark NAv link active
    $("#nav a[href='#" + lang + "-" + year + "-" + id + "']").addClass('active');

    $("#mainLine").html('');

    if (level == 2) {
        $(".navig a[href='" + hash + "']").siblings().show();
    }

    if (level == 3) {
        console.dir($(".navig a[href='" + hash + "']").parent().children("ul"));

        $(".navig a[href='" + hash + "']").parent().children("ul").find("li").each(function() {
            console.dir($(this)[0].innerHTML);
            $("#mainLine").append($(this)[0].innerHTML);

        });
        $(".navig a[href='" + hash + "']").parents().show();

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


function vratiJezik() {
    lang = window.location.hash;
    lang = lang.replace("#","");
    lang = lang.split("-")[0];
    if(lang =="")
        lang ='ci';
    return lang;
}