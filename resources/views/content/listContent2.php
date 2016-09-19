
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ReOrder / ReName</title>
<link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <!-- <link rel="stylesheet" type="text/css" href="style.css"> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <script type="text/javascript" src="../../order/js/ajax.js"></script>
    <script type="text/javascript" src="../../order/js/context-menu.js"></script><!-- IMPORTANT! INCLUDE THE context-menu.js FILE BEFORE drag-drop-folder-tree.js -->
    <script type="text/javascript" src="../../order/js/drag-drop-folder-tree.js"></script>
    <link rel="stylesheet" href="../../order/css/drag-drop-folder-tree.css" type="text/css"></link>
    <link rel="stylesheet" href="../../order/css/context-menu.css" type="text/css"></link>


</head>
<body>

<div id="top">
    <div id="poruka">&nbsp;</div>
</div>


<select name="godina" id="godina">
    
    <option value="2015">2015</option>
    <option value="2014">2014</option>
    <option value="2013">2013</option>
</select>


<select name="tip" id="tip_dokumenta">
    <option value="sadrzaj">sadrzaj</option>
    <option value="referenca">referenca</option>
</select>
<select name="jezik" id="jezik">
    <option value="rs-ci">Cirilica</option>
    <option value="rs-lat">Latinica</option>
    <option value="eng">Engleski</option>
</select>

<button id="prikazDokumenta">Prikazi</button>

<div id="catOrder">
    <ul id="catsTree" class="dhtmlgoodies_tree">
    <li id="node0" noDrag="true" noSiblings="true" noDelete="true" noRename="true">
    <a href="#">Root node</a>
      
    </li>
    </ul>
</div>

<script type="text/javascript">
    $(function() {
        prikaziDokument({tip:"sadrzaj","godina":2015});


        $("#prikazDokumenta").click(function(el) {
            var godina = $("#godina").val();
            var tip_dokumenta = $("#tip_dokumenta").val();
            var jezik = $("#jezik").val();

             prikaziDokument({tip:tip_dokumenta,"godina":godina,jezik:jezik});
        })
    })
        
 
    
    var treeObj;
    var ajaxObjects = new Array();


    function prikaziDokument( podaci) {

       $.ajax({
            url: 'content/up/showCategoriesOrder',
            data: podaci
        })
        .done(function(data) {

            //ukloni prethodni
            $("#node0 ul").remove(); 

            $("#node0").append(data)    
            initTree();
        })
        .fail(function() {
            console.error("error");
        })
        .always(function() {
            console.log("complete");
        });
    }


    function initTree() {
        treeObj = new JSDragDropTree();
        treeObj.setTreeId('catsTree');
        treeObj.setMaximumDepth(7);
        treeObj.setMessageMaximumDepthReached('Maximum depth reached');
        treeObj.initTree();
        treeObj.expandAll();
    }
    
    // Use something like this if you want to save data by Ajax.
    function saveMyTree()
    {
            saveString = treeObj.getNodeOrders();

            var http = new XMLHttpRequest();
            var url = "content/saveTree";
            var params = 'action=updateCatsOrder'+'&saveString=' + saveString;
            http.open("POST", url, true);
            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            http.onreadystatechange = function() {//Call a function when the state changes.
                if(http.readyState == 4 && http.status == 200) {
                    document.getElementById("poruka").innerHTML = http.responseText;
                }
            }
            http.send(params);


    }


</script>

<br /><br />
    <a href="#" onclick="treeObj.collapseAll()">Collapse all</a> |
    <a href="#" onclick="treeObj.expandAll()">Expand all</a>


<br /><br /><br />
    <form>
    <input type="button" onclick="saveMyTree()" value="Snimi redosled">
    </form>


</body>
</html>