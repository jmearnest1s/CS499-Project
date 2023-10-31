<?php
namespace App;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Post;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];
    protected $dates = ['birthday'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function timeline()

    {

        if($followed_by_this = Follow::where('by', $this->id)->pluck('followed'))
		{
			$follow_sql ='';
			//die(print_r( $followed_by_this));
			foreach($followed_by_this as $follow_id){
				@$follow_sql .= ','.$follow_id;
			}
	
	
			$follow_sql = substr($follow_sql,-1);
		}
		//$shares = Share::with('interests')->get() ;
		//die(print_r($shares));
		
		//die(print_r($followed_by_this));
		
		if(isset($_GET['topic']))
		{
			//die($_GET['topic']);
			
			$shares = Share::distinct('shares.post_id')->WhereRaw('post_id in (select post_id from interest_post ip inner join posts p on p.id = ip.post_id where ip.interest_id = '.$_GET['topic'].') ')->groupBy('shares.post_id')->orderBy('id', 'DESC')->paginate(10);//need to get pagination to work properly for topic feed
			$shares->setPath('');
			
		}
		elseif(isset($_GET['rss']))
		{
			//die($_GET['rss']);
			//DB::enableQueryLog();
			$shares = Share::distinct('shares.post_id')->join('posts','posts.id', '=', 'shares.post_id')->where('link' ,'like', '%' .$_GET['rss']. '%')->orWhere('scrabingcontent','like', '%' .$_GET['rss']. '%')->groupBy('shares.post_id')->orderBy('shares.id', 'DESC')->paginate(10);
			//dd(DB::getQueryLog());
			$shares->setPath('');
			
			//dd($shares);
			
			
		}		
		elseif(isset($_GET['fav']))
		{
			
			
			if(isset($_GET['search']))
			{
				$shares = Share::distinct('favorites.post_id')->join('posts','posts.id', '=', 'shares.post_id')->join('favorites','favorites.post_id', 'shares.post_id')->where('favorites.user_id',Auth::user()->id)->where('content' ,'like', '%' .$_GET['search']. '%')->groupBy('shares.post_id')->orderBy('shares.id', 'DESC')->paginate(10);
				$shares->setPath('');
			}
			else
			{
				//DB::enableQueryLog();
				$shares = Share::distinct('shares.post_id')->join('posts','posts.id', '=', 'shares.post_id')->join('favorites','favorites.post_id', 'posts.id')->where('favorites.user_id',Auth::user()->id)->groupBy('shares.post_id')->orderBy('favorites.id', 'DESC')->paginate(10);
				//dd(DB::getQueryLog());
				$shares->setPath('');
			}
				
			
		}
		elseif(isset($_GET['search']))
		{
			//die($_GET['rss']);
			//DB::enableQueryLog();
			$shares = Share::distinct('shares.post_id')->join('posts','posts.id', '=', 'shares.post_id')->where('content' ,'like', '%' .$_GET['search']. '%')->groupBy('shares.post_id')->orderBy('shares.id', 'DESC')->paginate(10);
			//dd(DB::getQueryLog());
			$shares->setPath('');
			
		}
		
		else
		{
		//DB::enableQueryLog();
		
		//need to try this to run faster///
		
		/*select distinct * from `shares` 
inner join `posts` on `posts`.`id` = `shares`.`post_id` 
inner join interest_post ip
on `posts`.id = ip.post_id
inner join interest_user iu
on iu.user_id =  1611
where 
`posts`.`user_id` in (1616, 1625, 1626)  
or `posts`.type like "feed" 
or `posts`.`user_id` = 1611 
    
or (`posts`.`user_id` = 1 and `posts`.`type` != 'feed') 
group by `shares`.`id`
order by `shares`.`id` desc */
		/////////////////////////////////
		
        	//$shares = Share::distinct('shares.post_id')->join('posts','posts.id', 'shares.post_id')->whereIn('posts.user_id', $followed_by_this)->orWhereRaw('post_id in (select post_id from interest_post ip inner join interest_user iu on ip.interest_id = iu.interest_id inner join posts p on p.id = ip.post_id where iu.user_id = '.Auth::user()->id.' and p.type like "feed")')->orWhere('posts.user_id', $this->id)->orWhere([['posts.user_id','=',1],['type','!=','feed']])->orderBy('shares.id', 'DESC')->paginate(5);
			
			
			//$shares = Share::distinct('shares.post_id')->join('posts','posts.id', 'shares.post_id')->whereIn('posts.user_id', $followed_by_this)->orWhereRaw('post_id in (select post_id from interest_post ip inner join interest_user iu on ip.interest_id = iu.interest_id inner join posts p on p.id = ip.post_id where iu.user_id = '.Auth::user()->id.' and p.type like "feed")')->orWhere('posts.user_id', Auth::user()->id)->orWhere([['posts.user_id','=',1],['type','!=','feed']])->orderBy('shares.id', 'DESC')->groupBy('shares.post_id')->paginate(5);
			//$shares = Share::distinct('shares.post_id')->join('posts','posts.id', 'shares.post_id')->whereIn('posts.user_id', $followed_by_this)->orWhereRaw('post_id in (select post_id from interest_post ip inner join interest_user iu on ip.interest_id = iu.interest_id inner join posts p on p.id = ip.post_id where iu.user_id = '.Auth::user()->id.' )')->orWhere('posts.user_id', Auth::user()->id)->orWhere([['posts.user_id','=',1],['type','!=','feed']])->orderBy('shares.id', 'DESC')->groupBy('shares.post_id')->paginate(10);
			
			$start = microtime(true);
			//DB::enableQueryLog();
			//$shares = Share::distinct('shares.post_id')->join('posts','posts.id', 'shares.post_id')
			$shares = Share::distinct('shares.post_id')->join('posts',
				function($join)
				{
					$join->on('posts.id', '=', 'shares.post_id');
					$join->where('shares.active','=', 1);
				})
				->whereIn('posts.user_id', $followed_by_this)->orWhereRaw('post_id in (select post_id from interest_post ip inner join interest_user iu on ip.interest_id = iu.interest_id inner join posts p on p.id = ip.post_id where iu.user_id = '.Auth::user()->id.' )')->orWhereRaw('post_id in (select post_id from interest_post ip inner join interest_user iu on ip.interest_id = iu.interest_id inner join posts p on p.id = ip.post_id and p.user_id =1 where iu.user_id = '.Auth::user()->id.')')->orWhereRaw('(post_id not in (select post_id from interest_post ip inner join posts p on p.id = ip.post_id)) and posts.user_id =1')->orWhere('posts.user_id', Auth::user()->id)->orderBy('shares.id', 'DESC')->groupBy('shares.post_id')->paginate(10);
			//dd(DB::getQueryLog());
			$time = microtime(true) - $start;
			//dd($shares);
			//die($time);
			
			//groupBy('posts.id')
			$shares->setPath('');
		}
		//->where('group_id',0)
		
		//dd(DB::getQueryLog());

	//	dd($shares);

		/*$shares = DB::select('SELECT * from shares s
		right join posts p
		on p.id = s.post_id
		left join interest_user iu
		on p.user_id = iu.user_id
		left join interest_post ip
		on p.id = ip.post_id and iu.interest_id = ip.interest_id
		where p.user_id = '.Auth::user()->id.'
		'.((isset($follow_sql))?'or p.user_id in ('.$follow_sql.')':"").'
		or p.id in (select post_id from interest_post ip inner join interest_user iu on ip.interest_id = iu.interest_id where iu.user_id = '.Auth::user()->id.')
		or iu.interest_id = ip.interest_id
		order by p.id desc');*/
		
		/*SELECT * from posts p
		left join interest_user iu
		on p.user_id = iu.user_id
		left join interest_post ip
		on p.id = ip.post_id and iu.interest_id = ip.interest_id
		where p.user_id = 1562
		or p.id in (select post_id from interest_post ip inner join interest_user iu on ip.interest_id = iu.interest_id where iu.user_id = 1562)
		order by p.id desc*/
		
		//die(print_r($shares));
		
//dd($shares);
        return $shares;

    }

    public static function getfollowandmutual($poststatus) {
        $Mutualfollow=array();
        if($poststatus=='2'){
        $mutualfollowers= DB::select('SELECT `by` from follows where `by` IN( SELECT followed FROM `follows` WHERE `by` IN ('.Auth::user()->id.') ) and followed IN ('.Auth::user()->id.')');
      
        if(count($mutualfollowers) > 0) 
        {
          foreach($mutualfollowers as $value)
          {
             $Mutualfollow[]=$value->by;
          }
         }
        }
        return $Mutualfollow; 
      }
  
    public  function isFollowedMe()

    {
		

        $follow = Follow::where(['by' => Auth::user()->id, 'followed' => $this->id])->first();

        if ($follow) return true;

        return false;

    }
	
	public static  function StaticisFollowedMe($id)

    {
		

        $follow = Follow::with('user')->join('users','users.id', '=','follows.by')->where(['by' => $id]);

       
        return $follow ;

    }
	
	public static  function StaticisFollowingMe($id )

    {
		

        $follow = Follow::with('user2')->join('users','users.id', '=','follows.followed')->where(['followed' => $id]);

       
        return $follow ;

    }

    public function isBlockedByMe($user_id)

    {

        $block = Block::where(['by_id' => $this->id, 'blocked_id' => $user_id])->first();

        if ($block) return true;

        return false;

    }

    public function posts()

    {

        return $this->hasMany(Post::class);

    }

    public function postCount()

    {

        return $this->posts()->count();

    }

    public function views()

    {

        return $this->hasMany(View::class);

    }

    public function viewCount()

    {

        return $this->views()->count();

    }

    public function comments()

    {

        return $this->hasMany(Comment::class);

    }

    public function commentCount()

    {

        return $this->comments()->count();

    }

    public function earned()

    {

        $trxs = Trx::where('user_id', $this->id)->where('type', '+')->sum('amount');
        
        return $trxs;

    }

    public function followers()

    {

        $followers = Follow::where('followed', $this->id)->get();

        $users = [];

        foreach ($followers as $follower) {
            $fo = self::find($follower->by);

            if ($fo) $users[] = $fo;
        }

        return $users;

    }
	
	public static function Staticfollowers($id)

    {

        $followers = Follow::where('followed', $id)->count();

       
        return $followers;

    }
	
	
	public static function Staticfollowing($id)

    {

        $followings = Follow::where('by', $id)->count();

       

        return $followings;

    }


    public function following()

    {

        $followings = Follow::where('by', $this->id)->get();

        $users = [];

        foreach ($followings as $followed) {
            $fo = self::find($followed->followed);

            if ($fo) $users[] = $fo;
        }

        return $users;

    }
	
	
	
	public static function jobs()
    {
		return job_desc::get();
	}
	
	public static function job($id)
    {
       // return $this->hasOne('App\job_desc','id','workplace');
	   //die('the id '.$id);
		///die(job_desc::where(['id' => $id])->first());
		return job_desc::where(['id' => $id])->first();
		//if ($job) return true;

        //return false;
    }

    public function notifications()

    {

        return $this->hasMany(Notify::class, 'to_id');

    }
	
	public static function StaticgetLatestNotifications($id)

    {

        return Notify::where('to_id', $id)->where('by_id','!=',$id)->orderBy('id', 'DESC')->get();//

    }
	
	public static function  StaticunreadNotificationsCount($id)
    
    {
        
        return Notify::where('to_id', $id)->where('by_id','!=',$id)->where('status', 0)->count();
        
    }
	

    public function getLatestNotifications()

    {

        return Notify::where('to_id', $this->id)->orderBy('id', 'DESC')->get();

    }

    public function unreadNotif()

    {

        return Notify::where('to_id', $this->id)->where('status', 0)->count();

    }
	
	public static function  StaticunreadMessageCount($id)
    
    {
        
        return Message::where('to', $id)->where('status', 0)->count();
        
    }
    
    public function  unreadMessageCount()
    
    {
        
        return Message::where('to', $this->id)->where('status', 0)->count();
        
    }
	
	public function interests(){
    	return $this->belongsToMany('App\Interest')->withPivot('user_id');
	}
	
	
	public static function StaticunreadMessages($id)
    
    {
		
    
        $convarsation_ids = Message::where('to', $id)->orWhere('from', $id)->distinct('from','to')->orderBy('id', 'DESC')->get(['id','from','to', 'content','created_at']);
        
        $mmss = [];
        
        foreach($convarsation_ids as $msgs){
            
            if($msgs->to != $id){
                $mmss[] = $msgs->to;
            }
                
            if($msgs->from != $id){
                $mmss[] = $msgs->from;
            }
        }
        
        $chats = array_unique($mmss);
        $ccc = [];
        foreach($chats as $bal){
        // $ccc[] = Message::where('to', $bal)->where('from',  $id)->orderBy('id','DESC')->first();
        $ccc[] = Message::where('from', $bal)->where('to',  $id)->orderBy('id','DESC')->first();
    
        }

       // return $ccc;
        return Message::where('to', $id)->distinct('from')->orderBy('id', 'DESC')->limit(10)->get(['from','content','created_at','status']);
        
    }
	
	

    
    public function unreadMessages()
    
    {
    
        $convarsation_ids = Message::where('to', $this->id)->orWhere('from', $this->id)->distinct('from','to')->orderBy('id', 'DESC')->get(['id','from','to']);
        
        $mmss = [];
        
        foreach($convarsation_ids as $msgs){
            
            if($msgs->to != $this->id){
                $mmss[] = $msgs->to;
            }
                
            if($msgs->from != $this->id){
                $mmss[] = $msgs->from;
            }
        }
        
        $chats = array_unique($mmss);
        $ccc = [];
        foreach($chats as $bal){
        // $ccc[] = Message::where('to', $bal)->where('from',  $this->id)->orderBy('id','DESC')->first();
        $ccc[] = Message::where('from', $bal)->where('to',  $this->id)->orderBy('id','DESC')->first();
    
        }

       // return $ccc;
        return Message::where('to', $this->id)->distinct('from')->orderBy('id', 'DESC')->get(['from']);
        
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
    
    public function getCoverAttribute($cover)

    {

        $path = 'assets/front/img/' . $cover;

        if (! file_exists($path)) {

            return 'banner.jpg';

        }

        return $cover;

    }

    public function isFriend()

    {

        if (Auth::user()->id == 9) return true;

        $myFollow = Follow::where('by', $this->id)->where('followed', Auth::user()->id)->first();
        $partFollow = Follow::where('by', Auth::user()->id)->where('followed', $this->id)->first();

        if ($myFollow && $partFollow) return true;

        return false;

    }
}
