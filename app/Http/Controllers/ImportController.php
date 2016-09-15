<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware(['auth']);
    }

    public function glavna(Request $request, $naziv_funkcije =""  )
    {	

    		

    	//$this->showCategories();
    	if($naziv_funkcije == ""){
    		return view('import.main');
    	}
    	elseif ($naziv_funkcije == "order") {
    		
    		return view('import.uporedjivanje');	
    	}
    	else{
    		
    		$this->$naziv_funkcije();	
    	}
		
    	
    }

    public function getFile( )
    {
	
		//ucitaj fajl i vrati ga
		
 		$target_file = "../storage/app/" . $_GET['file']; 
 		$er = file_exists( $target_file );

 		if( !$er )
 			return "nema fajla";

 		header("X-Sendfile: ".$_GET['file']);
		header("Content-type: application/octet-stream");
		header('Content-Disposition: attachment; filename="' . basename($_GET['file']) . '"');
		readfile($target_file);
		die();

    }


    public function uploadFile(Request $request)
    {

	    $target_file = "../storage/app/" . basename($_FILES['fileuplaod']['name']);	    
	    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);


	    if($fileType!="html" AND $fileType!="htm"){
	    	die("Mozete postaviti samo HTML fajlove");
	    }

			
	    if (file_exists($target_file)) {
	    	die("Fajl sa tim imenom vec postoji");
	    } 


	    if (move_uploaded_file($_FILES["fileuplaod"]["tmp_name"], $target_file)) {
	            echo "Fajl je USPESNO postavljen.";
	        } else {
	            echo "Doslo je do greske. Fajl NIJE postavljen";
	        }


    }


    public function showCategories(Request $request) {
		

		$out = array();

		$sql = 
	        "SELECT kid,kowner,knaziv 
	        FROM kategorijes 
	        WHERE tip =? AND  klang =? 
	        AND  kgodina =?
	        ORDER BY korder ";

		
		$result =  
			app('db')->select( 
				$sql, 
				array( 					
					$_POST['tip'], 
					$_POST['jezik'], 
					$_POST['year'] 
					) 
			);

		$topmenu = '';

		for ($i=0; $i < count($result) ; $i++)  {
			
	        $sql_upit = 
		        sprintf(
		            "SELECT count(*) AS number 
		            FROM `sadrzajs` 
		            WHERE kid = ? AND tip = ? AND slang = ? "
		            );

          	$row = $result[ $i ]; 


			$row->count =
				app('db')->select( 
					$sql_upit, 
					array( 
						$row ->kid,
						$_POST['tip'], 
						$_POST['jezik']
						) 
				)[0]->number;
		
			$out[] = $row;
		}

		$outpreped = $this->buildMenuTree($out);
		$this->olLiTree($outpreped);


	}




	public function showStoredSectionForYear() {
	
		$sql = sprintf(
			"SELECT scont
	        FROM sadrzajs
	        WHERE kid=? AND sgodina=? AND tip=? "
			);

		$result = app('db')->select( 
			$sql, 
			array( 
				/*'45', '2015', 'referenca'*/
				$_POST["id"], 
				$_POST["year"], 
				$_POST["tip"]
				) 
		);
		 

		$nr = count($result);
		

		switch ($nr) {
		case 0:
			echo 'Nema sadrzaja';
			break;
		case 1:
			echo nl2br($result[0]->scont);
			break;
		default:
			echo '<p style="color:red">Vraceno je vise od 1 rezultata. Hjustone imamo VELIKI problem. Jedan od njih mora da se obrise!!!';
		}
	}

	//insert update content for selected section and year
	public function insertSectionForYear() {
		if( empty($_POST) ) 	return "la";
		
		$sql = sprintf(
			"SELECT sid
	        FROM sadrzajs
	        WHERE kid=? AND sgodina=? AND tip=? AND slang=? " );

		/*	$_POST["id"], $_POST["year"], $_POST["tip"], $_POST["jezik"]*/

		$result = app('db')->select( 
			$sql, 
			array( 
				//'25', '2015', 'referenca','rs-ci'
				$_POST["id"], 
				$_POST["year"], 
				$_POST["tip"],
				$_POST["jezik"]
				) 
		);

		$nr = count($result);	
				
		$sadr_temp = $_POST["cont"] . "";
		$sadr_temp = str_replace("\\n", '', $sadr_temp);

		switch ($nr) {
		case 0:
			
			$sql = sprintf("INSERT INTO sadrzajs (`kid`,`sgodina`,`saltnaslov`,`s_orgin_naslov`,tip , scont, `scont_notag`, slang) VALUES( ?,?,?,?,?,?,?,?  ) ");

			$params = array(
				/*"25", "2015", "neki naslov", "neki naslov kopija", "referenca", "sadrzaj <br/>", "sadrzaj", "rs-ci" 	*/

				$_POST["id"], $_POST["year"],
				$_POST["altnaslov"], $_POST["altnaslov"], $_POST["tip"],
				stripslashes($sadr_temp), strip_tags($sadr_temp), $_POST["jezik"]
				);

			$res = app('db')->insert( $sql, $params ); 

			//$result = $db->query($sql) OR die(mysqli_error($db));
			echo "Tekst unesen $red";
			break;
		case 1:
			$sql = sprintf("UPDATE sadrzajs
	            SET  saltnaslov=? , scont=?, `scont_notag`=?
	            WHERE kid=? AND sgodina=?  ");

			$params	= array(
				//"raspusni milos", "sneki tekst" , "sneki tekst be ztagova", "25","2015"

				$_POST["altnaslov"], $sadr_temp,
				strip_tags($sadr_temp),
				$_POST["id"], $_POST["year"]
				);

			$res = app('db')->update( $sql, $params ); 
			
			//echo $sql;
			//$result = $db->query($sql) OR die(mysqli_error($db));
			echo "Tekst azuriran";
			break;
		default:
			echo '<p style="color:red">Vraceno je vise od 1 sekcije. Hjustone imamo VELIKI problem. Jedan od njih mora da se obrise!!!';
		}	

			
	}


	public function insertNewCategory() {

	    
		$sql = sprintf(
		"INSERT INTO kategorijes (`knaziv`,`kowner`, `kgodina`,`tip`, `klang`)
	    VALUES (?,?,?,?,?) ");

	    $params = array(
	    	 $_POST["title"], $_POST["owner"], $_POST["year"],
	    	  $_POST["tip"],  $_POST["jezik"]
	    	); 

		/* "INSERT INTO kategorijes (`knaziv`,`kowner`,`tip`) VALUES ('" . $_POST["title"] . "' , '" . $_POST["owner"] . "'   ) ";*/
		//echo $sql;
		
		$result = app('db')->insert( 
			$sql, $params 
		);

		
		echo "Nova kategorija unesena";

	}

	//HELPERS
	//build hierachical tree from flat mySQL parent/owner result
	//http://stackoverflow.com/questions/13877656/
	public function buildMenuTree(array $elements, $root = 0) {

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
	public function olLiTree($tree) {
		echo '<ul>';

		foreach ($tree as $item) {
			echo '<li ><p onclick="showStoredSectionForYear(' . $item->kid . ')" class="header" data-owner="' . $item->kowner . '" data-kid="' . $item->kid . '"  id="storeCont' . $item->kid . '">' . $item->knaziv . ' (' . $item->count . ')</p> <p class="insertSub" data-owner="' . $item->kid . '">Unesi kao podkategoriju</p></li>';
			if (isset($item->children)) {
				$this->olLiTree($item->children);
			}
		}

		if (count($tree) == 0) {
			echo '<li><p onclick="showStoredSectionForYear(0)" class="header" data-owner="0" data-kid="0"></p> <p class="insertSub" data-owner="0">Unesi kao podkategoriju</p></li>';
		}

		echo '</ul>';
	}

	///////////////////////////////////////////////////////////////////////////
	//categories reorder SECTION
	///////////////////////////////////////////////////////////////////////////
	public function showCategoriesOrder() {
		
		$sql = "SELECT kid,kowner,knaziv FROM kategorijes ORDER BY korder  ";
		
		$result = app('db')->select($sql);

		$topmenu = '';

		/*while ($row = mysqli_fetch_object($result))*/
		for ($i=0; $i < count($result) ; $i++) {
			$out[] = $result[$i];
		}



		$outpreped = $this->buildMenuTree($out);
		$this->olLiOrderTree($outpreped);

		//echo $topmenu;

	}

	public function olLiOrderTree($tree) {
		echo '<ul >';
		foreach ($tree as $item) {
			echo '<li id="node' . $item->kid . '" ><a  >' . $item->knaziv . '</a>';
			if (isset($item->children)) {
				olLiOrderTree($item->children);
			}
		}
		echo '</ul>';
	}

	public function renameCategory() {
		
		$sql = sprintf("UPDATE kategorijes SET knaziv=? WHERE kid=?  " );

		$params = array("naziv kategorije", "" /* $_POST["newName"], $_POST["renameId"] */);
		$result = app('db')->update($sql, $params);

		
		echo "Broj azuriranih redova: " . $result;

	}

	public function updateCatsOrder() {
		
		$items = explode(",", $_POST['saveString']);

		for ($no = 0; $no < count($items); $no++) {
			$tokens = explode("-", $items[$no]);

			$sql = "UPDATE kategorijes SET kowner=?, korder=? where kid=?";
			

			$params = array( $tokens[1], $no, $tokens[0] );
			$result = app('db')->update($sql, $params);

		}

		echo "Updated...";
	}


	function getMainCats (){
	    

		$sql = "SELECT knaziv FROM KATEGORIJES ";
		$result = app('db')->select($sql);

		$topmenu = '<ul>';

		
		for ($i=0; $i <  count($result) ; $i++) {

		 $topmenu .=  '<li>'. $row->knaziv .'</li>';

		}

		$topmenu .=  '</ul>';

		return $topmenu;

	}


	

}

?>