<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
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
	
	public static function countLike($id)
    {
		$likes = Like::where('post_id', $id)->count();
		return $likes;
	}

}
