<?php

namespace App\Http\Controllers\Auth;

use App\General;
use App\Trx;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Follow;
use Illuminate\Http\Request;
use App\Interest;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function registerViaReferral($username)

    {
		
		$interest = Interest::all()->sortBy('name');

        $user = User::where('username', $username)->first();

        if (! $user) return redirect()->back();

        return view('auth.register', compact('user','interest'));

    }

	
	public function register(Request $request)
	{
		$this->validator($request->all())->validate();
	
		event(new Registered($user = $this->create($request->all())));
	
		//$this->guard()->login($user);
	
		//return $this->registered($request, $user)
			//			?: redirect($this->redirectPath());
			
		        return redirect()->back()->with('message', 'Successfully created a new account.');
				 //Please check your email and activate your account.

	}

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            //'name' => 'required|string|max:255',
           // 'gender' => 'required|in:MALE,FEMALE',
           // 'birthday' => 'required|date_format:"m-d-Y"',
            'email' => 'required|string|email|max:255|unique:users',
           // 'username' => 'required|string|max:255|unique:users',
           // 'mobile' => 'required',
            'password' => 'required|string|min:6'//|confirmed',
            //'tap' => 'required',
        ], [
            'tap.required' => 'You must agree with our Terms And Policy.'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request)
    {

        $gnl = General::first();
		
		if(User::where('email', $request->email)->first())
				return redirect('/login')->with('message', 'User is already registered, please login');
		
		// mautic/////////////////////////////////////////////////////
		$email = $request->email;
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
		
		
		
		/*Mail::send('emails.welcome', $data, function($message) use ($data)
		{
			$message->from('no-reply@agwiki.com', "Agwiki");
			$message->subject("Welcome to Agwiki");
			$message->to($data['email']);
		});*/
		//flash()->success('Thanks for creating an account - please check your email!', $successmessage);
       
	   $email_hash = explode('@',$request->email);
	   $code = str_random(8);
	   $placeHoldername = $email_hash[0].'-'.rand(1, 999);
		if($request->field_name == '')
	    {
		   $user = User::create([
				'name' => $placeHoldername,
			   // 'gender' => $data['gender'],
			   // 'birthday' => Carbon::createFromFormat('m-d-Y', $data['birthday']),
			   // 'avatar' => ($data['gender'] == 'MALE')?'male.png':'female.png',
				'email' => $request->email,
				'password' => ((isset($request->password))?Hash::make($request->password):Hash::make("AD1234")),
			   //'password' => Hash::make("AD1234"),
				//'mobile' => $data['mobile'],
				'username' => $placeHoldername,//$data['email'],//'username' => $data['username'],
				'emailv' => 1,//$gnl->emailver,
				'vercode' => $code ,
				'smsv' => $gnl->smsver
			]);
		}
		else
			return redirect('/login')->with('message', 'Invalid Request');
		
		//app('App\Http\Controllers\FrontController')->sendemailver($request->email);
		//return $user;
		
		
			$to = "rpkrotz@agwiki.com";
			$message1 = "User Signup ".$request->email;
			$subject = 'New User For Approval';
			$headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			//mail($to, $subject, $message1, $headers);
		
			
			//$data = array('to'=>"rpkrotz@agwiki.com");
			$data = array('to'=>"rpkrotz@agwiki.com",'newuser'=>$request->email);
			Mail::send('emails.notifyadmin', $data, function($message) use ($data)
			{
				$message->from('no-reply@agwiki.com', "Agwiki");
				$message->subject("User Signup ".$data['newuser']);
				$message->to([$data['to']]);
			});
		
		
		Auth::login($user);
		
		
		
		
		
		
		
		
		
		
		
		
		//return redirect('emailverify?code='.$code)->with('message', 'Successfully created a new account. Please fill out all details');
    	
		if(session('link')!==null)
			return redirect(session('link'))->with('message', 'Successfully created a new account. Please fill out all details');
		else
			return redirect('feed')->with('message', 'Successfully created a new account. Please fill out all details');
    	
	}

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        
        $follow = Follow::where(['by' => $user->id, 'followed' => 9])->first();
        
        if(! $follow) {
            $follow = Follow::create([
                'by' => $user->id,
                'followed' => 9
            ]);
        }

        $ip = $request->ip();

        $r = file_get_contents('http://ip-api.com/json/' . $ip);
        $info = json_decode($r);

        if ($info->status && $info->status == 'success') {

            $user->country = $info->countryCode;
            $user->city = $info->city;
            $user->state = $info->region;
            $user->zip = $info->zip;

            $user->save();

        }

    }
}
