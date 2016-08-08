<?php

# app/Models/Quote.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Sadrzaj extends Model  
{

	protected $table = 'sadrzajs';

	  /*‘name’,
        ‘username’,
        ‘password’*/

	protected $fillable = [
      'content',
      'owner',
      'order'
    ];

    /*‘password’ */
    protected $hidden = [ 'order' ];

}