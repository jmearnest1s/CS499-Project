<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    protected $guarded = [];

    public function post()

    {

        return $this->belongsTo(Post::class);

    }
    
    public function group()

    {

        return $this->belongsTo(Group::class);

    }
	
	public function user()

    {

        return $this->belongsTo(User::class,'by_id');

    }
	
}
