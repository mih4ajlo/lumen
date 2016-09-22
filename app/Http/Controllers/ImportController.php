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
	        "SELECT kid, CASE WHEN kowner = -1 THEN 0 ELSE kowner END as kowner  ,knaziv 
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


	public function doBulkUpload(Request $request)
	{
		$sviPodaci = $_POST['podaci'];

				print_r("<pre>");
				var_dump($sviPodaci);
				print_r("</pre>");
				die();
		

		//treba ubaciti kategorije i sadrzaje
		$rootOwner = 0;

				
		for ($i=0; $i < count( $sviPodaci) ; $i++) { 
		

			$temp_own = $sviPodaci[$i]["owner"];
			$own_temp = 0;
			if($temp_own ==-1)  //svi H1 imaju ownera -1
				$own_temp = -1; //root element kowner, ne sme da bude nijedna druga cifra zato sto ce moze da se pojavi u regularnim stvarima
			else 
				{
				$own_temp = $rootOwner + $temp_own  ; //mora biti jednako idKat +temp_own
				//root owner se setuje kada se ubaci prvi element
				
				}

			$kategorija = array(
	    	  $sviPodaci[$i]["kategorija"], $own_temp , $sviPodaci[$i]["godina"],
	    	  $sviPodaci[$i]["tipDok"],  $sviPodaci[$i]["jezik"]
	    	); 

			$idKat = $this->insertCat($kategorija);
			//pretpostavka je da se zadrzava konzistentnst

			if($i==0) $rootOwner = $idKat; //npr. 156; referentan; u odnosu na njega sve ide
			//pretpostavka da struktura nije narusena; nikako se ne desava
			//prvi koji se unese je owner, svi ostali gledaju na njega
			//a za nove dokumente owner nije nula nego proizvoljan broj


			$sadr_temp = $sviPodaci[$i]['sadrzaj'];
			$sadrzaj = array(
				/*"25", "2015", "neki naslov", "neki naslov kopija", "referenca", "sadrzaj <br/>", "sadrzaj", "rs-ci" 	*/

				/*$sviPodaci[$i]["id"]*/$idKat, $sviPodaci[$i]["godina"],
				$sviPodaci[$i]["kategorija"], $sviPodaci[$i]["kategorija"], $sviPodaci[$i]["tipDok"],
				stripslashes($sadr_temp), strip_tags($sadr_temp), $sviPodaci[$i]["jezik"]
				);

			$this->insertSad($sadrzaj);


		}
		
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
	    $params = array(
	    	 $_POST["title"], $_POST["owner"], $_POST["year"],
	    	  $_POST["tip"],  $_POST["jezik"]
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
		"INSERT INTO kategorijes (`knaziv`,`kowner`, `kgodina`,`tip`, `klang`)
	    VALUES (?,?,?,?,?) ");

		
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