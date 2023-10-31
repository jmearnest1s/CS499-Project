<?php

namespace App\Http\Controllers;

use App\Share;
use App\User;
use App\Interest;
use App\Follow;
use App\Notify;
use App\Album;
use App\Albumdata;
use App\Post;
use App\GroupMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mail;
use Image;
class FrontController extends Controller
{

    public function __construct()

    {

        $this->middleware('auth')->only(['authorization', 'sendemailver', 'sendsmsver', 'smsverify']);

    }
	
	public function terms()
    {
		return view('terms');
	}
	public function privacy()
    {
		return view('privacy');
	}
	
	
	
	public function cleanShares()
    {
		//$shares = DB::select(DB::raw("select max(`id`) as id from shares where created_at > DATE_SUB(now(), INTERVAL 6 MONTH)"));
		
		//die(print_r($shares ));
		//die("id ".$shares[0]->id);
			
		DB::select(DB::raw('delete from shares where created_at > DATE_SUB(now(), INTERVAL 6 MONTH ) and group_id is NULL'));
		echo 'done';
	}
	
	public function sendNewsletter()
    {
		
		
		if(!isset($_GET['auth']) || $_GET['auth']!='1901')
			die('not authorized');
		
		ignore_user_abort(TRUE);
		set_time_limit(99999999);
       // die('herfe');
		//DB::enableQueryLog(); // Enable query log
        $users = User::distinct('email')->where('newsletter', '1')->groupBy('email')->get();
		//$users = User::whereIn('id', [2225,1719])->get(); //1623//need ot look for newletter value of 1 for all users
		//dd(DB::getQueryLog()); // Show results of log
		//die(print_r($users));
		
		
		//die('count is'.$users->count());
		
		$counter = 0;

        foreach ($users as $user)
        {
			$counter++;
			echo $counter." - ".$user->email."<br>";
			
			if ($counter == 300)
			{
				//sleep(90);
				$counter = 0;
			}
			//sleep(1);

			 $to = $user->email;
			 $name = $user->name;
			 $subject = "World Food Topics: ";
			// $message = $request->emailMessage;
			
			//die($to);
			/////////////////////////////getting feed data
			
			if($followed_by_this = Follow::where('by', $user->id)->pluck('followed'))
			{
				$follow_sql ='';
				//die(print_r( $followed_by_this));
				foreach($followed_by_this as $follow_id){
					@$follow_sql .= ','.$follow_id;
				}


				$follow_sql = substr($follow_sql,-1);
			}
			
			
			
			$shares = Share::distinct('shares.post_id')->join('posts','posts.id', 'shares.post_id')->whereIn('posts.user_id', $followed_by_this)->orWhereRaw('post_id in (select post_id from interest_post ip inner join interest_user iu on ip.interest_id = iu.interest_id inner join posts p on p.id = ip.post_id where iu.user_id = '.$user->id.' )')->orWhere('posts.user_id', $user->id)->orWhere([['posts.user_id','=',1],['type','!=','feed']])->orderBy('shares.id', 'DESC')->groupBy('shares.post_id')->paginate(4);
			
			
			
			if($shares[0]->scrabingcontent!='')
				$shares[0]->content = $shares[0]->scrabingcontent;
			
			preg_match_all('/<h1.*?>(.*)<\/h1>/msi', $shares[0]->content, $matches);
			//die($shares[0]->content);
			
			//die(print_r($matches));

			// Merge the first 2 matches

			$potential_title = implode ( ' - ', array_slice($matches[0], 0, 2));
			
			if( ! empty ( $potential_title ) ) {
				  $cleaner_title = strip_tags( $potential_title );
				
				  $subject .= $cleaner_title." & more";
				
			}
			else{
				
				preg_match_all('/<h2.*?>(.*)<\/h2>/msi', $shares[0]->content, $matches);

				// Merge the first 2 matches

				$potential_title = implode ( ' - ', array_slice($matches[0], 0, 2));
				
				if( ! empty ( $potential_title ) ) {
				  $cleaner_title = strip_tags( $potential_title );
				
				  $subject .= $cleaner_title." & more";
				
				}
			
				
			}
			
			$shares[0]->content = str_replace('/ajaxpage','https://'.$_SERVER['SERVER_NAME'].'/post/'.$shares[0]->post_id,$shares[0]->content);
			$shares[1]->content = str_replace('/ajaxpage','https://'.$_SERVER['SERVER_NAME'].'/post/'.$shares[1]->post_id,$shares[1]->content);
			$shares[2]->content = str_replace('/ajaxpage','https://'.$_SERVER['SERVER_NAME'].'/post/'.$shares[2]->post_id,$shares[2]->content);
			$shares[3]->content = str_replace('/ajaxpage','https://'.$_SERVER['SERVER_NAME'].'/post/'.$shares[3]->post_id,$shares[3]->content);
			
			
			//die(print_r($shares));
			//////////////////////////////
			
			if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
			 send_newsletter($to, $name, $subject,  $shares[0],$shares[1], $shares[2], $shares[3],base64_encode($user->username));

			}

       
        }
	}
	
	

    public function birthday()

    {

        $tomorrow = Carbon::tomorrow();
        $date = $tomorrow->format('d');
        $month = $tomorrow->format('m');

        $users = User::where('status', 1)->whereMonth('birthday', $month)->whereDay('birthday', $date)->get();

        $notifications = [];

        foreach ($users as $user) {
            
            $followers = $user->followers();
            foreach ($followers as $follower) {

                if (! $user->isBlockedByMe($follower->id)) {
                    $notifications[] = [
                        'by_id' => $user->id,
                        'to_id' => $follower->id,
                        'type' => 'birthday'
                    ];
                }

            }

        }

        Notify::insert($notifications);

    }

    public function tap()

    {

        return view('tap');

    }
    
    public function pp()

    {

        return view('pp');

    }

    public function profile( $username = null )

    {

        $user = User::with('Interests')->where('username', $username)->first();

        if (! $user) return redirect()->route('front');

        if (Auth::check() && $user->isBlockedByMe(Auth::user()->id)) return redirect()->route('front');

       // $shares = Share::join('posts','posts.id', 'shares.post_id')->where('posts.user_id', $user->id)->where('type', '!=','feed')->groupBy('shares.post_id')->orderBy('shares.id', 'DESC')->paginate(5);
	   
	    $shares = Share::distinct('shares.post_id')->join('posts','posts.id', 'shares.post_id')->where('shares.user_id', $user->id)->groupBy('shares.post_id')->orderBy('shares.id', 'DESC')->paginate(5);
	   
		$shares->setPath('');
		$groups = GroupMember::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
		$group_share = GroupMember::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
		
		//die(print_r($groups));
		$page_title = 'Profile';

        return view('profile', compact('user', 'groups','page_title','shares','group_share'));

    }
	
	public function profile_old( $username = null )

    {

        $user = User::where('username', $username)->first();

        if (! $user) return redirect()->route('front');

        if (Auth::check() && $user->isBlockedByMe(Auth::user()->id)) return redirect()->route('front');

        $shares = Share::where('user_id', $user->id)->orderBy('id', 'DESC')->paginate(2);

        return view('profile_old', compact('user', 'shares'));

    }
    
    function follower($username)

    {
        
        $user = User::where('username', $username)->first();
        
        if(! $user) return redirect()->route('front');
        
        if (Auth::check() && $user->id == Auth::user()->id) {
            $page_title = 'Your Followers';
        } else {
            $page_title = 'Followers Of ' . $user->name;
        }

        $followers = Follow::where('followed', $user->id)->paginate(10);

        $users = [];

        foreach ($followers as $follower) {
            $fo = User::find($follower->by);

            if ($fo) $users[] = $fo;
        }

        $records = $followers;

        return view('follow-users', compact('page_title', 'users', 'user', 'records'));

    }
    
    public function scrapper(Request $request)
    
    {
        
        $request->validate([
           'url' => 'required|url' 
        ]);
        
        $metas = get_meta_tags(urldecode($url));
        
        if ($metas && count($metas)) {
            
            return response()->json($metas+['success' => 1]);
            
        }
        
        return response()->json(['success' => 0]);
        
    }

    public function forgotPass(Request $request)
    {
        $this->validate($request,
            [
                'email' => 'required',
            ]);
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return redirect()->back()->withErrors('Email Not Available');
        } else {
            $to = $user->email;
            $name = $user->name;
            $subject = 'Password Reset';
            $code = str_random(30);
            $message = 'Use This Link to Reset Password: '.url('/').'/reset/'.$code;

            DB::table('password_resets')->insert(
                ['email' => $to, 'token' => $code, 'status' => 0, 'created_at' => date("Y-m-d h:i:s")]
            );

            send_email($to, $name, $subject, $message);

            return redirect()->route('login')->withSuccess('Password Reset Email Sent Succesfully');
        }

    }

    public function resetLink($code)
    {
        $reset = DB::table('password_resets')->where('token', $code)->orderBy('created_at', 'desc')->first();

        if ( $reset->status == 1) {
            return redirect()->route('login')->withErrors('Invalid Reset Link');
        }

        return view('auth.passwords.reset', compact('reset'));

    }

    public function passwordReset(Request $request)
    {
        $this->validate($request,
            [
                'email' => 'required',
                'token' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required',
            ]);

        $reset = DB::table('password_resets')->where('token', $request->token)->orderBy('created_at', 'desc')->first();
        $user = User::where('email', $reset->email)->first();
        if ( $reset->status == 1) {
            return redirect()->route('login')->withErrors('Invalid Reset Link');
        } else {
            if($request->password == $request->password_confirmation)
            {
                $user->password = Hash::make($request->password);
                $user->save();

                DB::table('password_resets')->where('email', $user->email)->update(['status' => 1]);

                $msg =  'Password Changed Successfully';
                send_email($user->email, $user->username, 'Password Changed', $msg);
                $sms =  'Password Changed Successfully';
                send_sms($user->mobile, $sms);

                return redirect()->route('login')->withSuccess('Password Changed');
            }
            else
            {
                return redirect()->back()->with('alert', 'Password Not Matched');
            }
        }
    }

    public function authorization()
    {
    	if(Auth::check() && Auth::user()->status == '1' && Auth::user()->emailv == 1 && Auth::user()->smsv == 1) {
            return redirect('home');
        } else {
          return view('auth.notauthor');
        }
    }

    public function sendemailver($email=null)
    {
		if($email == null)
        	$user = Auth::user();
		else
			$user = User::where('email', $email)->first();
			
        $chktm = $user->vsent+1000;
        if ($chktm >time())
         {
            $delay = $chktm-time();
           return back()->with('alert', 'Please Try after '.$delay.' Seconds');
        }
        else
        {
            $code = str_random(8);
            //$msg = 'Your Verification code is: <a href="http://agwiki.dev.blayzer.com/emailverify?code='.$code.'">http://agwiki.dev.blayzer.com/emailverify?code='.$code.'</a><br>Please follow link to verify';
			$msg = '			
The verification code you are about to click will take you to your profile page at AgWiki.com.<Br><br> 

To maximize your experience, beyond your standard biographical information, please take a couple of minutes to select topics that interest you, as well as reviewing the public Groups that currently exist that you may want to join. And of course, it would be great to see a photo of you.  
<Br><br> 
Verification Code: <a href="https://agwiki.com/emailverify?code='.$code.'">https://agwiki.com/emailverify?code='.$code.'</a>  
<Br><br> 
See you soon!

<Br><br> 
John LaRose Jr.<br> 
AgWiki - President  <br> 
';
            $user['vercode'] = $code ;
            $user['vsent'] = time();
            $user->save();
           // send_email($user->email, "New AgWiki User", 'Agwiki Verification Code', $msg);
			
			$data = array('code'=>$code,'to'=>$user->email);
			Mail::send('emails.verification', $data, function($message) use ($code,$data)
			{
				$message->from('no-reply@agwiki.com', "Agwiki");
				$message->subject("Agwiki Verification Code");
				$message->to([$data['to']]);
			});
			
			
            return back()->with('success', 'Email verification code sent succesfully');
        }

    }
     public function sendsmsver()
    {
        $user = User::find(Auth::id());
        $chktm = $user->vsent+1000;
        if ($chktm >time())
         {
            $delay = $chktm-time();
           return back()->with('alert', 'Please Try after '.$delay.' Seconds');
        }
        else
        {
            $code = str_random(8);
            $sms =  'Your Verification code is: '.$code;
            $user['vercode'] = $code;
            $user['vsent'] = time();
            $user->save();

            send_sms($user->mobile, $sms);
            return back()->with('success', 'SMS verification code sent succesfully');
        }


    }

    public function emailverify(Request $request)
    {
		
		//die(print_r($request->code));

        $this->validate($request, [
            'code' => 'required'
        ]);
       // $user = User::find(Auth::id());
		
		//emailverify
		
		$interest = Interest::all()->sortBy('name');
		
		//die(print_r($interest));

       // $code = $request->code;
        //if ($user->vercode == $code)
		if($user = User::where('vercode', $request->code)->first())
        {
           $user['emailv'] = 1;
          // $user['vercode'] = str_random(10);
          //  $user['vsent'] = 0;
		   $user['status'] = 1;//changed from 0 to 1 based on meeting 2/12/20
           $user->save();

            //return redirect('register')->with('success', 'Email Verified');
			
			return view('auth.register', compact('user','interest'));
        }
        else
        {
             return redirect('login')->with('alert', 'Wrong Verification Code');
        }

    }
	
	
	
	
	public function registeruser(Request $request)
    {
	
	
		if($user = User::where('vercode', $request->code)->first())
        {
			//die(print_r($request->all()));
			//$user->update($request->all());
			
			/*foreach($request->all() as $index => $key){
				//@$user[$index] = $key;
				echo $index. "=>". $key."<br>";
			}
			
			die();*/
			
			
			//google api key
			//AIzaSyAG9m26T1vX_wRL7qcAL0X2gRNM2ndd7z0 
			
			
			
			//if no lat//////////////////////////////
			
			if($request->lat=='')
			{
			
			// url encode the address
				$address = urlencode($request->location);
				 
				// google map geocode api url
				$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyAG9m26T1vX_wRL7qcAL0X2gRNM2ndd7z0";
			 
				// get the json response
				$resp_json = file_get_contents($url);
				 
				// decode the json
				$resp = json_decode($resp_json, true);
			 
				// response status will be 'OK', if able to geocode given address 
				if($resp['status']=='OK'){
			 
					// get the important data
					$lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
					$longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
					$formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";
					
					
					
					$user['zip'] = $resp['results'][0]['components']['postal_code'];

						$user['city'] = $resp['results'][0]['components']['locality'];

						$user['state'] = $resp['results'][0]['components']['administrative_area_level_1'];

						
					
					
						$user['lat'] = $lati;

						$user['lng'] = $longi;
				}
				
				
						


			
			
			}
			else
			{
				
				$user['city'] = $request->city;
				$user['state'] = $request->state;
				$user['zip'] = $request->zip;
				$user['lat'] = $request->lat;
				$user['lng'] = $request->lng;
				
			}
			
			
			//////////////////////////////
			
			//die($request->name);
			$user['fname'] = $request->fname;
			$user['email'] = $request->email;
			$user['lname'] = $request->lname;
		    $user['name'] = $request->fname." ".$request->lname;
		    $user['bio'] = $request->bio;
            $user['gender'] = $request->gender;
		    $user['workplace'] = $request->workplace;		   
		    
			$user['facebook'] = $request->facebook;
			$user['twitter'] = $request->twitter;
			$user['linkedin'] = $request->linkedin;			
			
		   
		    
		   
		   //$user['birthday'] = $request->birthday;
           $user['password'] = Hash::make($request->password);
			
           $user['emailv'] = 1;
           $user['vercode'] = '';
          //  $user['vsent'] = 0;
		   $user['status'] = 1;//0;//changed from needed to verify
		   
		   
		   if ($request->hasFile('avatar')) {
			   //echo 'has file';
				$file = $request->file('avatar');
				$avatar = $file->hashName();
				$im = Image::make($file);
				$im->orientate();
				//$im->resize(380, 295);				
				/*$im->resize(300, null, function ($constraint) {
					$constraint->aspectRatio();
				});*/
				$im->fit(300);
				$im->save('assets/front/img/' . $avatar);
				if ($user->avatar != 'male.png' && $user->avatar != 'female.png') {
					@unlink('assets/front/img/' . $user->avatar);
				}
				$user['avatar'] = $avatar;
			}
			//echo 'testing '.$request->file('avatar');
			//print_r($request->file);
			//print_r($user);
			//die();
		   
           $user->save();
		   $user->interests()->attach($request->interest);
		   
		   $data = array();
		   
		   foreach($request->all() as $index => $key){
				$data[$index] = $key;
				//echo $data. "=>". $key."<br>";
			}
			
			$data['email'] = $user['email'];
			
			//die($data['email']);
		   
		  /* Mail::send('emails.welcome', $data, function($message) use ($data)
			{
				$message->from('no-reply@agwiki.com', "Agwiki");
				$message->subject("AgWiki Account Pending Approval");
				$message->to([$data['email']]);
			});
			*/
			
			//need to email admin
			//send_email("rpkrotz@agwiki.com", "Agwiki", 'New User For Approval', "Please validate user ".$data['email']);
			/*$to = "rpkrotz@agwiki.com";
			$message1 = "Please validate user ".$data['email'];
			$subject = 'New User For Approval';
			$headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			/*
			Mail::send([], [], function($message) use($to, $subject, $message1)
			{
				//$message->setBody($message1)->to($to)->subject($subject);
				$message->from('no-reply@agwiki.com', "Agwiki");
				$message->subject($subject);
				$message->setBody($message1, 'text/html');
				$message->to($to);
			});*/
			//mail($to, $subject, $message1, $headers);

            //return redirect('login')->with('alert', 'Account sent for review! ');
		//return redirect('login')->with('alert', 'Please log in with your new account ');
		
			Auth::login($user);
						
			return redirect('/');
			
			//return view('welcome');
        }
        else
        {
             return back()->with('alert', 'Wrong Verification Code');
        }

		
	}
	
	
	

     public function smsverify(Request $request)
    {

        $this->validate($request, [
            'code' => 'required'
        ]);
        $user = User::find(Auth::id());

        $code = $request->code;
        if ($user->vercode == $code)
        {
           $user['smsv'] = 1;
           $user['vercode'] = str_random(10);
           $user['vsent'] = 0;
           $user->save();

            return redirect('home')->with('success', 'SMS Verified');
        }
        else
        {
             return back()->with('alert', 'Wrong Verification Code');
        }

    }

    /*chagne by dinesh start*/

    function UserPhotos($username)
    {
        $user = Auth::user();

        if(! $user) return redirect()->route('front');
        $page_title = 'Photos';
        $userimgs = Post::where('user_id', $user->id)->where('type','=','image')->paginate(50);
        $records = $userimgs;
        return view('user-photos', compact('page_title', 'userimgs', 'user', 'records'));
    }

    function UserVideos($username)
    {
        $user = Auth::user();

        if(! $user) return redirect()->route('front');
        $page_title = 'Videos';
        $uservideos = Post::where('user_id', $user->id)->whereIn('type',['video','youtube','vimeo'])->paginate(50);
        $records = $uservideos;
        return view('user-videos', compact('page_title', 'uservideos', 'user', 'records'));
    }

    function UserCreateAlbum(Request $request){
        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->withErrors('Your session expire please login again');
        }
        else{
            $album= new Album();
            $album->album_name =$request->albumname;
            $album->type =$request->albumtype;
            $album->userid=$user->id;
            $album->save();

            if(isset($request->images) && count($request->images)>0){
                for ($i=0; $i <count($request->images) ; $i++) { 
                    if($request->images[$i]!=''){
                        $albumdata= new Albumdata();
                        $albumdata->albumid =$album->id;
                        $albumdata->value =$request->images[$i];
                        $albumdata->save();
                    }
                }
            }
            return redirect()->back()->withSuccess('Album created Successfully');
        }
    }

    function GetUserAlbums($username){
        $user = Auth::user();

        if(! $user) return redirect()->route('front');
        $page_title = 'Albums';
        $albums = Album::where('userid', $user->id)->where('type','image')->get();
        $records = $albums;
        return view('user-albums', compact('page_title', 'albums','user'));
    }

    function GetUserVideoAlbums($username){
        $user = Auth::user();

        if(! $user) return redirect()->route('front');
        $page_title = 'Albums';
        $albums = Album::where('userid', $user->id)->where('type','video')->get();
        $records = $albums;
        return view('user-video-albums', compact('page_title', 'albums','user'));
    }

    public static function getalbumimg($albumid,$type){
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->withErrors('Your session expire please login again');
        }
        else{
            $albumimg=Albumdata::select('albumdatas.*','albums.album_name')->join('albums','albumdatas.albumid','=','albums.id')->where('albumdatas.albumid',$albumid)->first();
            $html='<div class="col-md-6">';
            if($type=='image'){
                if($albumimg->value!='' && file_exists('./assets/front/content/'.$albumimg->value)){
                 $html.='<a href="'.route('user.single.album',['albumid'=>$albumid,'username'=>$user->username]).'"><img src="'.asset('assets/front/content/'.$albumimg->value).'" width="200" height="200"></a>';
                }
            }
            else{
                $html.='<a href="'.route('user.videosingle.album',['albumid'=>$albumid,'username'=>$user->username]).'">';
                if (strpos($albumimg->value, '.mp4') !== false) {
                    $html.='<video width="200" height="200"><source src="'.asset('assets/front/content/'.$albumimg->value).'" type="video/mp4"></video>';
                }
                else if (strpos($albumimg->value, 'www.youtube.com') !== false) {
                    $youtubevideo=explode('?v=',$albumimg->value);
                    $html.='<img width="200" height="200" src="https://img.youtube.com/vi/'.$youtubevideo[1].'/0.jpg">>';
                }
                else{

                  $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$albumimg->value.".php"));
                                       
                  $html.='<img width="200" height="200" src="'.$hash[0]['thumbnail_medium'].'">';
                }
                $html.='</a>';

            }
            $html.='<div class="row"><p class="col-md-9">'.$albumimg->album_name.'</p> <i class="fa fa-trash col-md-3 delalbum post-action" data="'.$albumid.'" type="'.$type.'" ></i></div>';
            $html.='<p></p></div>';
            echo $html;                          
        }
    }

    function GetAlbumData($albumid,$username){
        $user = Auth::user();

        if(! $user) return redirect()->route('front');
        $albums = Album::find($albumid);
        $page_title = $albums->album_name;
        $albumdata=Albumdata::where('albumid',$albumid)->get();
        return view('user-singlealbum', compact('page_title','albumdata','user'));
    }

    function GetVideoAlbumData($albumid,$username){
        $user = Auth::user();

        if(! $user) return redirect()->route('front');
        $albums = Album::find($albumid);
        $page_title = $albums->album_name;
        $albumdata=Albumdata::where('albumid',$albumid)->get();
        return view('user-singlevideoalbum', compact('page_title','albumdata','user'));
    }

    function AlbumDelete(Request $request){
       $user = Auth::user();
        if (!$user) {
            return redirect()->back()->withErrors('Your session expire please login again');
        }
        else{
            Albumdata::where('albumid',$request->albumid)->delete();
            Album::destroy($request->albumid);
            if($request->type=="image"){
                echo route('user.photo.album', $user->username);
            }
            else{
                echo route('user.video.album', $user->username);
            }
        }
    }

    /*chagne by dinesh end*/
}
