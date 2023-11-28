<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $guarded = [];
	
	public function user()

    {

        return $this->belongsTo(User::class,'followed');

    }
	
	public function user2()

    {

        return $this->belongsTo(User::class,'by');

    }
	
	
	
	
	
	    
    

    public function getAvatarAttribute($avatar)

    {

        $path = 'assets/front/img/' . $avatar;

        if (! file_exists($path)) {

            if ($this->gender == 'MALE') {
                return 'male.png';
            } else {
                return 'female.png';
            }

        }

        return $avatar;

    }
    
	
	

}
