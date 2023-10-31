<?php

namespace App\Http\Controllers;

use App\Block;
use App\Category;
use App\Comment;
use App\Follow;
use App\General;
use App\Group;
use App\GroupMember;
use App\Like;
use App\Dislike;
use App\Favorite;
use App\Message;
use App\Notify;
use App\Post;
use App\Mention;
use App\Interest;
use App\Report;
use App\Share;
use App\SocialShare;
use App\User;
use App\Ads;
use App\View;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Image;
use App\Etemplate;
use Mail;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'ckstatus'])->except(['postSingle','socialShare']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $page_title = 'Home';
        $shares = Auth::user()->timeline();
		$user = Auth::user();

        return view('home', compact('page_title', 'shares','user'));
    }

	public function oldhome()
    {

        $page_title = 'Home';
        $shares = Auth::user()->timeline();
		$user = Auth::user();

        return view('home_old', compact('page_title', 'shares','user'));
    }

	public function weather()
    {

        $page_title = 'Weather';


        return view('weather');
    }

	public function menupage()
    {

        $page_title = 'Menu';


        return view('menu');
    }

  // Moved to CommoditiesController
  public function commodities() {
    $page_title = 'Commodities';
    return view('commodities');
  }
	
	
	public function ajaxpage()
    {
		
		$url = $_GET['url'];
		
		//die($url);
		$user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        //return $header;
		//return $content;
		//return file_get_contents($url);
		
		
		$url_info = parse_url($url);
		
		
		$content = str_replace('href="/','href="https://'.$url_info['host'].'/',$content);
		$content = str_replace('src="/','src="https://'.$url_info['host'].'/',$content);
		
		
		//$content = str_replace('<script','',$content);
		
		$content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content); 
		
		
		
		//die(htmlentities($content));
		//content="text/html;charset=UTF-8"
		print('<a href="'.$url.'" target="_blank" style="width:100%; text-align:center" class="btn btn-success button button-m button-round-small bg-blue1-dark shadow-small">Page not loading?  Click here</a>');
		//die($content);
		
		$filename = "testfile".rand(0,3).".html";
		
		$myfile = fopen($filename, "w+") ;
		fwrite($myfile, $content);
		
		//print('<div style="width:350; overflow: auto">'.$content.'</div>');
		echo '<iframe id="iframe1" height="600" width="100%" frameborder="0" marginheight="0" marginwidth="0" src="/'.$filename.'" ></iframe>';
	}

	public function feed()
    {
		
		//$user = User::find(rand(2225,2290));
		//Auth::login($user);
		// to use, comment out the auth at the top
        $page_title = 'Feed';
		$groups = GroupMember::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        $shares = Auth::user()->timeline();
		$user = Auth::user();
		$interest = Interest::all();

		//$ads = array(['link'=>'https://www.eatkidfriendly.com/info/','image'=>'http://agwiki.dev.blayzer.com/assets/front/img/s6hauarvTzfjcKWlk3ORMlJRBhwBGa3ymoBtMBR7.png','content'=>"We provide families with kid friendly restaurants, products and other great information."]);
		//$ads = json_decode(json_encode($ads), FALSE);
		
		//$ads = Ads::inRandomOrder()->first();
		
		$ads = Ads::WhereRaw('id in (select ads_id from interest_ads ip inner join interest_user iu on ip.interest_id = iu.interest_id inner join ads p on p.id = ip.ads_id where iu.user_id = '.Auth::user()->id.' )')->inRandomOrder()->first();
		
		$ads = array($ads);


        return view('feed', compact('page_title', 'shares','user','interest','groups','ads'));
    }


    public function socialShare(Post $post, $platform)

    {

        if (!$post) return redirect()->back();

        $user = Auth::user();

        $link = route('front');
        $post_url = route('user.post.single', $post->id);
		
		if($post->scrabingcontent!='')
			$text = str_replace(array("\r", "\n"), '', substr(strip_tags($post->scrabingcontent),0,100));
		else
			$text = str_replace(array("\r", "\n"), '', substr(strip_tags($post->content),0,100));

          //fix the pipe and ampersand in the url
          $text = html_entity_decode($text);
          $text = urlencode($text);

                     

      if ($platform == 'facebook') {
          $link = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($post_url).'&quote='.$text;
      } elseif ($platform == 'twitter') {
          $link = 'http://twitter.com/intent/tweet?url=' . urlencode($post_url).'&text='.$text;
      } elseif ($platform == 'google') {
          $link = 'https://plus.google.com/share?url=' . urlencode($post_url);
      } elseif ($platform == 'linkedin') {
          $link = 'http://www.linkedin.com/shareArticle?mini=true&title=' . urlencode('Post Of ' . $post->user->name) . '&source=' . url('/') . '&url=' . urlencode($post_url);
      } elseif ($platform == 'pinterest') {
          $link = 'http://pinterest.com/pin/create/button/?description=' . urlencode('Post Of ' . $post->user->name) . '&url=' . urlencode($post_url);
      } else {
          return redirect()->back();
      }
		
		//Sdie($post_url . ' '.$text );

		if(Auth::user())
		{

       	 	$share = SocialShare::where('post_id', $post->id)->where('platform', $platform)->where('user_id', $user->id)->first();
		}
		else
		{
			$share = SocialShare::create([
					'ip' => request()->ip(),

					'post_id' => $post->id,
					'platform' => $platform
				]);
		}

        if (!$share) {

			if(Auth::user())
			{



				$share = SocialShare::create([
					'ip' => request()->ip(),
					'user_id' => $user->id,
					'post_id' => $post->id,
					'platform' => $platform
				]);
			}


        }

        return redirect($link);

    }

    public function peoples()

    {

		$id = ((isset($_GET['user']))?$_GET['user']:Auth::user()->id);

		$isFollowedMe = User::StaticisFollowedMe($id)->get();

		$StaticisFollowingMe = User::StaticisFollowingMe($id)->get();

		$Users = User::get();

		$currentUser = User::find($id);

		//die(print($currentUser->name));

		//die(print_r($isFollowedMe));


        $page_title = 'Who To Follow';

        return \view('peoples', compact('page_title','isFollowedMe','StaticisFollowingMe','Users','currentUser'));

    }

    public function groups()

    {

        $page_title = 'Groups';

        return view('groups', compact('page_title'));

    }

    public function links()

    {

        $page_title = 'Links';

        return view('links', compact('page_title'));

    }

	public function updateNotifyStatus(Request $request)
	{


		$message = Notify::where('id',$request->id)->first();
		$message->status = 1;
		$message->save();


	}

	public function updateNotifyStatusAll(Request $request)
	{


		$message = Notify::where('to_id',Auth::user()->id)->get();
		foreach($message as $themessage)
		{
			$themessage->status = 1;
			$themessage->save();
		}
		return response()->json(['success' => 1]);

	}

    public function imageStore(Request $request)

    {
        // return var_dump($request->all());
        $request->validate([
            'file' => 'required'
        ]);

        $file = $request->file('file');

        //Extension Check

        $allowed = ['jpg', 'jpeg', 'png'];
        if (!checkExt($file->getClientOriginalExtension(), $allowed)) {
            response()->json(['error' => 'Only ' . implode(', ', $allowed) . ' files are allowed']);
        }
        // Extension Check

        $name = $file->hashName();

        $im = Image::make($file);
        $im->orientate();
        $im->save('assets/front/tmp/' . $name);
        @unlink('assets/front/tmp/' . $request->link);

        return response()->json(['success' => 1, 'image' => $name]);

    }

    public function imageCrop(Request $r)

    {

        $r->validate([
            'link' => 'required',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'w' => 'required|numeric',
            'h' => 'required|numeric',
            'type' => 'required|in:avatar,cover',
            'ratio' => 'required'
        ]);

        $name = str_random(20) . '.jpg';

        if (file_exists('assets/front/tmp/' . $r->link)) {
            if ($r->type == 'avatar') {
                Image::make('assets/front/tmp/' . $r->link)->crop($r->w, $r->h, $r->x, $r->y)->resize(200, 200)->save('assets/front/img/' . $name);
            } else {
                Image::make('assets/front/tmp/' . $r->link)->crop($r->w, $r->h, $r->x, $r->y)->resize(1500, 500)->save('assets/front/img/' . $name);
            }
        }
        @unlink('assets/front/tmp/' . $r->link);
        $user = Auth::user();

        $property = $r->type;
        $user->$property = $name;
        $user->save();

        return response()->json(['success' => 1, 'link' => $user->$property, 'type' => $r->type]);

    }

    public function allPosts()
    {

        $page_title = 'All Post';
        $posts = Post::orderBy('id', 'DESC')->paginate(2);

        return view('all', compact('page_title', 'posts'));
    }

    public function verifyRequest()

    {

        $user = Auth::user();

        if ($user->verified != 0) return redirect()->back();

        $user->verified = -1;
        $user->save();

        return redirect()->back()->withSuccess('Requested Successfully');

    }

    public function profile()

    {

        $user = Auth::user();

        $page_title = 'Edit Profile';

		//$jobs = App\User::jobs();


        return view('edit-profile', compact('page_title', 'user'));

    }

    public function postByCategory($title, Category $category)

    {

        if (!$category) return redirect()->back();

        $page_title = $category->name;
        $posts = Post::where('category_id', $category->id)->orderBy('id', 'DESC')->paginate(2);


        return view('category', compact('category', 'posts', 'page_title'));

    }

    public function profileUpdate(Request $request)

    {

        $request->validate([
            /*'name' => 'required|string',
            'mobile' => 'required',
            'position' => 'required',
            'quote' => 'required',
            'country' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'cover' => 'image',
            'avatar' => 'image',
            'work' => 'required',
            'workplace' => 'required'*/
        ]);

        if (isset($request->notificationtype) && count($request->notificationtype) > 0) {
            $notifystsimp = implode(',', $request->notificationtype);
        } else {
            $notifystsimp = '';
        }

        $user = Auth::user();

        $user->name = $request->fname. ' ' . $request->lname;
		$user->fname = $request->fname;
		$user->lname = $request->lname;
		$user->bio = $request->bio;

		$user->facebook = $request->facebook;
		$user->twitter = $request->twitter;
		$user->linkedin = $request->linkedin;

       // $user->mobile = $request->mobile;
       // $user->position = $request->position;
       // $user->quote = $request->quote;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
       // $user->work = $request->work;
        $user->workplace = $request->workplace;
       // $user->notifystatus = $notifystsimp;



	   // url encode the address
		$address = urlencode($request->city." ".$request->state." ".$request->zip." ".$request->country);

		// google map geocode api url
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=AIzaSyBnpLwQWEjPKannY5dzSTknl8BPcZFa2Y0";

		// get the json response
		$resp_json = file_get_contents($url);

		// decode the json
		$resp = json_decode($resp_json, true);

		//die(print_r($resp));

		// response status will be 'OK', if able to geocode given address
		if($resp['status']=='OK'){

			// get the important data
			$lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
			$longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";

			//die($lati. " ".$longi );

			$user->lat = $lati;
			$user->lng = $longi;
		}



        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $cover = $file->hashName();
            $im = Image::make($file);
            $im->orientate();
            $im->resize(2200, 912);
            $im->save('assets/front/img/' . $cover);
            if ($user->cover != 'cover.jpg') {
                @unlink('assets/front/img/' . $user->cover);
            }
            $user->cover = $cover;
        }

        if ($request->hasFile('avatar')) {
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
            $user->avatar = $avatar;
        }
		
		
		//mautic///////////////////////
		
		
		$email = Auth::user()->email;
		
		
		$segment = 3;
		
		$url = 'http://mautic.agwiki.com/api/contacts?search='.$email;
		//$data = array('key1' => 'value1', 'key2' => 'value2');
		
		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => array("Content-type: application/x-www-form-urlencoded",
		"Authorization: Basic " . base64_encode("sitecontrol:flattir3")),
				'content' => '',
				'method' => 'GET',
				"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
					)
				
			),
			
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$contact = json_decode($result, true);
		if(isset(array_keys($contact['contacts'])[0]))
		{
			$contact_id = array_keys($contact['contacts'])[0];
			
			
			$url = 'https://mautic.agwiki.com/api/segments/'.$segment.'/contact/'.$contact_id.'/remove';
			//$data = array('key1' => 'value1', 'key2' => 'value2');
			
			// use key 'http' even if you send the request to https://...
			$options = array(
				'http' => array(
					'header'  => array("Content-type: application/x-www-form-urlencoded",
			"Authorization: Basic " . base64_encode("sitecontrol:flattir3")),
					'content' => '',
					'Cache-Control: no-cache' ,
					'method' => 'POST'
				)
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			$dnc_result = json_decode($result, true);
			
		}
		
		///////////////////////////////

        $user->save();

        //return redirect()->back()->withSuccess('Profile Updated Successfully');
		return redirect()->route('profile',$user->username)->withSuccess('Profile Updated Successfully');


    }

    public function search()

    {

        $s = request()->get('s');

        if (!$s || empty($s)) return redirect()->back();

        $posts = Post::where('content', 'LIKE', "%$s%")->get();
        $groups = Group::where('name', 'LIKE', "%$s%")->get();
        $users = User::where('username', 'LIKE', "%$s%")->orWhere('name', 'LIKE', "%$s%")->orWhere('email', 'LIKE', "%$s%")->get();

        $page_title = 'Search Results';

        return view('search', compact('page_title', 'posts', 'users', 'groups'));

    }

    public function tag($tag)

    {

        if (empty($tag)) return redirect()->back();

        $posts = Post::where('content', 'LIKE', "%$tag%")->get();
        $groups = Group::where('name', 'LIKE', "%$tag%")->get();
        $users = User::where('username', 'LIKE', "%$tag%")->orWhere('name', 'LIKE', "%$tag%")->orWhere('email', 'LIKE', "%$tag%")->get();

        $page_title = '#' . $tag;

        return view('search', compact('page_title', 'posts', 'users', 'groups'));

    }

    public function postSingle(Post $post)
    {

        if (!$post) return redirect()->back()->withErrors('Post Not Found');
		
		//die('test');

        //$pos = strpos($post->scrabingcontent, '<p class="excerpt">') + 14;
		$pos = strpos($post->scrabingcontent, '<p>')+3 ;
		
		if(strstr($post->scrabingcontent,'h2'))
		{
			$titlepos = strpos($post->scrabingcontent, '<h2>') + 4;
			$titleposend = strpos($post->scrabingcontent, '</h2>') - $titlepos;
		}
		else
		{
			$titlepos = strpos($post->scrabingcontent, '<h1>') + 4;
			$titleposend = strpos($post->scrabingcontent, '</h1>') - $titlepos;
		
		}
		$imgstart = strpos($post->scrabingcontent, '<img src="') + 10;
        $imgend = strpos($post->scrabingcontent, '" style') - $imgstart;
		
		$html = '<img id="12" border="0" src="/images/image.jpg"
         alt="Image" width="100" height="100" />';

		$fb_image = '';
		preg_match_all('/<img[^>]+>/i',$post->scrabingcontent, $imgTags); 

			for ($i = 0; $i < count($imgTags[0]); $i++) {
			  // get the source string
			  preg_match('/src="([^"]+)/i',$imgTags[0][$i], $imgage);

			  // remove opening 'src=' tag, can`t get the regex right
			  $fb_image = str_replace( 'src="', '',  $imgage[0]);
				//echo str_ireplace( 'src="', '',  $imgage[0]);
			}
	
		
		
		
		
		
		
		
		
		//die();

        
		$tw_image ='';
		$og_title = 'Agwiki Post';
		if ($post->scrabingcontent != '') {
			
			$d = new \DOMDocument();
			@$d->loadHTML($post->scrabingcontent);
			$return = array();
			foreach($d->getElementsByTagName('h1') as $item){
				//$return[] = $item->textContent;
				$og_title = $item->textContent;
			}
			
			if($og_title == '')
			{
				$d = new \DOMDocument();
				@$d->loadHTML($post->scrabingcontent);
				$return = array();
				foreach($d->getElementsByTagName('h2') as $item){
					//$return[] = $item->textContent;
					$og_title = $item->textContent;
				}
			}
		
            //$og_title = str_replace(' ', '-', substr($post->scrabingcontent, $titlepos, $titleposend));
			//$og_title = substr($post->scrabingcontent, $titlepos, $titleposend);
			
			//
           // $og_description = substr($post->scrabingcontent, $pos, 120);
			$og_description = strip_tags($post->scrabingcontent);
			
			//$fb_image = substr($post->scrabingcontent, $imgstart, $imgend);
			
			
			if(!strstr($fb_image,'http'))
            	$og_image = 'https://'.$_SERVER['SERVER_NAME'].'/' . $fb_image;
			else
				$og_image =  $fb_image;
			
        	//die('test1');
			
			//die($fb_image);
			
			
						
			//if(strstr($fb_image,'http'))
			//	$tw_image = 'https://'.$_SERVER['SERVER_NAME'].'/assets/front/content/twitter_' . $fb_image;
			//else
			//{
				$new_tw_image = explode('/content/',$fb_image);

				//$tw_image = 'https://'.$_SERVER['SERVER_NAME'].'/' . @$new_tw_image[0].'/content/'.'twitter_'.@$new_tw_image[1];
				$tw_image =  @$new_tw_image[0].'/content/'.'twitter_'.@$new_tw_image[1];
			//}
			
			
			if($tw_image =='') $tw_image = $fb_image ;
			
			if(!strstr($tw_image,'http'))
            	$tw_image = 'https://'.$_SERVER['SERVER_NAME'].'/' . $tw_image;
			
			
			//print($fb_image);
			//die($tw_image);
        	
		} else {
            $og_title = strip_tags( substr(preg_replace("/<img[^>]+\>/i", "",excerpt($post)), 0, 60));
            $og_description = strip_tags(substr(excerpt($post), 0, 100));
			//die($og_description);

            if ($post->type == 'image') {
				//die('test2');
                $og_image =  'https://'.$_SERVER['SERVER_NAME'].'/assets/front/content/' . $post->link;
            	$new_tw_image = explode('/content/',$og_image);
				$tw_image =  @$new_tw_image[0].'/content/'.'twitter_'.@$new_tw_image[1];
        	
		
			} else {
				
				//die(print_r(explode('/assets/',$og_description)));
                //$og_image = 'https://'.$_SERVER['SERVER_NAME'].'/assets/front/img/icon_512.png';
            	//$tw_image = 'https://'.$_SERVER['SERVER_NAME'].'/assets/front/img/icon_512.png';
				
				//die($post->content);
            	 
				 $imgstart2 = strpos($post->content, '<img src="') + 10;
				$imgend2 = strpos($post->content, '" style') - $imgstart;

				$og_image =  'https://'.$_SERVER['SERVER_NAME'].'' . substr($post->content, $imgstart2, $imgend2);
				
				$fb_image = $og_image;
				//die('test1');
				//$fb_image = substr($post->content, $imgstart2, $imgend2);
				$new_tw_image = explode('/content/',$fb_image);
				if(sizeof($new_tw_image)>1)
					$tw_image = 'https://'.$_SERVER['SERVER_NAME'].'/' . @$new_tw_image[0].'/content/'.'twitter_'.@$new_tw_image[1];
				else
					$tw_image = $fb_image;
					
					//die($og_image);

			}
        }
		//die(print_r($_ENV));
		//die($_ENV['APP_URL']. $og_image);
        $og_url = route('user.post.single', $post->id);
        $page_title = str_replace('<h2>','',$og_title);

		//die(" = ".$page_title);
		
		$og_description = trim($og_description);
		
		
		
		if(strstr($fb_image,'base64'))
		{
			
			$tw_image = str_replace('https://'.$_SERVER['SERVER_NAME'].'/','',$tw_image);
			$fb_image = str_replace('https://'.$_SERVER['SERVER_NAME'].'/','',$fb_image);
			$og_image = str_replace('https://'.$_SERVER['SERVER_NAME'].'/','',$og_image);
			//die($fb_image);
			
			$tw_image = str_replace('/content/twitter_','',$tw_image);
			
			//die($tw_image);
			
		}
		
		
		//last twitter checks
		if(strstr($tw_image,'https://agwiki.com/https://agwiki.com'))
		{
			$tw_image = str_replace('https://agwiki.com/https://agwiki.com','https://agwiki.com',$tw_image);
		}
		
		
		

        if (Auth::check()) {
            $user = Auth::user();
			$groups = GroupMember::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            if ($post->user->isBlockedByMe($user->id)) return redirect()->route('front');
            $viewed = View::where(['user_id' => $user->id, 'post_id' => $post->id])->count();

            if ($post->user_id != $user->id && !$viewed) {

                $gnl = General::first();

                $view = View::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                    'author_id' => $post->user->id
                ]);
				$comments = Comment::where('post_id', $post->id)->where('comment_id', 0)->get();
				
				

                return view('single-post', compact('post', 'page_title', 'og_title', 'og_description', 'og_url','comments', 'og_image','groups','tw_image'));

            }
			else
			{
				
				
				$comments = Comment::where('post_id', $post->id)->where('comment_id', 0)->get();
				return view('single-post', compact('post', 'page_title', 'og_title', 'og_description', 'og_url','comments', 'og_image','groups','tw_image'));
			}
        }

		
        $comments = Comment::where('post_id', $post->id)->where('comment_id', 0)->get();
		
		
		

        return view('single-post', compact('post', 'page_title', 'comments', 'og_title', 'og_description', 'og_url', 'og_image','tw_image'));

    }

	public static function getCommentsforPost($id){


		return $comments = Comment::where('post_id', $id)->get();
	}

    public function message($username = null)

    {

        $user = Auth::user();
		$convarsations = array();

        $convarsation_ids = Message::where('to', $user->id)->orWhere('from', $user->id)->distinct('from', 'to')->orderBy('id', 'DESC')->get(['id', 'from', 'to']);
        $mmss = array();
        foreach ($convarsation_ids as $msgs) {

            if ($msgs->to != $user->id) {
                $mmss[] = $msgs->to;
            }

            if ($msgs->from != $user->id) {
                $mmss[] = $msgs->from;
            }
        }

        $chats = array_unique($mmss);

        foreach ($chats as $chat) {
            $use = User::find($chat);
            if ($use) $convarsations[] = $use;
        }

        $last = Message::where('to', $user->id)->orWhere('from', $user->id)->orderBy('id', 'DESC')->first();

        if (!$username) {

            if ($last->from == $user->id) {
                $to_id = $last->to;
            } else {
                $to_id = $last->from;
            }

            $to = User::find($to_id);
        } else {
            $to = User::where('username', $username)->first();
        }

        $from = $user;

        if (!$to || $to->isBlockedByMe($from->id)) return redirect()->back();
        //if (!$to->isFriend()) return redirect()->back();

        $readMessage = Message::where('to', $from->id)->where('from', $to->id)->update(['status' => 1]);

        $messages = Message::where(function ($query) use ($from, $to) {
            $query->where('from', $from->id)->where('to', $to->id);
        })->orWhere(function ($query) use ($from, $to) {
            $query->where('from', $to->id)->where('to', $from->id);
        })->orderBy('id', 'DESC')->take(30)->get();

        $messages = $messages->reverse();

		$page_title = $from->name . '>>' . $to->name;

        return view('message', compact('page_title', 'to', 'from', 'messages', 'convarsations'));

    }

    public function messageStore(Request $request)

    {

        $request->validate([
            'to' => 'required|numeric'
        ]);

        $to = User::find($request->to);

        if (!$to) return response()->json(['success' => 0]);

        $from = Auth::user();

		//die(print_r($request));

        if ($request->type == 'image') {
            $request->validate([
                'file' => 'required'
            ]);

            $file = $request->file('file');

            //Extension Check

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            if (!checkExt($file->getClientOriginalExtension(), $allowed)) {
                return response()->json(['error' => 'Only ' . implode(', ', $allowed) . ' files are allowed']);
            }
            // Extension Check

            $link = $file->hashName();

            $im = Image::make($file);
            $im->orientate();
            $im->save('assets/front/content/' . $link);

            $message = Message::create([
                'from' => $from->id,
                'to' => $to->id,
                'type' => 'image',
                'link' => $link
            ]);

        } elseif ($request->type == 'youtube') {
            $request->validate([
                'link' => 'required'
            ]);
            $message = Message::create([
                'from' => $from->id,
                'to' => $to->id,
                'type' => 'youtube',
                'link' => $request->link
            ]);
        } elseif ($request->type == 'vimeo') {
            $request->validate([
                'link' => 'required'
            ]);
            $message = Message::create([
                'from' => $from->id,
                'to' => $to->id,
                'type' => 'vimeo',
                'link' => $request->link
            ]);
        } else {
            $request->validate([
                'message' => 'required'
            ]);

			// The Regular Expression filter
			$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

			if(preg_match($reg_exUrl, $request->message, $url)) {
					$checktext = $request->message;
				   // make the urls hyper links
				   $text =  preg_replace($reg_exUrl, "<a target='_blank' href=".$url[0].">".$url[0]."</a> ",$checktext );

			} else {

				   // if no urls in the text just return the text
				   $text = $request->message;

			}
//return response()->json(['success' => 0,'text'=>$text]);

            $message = Message::create([
                'from' => $from->id,
                'to' => $to->id,
                'content' => $text //htmlentities
            ]);
        }

        if (!$message) return response()->json(['success' => 0]);

		Mail::send([], [], function($message2) use($to, $from, $text)
		{

			$emailMessage = "<img src='".asset('assets/front/img/' . $from->avatar)."'><br>".$from->name." sent you a message on AgWiki.com<br>To view your message <a href='".url('/message/'.$from->username)."'>click here</a>";

			$message2->from('no-reply@agwiki.com', "AgWiki");
			$message2->subject("AgWiki - New Message From ".$from->name);
			$message2->setBody($emailMessage, 'text/html');
			$message2->to($to->email);
		});

        return response()->json([
            'success' => 1,
            'user' => [
                'avatar' => asset('assets/front/img/' . $from->avatar),
                'name' => $from->name
            ],
            'created_at' => $message->created_at->toDateTimeString(),
            'created_at_format' => $message->created_at->format('M d, Y') . ' at ' . $message->created_at->format('g:i a'),
            'content' => $message->content
        ]);

    }

    public function messageUpdate(Request $request)

    {

        $request->validate([
            'to' => 'required|numeric',
            'last' => 'required|numeric'
        ]);

        $to = User::find($request->to);

        if (!$to) return response()->json(['success' => 0]);

        $from = Auth::user();

        $readMessage = Message::where('to', $from->id)->where('from', $to->id)->update(['status' => 1]);

        $messages = Message::where('id', '>', $request->last)->where(function ($query) use ($from, $to) {
            $query->where(function ($query) use ($from, $to) {
                $query->where('from', $from->id)->where('to', $to->id);
            })->orWhere(function ($query) use ($from, $to) {
                $query->where('from', $to->id)->where('to', $from->id);
            });
        })->get();

        return response()->json([
            'success' => 1,
            'messages' => $messages
        ]);

    }

    public function commentUpdate(Request $request, Post $post)

    {

        if (!$post) return response()->json(['success' => 0]);

        $request->validate([
            'last' => 'required|numeric'
        ]);

        $comments = Comment::with('user')->where('post_id', $post->id)->where('id', '>', $request->last)->get();

        return response()->json([
            'success' => 1,
            'comments' => $comments
        ]);

    }

    public function invite()

    {

        $page_title = 'Invite Friends';

        return \view('invite', compact('page_title'));

    }

    public function inviteSendViaEmail(Request $request)

    {

        $request->validate([
            'to' => 'required|email',
            'message' => 'required'
        ]);

        $temp = Etemplate::first();
        $gnl = General::first();

        $from = $temp->esender;

        $headers = "From: $gnl->title <$from> \r\n";
        $headers .= "Reply-To: $gnl->title <$from> \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
       // $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        //mail($request->to, 'Invitation to join AgWiki', $request->message, $headers);
		send_email( $request->to, $request->to, 'Invitation to join AgWiki', $request->message);

        return redirect()->back()->withSuccess('Email Send Successfully');

    }

    public function messagesNotify()

    {

        $user = Auth::user();

        $messages = $user->unreadMessages();
        $page_title = 'Messages';

        return view('notify-message', compact('messages', 'page_title'));

    }

    public function typingUpdate(Request $request)

    {

        $request->validate([
            'type' => 'required|numeric'
        ]);

        $user = Auth::user();

        $user->typing = $request->type;
        $user->save();

    }

    public function typingCheck(Request $request)

    {

        $request->validate([
            'to' => 'required|numeric'
        ]);

        $user = User::find($request->to);

        if (!$user) return response()->json(['type' => 0]);

        if ($user->typing == 1) return response()->json(['type' => 1]);

        return response()->json(['type' => 0]);

    }


    public function viewLike(Post $post)

    {

        if (!$post) return redirect()->back();

        $page_title = 'All Liker';
        $actions = Like::where('post_id', $post->id)->orderBy('id', 'DESC')->paginate(50);

        return view('view-action', compact('page_title', 'actions'));

    }

    public function viewShare(Post $post)

    {

        if (!$post) return redirect()->back();

        $page_title = 'All Sharer';
        $actions = Share::where('post_id', $post->id)->orderBy('id', 'DESC')->paginate(50);

        return view('view-action', compact('page_title', 'actions'));

    }

    public function newPost()
    {

        $page_title = 'New Post';
        $categories = Category::orderBy('id', 'DESC')->get();

        return view('new-post', compact('page_title', 'categories'));

    }

    public function newCategoryStore(Request $request)

    {

        $request->validate([
            'name' => 'required'
        ]);

        $category = Category::where('name', $request->name)->first();

        if ($category) return response()->json(['success' => 2]);

        $category = Category::create([
            'name' => $request->name
        ]);

        if ($category) return response()->json([
            'success' => 1,
            'id' => $category->id,
            'text' => $category->name
        ]);

        return response()->json([
            'success' => 0
        ]);

    }


    public function fileStore(Request $request)

    {

        $request->validate([
            'type' => 'required|in:image,video,audio,doc',
            'file' => 'required'
        ]);

        $file = $request->file('file');
		//die(print_r($file));

        if ($request->type == 'image') {
            //Extension Check

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            if (!checkExt($file->getClientOriginalExtension(), $allowed)) {
                if ($request->ajax()) return response()->json(['error' => 'Only ' . implode(', ', $allowed) . ' files are allowed']);
                return redirect()->route('front')->withErrors('Only ' . implode(', ', $allowed) . ' files are allowed');
            }
            // Extension Check

            $name = $file->hashName();
            $im = Image::make($file);
            $im->orientate();
            $im->save('assets/front/content/' . $name);
			
			
			
			/////////////////////
			
			$path = 'assets/front/content/'.$name;
			//die('path '.$path);
			//return response()->json(['link' => $path, 'type' => 'image']);
			
			$tw_image = explode('/content/',$path);
			
			// open file a image resource
			$img = Image::make($path);

			
			$img->resize(600, null, function ($constraint) {
				$constraint->aspectRatio();
			});
			
			
			
			
			$img->save('assets/front/content/twitter_'.$tw_image[1] );
			
			/////////////////////
			

            return response()->json(['link' => $name, 'type' => 'image']);

        } elseif ($request->type == 'video') {

            // Extension Check
            $allowed = ['mp4', 'webm'];
            if (!checkExt($file->getClientOriginalExtension(), $allowed)) {
                if ($request->ajax()) return response()->json(['error' => 'Only ' . implode(', ', $allowed) . ' files are allowed']);
            }
            // Extension Check

            $name = $file->hashName();
            $file->move('assets/front/content', $name);

            return response()->json(['link' => $name, 'type' => 'video']);

        } elseif ($request->type == 'audio') {
            // Extension Check
            $allowed = ['mp3', 'mpeg'];
            if (!checkExt($file->getClientOriginalExtension(), $allowed)) {
                if ($request->ajax()) return response()->json(['error' => 'Only ' . implode(', ', $allowed) . ' files are allowed']);
            }
            // Extension Check

            $name = $file->hashName();
            $file->move('assets/front/content', $name);

            return response()->json(['link' => $name, 'type' => 'audio']);
        } elseif ($request->type == 'doc') {
            // Extension Check
            $allowed = ['doc', 'docx', 'pdf', 'txt'];
            if (!checkExt($file->getClientOriginalExtension(), $allowed)) {
                if ($request->ajax()) return response()->json(['error' => 'Only ' . implode(', ', $allowed) . ' files are allowed']);
            }
            // Extension Check

            $name = $request->file->getClientOriginalName()."_".$file->hashName();
            $file->move('assets/front/content', $name);

            return response()->json(['link' => $name, 'type' => 'doc']);
        }

        if ($request->ajax()) return response()->json(['error' => 'Unexpected Error!']);
        return redirect()->route('front')->withErrors('Unexpected Error!');

    }

    public function fileDelete(Request $request)

    {

        $request->validate([
            'link' => 'required'
        ]);

        @unlink('assets/front/content/' . $request->link);

        return 1;

    }

    public function newPostStore(Request $request)

    {

		/*echo $request->article."<br>";
		$mention_regex = '/^(?!.*\bRT\b)(?:.+\s)?@\w+/i'; //mention regrex to get all @texts
		$mention_regex = '([@#][\w_-]+)'; //mention regrex to get all @texts

		//preg_match_all($mention_regex, $request->article, $matches);
		//die(print_r($matches));

			if (preg_match_all($mention_regex, $request->article, $matches))
			{
				//die(print_r($matches));

				foreach($matches[0] as $match)
				{
					echo $match."<br>";
					echo str_replace('@','',$match)."<br>";
					$userMention = User::where('username',str_replace('@','',$match))->first();
					echo $userMention->name;
					//print_r($userMention);
				}
			}
		die();*/
        $request->validate([
            'type' => 'nullable|in:image,video,audio,youtube,vimeo,doc'
        ]);

        if (!$request->type) {
            $type = 'article';
        } else {
            $type = $request->type;
        }
		
		if($request->make_feed ==1  ) {
            $type = 'feed';        
        } 

        if ($request->group_id && is_numeric($request->group_id) && $request->group_id > 0) {

            $member = GroupMember::where('group_id', $request->group_id)->where('user_id', Auth::user()->id)->where('status', 1)->first();

            if ($member) {
                $group_id = $request->group_id;

                if ($member->status != 1) return redirect()->route('front');

            } else {
                $group_id = 0;
            }

        } else {
            $group_id = 0;
        }

        if ($type == 'article' || $type == 'feed') {



            if ($request->urldataval != '') {
                //$titel = '<a class="postTitle" href="' . $request->hrefurl . '" target="_blank" >' . $request->article . '</a>';
				$titel = '' . str_replace($request->hrefurl,'',$request->article) . '';
                $scrabingcontent = '<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">';
				$scrabingcontent .= '<p class="linkContent">' . $titel . '</p>';
                $scrabingcontent .= $request->urldataval;

                $scrabingcontent .= '</div>';
				
				//die($request->urldataval);
            } else {
				$request->validate([
					'article' => 'required'
				]);
                $scrabingcontent = '';
            }

            $arti = nl2br($request->article);
			//$scrabingcontent = nl2br($scrabingcontent);
			//die(print_r($arti));
			
			if($type =="feed"  ) {
				 $post = Post::create([
					'user_id' => Auth::user()->id,
					'content' => $scrabingcontent,
					'link' => $request->hrefurl,
					'type' => $type,
					'group_id' => $group_id
				]);        
			} 
			else {
				$post = Post::create([
					'user_id' => Auth::user()->id,
					'content' => $arti,
					'scrabingcontent' => $scrabingcontent,
					'type' => $type,
					'group_id' => $group_id
				]);    
			}


            /*$post = Post::create([
                'user_id' => Auth::user()->id,
                'content' => $arti,
                'scrabingcontent' => $scrabingcontent,
                'type' => 'article',
                'group_id' => $group_id
            ]);*/

			$post->interests()->attach($request->interest);
        } elseif ($type == 'image' || $type == 'video' || $type == 'audio' || $type == 'doc') {

            $request->validate([
                'link' => 'required'
            ]);

            $arti = $request->article;

            $post = Post::create([
                'user_id' => Auth::user()->id,
                'content' => $arti,
                'link' => $request->link,
                'type' => $type,
                'group_id' => $group_id
            ]);
			$post->interests()->attach($request->interest);

        } elseif ($type == 'youtube') {

            $request->validate([
                'youtube' => 'required'
            ]);

            $arti = $request->article;

            $post = Post::create([
                'user_id' => Auth::user()->id,
                'content' => $arti,
                'link' => $request->youtube,
                'type' => 'youtube',
                'group_id' => $group_id
            ]);
			$post->interests()->attach($request->interest);

        } elseif ($type == 'vimeo') {

            $request->validate([
                'vimeo' => 'required'
            ]);

            $arti = $request->article;

            $post = Post::create([
                'user_id' => Auth::user()->id,
                'content' => $arti,
                'link' => $request->vimeo,
                'type' => 'vimeo',
                'group_id' => $group_id
            ]);
			$post->interests()->attach($request->interest);




        }

        if (!$post) {
            if ($request->ajax()) return response()->json(['error' => 'Unexpected Error, Please Try Again']);
            return redirect()->back()->withErrors('Unexpected Error, Please Try Again');
        }




		//look for @ mentions


		$mention_regex = '([@][\w_-]+)'; //mention regrex to get all @texts


			preg_match_all($mention_regex, $request->article, $matches);
			if(is_array($matches))
			{

				$updatedContent = ((isset($scrabingcontent) && $scrabingcontent!='')?$scrabingcontent:$request->article) ;

				foreach($matches[0] as $match)
				{


					if($userMention = User::where('username',str_replace('@','',$match))->first())
					{
						$mention = Mention::create([
							'user_id' => $userMention->id,
							'type' => 'post',
							'type_id' => $post->id,

						]);

						$notify[] = [
							'post_id' => $post->id,
							'to_id' => $userMention->id,
							'by_id' => Auth::user()->id,
							'type' => 'userTag'
						];


						$updatedContent = str_replace($match,'<a href="/profile/'.$userMention->username.'">'.$userMention->name.'</a>',$updatedContent);
						//die("see ".$updatedContent);

					}
				}

				$updatepost = Post::find($post->id);

				if(isset($scrabingcontent) && $scrabingcontent!='')
				{
					$updatepost->scrabingcontent = $updatedContent;
				}
				else
				{
					$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

					if(preg_match($reg_exUrl, $updatedContent, $url)) {
							$checktext = $updatedContent;
						   // make the urls hyper links
						   $updatedContent =  preg_replace($reg_exUrl, "<a target='_blank' href=".$url[0].">".$url[0]."</a> ",$checktext );

					}
					$updatepost->content = $updatedContent;
				}

				$updatepost->save();

				if(isset($notify))
					$post->notifies()->createMany($notify);

			}


		////////////////////





        $user = Auth::user();

        if ($post->group_id == 0) {

            // This is timeline post, So
            // Notify Follow Users
           /* $notify = [];
            $followers = $user->followers();
            foreach ($followers as $follower) {

                if (!$user->isBlockedByMe($follower->id)) {
                    $notify[] = [
                        'to_id' => $follower->id
                    ];
                }

            }
            $post->notifies()->createMany($notify);
			*/

        } else {
            // This is Group Post
            $notify = [];
            $members = $member->group->members;
            foreach ($members as $member) {

                if (!$user->isBlockedByMe($member->user_id) && $member->user_id != $user->id) {
                    $notify[] = [
                        'to_id' => $member->user_id,
                        'type' => 'group'
                    ];
                }

            }
            $post->notifies()->createMany($notify);
        }

        $share = Share::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
			'group_id' => $group_id
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Post Published Successfully.']);
        } else {
            if ($post->group_id != 0) return redirect()->route('user.groups', $member->group->slug)->withSuccess('Post Published Successfully.');
            return redirect()->route('feed')->withSuccess('Post Published Successfully.');
        }

    }

    public function postDelete(Request $request)

    {

        $request->validate([
            'post_id' => 'required|numeric'
        ]);

        $post = Post::find($request->post_id);

        if ($post->group && ($post->group->isCreator() || $post->group->isAdmin() || $post->group->isModerator())) {
            $gr = true;
        } else {
            $gr = false;
        }
		
		if ($post->type == 'feed' && Auth::user()->id == 1) {
           //check for feed and set as inactive
			//$share = Share::find('post_id',$request->post_id);
			$share = Share::distinct('shares.post_id')->where('shares.post_id',$request->post_id)->groupBy('shares.post_id')->orderBy('shares.id', 'DESC')->update(['active'=>0]);
			
			//die(print_r($share));
				
			//$share->active = 0;
			//$share->update(['active',0]);
			
			return response()->json(['success' => 1]);

        }

        if ($post && ($post->user_id == Auth::user()->id || Auth::user()->id == 9 || $gr)) {
            @unlink('assets/front/content/' . $post->link);
            $post->delete();

            return response()->json(['success' => 1]);
        }

        return response()->json(['error' => 1]);

    }

	public function shareDelete(Request $request)

    {

        $request->validate([
            'post_id' => 'required|numeric'
        ]);

        $share = Share::where('post_id', $request->post_id)->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get()    ;
        /*if ($share->group && ($share->group->isCreator() || $share->group->isAdmin() || $share->group->isModerator())) {
            $gr = true;
        } else {
            $gr = false;
        }*/
		//echo "<pre>";
		//die(print_r($share));

        if ($share && ($share[0]->user_id == Auth::user()->id )) {

            $share[0]->delete();

            return response()->json(['success' => 1]);
        }

        return response()->json(['error' => 1]);

    }

    public function commentDelete(Request $request)

    {

        $request->validate([
            'post_id' => 'required|numeric',
            'comment_id' => 'required|numeric'
        ]);

        $post = Post::find($request->post_id);
        $comment = Comment::find($request->comment_id);

        if ($post && $comment && ($post->user_id == Auth::user()->id || Auth::user()->id == 9 || $comment->user_id == Auth::user()->id)) {
            @unlink('assets/front/content/' . $comment->link);
            $comment->delete();

            return response()->json(['success' => 1]);
        }

        return response()->json(['error' => 1]);

    }

 

    public function editPost(Post $post)

    {
        
        /* die(print_r($post));
 */
        if (!$post) return redirect()->back();

        $user = Auth::user();

        if ($post->user_id != $user->id) return redirect()->back()->withErrors('Unexpected Error!');

        $currentInterestIDs = Post::getPostInterestIds($post->id);

        $page_title = 'Edit';
       
        
        return view('post-edit', compact('post', 'page_title', 'currentInterestIDs'));

    }

    //public function editPostUpdate(Request $request, $title, Post $post)
	public function editPostUpdate(Request $request,  Post $post)

    {

        $topics = explode (",", $request->topic_ids);
        array_pop($topics);

       /*  dd(print_r($topics)); */
        /* die(print_r($post)); */

        

        

        $request->validate([
            //'post_title' => 'required|string',
            'post_content' => 'required',
            'post_image' => 'image'
        ]);

        if (!$post) return redirect()->back();

		//dd($request->file('post_image')->getMimeType());

        $user = Auth::user();

        if ($post->user_id != $user->id) return redirect()->back()->withErrors('Unexpected Error!');

        if ($request->hasFile('post_image')) {

            $file = $request->file('post_image');
            $image = $file->hashName();

            $im = Image::make($file);
            $im->orientate();
            $im->resize(300, null, function ($constraint) {
				$constraint->aspectRatio();
			});
            $im->save('assets/front/content/' . $image);

            @unlink('assets/front/content/' . $post->image);

            $post->link = $image;

        }

//die(print_r($post));
//echo "here";
//$contentstring = (string)trim($post->post_content);
//echo $post->content.",".$request->post_content.",";
//die(str_replace($post->post_content ,trim($request->post_content),$post->scrabingcontent));

        //$post->title = $request->post_title;
		//echo $post->content.",".$request->post_content.",".$post->scrabingcontent;
		$post->scrabingcontent = str_replace('<p class="linkContent">'.$post->content.'</p>','<p class="linkContent">'.$request->post_content.'</p>',$post->scrabingcontent);
        $post->content = $request->post_content;

//echo "here2";


		//die($post->scrabingcontent);

         Post::UpdatePostTopics($topics, $post->id);
 
      



         $post->save(); 

        return redirect()->route('feed')->withSuccess('Post Updated Successfully.');

    }

    public function commentStore(Request $request)
    {

        $request->validate([
            'comment_post' => 'required|numeric'
        ]);

        $comCom = $request->comment_comment;

        if ($comCom == "") {
            $comCom = 0;
        }

        $post = Post::find($request->comment_post);

        if (!$post) return response()->json(['success' => 0]);

        $user = Auth::user();

        $commenters = Comment::where('post_id', $post->id)->pluck('user_id');

        if (isset($request->type) && ($request->type == 'image' || $request->type == 'doc')) {

          //  $request->validate([
           //     'file' => 'required'
           // ]);

           // $file = $request->file('file');


		   /////////////////////////////////////
		   $request->validate([
                'link' => 'required'
            ]);


		   ///////////////////////////////////////


            //Extension Check

            //$allowed = ['jpg', 'jpeg', 'png', 'gif'];
            //if (!checkExt($file->getClientOriginalExtension(), $allowed)) {
            //    return response()->json(['error' => 'Only ' . implode(', ', $allowed) . ' files are allowed']);
           // }
            // Extension Check

            //$link = $file->hashName();
			$link = $request->link;


			$imageType = (($request->type == 'image' )?'image':'doc');

            //$im = Image::make($file);
            //$im->orientate();
            //$im->save('assets/front/content/' . $link);

            $comment = Comment::create([
                'user_id' => $user->id,
                'post_id' => $request->comment_post,
                'link' => $link,
                'type' => $imageType,
                'comment_id' => $comCom
            ]);

        } elseif (isset($request->type) && $request->type == 'youtube') {
            $request->validate([
                'link' => 'required'
            ]);
            $comment = Comment::create([
                'user_id' => $user->id,
                'post_id' => $request->comment_post,
                'link' => $request->link,
                'type' => 'youtube',
                'comment_id' => $comCom
            ]);
        } elseif (isset($request->type) && $request->type == 'vimeo') {
            $request->validate([
                'link' => 'required'
            ]);
            $comment = Comment::create([
                'user_id' => $user->id,
                'post_id' => $request->comment_post,
                'link' => $request->link,
                'type' => 'vimeo',
                'comment_id' => $comCom
            ]);
        } else {


			if ($request->urldataval != '') {
                //$titel = '<a class="postTitle" href="' . $request->hrefurl . '" target="_blank" >' . $request->article . '</a>';
				$titel = '' . str_replace($request->hrefurl,'',$request->comment_content) . '';
                $scrabingcontent = '<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">';
				$scrabingcontent .= '<p class="linkContent">' . $titel . '</p>';
                $scrabingcontent .= $request->urldataval;

                $scrabingcontent .= '</div>';




				$comment = Comment::create([
					'user_id' => $user->id,
					'post_id' => $request->comment_post,
					'content' =>  $scrabingcontent,
					'comment_id' => $comCom
				]);

            } else {

				$request->validate([
					'comment_content' => 'required'
				]);
				$comment = Comment::create([
					'user_id' => $user->id,
					'post_id' => $request->comment_post,
					'content' => htmlentities($request->comment_content),
					'comment_id' => $comCom
				]);
			}
        }

        if (!$comment) return response()->json(['success' => 0]);




		//look for @ mentions


		$mention_regex = '([@][\w_-]+)'; //mention regrex to get all @texts


			preg_match_all($mention_regex, $request->comment_content, $matches);
			if(is_array($matches))
			{
				$updatedContent = ((isset($scrabingcontent))?$scrabingcontent:$request->comment_content) ;

				foreach($matches[0] as $match)
				{


					if($userMention = User::where('username',str_replace('@','',$match))->first())
					{
						$mention = Mention::create([
							'user_id' => $userMention->id,
							'type' => 'comment',
							'type_id' => $comment->id,

						]);

						$notify[] = [
							'post_id' => $comment->id,
							'to_id' => $userMention->id,
							'by_id' => Auth::user()->id,
							'type' => 'userTagComment'
						];


						$updatedContent = str_replace($match,'<a href="/profile/'.$userMention->username.'">'.$userMention->name.'</a>',$updatedContent);







					}
				}

				$updatecomment = Comment::find($comment->id);
				 $updatecomment->content = $updatedContent;

				 $updatecomment->save();

				//if(isset($notify))
				//	$post->notifies()->createMany($notify);

			}


		////////////////////




        // Notification
        $notify = [];

        foreach ($commenters as $commenter) {

            if (! $user->isBlockedByMe($commenter)) {
                $notify[] = [
                    'type' => 'comment',
                    'to_id' => $commenter,
                    'by_id' => $user->id
                ];
            }

        }

      /*  $notify[] = [
                    'type' => 'comment',
                    'to_id' => $post->user_id,
                    'by_id' => $user->id
                ];*/
        
		//die(print_r($notify));
		$post->notifies()->createMany($notify);


        $notifystatus = User::where('id', $post->user_id)->first();

        if ($notifystatus->notifystatus != "")
            $notify_Array = explode(",", $notifystatus->notifystatus);
        else
            $notify_Array = array();

        if (($notifystatus->notifystatus == "") || in_array("4", $notify_Array)) {

            if (!$user->isBlockedByMe($post->user_id)) {


                $notify = Notify::create([
                    'post_id' => $request->comment_post,
                    'to_id' => $post->user_id,
                    'type' => 'comment',
                    'by_id' => $user->id,
                    'status' => '0'
                ]);

            }

        }

        //return response()->json([
        //    'success' => 1
        //]);

		return redirect()->back()->withSuccess('Comment Successful');

    }

    public function processLike(Request $request)

    {

        $request->validate([
            'post_id' => 'required|numeric'
        ]);

        $post = Post::find($request->post_id);

        if (!$post) return response()->json(['success' => 0,'message'=>'no post id']);

        $user = Auth::user();

        $like = Like::where(['post_id' => $request->post_id, 'user_id' => $user->id])->first();

        if ($like)
		{
			$like->delete();
			return response()->json(['success' => 0,'user_id'=>$user->id]);
		}

        $like = Like::create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        if ($user->id != $post->user_id) {

            $post->notifies()->create([
                'to_id' => $post->user_id,
                'type' => 'like',
                'by_id' => $user->id
            ]);
        }

        return response()->json(['success' => 1]);

    }


	public function processDislike(Request $request)

    {

        $request->validate([
            'post_id' => 'required|numeric'
        ]);

        $post = Post::find($request->post_id);

        if (!$post) return response()->json(['success' => 0,'message'=>'no post id']);

        $user = Auth::user();

        $like = Dislike::where(['post_id' => $request->post_id, 'user_id' => $user->id])->first();

        if ($like) return response()->json(['success' => 0,'user_id'=>$user->id]);

        $like = Dislike::create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        if ($user->id != $post->user_id) {

            $post->notifies()->create([
                'to_id' => $post->user_id,
                'type' => 'dislike',
                'by_id' => $user->id
            ]);
        }

        return response()->json(['success' => 1]);

    }


	public function processFavorite(Request $request)

    {

        $request->validate([
            'post_id' => 'required|numeric'
        ]);

        $post = Post::find($request->post_id);

        if (!$post) return response()->json(['success' => 0,'message'=>'no post id']);

        $user = Auth::user();

        $like = Favorite::where(['post_id' => $request->post_id, 'user_id' => $user->id])->first();

        if ($like)
		{
			$like->delete();
			return response()->json(['success' => 0,'user_id'=>$user->id]);
		}
        $like = Favorite::create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        if ($user->id != $post->user_id) {

            $post->notifies()->create([
                'to_id' => $post->user_id,
                'type' => 'favorite',
                'by_id' => $user->id
            ]);
        }

        return response()->json(['success' => 1]);

    }

    public function processFollow(Request $request, $username)

    {
		
		

        $user = User::where('username', $username)->first();

        if (!$user) return redirect()->route('front');

        $me = Auth::user();

        $follow = Follow::where(['by' => $me->id, 'followed' => $user->id])->first();

        if ($follow) {

            $follow->delete();

            return redirect()->back()->withSuccess('Unfollow Successfull');

        } else {
			

            $follow = Follow::create([
                'by' => $me->id,
                'followed' => $user->id
            ]);


            $notifystatus = User::where('id', $user->id)->first();

            if ($notifystatus->notifystatus != "")
                $notify_Array = explode(",", $notifystatus->notifystatus);
            else
                $notify_Array = array();


            
			$from = $me;
			$to = $user;
			
			$text = '';
			
			Mail::send([], [], function($message2) use($to, $from, $text)
			{

				$emailMessage = "<a href='https://".$_SERVER['SERVER_NAME']."/profile/".$from->username."'><img src='".asset('assets/front/img/' . $from->avatar)."'></a><br>".$from->name." is now following you!";

				$message2->from('no-reply@agwiki.com', "AgWiki");
				$message2->subject("AgWiki - New User Following: ".$from->name);
				$message2->setBody($emailMessage, 'text/html');
				$message2->to('brandon@eatkidfriendly.com');//$message2->to($to->email);
			});
			
			
			
			if ($follow) {
                if (($notifystatus->notifystatus == "") || in_array("1", $notify_Array)) {
                    $notify = Notify::create([
                        'to_id' => $user->id,
                        'type' => 'follow',
                        'by_id' => $me->id
                    ]);
                    return redirect()->back()->withSuccess('Follow Successful');
                }
            }
			
			

        }

        return redirect()->back()->withErrors('Unexpected Error!');

    }

    public function processBlock(Request $request, $username)

    {

        $user = User::where('username', $username)->first();

        if (!$user) return redirect()->route('front');

        $me = Auth::user();

        $block = Block::where(['by_id' => $me->id, 'blocked_id' => $user->id])->first();

        if ($block) {

            $block->delete();

            return redirect()->back()->withSuccess('Unblocked Successfull');

        } else {

            $block = Block::create([
                'by_id' => $me->id,
                'blocked_id' => $user->id
            ]);

            if ($block) return redirect()->back()->withSuccess('Blocked Successfull');

        }

        return redirect()->back()->withErrors('Unexpected Error!');

    }

    public function processShare(Request $request)

    {

        $request->validate([
            'post_id' => 'required|numeric'
        ]);

        $post = Post::find($request->post_id);

        if (!$post) return response()->json(['success' => 0]);

        $user = Auth::user();

		if($request->group_id)
		{

			$checkShare = Share::where('user_id', $user->id)->where('post_id',$post->id)->where('group_id',$request->group_id)->count();
			if ($checkShare>0) return response()->json(['success' => 0]);
			
			$oldpost = $post;
			
			
			if($oldpost->type == 1  ) {
				 $post = Post::create([
					'user_id' => Auth::user()->id,
					'content' => $oldpost->scrabingcontent,
					'link' => $oldpost->link,
					'type' => $oldpost->type,
					'group_id' => $request->group_id
				]);        
			} 
			else {
				$post = Post::create([
					'user_id' => Auth::user()->id,
					'content' => $oldpost->content,
					'scrabingcontent' => $oldpost->scrabingcontent,
					'link' => $oldpost->link,
					'type' => $oldpost->type,
					'group_id' => $request->group_id
				]);    
			}
			
				
			
			

			$share = Share::create([
				'user_id' => $user->id,
				'post_id' => $post->id,
				'group_id' => $request->group_id
			]);
		}
		else
		{


			$checkShare = Share::where('user_id', $user->id)->where('post_id',$post->id)->count();
			if ($checkShare>0) return response()->json(['success' => 0]);

			$share = Share::create([
				'user_id' => $user->id,
				'post_id' => $post->id
			]);
		}

        if (!$share) return response()->json(['success' => 0]);

        if ($user->id != $post->user_id) {

            $post->notifies()->create([
                'to_id' => $post->user_id,
                'type' => 'share',
                'by_id' => $user->id
            ]);
        }

        return response()->json([
            'success' => 1
        ]);

    }

    public function postReport(Post $post)

    {

        if (!$post) return redirect()->back();

        $page_title = 'Report Submit';

        return view('report', compact('post', 'page_title'));

    }

    public function postReportStore(Request $request, Post $post)

    {

        if (!$post) return redirect()->route('front')->withErrors('Post Not Found.');

        $request->validate([
            'report_content' => 'required'
        ]);

        $user = Auth::user();

        $report = Report::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'type' => 'post',
            'content' => $request->report_content
        ]);

        if ($report) {
            $post->report = $post->report + 1;
            $post->save();
            return redirect()->route('front')->withSuccess('Report Submitted Successfully.');
        }

        return redirect()->back()->withErrors('Unexpected Error. Please Try Again');

    }

    public function processNotify(Notify $notify)

    {

        if (!$notify || !$notify->post || $notify->to_id != Auth::user()->id) return redirect()->back();

        return redirect()->route('user.post.single', $notify->post_id);

    }

    public function notifications()

    {

        $page_title = 'Notifications';
        $user = Auth::user();
        $notifications = Notify::where('to_id', $user->id)->orderBy('id', 'DESC')->paginate(10);

        return \view('notifications', compact('page_title', 'notifications'));

    }

    function following()

    {

        $page_title = 'You Are Following Those People';
        $user = Auth::user();

        $followings = Follow::where('by', $user->id)->paginate(10);

        $users = [];

        foreach ($followings as $followed) {
            $fo = User::find($followed->followed);

            if ($fo) $users[] = $fo;
        }

        $records = $followings;

        return \view('follow-users', compact('page_title', 'users', 'user', 'records'));

    }

    /*Chnage by dines start*/
    public function GeturllinkData(Request $request)
    {
		
		if(isset($_POST['image']))
		{
			//die($_POST['image']);
			
			$image = $_POST['image'];
			
			$opts = array('http' => array(
			  'method' => "GET",
			  'timeout' => 10,
			  'header' => array(
				"user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36",
			  )
			));
			$context = stream_context_create($opts);
			
			//die($image);

            if($imgcnt = @file_get_contents($image,FALSE, $context))
			{
				$ext = pathinfo(
				parse_url($image, PHP_URL_PATH), 
				PATHINFO_EXTENSION
				); 
				
				if($ext=='') $ext = 'jpg';
				
				//die($ext);
				
				
				//if($ext == 'gif' || $ext == 'png' || $ext == 'jpg'|| $ext == 'jpeg' )
				//{
            		$path = 'assets/front/content/' . time() . 'image.' . $ext;//substr($image, -3);
					
					@file_put_contents($path, $imgcnt);
					
					$tw_image = explode('/content/',$path);
			
					// open file a image resource
					$img = Image::make($path);
					
				
					$img->resize(600, null, function ($constraint) {
						$constraint->aspectRatio();
					});

					//die($tw_image[1]);

					$ext = pathinfo(
						parse_url(@$tw_image[1], PHP_URL_PATH), 
						PATHINFO_EXTENSION
						); 

					//if($ext == 'gif' || $ext == 'png' || $ext == 'jpg'|| $ext == 'jpeg' )
								$img->save('assets/front/content/twitter_'.@$tw_image[1] );
					
				
				//}
				
				
				die('https://'.$_SERVER['SERVER_NAME'].'/'.$path);
				
			}
			
			
			die('fail');
			
			
		}
		
		if (in_array("Content-Type: application/pdf", get_headers($request->urllink))) {
			exit;
		}

        $tags = @get_meta_tags($request->urllink);
		//die(print_r($tags));

        if (isset($tags['twitter:image']) && $tags['twitter:image'] != '') {
            $image = $tags['twitter:image'];
        } else if (isset($tags['og:image']) && $tags['og:image'] != '') {
            $image = $tags['og:image'];
        } else if (isset($tags['image']) && $tags['image'] != '') {
            $image = $tags['image'];
        } else {
            $image = "";
        }

        if (isset($tags['twitter:title']) && $tags['twitter:title'] != '') {
            $title = $tags['twitter:title'];
        } else if (isset($tags['og:title']) && $tags['og:title'] != '') {
            $title = $tags['og:title'];
        } else if (isset($tags['title']) && $tags['title'] != '') {
            $title = $tags['title'];
        } else {
            $title = "";
        }

        if (isset($tags['twitter:description']) && $tags['twitter:description'] != '') {
            $description = $tags['twitter:description'];
        } else if (isset($tags['og:description']) && $tags['og:description'] != '') {
            $description = $tags['og:description'];
        } else if (isset($tags['description']) && $tags['description'] != '') {
            $description = $tags['description'];
        } else {
            $description = "";
        }




		 $html = new \DOMDocument();

			$ch = curl_init();
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
			curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com');
			curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
		
			curl_setopt($ch, CURLOPT_NOBODY, false); 
		
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			curl_setopt($ch, CURLOPT_COOKIESESSION, true);
		
		
		
			curl_setopt($ch, CURLOPT_COOKIE, '__cfduid=d8af70c3b49361a5a1b818e91171e598d1431355518; cf_clearance=5857af9797c612cde4ac590fe900e0e9f3d7098f-1431355526-57600; PHPSESSID=eefc5d29f6cea1ddb70ca5a0baaf60e1');
			//$cookie_jar = tempnam('/tmp','cookie'); 
			//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar); 
		
			$headervar = array(
						'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
						'Accept-Language: en-US,en;q=0.5',
						'Connection: keep-alive',
						'Upgrade-Insecure-Requests: 1',
				);
		
			//curl_setopt($ch, CURLOPT_HEADER, false); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headervar); 
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 
		
			curl_setopt($ch,CURLOPT_URL,$request->urllink);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36");
			$data = curl_exec($ch);
			curl_close($ch);
			@$html->loadHTML($data);

			/*$opts = array('http' => array(
			  'method' => "GET",
			  'timeout' => 10,
			  'header' => array(
				"user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36",
			  )
			));
			$context = stream_context_create($opts);
			@$html->loadHTML(file_get_contents($request->urllink,FALSE, $context));
			*/

			//@$html->loadHTML(file_get_contents($request->urllink));

			//Get all meta tags and loop through them.
			//print_r($html->getElementsByTagName('meta'));
		//die("image 2".$image);

		//die(print_r($html));
		
		//die(print_r($html->getElementsByTagName('meta')));

			foreach($html->getElementsByTagName('meta') as $meta) {
				if ($meta->hasAttribute('property') && 	strpos($meta->getAttribute('property'), 'og:') === 0) {
					if($meta->getAttribute('property')=='og:image' && $image==''){
						$image = $meta->getAttribute('content');
					}
					if($meta->getAttribute('property')=='og:title'){
						$title = $meta->getAttribute('content');
					}
					elseif($meta->getAttribute('property')=='title')
						$title = $meta->getAttribute('content');


					if($meta->getAttribute('property')=='og:description'){
						$description = $meta->getAttribute('content');

					}
					elseif($meta->getAttribute('property')=='content')
						$description = $meta->getAttribute('content');


				}

				if ($meta->hasAttribute('property') && 	strpos($meta->getAttribute('property'), 'twitter:') === 0) {
					if($meta->getAttribute('property')=='twitter:image' && $image==''){
						$image = $meta->getAttribute('content');
					}
					if($meta->getAttribute('property')=='twitter:title'){
						$title = $meta->getAttribute('content');
					}
					elseif($meta->getAttribute('property')=='title')
						$title = $meta->getAttribute('content');


					if($meta->getAttribute('property')=='twitter:description'){
						$description = $meta->getAttribute('content');

					}
					elseif($meta->getAttribute('property')=='content')
						$description = $meta->getAttribute('content');


				}
			}

			//die('desc ' .$description);
		//die("image 3".$image);
		if($title=='')
		{
			$list = $html->getElementsByTagName("title");
			if ($list->length > 0) {
				$title = $list->item(0)->textContent;
			}
		}
		
		$replace = array(
				"‘" => "'",
				"’" => "'",
				"”" => '"',
				"“" => '"',
				"–" => "-",
				"—" => "-",
				"…" => "&#8230;"
			);
		
		foreach($replace as $k => $v)
		{
			$title = str_replace($k, $v, $title);
		}

		 $title = preg_replace('/[^\x20-\x7E]*/','', $title);

     
		
		//$title = str_replace($search, $replace, $title);
		
		//die('image'.$image);

		if($image=='')//at this point, let's look for the logo
		{
			$list = $html->getElementsByTagName("img");
			if ($list->length > 0) {
				foreach ($list as $images) {
				  $checkImage = $images->getAttribute('src');
					if(strstr(strtolower($checkImage),'logo'))
					{
						$image = $images->getAttribute('src');
						//exit();
					}
				}

				//$image = $list->item(0)->getAttribute('src');
			}
		}

		$image = urldecode($image);
		
		//die(urldecode($image));
        $html = '<div >';




		if(strstr($image,'https://') || strstr($image,'http://'))
		{

		}
		else
		{
			$urlresult = parse_url($request->urllink);
			$image = $urlresult['scheme']."://".$urlresult['host'].'/'.$image;
			//die($image);
			

		}

		$headers = get_headers($image, 1);

		//die(print_r($headers));
		//die($image);
        if (@is_array(getimagesize($image)) && $image != '' && $image !='http://' && $image !='https://' && (strstr($headers[0],'OK') || strstr($headers[0],'301'))  ) { //$headers[0] == 'HTTP/1.1 200 OK'



			$opts = array('http' => array(
			  'method' => "GET",
			  'timeout' => 10,
			  'header' => array(
				"user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36",
			  )
			));
			$context = stream_context_create($opts);
			
			//die($image);

            if($imgcnt = @file_get_contents($image,FALSE, $context))
			{
				$ext = pathinfo(
				parse_url($image, PHP_URL_PATH), 
				PATHINFO_EXTENSION
				); 
				
				//die($ext);
				
				
				if($ext == 'gif' || $ext == 'png' || $ext == 'jpg'|| $ext == 'jpeg' )
				{
            		$path = 'assets/front/content/' . time() . 'image.' . $ext;//substr($image, -3);
					
					@file_put_contents($path, $imgcnt);
					
					$tw_image = explode('/content/',$path);
			
					// open file a image resource
					$img = Image::make($path);
					
					//	die($path);

					// crop image
					//$img->crop(120, 120, 25, 25);
					//$img->resize(120, null);

					$img->resize(600, null, function ($constraint) {
						$constraint->aspectRatio();
					});

					//die($tw_image[1]);

					$ext = pathinfo(
						parse_url($tw_image[1], PHP_URL_PATH), 
						PATHINFO_EXTENSION
						); 

					if($ext == 'gif' || $ext == 'png' || $ext == 'jpg'|| $ext == 'jpeg' )
								$img->save('assets/front/content/twitter_'.$tw_image[1] );
					
				
				}
				else
				{
					
					
					$path = $image;
					
					@file_put_contents($path, $imgcnt);
					
					$img = Image::make($path);

				
					$img->resize(600, null, function ($constraint) {
						$constraint->aspectRatio();
					});

					

					$ext = pathinfo(
						parse_url($path, PHP_URL_PATH), 
						PATHINFO_EXTENSION
						); 
					//die($ext);
					//die($path);
					if($ext == 'gif' || $ext == 'png' || $ext == 'jpg'|| $ext == 'jpeg' )
								$img->save('assets/front/content/twitter_'.$path );
					
				}
				
				
				
			}
			else
			{
				$path = $image;
				//die($path);
			}
			
			//die($path);
			
			

				//https://www.sciencedaily.com/releases/2019/04/190410095939.htm
           // if(file_exists($image))
			//if(@is_array(getimagesize($image)))
			//die($image . ' - '.$path);
            	$html .= '<p class="article-img"><a href="/ajaxpage?url=' . $request->urllink . '" rel="modal:open"><img src="'.((strstr($path,'https://') || strstr($path,'http://'))?$path:'/' . $path ). '" style="width:100%;"></a></p>';
        }
        $html .= '</div>';
        $html .= '<div >';
        if ($title != '') {
            $html .= '<a href="/ajaxpage?url=' . $request->urllink . '" rel="modal:open"><h2>' . $title . '</h2>';
        }
        if ($description != '') {
            $html .= '<p>' . substr($description, 0, 90) . '...</p>';
        }
        $html .= '</a></div>';
        echo $html . '!~' . $request->urllink;
        exit();
    }

    /*Chnage by dines end*/

    public function passwordChange()

    {

        $page_title = 'Change Password';

        return \view('password-change', compact('page_title'));

    }

    public function passwordUpdate(Request $request)

    {

        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        if (Hash::check($request->current_password, $user->password)) {

            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->withSuccess('Password Updated Successfully');

        }

        return redirect()->back()->withErrors('Current Password Not Matched');

    }


}
