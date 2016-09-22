<!DOCTYPE html>
<html lang="en">

<head>

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="icon" href="data:;base64,iVBORw0KGgo=">

	<script type="text/javascript" src="../../order/js/ajax.js"></script>
    <script type="text/javascript" src="../../order/js/context-menu.js"></script><!-- IMPORTANT! INCLUDE THE context-menu.js FILE BEFORE drag-drop-folder-tree.js -->
    <script type="text/javascript" src="../../order/js/drag-drop-folder-tree.js"></script>
    <link rel="stylesheet" href="../../order/css/drag-drop-folder-tree.css" type="text/css"></link>
    <link rel="stylesheet" href="../../order/css/context-menu.css" type="text/css"></link>

	<style type="text/css">
	.ta-right{
		text-align: right;
	}
	</style>

	<meta charset="UTF-8">
	<title>List of content</title>
</head>
<body>


	<div class="container-fluid">
		<?php 
			   
		include '../resources/views/dashboard/header.php';
		?>
	</div>


	<div class="navigation col-xs-12 col-sm-6 col-md-2">
		<?php 
			   
		include '../resources/views/dashboard/side-menu.php';
		?>
	</div>
	<div class="main col-xs-12 col-sm-6 col-md-8">
		<div class="container-fluid">
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


            	function editRedirect(id) {

            		var por = window.location.href ;
            		var komponente = id.split("/");

                    //ista tabela i za reference 
                    //i za sadrzaj
                    
            		/*if(komponente[0]=="referenca")
            			por = por.replace("content",komponente[0]);*/
            		
            		por += "/" + komponente[1];
            		
            		window.location = por;

            	}

                function prikaziDokument( podaci) {

                   $.ajax({
                        url: 'content/up/showCategoriesOrder',
                        data: podaci
                    })
                    .done(function(data) {

                        //ukloni prethodni
                        $("#node0 ul").remove(); 


                        $("#node0").append(data)    
                        $("#node0 img").remove();
                        initTree();
                    })
                    .fail(function() {
                        console.error("error");
                    })
                    .always(function() {
                        $(".editDugme").click(function(el) {
            	        	

            	        	editRedirect( $(el.target).attr('clickid') );
            	        	
            	        })
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

          
			</div>

	</div>
	

	
</body>
</html>