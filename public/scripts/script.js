//Set globals
//apiLocation = 'http://z.com/';
apiLocation = 'http://z.com/';
lang = 'cir';
year = '2015';
id='';
compareTo = '';

$(document).ready(function() {
    console.log("ready!");
//parse URL on start
	parseUrl();
    

});


window.onpopstate = function(event) {
	parseUrl();
};


function parseUrl(){

    //some cleanup
    turnOffCompare();

	var hash = window.location.hash.replace("#", "");
    var hashVars = hash.split("-");

    if(hashVars[0]){lang = hashVars[0];}
    if(hashVars[1]){year = hashVars[1];}
    if(hashVars[2]){id = hashVars[2];}
    if(hashVars[3]){compareTo = hashVars[3];}


    showMenu(year);
    showMainCont(year,id);
    availableYearsToCompare(); //za padajuci COMPARE meni

    //if compare active
    if(compareTo){ compareToYear(compareTo); }


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
        $("#displayCont").html('<p class="emptyHeader nav-section" >Greška prilikom učitavanja sadržaja za uporedjivanje.</p>');
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
        $("#displayCont").html('<p class="emptyHeader nav-section" >Greška prilikom učitavanja sadržaja za uporedjivanje.</p>');
    });

}









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