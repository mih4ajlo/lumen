function prikaziTagovane(arg,ev) {
  
  var naziv = "";

  switch( arg ) {
    case "ministarstva":
      naziv = "ministarstvo";   
      break;
    case "vlada":
      naziv = "vlada";
      break;
    case "skupstina":
      naziv = "skupstina";
      break;
    case "javne":
      naziv = "javne";
      break;  
    case "ostali":
      naziv = "ostali";
      break;
  }

    $("[akter]").parents().filter('p').hide()
     
    if(naziv =="ministarstvo"){
     //$("p[akter]").hide() 
      $("[akter*='ministarstvo']").parents().filter('p').show()
      //$("[akter]").filter(function(ind,la){if($(la).attr('akter')) return ($(la).attr('akter').indexOf('ministar')!=-1)}).show()
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