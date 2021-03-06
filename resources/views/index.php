<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Izvestaj 2015</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/black-tie/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="scripts/script.js"></script>
    <script src="scripts/tags.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.2/components/dropdown.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.2/components/dropdown.min.css">


    <style type="text/css">

    #nav{
        height: 100vh;
        overflow: scroll;
    }
    </style>

    <script type="text/javascript">
    $(document).ready(function() {
        //console.log("ready!");

        //define var for parsed data
        var cont = {};


    })
    </script>
</head>

<body>
    <header>
        <nav class="main-nav navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">
                        <div class="logo krug hidden lat">Izveštaj Zaštitnika Građana</div>
                        <div class="logo krug cir">Извештај Заштитника Грађана</div>
                        <div class="logo krug hidden en">Protectors Public <br> report</div>
                    </a>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div id="navbar" class="navbar-collapse collapse ">
                    <ul class="nav navbar-nav"  style="display: none;">
                        <li class="active lat" onclick="prikaziTagovane('vlada')"><a >Vlada</a></li>
                        <li class="lat" onclick="prikaziTagovane('ministarstva')"><a >Ministarstva</a></li>
                        <li class="lat" onclick="prikaziTagovane('skupstina')"><a >Skupština</a></li>
                        <!-- <li class="lat" onclick="prikaziTagovane('javne')"><a >Javne institucije</a></li> -->
                        <li class="lat" onclick="prikaziTagovane('ostali')"><a >Ostali organi i organizacije</a></li>


                        <li class=" hidden active cir" onclick="prikaziTagovane('vlada')"><a >Влада</a></li>
                        <li class=" hidden cir" onclick="prikaziTagovane('ministarstva')"><a >Министарства</a></li>
                        <li class=" hidden cir" onclick="prikaziTagovane('skupstina')"><a >Скупштина</a></li>
                        <!-- <li class=" hidden cir" onclick="prikaziTagovane('javne')"><a >Јавне институције</a></li> -->
                        <li class=" hidden cir" onclick="prikaziTagovane('ostali')"><a >Остали органи и организације</a></li>

                        <li class=" hidden active en" onclick="prikaziTagovane('vlada')"><a >Vlada</a></li>
                        <li class=" hidden en" onclick="prikaziTagovane('ministarstva')"><a >Ministarstva</a></li>
                        <li class=" hidden en" onclick="prikaziTagovane('skupstina')"><a >Skupština</a></li>
                        <!-- <li class=" hidden en" onclick="prikaziTagovane('javne')"><a >Javne institucije</a></li> -->
                        <li class=" hidden en" onclick="prikaziTagovane('ostali')"><a >Ostali organi i organizacije</a></li>

                    </ul>
                    <ul class="nav navbar-right">
                        <li>
                            <div class="versions" id="verzije">
                                <a class="lang" href="#ci">Ћир</a>
                                <a class="lang" href="#lat">Lat</a>
                                <a class="lang" href="#en">En</a>

                            </div>
                        </li>
                        <li class="compare clear pull-left">
                            <!-- <button id="uporedi">Uporedi sa prethodnim izveštajima</button> -->


                            <div class="ui compact menu">
                              <div class="ui simple dropdown " >
                               <span id="uporedi" class="hidden lat">Uporedi po godinama</span>
                               <span id="uporedi" class=" cir">Упореди по годинама</span>
                               <span id="uporedi" class="hidden en">Uporedi po godinama</span>
                                
                                <div id="timelineList" class="menu">
                                  <div class="item">2015</div>
                                  <div class="item">2014</div>
                                  <div class="item">2013</div>
                                </div>
                              </div>
                            </div>

                        </li>
						<li class="search pull-left">
                            <div >
                                <span id="uporediOff" class=" cir">Искључи поређење по годинама</span>
                                <span id="uporediOff" class="hidden lat">Isključi poredjenje po godinama</span>
                                <span id="uporediOff" class="hidden en">Isključi poredjenje po godinama</span>
                            </div>
                        </li>
                        <li class="search pull-left">
                            <div id="pretraga">
                                <input id="filter" class=" " type="text" placeholder="Pretražite">
                               <!--  <input id="filter"  class=" cir" type="text" placeholder="Претражите">
                               <input id="filter"  class="hidden en" type="text" placeholder="Search"> -->
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!--dummy content-->
    <section class="content">

	     

	<div class="navigation col-xs-12 col-sm-6 col-md-2">
            <div id="content" class="left-side">

                <div id="my-tab-content" class="tab-content">
                    <div class="navig tab-pane active" id="nav">
				
                    </div>
                 
                </div>
            </div>
        </div>
		
        <div  class="col-xs-12 col-sm-6 col-md-8">
		<div id="mainLine"></div>
            <div id="displayCont" style="display: inline-block; vertical-align: top;"></div>
            <div id="displayContCompare" style="display: inline-block; vertical-align: top;"> </div>
            <div id="clearFloat"></div>
            <div id="rezultat-pretrage">
                <div class="control" style="display: none">
                    <a id="hideSearch" href="#">sakrij</a>
                    <a id="closeSearch" href="#">X</a>
                </div>
                <div id="rezultatiPretrage" class="sadrzaj-pretrage">
                </div>
            </div>
        </div>
		
		<div class="navigation col-xs-12 col-sm-6 col-md-2">
			<div id="displayRefButton" style="display: inline-block; vertical-align: top;">
			<a class=" cir" id="displayRefButtonReference"  href="dodatne.html#1" >Додатне теме</a>
            <a class="hidden lat" id="displayRefButtonReference"  href="dodatne.html#1" >Dodatne teme</a>
            <a class="hidden en" id="displayRefButtonReference"  href="dodatne.html#1" >Dodatne teme</a>
			<!-- <a id="displayRefButtonNav" href="#nav" data-toggle="tab" aria-expanded="false">SADRŽAJ</a> -->
			</div>
		</div>
    </section>
    <section>
        <div style="display: none" id="mainDocContent">
        </div>
         
    </section>

    <section>
       <div id="footnoteContent">
       </div>
		<div id="footnoteContentCompare">
        </div>
    </section>
	
	
<div id="toTop"><span class="glyphicon glyphicon-triangle-top"></span></div>	


</body>

</html>
