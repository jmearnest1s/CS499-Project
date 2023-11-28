<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    //
	protected $fillable = array('image', 'link', 'content');
	
	public function topics(){
    	return $this->belongsToMany('App\Interest','interest_ads')->withPivot('ads_id');
	}
}
