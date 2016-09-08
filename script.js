var cont = {};
var curPage;

$(document).ready(function() {

    loadFile();
    loadCategories();


});

function loadFile() {
    show("Loading file...");

    $("#mainDocContent").load("imported/" + $.urlParam('file'), function(cond) {
        show("File loaded...");
        //h = $("h1,h2");
        h = $("h1,h2,h3,h4,h5");

        $(':header').each(parseDoc);
        //$('h1,h2').each(parseDoc);
    });

    show("File loaded and parsed.");

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


var parseDoc = function(index) {
    //console.dir(h[index]);
    //trim &nbsp

    if (h[index].nodeName == "H1") {
        $("#parsedNav").append('<p class="emptyHeader nav-section nav' + h[index].nodeName.toUpperCase() + ' " id="showCont' + index + '">' + h[index].innerText.replace(/\u00a0/g, " ") + '</p>'); //return true;
        oldH = index;
        //excludedSearch.push('showCont' + index);
        //set plain HTML for Chapters

    } else {
        $("#parsedNav").append('<p class="header nav' + h[index].nodeName.toUpperCase() + ' " id="showCont' + index + '">' + h[index].innerText.replace(/\u00a0/g, " ") + '</p>');
    }

    //put everything between H tags into array
    cont['showCont' + index] = $(h[index]).nextUntil(h[index + 1]).andSelf();

    cont['showCont' + index].each(function() {
        var $this = $(this);
        $this.html($this.html().replace(/&nbsp;/g, '')); });

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
    if (draggable.attr('id').indexOf("showCont") < 0) { alert("Ovde mozete prevuci samo polja iz LEVE NAVIGACIJE.");
        return; }

    //upisi podatke iz parsedCont
    $.post("ajax.php", { action: "insertNewCategory", owner: $(this)[0].dataset.owner, title: draggable[0].innerText }, function(data) {
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
    if (draggable.attr('id') != "parsedCont") { alert("Ovde mozete prevuci samo tekstualni sadrzaj sekcije.");
        return; }
    if ($('#year :selected').text() == "") { alert("Izaberite godinu za koju zelite da ubacite postojeci sadrzaj!!!");
        return; }
    if ($("#outNav ul li .active").length != 1) { alert("Niste izabrali sekciju u koju kopirate text!!!");
        return; }



    //upisi podatke iz parsedCont
    $.post("ajax.php", {
        action: "insertSectionForYear",
        year: $('#year :selected').text(),
        id: $("#outNav ul li .active")[0].dataset.kid,
        cont: $("#parsedCont").html(),
        tip:"sadrzaj",
        altnaslov: $("#parsedNav .active")[0].innerText
    }, function(data) {
        show(data);
        $("#outNav ul li .active").trigger('click');
    });

}



function loadCategories() {
    $.post("ajax.php", { action: "showCategories" }, function(data) {
        $("#outNav").html(data);
        //make outNav droppable
        $("#outNav ul li").children().droppable({ drop: dropHelperNav, hoverClass: "activeHover" });
        //show( "Postojece kategorije ucitane." );
        //console.log("Postojece kategorije ucitane.")
    });
}

function showStoredSectionForYear(contid) {
    //check if year selected
    if ($('#year :selected').text() == "") { alert("Izaberite godinu za koju gledate postojeci sadrzaj!!!");
        return; }

    $("#outCont").html('');

    $("#outNav ul li").children().removeClass("active");
    $('#storeCont' + contid).addClass('active');

    $.post("ajax.php", { action: "showStoredSectionForYear", year: $('#year :selected').text(), id: contid }, function(data) {
        $("#outCont").html(data);
    });
}
