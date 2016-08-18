<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContentController extends Controller
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



    public function index($value='')
    {
 			
              
        //echo "string";
        return view('dashboard.home');
    }


	public function single(Request $request, $content_id )
	{


		try {
			$unos = app('db')->select("SELECT * FROM sadrzajs where sid=? ",[$content_id] );	
		} catch (Exception $e) {
			print_r("<pre>");
			var_dump($e);
			print_r("</pre>");
			//die();
		}
		
		//var_dump($content_id);	
		return view(
            "content.content", ["sadrzaj_unos"=>$unos] 
        );

	}

    public function list_content(Request $request)
    {
        
        try {
            $unosi = app('db')->select("SELECT * FROM sadrzajs   "  );    
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }
              
        
        //var_dump($content_id);    
        return view(
            "content.listContent", 
            ["lista_unosa"=>$unosi] 
        );
    }

    public function new_content($value='')
    {
        # code...
    }
    

public function delete_content(Request $request, $content_id)
    {
       //neka potvrda, ajax
       return "izbrisano";
    }

    public function kategorije( $value='' )
    {
        
        $kategorije = app('db')->select("SELECT kid, knaziv FROM kategorijes where 1 " );
   

         return view('dashboard.kategorije',[ "kategorije"=>$kategorije]);
    }

    public function users($value='')
    {
        $users = app('db')->select("SELECT * FROM users where 1 " );

        return view('dashboard.users',[ "users"=>$users]);
    }


//acters
public function list_acters(Request $request)
    {

        try {
            $akteri = app('db')->select("SELECT * FROM akters ORDER BY akategorija, anaziv    "  );
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }

$out ='
<style>
#dodajNovi{display:none;text-align: left;margin-bottom:50px; }
</style>

<script>
$(document).ready(function() {
    $("input:text").change(
    function(){
        $(this).css({"background-color" : "#ffd6d6"});
    });
$(".deleteacter").click( function(){return confirm("Da li ste sigurni da želite da obrišete aktera?");})
$("#dodajnovogaktera").click(function(){ $("#dodajNovi").toggle(); });
});

$( function() {
        var autokategorije = ["Vlada", "Ministarstva","Skupština","Ostalo" ];
        $( ".kategorije" ).autocomplete({
            source: autokategorije,
            delay: 50
        });
    } );

</script>

<div class="row">
			<div class="ta-right" >
					<a id="dodajnovogaktera"><span>Dodaj unos </span><span class="glyphicon glyphicon-plus"  aria-hidden="true"></span></a>

                    <div id="dodajNovi">
                    <table class="table table-condensed">
			        <tr><th>Kategorija</th>	<th>Naziv</th><th>Tagovi</th><th>Godina</th><th>Dodaj</th> 	</tr>
                    <form action="acters/add" method="POST" ><tr>
					<td><input name="kat" class="kategorije" size="10" ></td>
					<td><input name="naziv" class="nazivi" size="30" ></td>
					<td><input name="tags" class="tagovi" size="50" ></td>
					<td><input name="godina" size="5" ></td>
 					<td><button   type="submit">Dodaj</button></td>
					</tr></table></form>
                    </div>
			</div>
</div>
			<table class="table table-condensed">
			<tr><th>Kategorija</th>	<th>Naziv</th><th>Tagovi</th><th>Godina</th><th>Izmeni</th>	<th>Obrisi</th>	</tr>
';

					foreach ($akteri as  $value) {

						$temp_id = $value->aid;
						$ikonica_edit =  '<span class="glyphicon glyphicon-pencil" content="'.$temp_id.'" aria-hidden="true"></span>';
						$ikonica_delete =  '<span class="glyphicon glyphicon-remove" content="'.$temp_id.'" aria-hidden="true"></span>';
						$link_edit = "<button   type='submit'>{$ikonica_edit}</button>";

						//ajax potvrda akcije
						$link_del = "<a class='deleteacter' href='acters/delete/$temp_id'>$ikonica_delete</a>";

						$out .= print_r("<form action='acters/edit/{$temp_id}' method='POST' ' ><tr>"
							."<td><a name='acter{$temp_id}'></a><input name='kat' class='kategorije' size='10' value='{$value->akategorija}'></td> "
							."<td><input name='naziv' class='nazivi' size='30' value='{$value->anaziv}'></td>"
							."<td><input name='tags' class='tagovi' size='50' value='{$value->atags}'></td>"
							."<td><input name='godina' size='5' value='{$value->agodina}'></td>"
							."<td>$link_edit</td>"
							."<td>$link_del</td>"
							."</tr></form>",true);

					}

$out .= '
			</table>
';


        $title = "Editor aktera";
        $head ="";
        return view( "content.Display",["content"=>$out,"title"=>$title,"head"=>$head ]);
    }

public function edit_acter(Request $request, $id_aktera)
    {

        try {
            $res = app('db')->update('UPDATE akters SET akategorija=?, anaziv=?, atags=?, agodina=?  where aid = ?', [$_POST['kat'],$_POST['naziv'],$_POST['tags'],$_POST['godina'],$id_aktera]);
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }


    $out = "Izmena uspesna. Vratite se <a href='../../acters#acter{$id_aktera}'>ovde</a>.".$res ;


        $title = "Editor aktera";
        $head ="";
        return view( "content.Display",["content"=>$out,"title"=>$title,"head"=>$head ]);

    }


}
