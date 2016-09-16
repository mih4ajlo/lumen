<?php


//include("ajax.php");

//echo getMainCats();


?>

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


hocu da se bira godina i tip dokumenta i da se onda izlista struktura i da tu budu dugmici za editovanje i neke druge informacije
<div id="catOrder">
    <ul id="catsTree" class="dhtmlgoodies_tree">
    <li id="node0" noDrag="true" noSiblings="true" noDelete="true" noRename="true">
    <a href="#">Root node</a>
      
    </li>
    </ul>
</div>

<script type="text/javascript">
    $(function() {
        $.ajax({
            url: 'content/up/showCategoriesOrder',
            data:{tip:"sadrzaj","godina":2015}
        })
        .done(function(data) {
            $("#node0").append(data)    
            initTree();
        })
        .fail(function() {
            console.error("error");
        })
        .always(function() {
            console.log("complete");
        });
    })
        
    
    
</script>
<script type="text/javascript">
    
    var treeObj;
    var ajaxObjects = new Array();

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