var cont = {};
var curPage;

var owner = -1; //trenutni owner
var h1pos = -1; //kada se prolazi kroz stablo pozicija trentunog h1 el
var h2pos = -1;
var h3pos = -1;
var h4pos = -1;


$(document).ready(function() {

    loadFile();
    loadCategories("sadrzaj");
    $("select").change(function() {
        loadCategories($("#tip").val());
    })


    $("#bulk").click(function(el) {
        var god = $("#year").val() ; //2015
        var tip = $("#tip").val();//"reference";
        var jezik = $("#jezik").val(); //"ci";

        doBulkUpload(god, tip, jezik);
    })

});

function loadFile() {
    show("Loading file...");

    $("#mainDocContent").load("getFile/?file=" + $.urlParam('file'), function(cond) {
        show("File loaded...");
        //h = $("h1,h2");
        h = $("h1,h2,h3,h4,h5");

        $(':header').each(parseDoc);
        //$('h1,h2').each(parseDoc);
    });

    show("File loaded and parsed.");

}


//godina, jezik, tip dokumenta
//ako vec postoji dokument, stopiraj
function doBulkUpload(god, tip, jezik) {
    //prepakuje se cont
    //salje se niz objekata [{kategorija:'naslov',sadrzaj:"content",parent:"0",order:"order"}]
    //sortira se prema parentima i orderu
    //i ubacuje redom
    
    var svi = Object.keys(cont) 
    var result = [];

    var granica1 = Number.parseInt (svi.length /3 );
    var granica2 = Number.parseInt (2*  (svi.length /3 ) );

    for (var i = 0; i < granica1 ; i++) {
        //ovde moram da proveravam kog su tipa i da odredjujem
        var temp_obj = {}
        temp_obj.kategorija = cont[ svi[i] ].naslov || ""; 
        temp_obj.sadrzaj = cont[ svi[i] ].sadrzaj || ""; 
        temp_obj.owner = cont[ svi[i] ].owner || ""; 
        /*temp_obj.order = cont[ svi[i] ].order;*/
       /* temp_obj.godina = god; 
        temp_obj.tipDok = tip; 
        temp_obj.jezik = jezik; */
        result.push(temp_obj);
    }

    
    salji({ godina:god, tip:tip, jezik:jezik, podaci: result});
    result =[];

    for (var i = granica1; i < granica2  ; i++) {
        //ovde moram da proveravam kog su tipa i da odredjujem
        var temp_obj = {}
        temp_obj.kategorija = cont[ svi[i] ].naslov || ""; 
        temp_obj.sadrzaj = cont[ svi[i] ].sadrzaj || ""; 
        temp_obj.owner = cont[ svi[i] ].owner || ""; 
        /*temp_obj.order = cont[ svi[i] ].order;*/
       /* temp_obj.godina = god; 
        temp_obj.tipDok = tip; 
        temp_obj.jezik = jezik; */
        result.push(temp_obj);
    }

    salji({ godina:god, tip:tip, jezik:jezik, podaci: result});
    result =[];

    for (var i = granica2; i < svi.length  ; i++) {
        //ovde moram da proveravam kog su tipa i da odredjujem
        var temp_obj = {}
        temp_obj.kategorija = cont[ svi[i] ].naslov || ""; 
        temp_obj.sadrzaj = cont[ svi[i] ].sadrzaj || ""; 
        temp_obj.owner = cont[ svi[i] ].owner || ""; 
        /*temp_obj.order = cont[ svi[i] ].order;*/
       /* temp_obj.godina = god; 
        temp_obj.tipDok = tip; 
        temp_obj.jezik = jezik; */
        result.push(temp_obj);
    }

    salji({ godina:god, tip:tip, jezik:jezik, podaci: result});
    result =[];

    //podeliti na trecine i proslediti

}

function salji(podaci) {
    $.ajax({
        url: 'up/doBulkUpload',
        type: 'POST',
        data: podaci,
    })
    .done(function(data) {
        console.log(data);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}


function show(message) {
    //console.log("message shown");
    //$("#poruka").fadeIn(100);
    $("#poruka").text(message);
    //$("#poruka").fadeOut(500);

}

$.urlParam = function(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
}

/**
 * salje se H* element
 * 
 * 
 * [parseDoc description]
 * @param  {[type]} index [description]
 * @param  {[type]} el    [description]
 * @return {[type]}       [description]
 */
var parseDoc = function(index,el) {
    //console.dir(h[index]);
    //trim &nbsp
    if(h[index] == undefined) return;
    var temp_naslov = h[index].innerText.replace(/\u00a0/g, " ");
   

    if (h[index].nodeName == "H1") {
        
        owner = -1; //ako je h1 owner je sigurno 0
        h1pos = index;

        $("#parsedNav").append('<p class="emptyHeader nav-section nav' + h[index].nodeName.toUpperCase() + ' " id="showCont' + index + '">' + temp_naslov + '</p>'); //return true;
        oldH = index;
        //excludedSearch.push('showCont' + index);
        //set plain HTML for Chapters

    } else {
        var temp_h = h[index].nodeName.toUpperCase();    

        $("#parsedNav").append('<p class="header nav' + temp_h + ' " id="showCont' + index + '">' + temp_naslov + '</p>');
        
        if(temp_h == "H2"){
            
            owner = h1pos ;
            h2pos = index;
            //neki element sa h1 nivo-a
        }
        else if(temp_h == "H3"){

            owner = h2pos ;
            h3pos = index;
        }
        else if(temp_h == "H4"){
            owner = h3pos ;
            h4pos = index;   
        }
        else if(temp_h == "H5"){
            owner = h4pos ;
            h5pos = index;      
        }
    }

    //put everything between H tags into array
    cont['showCont' + index] = $(h[index]).nextUntil(h[index + 1]).andSelf();

    cont['showCont' + index].sadrzaj = '';    
    cont['showCont' + index].owner = owner;    
    cont['showCont' + index].naslov = temp_naslov;  


    cont['showCont' + index].each(function() {
        var $this = $(this);
        $this.html($this.html().replace(/&nbsp;/g, ''));

        cont['showCont' + index].sadrzaj +=" "+ $this[0].outerHTML;
        

        


    });

    //merge is ok as lon as you put header variable name into excluded search
    //DISABLED - ako je ukljuceno stavlja sve subsekcija u glavnu sekciju - npr H2 i H3 sadrzaj ulazi u H1 koji je iznad
    //$.merge(cont['showCont' + oldH], cont['showCont' + index]);


    //console.dir(cont['showCont' + index]);

    //function for click in left menu - ID's in main menu has same id as index in cont variable
    $('#showCont' + index).click(function() {

        //remove active class
        $(this).parent().children().removeClass("active")
        $(this).addClass('active');
        $('#parsedCont').html();
        $('#parsedCont').html(cont['showCont' + index]);


        //set Global current pageX
        curPage = 'showCont' + index;

    });

    //make parsedNav items draggable
    $("#parsedNav").children().draggable({
        cursor: 'move',
        containment: 'document',
        helper: dragHelperNav,
        cursorAt: { top: 0, left: 0 }
    });

    //make content draggable / dropable
    $("#parsedCont").draggable({
        cursor: 'move',
        containment: 'document',
        helper: dragHelperCont,
        cursorAt: { top: -90, left: 0 } //bug in latest jqueryUI 
    });

    $("#outCont").droppable({
        drop: dropHelperCont,
        hoverClass: "activeHover"
    });



}

function dragHelperNav() {
    return '<div id="draggableHelper">' + $(this)[0].innerText.substring(0, 20) + '...</div>';
}
//handle left NAV DROP
function dropHelperNav(event, ui) {
    var draggable = ui.draggable;
    //console.dir(draggable[0].innerText);
    //console.dir($(this)[0].dataset.owner);
    if (draggable.attr('id').indexOf("showCont") < 0) {
        alert("Ovde mozete prevuci samo polja iz LEVE NAVIGACIJE.");
        return;
    }

    //upisi podatke iz parsedCont
    $.post("up/insertNewCategory", {
            action: "insertNewCategory",
            owner: $(this)[0].dataset.owner,
            title: draggable[0].innerText,
            tip: $("#tip").val(),
            year: $("#year").val(),
            jezik:$("#jezik").val(),
        },
        function(data) {
            show(data);
            loadCategories();
        });




}

function dragHelperCont() {
    return '<div id="draggableHelper">Prevucite tekst u polje desno.</div>';
}

//handle left CONT DROP
function dropHelperCont(event, ui) {
    var draggable = ui.draggable;
    //console.dir($( "#parsedNav .active" )[0].innerText);
    //console.dir($(this));
    if (draggable.attr('id') != "parsedCont") {
        alert("Ovde mozete prevuci samo tekstualni sadrzaj sekcije.");
        return;
    }
    if ($('#year :selected').text() == "") {
        alert("Izaberite godinu za koju zelite da ubacite postojeci sadrzaj!!!");
        return;
    }
    if ($("#outNav ul li .active").length != 1) {
        alert("Niste izabrali sekciju u koju kopirate text!!!");
        return;
    }



    //upisi podatke iz parsedCont
    $.post("up/insertSectionForYear", {
        action: "insertSectionForYear",
        year: $('#year :selected').text(),
        id: $("#outNav ul li .active")[0].dataset.kid,
        cont: $("#parsedCont").html(),
        tip: $("#tip").val(),
        jezik: $("#jezik").val(),
        altnaslov: $("#parsedNav .active")[0].innerText
    }, function(data) {
        show(data); //TODO proveriti sta se ovde vraca
        $("#outNav ul li .active").trigger('click');
    });

}



function loadCategories(tipDok) {
    $.post("up/showCategories", {
            action: "showCategories",
            tip: tipDok,
            jezik: $("#jezik").val(),
            year: $("#year").val()
        },
        function(data) {
            $("#outNav").html(data); //TODO proveriti sta se ovde vraca
            //make outNav droppable
            $("#outNav ul li").children().droppable({ drop: dropHelperNav, hoverClass: "activeHover" });
            //show( "Postojece kategorije ucitane." );
            //console.log("Postojece kategorije ucitane.")
        });
}

function showStoredSectionForYear(contid) {
    //check if year selected
    if ($('#year :selected').text() == "") {
        alert("Izaberite godinu za koju gledate postojeci sadrzaj!!!");
        return;
    }

    $("#outCont").html('');

    $("#outNav ul li").children().removeClass("active");
    $('#storeCont' + contid).addClass('active');

    $.post("up/showStoredSectionForYear", {
        action: "showStoredSectionForYear",
        year: $('#year :selected').text(),
        id: contid,
        tip: $("#tip").val()
    }, function(data) {


        $("#outCont").html(data);
    });
}
