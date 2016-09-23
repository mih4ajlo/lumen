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


    public function showCategories( ) {
		

		$out = array();

		$sql = 
	        "SELECT kid,  kowner  ,knaziv, korder 
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

			// da li ovde treba da ide upit nad kategorijas
			// ili mozda join za kategorijas
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

	//treba ubaciti kategorije i sadrzaje
	public function doBulkUpload(Request $request)
	{
		$sviPodaci 		= 	$_POST['podaci'];
		$godina 		= 	$_POST['godina'];
		$tip 			= 	$_POST['tip'];
		$jezik 			= 	$_POST['jezik'];			
		$nultiElement 	= 	1; //klasicno brojanje od nultog elementa
								//u bazi ne moze imati indeks 0
		$rootOwner		= 	-1; //nije ni jedan element parent
		//minimalni sledeci broj je 1
		//ako nije ubacen nijedan element root owner je -1;
		//vraca prvu unetu kategoriju, u odnosu na nju se odredjuju sve ostale kategorije

				//dodji indeks broj 45 - 90
		for ($i=0; $i < count( $sviPodaci) ; $i++) { 	
			

			if($i == 0){ //prvi u ovoj tur
				//idKat = 105
				$nultiElement = $this->vratiNulti($godina, $tip, $jezik);
			}

			$temp_ind = $sviPodaci[$i]["ind"] + 1; //pocinje od nultog elementa 
			$temp_own = $sviPodaci[$i]["owner"]; 

					
			
			$own_temp = 0; 

			if($temp_own ==-1  )  
				//svi H1 imaju ownera -1
				$own_temp = 0; 
			else 
				{
				//u odnosu na prvi ubaceni element - nultiElement
				$own_temp = $nultiElement + $temp_own   ; //105 +39
				// moraju da se upisu u istom redosledu kao kad su posalti
				// temp_own je index elementa u nizu, 
				// ako se neki drugi element upise ranije (asyinc)
				// narusava se cela struktura

				}

			$kategorija = array(
				$sviPodaci[$i]["kategorija"], 
				$own_temp, //owner nula ili bilo koji drugi broj
				$godina,	 
				$tip,  
				$jezik,
				$temp_ind 
	    	); 

			//vrati id kategorije, da bi u odnosu na nju racunali ostale
			$idKat = $this->insertCat($kategorija);	


			
			//id kat ili
			//$this->vratiNulti($godina, $tip, $jezik);

			$sadr_temp = $sviPodaci[$i]['sadrzaj'];
			$sadrzaj = array(
				$idKat, $godina,$sviPodaci[$i]["kategorija"], 
				$sviPodaci[$i]["kategorija"],$tip,
				stripslashes($sadr_temp), 
				strip_tags($sadr_temp), $jezik 
				);

			$this->insertSad($sadrzaj);


		}
		
	}

	private function vratiNulti($godina, $tip, $jezik)
	{
		$firstEl = app('db')->select("SELECT kid FROM kategorijes where `klang` = ? and `kgodina`=? and tip =? ORDER BY kid ASC LIMIT 0,1", array($jezik, $godina, $tip));
		$ret = 1;  //u bazi indeksi pocinju od jedan
		if( count($firstEl) > 0)
		$ret = $firstEl[0]->kid;

		return $ret;
	}

	public function showStoredSectionForYear( Request $request) {
	
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
	public function insertSectionForYear( Request $request ) {
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
			
			/*$sql = sprintf("INSERT INTO sadrzajs (`kid`,`sgodina`,`saltnaslov`,`s_orgin_naslov`,tip , scont, `scont_notag`, slang) VALUES( ?,?,?,?,?,?,?,?  ) ");*/

			$params = array(
				/*"25", "2015", "neki naslov", "neki naslov kopija", "referenca", "sadrzaj <br/>", "sadrzaj", "rs-ci" 	*/

				$_POST["id"], $_POST["year"],
				$_POST["altnaslov"], $_POST["altnaslov"], $_POST["tip"],
				stripslashes($sadr_temp), strip_tags($sadr_temp), $_POST["jezik"]
				);

			$this->insertSad( $params );
			/*$res = app('db')->insert( $sql, $params );*/ 

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


	public function insertNewCategory( Request $request  ) {

	    
		/*$sql = sprintf(
		"INSERT INTO kategorijes (`knaziv`,`kowner`, `kgodina`,`tip`, `klang`)
	    VALUES (?,?,?,?,?) ");
*/
	    //treba ubaciti korder
	    $params = array(
	    	 $_POST["title"], $_POST["owner"], $_POST["year"],
	    	  $_POST["tip"],  $_POST["jezik"],  $_POST["order"]
	    	); 

	    $res = $this->insertCat($params);


		/* "INSERT INTO kategorijes (`knaziv`,`kowner`,`tip`) VALUES ('" . $_POST["title"] . "' , '" . $_POST["owner"] . "'   ) ";*/
		//echo $sql;
		
		/*$result = app('db')->insert( 
			$sql, $params 
		);
*/
		
		echo "Nova kategorija unesena";

	}

	private function insertSad( $params )
	{
			$sql = sprintf("INSERT INTO sadrzajs (`kid`,`sgodina`,`saltnaslov`,`s_orgin_naslov`,tip , scont, `scont_notag`, slang) VALUES( ?,?,?,?,?,?,?,?  ) ");


			$res = app('db')->insert( $sql, $params ); 
			
			return $res ;
	}


	private function insertCat($params)
	{
		$sql = sprintf(
		"INSERT INTO kategorijes (`knaziv`,`kowner`, `kgodina`,`tip`, `klang`, `korder`)
	    VALUES (?,?,?,?,?,?) ");

		
		$result = app('db')->insert( 
			$sql, $params 
		);

		$last_id = app('db')->select("SELECT kid FROM kategorijes ORDER BY kid DESC LIMIT 0,1 ");
		
		return $last_id[0]->kid;

	}

	//HELPERS
	//build hierachical tree from flat mySQL parent/owner result
	//http://stackoverflow.com/questions/13877656/
	public function buildMenuTree(array $elements, $root = 0) {

		$branch = array();

		//korder umesto kid
		foreach ($elements as $element) {
			if ($element->kowner == $root) {
				$children = buildMenuTree($elements, $element->korder);
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

		//todo treba videti da li se ovde stavlja korder

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

		//mora se proslediti neki parametar (godina, tip dokumenta)
		$uslov = "";
		$args = array();

		if(count($_GET) > 0){
			$uslov = "WHERE kgodina =? and tip=?";
			$args =   array( $_GET['godina'], $_GET['tip'] );
		}

		$sql = "SELECT * FROM kategorijes $uslov ORDER BY korder  ";
		
		$result = app('db')->select($sql,$args);

		$topmenu = '';

		/*while ($row = mysqli_fetch_object($result))*/
		for ($i=0; $i < count($result) ; $i++) {
			$out[] = $result[$i];
		}

		$promenljiva = "";
		$outpreped = $this->buildMenuTree($out);			
		$ukupno = $this->olLiOrderTree($outpreped, $promenljiva );
				
		
		echo $ukupno;

	}

	public function olLiOrderTree($tree,&$tekst) {				
		
		$tekst .= '<ul >';
		foreach ($tree as $item) {
			
			$ikonica = '<span  clickId="' . $item->tip . '/' . $item->kid . '" class="glyphicon glyphicon-pencil editDugme" aria-hidden="true"></span>';	
			$tekst .= '<li id="node' . $item->kid . '" k_godina="' . $item->kgodina . '" ktags="' . $item->ktags . '" kowner="' . $item->kowner . '" ><a  >' . $item->knaziv . '</a> <span  >'.$ikonica.'</span>';
			if (isset($item->children)) {
				$this->olLiOrderTree($item->children,$tekst);
			}
		}
		$tekst .= '</ul>';
		return $tekst;
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