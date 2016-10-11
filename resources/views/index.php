<html>
<head>


    <meta name="keywords" content="gradjanin na strazi, gradjani, straza, politika, pravo, izbori, ucestvovanje grajdana, participacija, izvestaj, nepravilnosti, korupcija, sigurnost, sistem, uredjeno drustvo" />
    <meta name="description" content="Izveštaj inicijative 'Građanin na straži' u otvorenom formatu. Pretraga izveštaja, preuzimanje sadržaja preko API." />
    <meta property="og:title" content="Izveštaj inicijative 'Građanin na straži'" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://izvestaj.gradjaninastrazi.rs" />
    <meta property="og:image" content="gradjaninastrazi.rs/logo-fb.png" />
    
    <meta property="og:description" content="Izveštaj inicijative 'Građanin na straži' u otvorenom formatu. Pretraga izveštaja, preuzimanje sadržaja preko API." />

    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Izveštaj inicijative 'Građanin na straži'</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.2/components/dropdown.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.2/components/dropdown.min.css">

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.4/components/transition.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.4/components/transition.min.css">

    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/black-tie/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="scripts/script.js"></script>
    <script src="scripts/tags.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        //console.log("ready!");
        //define var for parsed data
        var cont = {};


       
        $('.navbar-toggle').on('click', function(e) {
            e.preventDefault();
            $(".navigation").toggle();
            $('.left-side').toggleClass('left-side-mobile');
        });

    })

   
    
    </script>
</head>

<body>
    <header>
        <nav class="main-nav navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="http://izvestaj.zastitnikgradjana.rs" >     
                        

                        <div class="logo krug cir" style="    padding-top: 40px !important;"><img src="/svgs/gns_logo.svg" alt=""></div>                       
                    </a>
                    <div class="icoNavCont">
                        <a href="http://gradjaninastrazi.rs/" title="Povratak na gradjaninastrazi.rs" ><img class="icoNav" src="svgs/home.svg"></a>
                        <a href="#" title="Izveštaj (pdf)" target="_blank"><img class="icoNav" src="svgs/web.svg"></a>
                        <a href="api.html" title="Deo za programere" target="_blank"><img class="icoNav" src="svgs/api.svg"></a>
                    </div>
                    <!-- data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" -->
                    <button type="button" class="navbar-toggle collapsed" >
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div id="navbar" class="navbar-collapse collapse ">
                    <ul class="nav navbar-nav"  style="display: none;">
                        <li id="filterSve" class=" active  cir" onclick="prikaziTagovane('sve')"><a >Све</a></li>
                        <li id="filterVlada" class="   cir" onclick="prikaziTagovane('vlada')"><a >Влада</a></li>
                        <li id="filterMin" class="  cir" onclick="prikaziTagovane('ministarstva')"><a >Министарства</a></li>
                        <li id="filterSkup" class="  cir" onclick="prikaziTagovane('skupstina')"><a >Скупштина</a></li>                        
                        <!-- <li id="filterJavne"  class="  cir" onclick="prikaziTagovane('javne')"><a >Јавне институције</a></li> -->
                        <li id="filterOstali" class="  cir" onclick="prikaziTagovane('ostali')"><a >Остали органи и организације</a></li>


                    </ul>
                    <ul class="nav navbar-right">
                        
                        <li class="compare clear pull-left">
                            <!-- <button id="uporedi">Uporedi sa prethodnim izveštajima</button> -->


                            <!-- <div class="ui compact menu">
                              <div class="ui simple dropdown " >
                               <span id="uporedi" class=" cir">Упореди по годинама</span>
                                <div id="timelineList" class="menu">
                                  <div class="item">2015</div>
                                  <div class="item">2014</div>
                                  <div class="item">2013</div>
                                </div>
                              </div>
                            </div> -->

                        </li>
						<li class="search pull-left">
                            <div >
                               <!--  <span id="uporediOff" class=" cir">Искључи поређење по годинама</span> -->
                            </div>
                        </li>
                        <li class="search pull-left">
                            <div id="pretraga">
                                <input id="filter" class=" " type="text" placeholder="Pretražite">
                               
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>





    <div class="socialCont">

        <a href="https://www.facebook.com/sharer/sharer.php?display=popup&ref=plugin&src=like&u="
           onclick="javascript:window.open(this.href + window.location.href.replace('#','%23'), '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
           target="_blank" title="Share on Facebook"><img class="socialImg" src="svgs/facebook.svg">
        </a>

        <a href="https://twitter.com/intent/tweet?text= &tw_p=tweetbutton&url="
           onclick="javascript:window.open( this.href +  window.location.href.replace('#','%23') + '&original_referer=' +  window.location.href.replace('#','%23') , '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
           target="_blank" title="Share on Twitter"><img class="socialImg" src="svgs/twitter.svg">
        </a>
        <!-- <a href="https://www.facebook.com/sharer/sharer.php?u=http://izvestaj.rs/zastitnikgradjana&display=popup&ref=plugin&src=like" target="_blank"><img class="socialImg" src="svgs/facebook.svg"></a> -->
        <!-- <a href="https://twitter.com/intent/tweet?original_referer=http://izvestaj.rs/zastitnikgradjana&text= &tw_p=tweetbutton&url=http://izvestaj.rs/zastitnikgradjana" target="_blank"><img class="socialImg" src="svgs/twitter.svg"></a> -->
    </div>
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
		

         <!-- style="display: inline-block; vertical-align: top;" -->
        <div class="col-xs-12 col-sm-6 col-md-8 main-container">
		      
            <div id="mainLine"></div>
            <div id="displayCont"></div>
            <div id="displayContCompare" style="display: inline-block; vertical-align: top;"> </div>
            <div id="clearFloat"></div>
            <div id="rezultat-pretrage"  style="display: none">
                <div class="control">
                    <!-- <a id="hideSearch" href="#">сакриј</a> -->
                    <a id="closeSearch" href="#">X</a>
                </div>
                <div id="rezultatiPretrage" class="sadrzaj-pretrage">
                </div>
            </div>
        </div>
		
		<div class="navigation col-xs-12 col-sm-6 col-md-2">
			<div id="displayRefButton" style="display: inline-block; vertical-align: top;">
			<a class=" cir" id="displayRefButtonReference"  href="dodatne.html#1">Dodatne teme</a>
			
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

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-85089175-1', 'auto');
  ga('send', 'pageview');

</script>
</body>

</html>
