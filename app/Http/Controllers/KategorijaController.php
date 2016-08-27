<?php 


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\Authenticate as Auth;

class KategorijaController extends Controller
{

public function list_kategorija(Request $request)
	{


		try {
            $unosi = app('db')->select("SELECT * FROM kategorijes "  );    
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }
              
              
        
        //var_dump($content_id);    
        return view(
            "kategorija.listKategorija", ["lista_kategorija"=>$unosi] 
        );
	}


	public function edit_kategorija(Request $request,$kategorija_id)
	{
		try {
            $res = app('db')->update('UPDATE kategorija SET   knaziv=?, korder=?, kgodina=?   where kid = ?', [ $_POST['knaziv'],$_POST['korder'] , $_POST['kgodina'],$kategorija_id ] );

          
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }

        $out = "Izmena uspesna. Vratite se <a href='../../kategorija#kategorija{$kategorija_id}'>ovde</a>." ;

        $title = "Editor kategorija";
        $head ="";
        return view( "kategorija.Display",["content"=>$out,"title"=>$title,"head"=>$head ]);
	}

	public function delete_kategorija(Request $request, $kategorija_id)
	{
		# code...
	}


    //na foru akters
	public function add_kategorija(Request $request)
	{
		  try {
            $res = app('db')->insert('INSERT INTO kategorija ( knaziv, korder, kgodina  ) VALUES (  ?, ?, ? )  ', [$_POST['knaziv'],$_POST['korder'] , $_POST['kgodina']]);

          
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }

    $out = "Kategorija dodat. Vratite se <a href='../kategorija'>ovde</a>." ;

        $title = "Kategorija aktera";
        $head ="";
        return view( "content.Display",["content"=>$out,"title"=>$title,"head"=>$head ]);
	}



}

?>
