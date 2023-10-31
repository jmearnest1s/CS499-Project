<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Znck\Eloquent\Traits\BelongsToThrough;

class Share extends Model
{
    protected $guarded = [];

    public function post()

    {

        return $this->belongsTo(Post::class);

    }

    public function user()

    {

        return $this->belongsTo(User::class);

    }
	
	public function interests()
    {
		return $this->hasManyThrough(Interest::class, Post::class, 'interest_post.post_id');
       // return $this->belongsToThrough(Interest::class, Post::class);
    }
	public function delete()

    {

      
        
       

        return parent::delete();

    }
}
