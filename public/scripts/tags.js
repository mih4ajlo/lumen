function prikaziTagovane(arg) {
  
  var naziv = "";

  $(".navbar-nav li").removeClass('active');

  switch( arg ) {
    case "sve":
      naziv = "sve";   
      $("#filterSve").addClass('active')    
      break;
    case "ministarstva":
      naziv = "ministarstvo";   
      $("#filterMin").addClass('active')    
      break;
    case "vlada":
      naziv = "vlada";
      $("#filterVlada").addClass('active')    
      break;
    case "skupstina":
      naziv = "skupstina";
      $("#filterSkup").addClass('active')    
      break;
    case "javne":
      naziv = "javne";
      $("#filterJavne").addClass('active')    
      break;  
    case "ostali":
      naziv = "ostali";
      $("#filterOstali").addClass('active')    
      break;
  }

    $("[akter]").parents().filter('p').hide()
     
    if(naziv =="ministarstvo"){
     //$("p[akter]").hide() 
      $("[akter*='ministarstvo']").parents().filter('p').show()
      //$("[akter]").filter(function(ind,la){if($(la).attr('akter')) return ($(la).attr('akter').indexOf('ministar')!=-1)}).show()
    }
    else if(naziv =="sve"){
     //$("p[akter]").hide() 
      $("p").show()
    }
    else if(naziv =="vlada"){
     //$("p[akter]").hide() 
      $("[akter*='vlada']").parents().filter('p').show()
    }

    else if(naziv =="skupstina"){
     //$("p[akter]").hide() 
      $("[akter*='skupstina']").parents().filter('p').show()
    }
    else if(naziv =="javne"){
     //$("p[akter]").hide() 
      $("[akter*='javne']").parents().filter('p').show()
    }
    else if(naziv =="ostali"){
     //$("p[akter]").hide() 
      $("[akter*='ostali']").parents().filter('p').show()
    }

}