<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mention extends Model
{
    //
	protected $table = 'mentions';
	protected $fillable = ['user_id','type','type_id'];
}
