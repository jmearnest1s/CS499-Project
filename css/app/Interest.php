<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    //
	
	protected $fillable = array('name');
	
	
	public function users(){
		return $this->belongsToMany('App\User')->withPivot('name');
	}
	
	public function rss_feeds(){
    	return $this->belongsToMany('App\rss_feeds')->withPivot('name');
	}
	
	public function Ads(){
    	return $this->belongsToMany('App\Ads')->withPivot('name');
	}
	
	public function groups(){
		return $this->belongsToMany('App\Group')->withPivot('group_id');
	}
	
	public static function allTopics()
    {

        $interests = Interest::get();

       // die(print_r( $post->content));
	  // dd($interests );
        return $interests;

    }
	
	public static function getTopic($id)
    {

        $interests = Interest::where('id',$id)->get();

       // die(print_r( $post->content));
	  // dd($interests );
        return $interests;

    }
	
	
}


