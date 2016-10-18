<?php
include("config.php");


if (isset($_POST["action"])) {
//call function whose name is stored in variable action
$_POST["action"]();
}

function showCategories(){
    global $db;

    $sql = "SELECT kid,kowner,knaziv FROM kategorijes ORDER BY korder ";
    $result = $db->query($sql);

    $topmenu = '';

    while($row = mysqli_fetch_object($result)){
     //$topmenu .=  '<p onclick="showStoredSectionForYear('.$row->kid.')" class="header" id="storeCont'.$row->kid.'">'. $row->knaziv .'</p>';
     $row->count= $db->query("SELECT count(*) AS number FROM `sadrzajs` WHERE kid='".$row->kid."'")->fetch_object()->number;  //"SELECT count(*) FROM `sadrzajs` WHERE kid='".$row->kid."'";
     $out[]=$row;
    }


$outpreped = buildMenuTree($out);
olLiTree($outpreped);

    //echo $topmenu;

}


function showStoredSectionForYear(){
    global $db;

    checkLang();

    $sql = "SELECT scont FROM sadrzajs WHERE kid='".$_POST["id"]."' AND sgodina='".$_POST["year"]."'  AND slang='".$_POST["lang"]."'  ";
    $result = $db->query($sql);

    $nr = mysqli_num_rows($result) ;

    switch($nr){
    case 0:
        echo 'Nema sadrzaja';
        break;
    case 1:
        echo mysqli_fetch_object($result)->scont;
        break;
    default:
        echo '<p style="color:red">Vraceno je vise od 1 rezultata. Hjustone imamo VELIKI problem. Jedan od njih mora da se obrise!!!';
    }
}


//insert update content for selected section and year
function insertSectionForYear(){
    global $db;

    checkLang();

    //ok for now
    foreach ($_POST as $name => $val) { $_POST[$name] = mysqli_real_escape_string($db, $val); }

    $sql = "SELECT sid FROM sadrzajs WHERE kid='".$_POST["id"]."' AND sgodina='".$_POST["year"]."' AND slang='".$_POST["lang"]."'  ";
    $result = $db->query($sql);

    $nr = mysqli_num_rows($result) ;

    switch($nr){
    case 0:
        //uradi insert
        $sql = "INSERT INTO sadrzajs (`slang`,`kid`,`sgodina`,`scont`, `scont-notag`,`saltnaslov`) VALUES('".$_POST["lang"]."','".$_POST["id"]."','".$_POST["year"]."', '".$_POST["cont"]."', '".strip_tags($_POST["cont"])."','".$_POST["altnaslov"]."'  ) ";
        //echo $sql;
        $result = $db->query($sql) OR die(mysqli_error($db));
        echo "Tekst unesen";
        break;
    case 1:
        $sql = "UPDATE sadrzajs SET  saltnaslov='".$_POST["altnaslov"]."' , scont='".$_POST["cont"]."' WHERE kid='".$_POST["id"]."' AND sgodina='".$_POST["year"]."' AND slang='".$_POST["lang"]."'   ";
        //echo $sql;
        $result = $db->query($sql) OR die(mysqli_error($db));
        echo "Tekst azuriran";
        break;
    default:
        echo '<p style="color:red">Vraceno je vise od 1 sekcije. Hjustone imamo VELIKI problem. Jedan od njih mora da se obrise!!!';
    }

}

function insertNewCategory(){
    global $db;
    $sql = "INSERT INTO kategorijes (`knaziv`,`kowner`) VALUES ('".$_POST["title"]."' , '".$_POST["owner"]."'   ) ";
    //echo $sql;
    $result = $db->query($sql) OR die(mysqli_error($db));
    echo "Nova kategorija unesena";

}

function deleteTextForYear(){
    global $db;

    checkLang();

    $sql = "DELETE FROM sadrzajs WHERE sgodina='".$_POST["sgodina"]."' AND kid='".$_POST["kid"]."' AND slang='".$_POST["lang"]."'   ";
    echo $sql;
    $result = $db->query($sql) OR die(mysqli_error($db));
    echo " Tekst obrisan za godinu ".$_POST["sgodina"]." i id ".$_POST["kid"] ."i jezik ".$_POST["lang"] ;

}








//HELPERS
//build hierachical tree from flat mySQL parent/owner result
//http://stackoverflow.com/questions/13877656/
function buildMenuTree(array $elements, $root = 0) {
    $branch = array();

    foreach ($elements as $element) {
        if ($element->kowner == $root) {
            $children = buildMenuTree($elements, $element->kid);
            if ($children) {
                $element->children = $children;
            }
            $branch[] = $element;
        }
    }

    return $branch;
}

//build tree from array
//http://stackoverflow.com/questions/16837415
function olLiTree( $tree ) {
    echo '<ul>';
    foreach ( $tree as $item ) {
        echo '<li ><p onclick="showStoredSectionForYear('.$item->kid.')" class="header" data-owner="'.$item->kowner.'" data-kid="'.$item->kid.'"  id="storeCont'.$item->kid.'">'. $item->knaziv .' ('.$item->count.')</p> <p class="insertSub" data-owner="'.$item->kid.'">Unesi kao podkategoriju</p></li>';
        if ( isset( $item->children ) ) {
            olLiTree( $item->children );
        }
    }
    echo '</ul>';
}


///////////////////////////////////////////////////////////////////////////
//categories reorder SECTION
///////////////////////////////////////////////////////////////////////////
function showCategoriesOrder(){
    global $db;

    $sql = "SELECT kid,kowner,knaziv FROM kategorijes ORDER BY korder  ";
    $result = $db->query($sql);

    $topmenu = '';

    while($row = mysqli_fetch_object($result)){
     $out[]=$row;
    }


$outpreped = buildMenuTree($out);
olLiOrderTree($outpreped);

    //echo $topmenu;

}

function olLiOrderTree( $tree ) {
    echo '<ul >';
    foreach ( $tree as $item ) {
        echo '<li id="node'.$item->kid.'" ><a  >'. $item->knaziv .'</a>';
        if ( isset( $item->children ) ) {
            olLiOrderTree( $item->children );
        }
    }
    echo '</ul>';
}

function renameCategory(){
    global $db;

        $sql = "UPDATE kategorijes SET  knaziv='".$_POST["newName"]."'  WHERE kid='".$_POST["renameId"]."'   ";
        //echo $sql;
        $result = $db->query($sql) OR die(mysqli_error($db));
        echo "Broj azuriranih redova: ".mysqli_affected_rows($db) ;

}

function updateCatsOrder(){
    global $db;


    $items = explode(",",$_POST['saveString']);

    for($no=0;$no<count($items);$no++){
        $tokens = explode("-",$items[$no]);

        $sql = "UPDATE kategorijes SET kowner='".$tokens[1]."', korder='$no' where kid='".$tokens[0]."'" ;
        //echo $sql."<br>";

        // Example of sql

        $db->query($sql) OR die(mysqli_error($db));



    }

     echo "Updated...";
}

//die if no lang
function checkLang(){
    global $_POST;

    if (empty($_POST["lang"]) ) {
        die("Izaberite jezik/pismo!!!");
    }
}



?>