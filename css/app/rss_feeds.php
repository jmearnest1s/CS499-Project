<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rss_feeds extends Model
{
    //
	protected $fillable = array('title', 'url', 'description');
	public function topics(){
    	return $this->belongsToMany('App\Interest')->withPivot('rss_feeds_id');
	}
}
