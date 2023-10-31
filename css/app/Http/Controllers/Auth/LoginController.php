<?php
namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\UserLogin;
use App\User;
use Carbon\Carbon;
use App\Follow;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
	//use Auth;
	
	
	
	/**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
		//die(print_r($user));
		//$provider = 'linkedin';
		
        $userSocial = Socialite::driver($provider)->user();		
		
		//die(print_r($userSocial));
		
		$users = User::where(['email' => $userSocial->getEmail()])->first();
		
		
				if($users){
					Auth::login($users);
					
					return redirect('/');
				}else{
					
					$email_hash = explode('@',$userSocial->getEmail());
					
					$code = str_random(8);
		
					$user = User::create([
						'name'          => $userSocial->getName(),
						'email'         => ($userSocial->getEmail()!='')?$userSocial->getEmail():$code."@somedomain.com",
						'username'         => $email_hash[0].'-'.rand(1, 999),//$userSocial->getEmail(),
						'avatar'         => $userSocial->getAvatar(),
						'password' =>	Hash::make($userSocial->getId().$userSocial->getName()),
						'provider_id'   => $userSocial->getId(),
						'provider'      => $provider,
						'vercode' => $code ,
						'status'		=> 1
					]);
					
					//die(print_r($user));
					
					////Auth::login($user);
					//app('App\Http\Controllers\FrontController')->sendemailver($userSocial->getEmail());
				 	////return redirect('/')->with('message', 'Please check email to verify account');
					//return redirect('login')->with('alert', 'Please check email to verify account');
					////return redirect('/');
					
					
					$users = User::where(['email' => $userSocial->getEmail()])->first();
					
					Auth::login($users);
					
					
					
					
					// mautic/////////////////////////////////////////////////////
					$email = $userSocial->getEmail();
					//die('email '.$email);

						$segment = 8;//master

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
					}
					else{

						//die($email);

						$url = 'https://mautic.agwiki.com/api/contacts/new';




						$options = array(
							'http' => array(
								'header'  => array("Content-type: application/x-www-form-urlencoded",
						"Authorization: Basic " . base64_encode("sitecontrol:flattir3")),
								'content' =>http_build_query(array('email'=>$email,'overwriteWithBlank' => true)),
								'Cache-Control: no-cache' ,
								'method' => 'POST'
							)
						);
						$context  = stream_context_create($options);

						$result = file_get_contents($url, false, $context);
						$contact = json_decode($result, true);

						//die(print_r($contact));

						if(isset($contact['contact']['id']))
						{
							$contact_id = $contact['contact']['id'];
						}

					}

					if(isset($contact_id))
					{

						//print_r($contact_id);

						$url = 'https://mautic.agwiki.com/api/segments/'.$segment.'/contact/'.$contact_id.'/add';
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

						//print_r($contact);

						$segment = 3;//non registred list
						$url = 'https://mautic.agwiki.com/api/segments/'.$segment.'/contact/'.$contact_id.'/add';
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



						$segment = 6;//no groups
						$url = 'https://mautic.agwiki.com/api/segments/'.$segment.'/contact/'.$contact_id.'/add';
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



						$segment = 7;//no topics
						$url = 'https://mautic.agwiki.com/api/segments/'.$segment.'/contact/'.$contact_id.'/add';
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
		
				/////////////////////////////////////////////////////////////////
					
					
					
					
					
					
					
					
					
					
					
					
					//return redirect('emailverify?code='.$code)->with('message', 'Successfully created a new account. Please fill out all details');
					
					if(session('link')!==null)
						return redirect(session('link'))->with('message', 'Successfully created a new account. Please fill out all details');
					else
						return redirect('feed')->with('message', 'Successfully created a new account. Please fill out all details');
				}
		
		

        // $user->token;
    }
	

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
   // protected $redirectTo = '/feed';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }
	
	
	public function login(Request $request)
	{
		$this->validate($request, [
			'username'    => 'required',
			'password' => 'required',
		]);
	
		$login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL ) 
			? 'email' 
			: 'email';//username
	
		$request->merge([
			$login_type => $request->input('username')
		]);
	
		if (Auth::attempt($request->only($login_type, 'password'),1)) {//$login_type
			//return redirect()->intended($this->redirectPath());
			return redirect('/feed');
		}
	
		return redirect()->back()
			->withInput()
			->withErrors([
				'login' => 'These credentials do not match our records.',
			]);
	} 
	
	public function showLoginForm()
	{
		session(['link' => url()->previous()]);
		//die(session('link'));
		return view('auth.login');
	}
	
	protected function redirectTo()
	{
		//if (\Session::has('userRequest')) {
		//	return route('request');
		//}
		//return redirect()->intended(session('link'));
		//return $this->redirectTo; // or any route you want.
		if(session('link')!==null)
			return session('link');
		else
			return 'feed';
	}


	
	

    public function authenticated(Request $request, $user)
    {

        if($user->status == 0){
            $this->guard()->logout();
            session()->flash('alert','Sorry Your Account is Blocked Now!');
            return redirect('/login');
        }
        
        $follow = Follow::where(['by' => $user->id, 'followed' => 9])->first();
        
        if(! $follow) {
            $follow = Follow::create([
                'by' => $user->id,
                'followed' => 9
            ]);
        }
		
		//return redirect(session('link'));

//        $ip = NULL; $deep_detect = TRUE;
//
//        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
//            $ip = $_SERVER["REMOTE_ADDR"];
//            if ($deep_detect) {
//                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
//                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
//                    $ip = $_SERVER['HTTP_CLIENT_IP'];
//            }
//        }
//        $xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$ip);
//
//        $country =  $xml->geoplugin_countryName ;
//        $city = $xml->geoplugin_city;
//        $area = $xml->geoplugin_areaCode;
//        $code = $xml->geoplugin_countryCode;
//
//        $user_agent     =   $_SERVER['HTTP_USER_AGENT'];
//        $os_platform    =   "Unknown OS Platform";
//        $os_array       =   array(
//            '/windows nt 10/i'     =>  'Windows 10',
//            '/windows nt 6.3/i'     =>  'Windows 8.1',
//            '/windows nt 6.2/i'     =>  'Windows 8',
//            '/windows nt 6.1/i'     =>  'Windows 7',
//            '/windows nt 6.0/i'     =>  'Windows Vista',
//            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
//            '/windows nt 5.1/i'     =>  'Windows XP',
//            '/windows xp/i'         =>  'Windows XP',
//            '/windows nt 5.0/i'     =>  'Windows 2000',
//            '/windows me/i'         =>  'Windows ME',
//            '/win98/i'              =>  'Windows 98',
//            '/win95/i'              =>  'Windows 95',
//            '/win16/i'              =>  'Windows 3.11',
//            '/macintosh|mac os x/i' =>  'Mac OS X',
//            '/mac_powerpc/i'        =>  'Mac OS 9',
//            '/linux/i'              =>  'Linux',
//            '/ubuntu/i'             =>  'Ubuntu',
//            '/iphone/i'             =>  'iPhone',
//            '/ipod/i'               =>  'iPod',
//            '/ipad/i'               =>  'iPad',
//            '/android/i'            =>  'Android',
//            '/blackberry/i'         =>  'BlackBerry',
//            '/webos/i'              =>  'Mobile'
//        );
//        foreach ($os_array as $regex => $value) {
//            if (preg_match($regex, $user_agent)) {
//                $os_platform    =   $value;
//            }
//        }
//        $browser        =   "Unknown Browser";
//        $browser_array  =   array(
//            '/msie/i'       =>  'Internet Explorer',
//            '/firefox/i'    =>  'Firefox',
//            '/safari/i'     =>  'Safari',
//            '/chrome/i'     =>  'Chrome',
//            '/edge/i'       =>  'Edge',
//            '/opera/i'      =>  'Opera',
//            '/netscape/i'   =>  'Netscape',
//            '/maxthon/i'    =>  'Maxthon',
//            '/konqueror/i'  =>  'Konqueror',
//            '/mobile/i'     =>  'Handheld Browser'
//        );
//        foreach ($browser_array as $regex => $value) {
//            if (preg_match($regex, $user_agent)) {
//                $browser    =   $value;
//            }
//        }
//        $user->login_time = Carbon::now();
//        $user->save();
//        $ul['user_id'] = $user->id;
//        $ul['user_ip'] = $ip;
//        $ul['location'] = $city.(" - $area - ").$country .(" - $code ");
//        $ul['details'] = $browser.' on '.$os_platform;
//        UserLogin::create($ul);
    }
}
