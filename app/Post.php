<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class Post extends Model
{
    protected $guarded = [];
	
	public function interests(){
    	return $this->belongsToMany('App\Interest')->withPivot('interest_id');
	}
	
	public function interestCount()

    {

        return $this->interests()->count();

    }


    public function delete()

    {

        $this->views()->delete();
        $this->likes()->delete();
        $this->shares()->delete();
        $this->comments()->delete();
        $this->notifies()->delete();
        $this->reports()->delete();

        return parent::delete();

    }
    
    public function group()

    {

        return $this->belongsTo(Group::class);

    }

    public function user()

    {

        return $this->belongsTo(User::class);

    }

    public function views()

    {

        return $this->hasMany(View::class);

    }

    public function notifies()

    {

        return $this->hasMany(Notify::class);

    }

    public function reports()

    {

        return $this->hasMany(Report::class);

    }

    public function reportCount()

    {

        return $this->reports()->count();

    }

    public function viewCount()

    {

        return $this->views()->count();

    }

    public function likes()

    {

        return $this->hasMany(Like::class);

    }
	public function dislikes()

    {

        return $this->hasMany(Dislike::class);

    }
	
	public function favorites()

    {

        return $this->hasMany(Favorite::class);

    }

    public function likeCount()

    {

        return $this->likes()->count();

    }
	public function dislikeCount()

    {

        return $this->dislikes()->count();

    }
	
	public function favoriteCount()

    {

        return $this->favorites()->count();

    }
	
	public static function postTopics($id)
    {

        $post = Post::with('interests')->where(['id' => $id])->get();

       // die(print_r( $post->content));
	  // dd($interests );
        return $post;

    }



    public static function getPostInterestIds($id)
    {
        /* DB::table('interest_post')->where('post_id', $id)->pluck('interest_id'); */
        $currentInterestIDs =  DB::select("SELECT interest_id FROM interest_post WHERE post_id = $id");

        return $currentInterestIDs;

    }

    public static function UpdatePostTopics($topics, $id)
    {

        //delete first
        DB::table('interest_post')->where('post_id', '=', $id)->delete();

        //then replaace with new topics
        foreach($topics as $topic){     
           

            DB::table('interest_post')->insert(
                ['post_id' => $id, 'interest_id' => $topic]
            ); 
                                  
        }
        


        return true;

    }
	
	

    public function isLiked()

    {

        $likes = Like::where(['post_id' => $this->id, 'user_id' => Auth::user()->id])->first();

        if ($likes) return true;

        return false;

    }
	
	public function isDisliked()

    {

        $dislikes = Dislike::where(['post_id' => $this->id, 'user_id' => Auth::user()->id])->first();

        if ($dislikes) return true;

        return false;

    }
	
	public function isFavorited()

    {

        $Favorite = Favorite::where(['post_id' => $this->id, 'user_id' => Auth::user()->id])->first();

        if ($Favorite) return true;

        return false;

    }
	
	

    public function shares()

    {

        return $this->hasMany(Share::class);

    }

    public function shareCount()

    {

        return $this->shares()->count();

    }

    public function isShared()

    {

        $share = Share::where(['post_id' => $this->id, 'user_id' => Auth::user()->id])->get();

        if ($share && count($share)) return true;

        return false;

    }

    public function comments()

    {

        return $this->hasMany(Comment::class);

    }

    public function commentCount()

    {

        return $this->comments()->count();

    }

    public function category()

    {

        return $this->belongsTo(Category::class);

    }
    
    public function getLinkAttribute($link)
    
    {
        
        $path = 'assets/front/content/' . $link;
        
        if (! file_exists($path) && $this->type == 'image') return 'not-content.png';
        
        return $link;
        
    }
    
    public function getContentAttribute($content)
    
    {
        
        return nl2br($content);
        
    }
    public function hidePostForm($id)

    {
	$post = Post::findOrFail($id);
	return view('post.hide', compact('post'));
    }
    public function hidePost(Request $request, $id)
	
    {
	$post = Post::findorFail($id);
	$post->update(['hidden' => true]);

	return redirect()->route('post.index')->with('success', 'Post hidden successfully');
    }
}
