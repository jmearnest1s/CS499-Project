<?php

namespace App\Http\Controllers;


use App\General;
use App\User;
use App\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Share;
use App\Follow;
use Image;
use Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $gnl = General::first();
        $this->sitename = $gnl->title;
    }
	
	public function userDelete(Request $request)

    {

        $user = User::find($request->id);    
		
		$user->views()->delete();
        $user->posts()->delete();
        $user->comments()->delete();
        $user->notifications()->delete();
        
		
		$user->delete();
		return back()->with('success', 'User Deleted');

    }
    
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Users';
        return view('admin.users.index', compact('users', 'data'));
    }

    public function userSearch(Request $request)
    {
        $this->validate($request,
            [
                'search' => 'required',
            ]);
        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Search Results';

        $users = User::where('username', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%')->orWhere('name', 'like', '%' . $request->search . '%')->get();

        return view('admin.users.search', compact('users', 'data'));

    }

    public function single($id)
    {
        $user = User::findorFail($id);
        $last_login = UserLogin::whereUser_id($user->id)->orderBy('id','desc')->first();
        $data['sitename'] = $this->sitename;
        $data['page_title'] = $user->name;
    	return view('admin.users.single', compact('data', 'user', 'last_login'));
    }

    public function loginLogs($user = 0)

    {

        $user = User::find($user);

        if ($user) {

            $logs = UserLogin::where('user_id', $user->id)->orderBy('id', 'DESC')->paginate(10);
            $page_title = 'Login Logs Of ' . $user->name;


            $data['sitename'] = $this->sitename;
            $data['page_title'] = 'Login Logs Of ' . $user->name;

        } else {

            $logs = UserLogin::orderBy('id', 'DESC')->paginate(10);
            $page_title = 'User Login Logs';
            $data['sitename'] = $this->sitename;
            $data['page_title'] = 'User Login Logs';

        }

        return view('admin.users.login-logs', compact('logs', 'page_title', 'data'));

    }

     public function email($id)
    {
        $user = User::findorFail($id);
        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Send Email';
        return view('admin.users.email',compact('user', 'data'));
    }

    public function sendemail(Request $request)
    {
         $this->validate($request,
            [
                'emailto' => 'required|email',
                'reciver' => 'required',
                'subject' => 'required',
                'emailMessage' => 'required'
            ]);
         $to = $request->emailto;
         $name = $request->reciver;
         $subject = $request->subject;
         $message = $request->emailMessage;

         send_email($to, $name, $subject, $message);

        return back()->withSuccess('Mail Sent Successfuly');

    }

     public function broadcast()
    {

        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Broadcast Email';
        return view('admin.users.broadcast', compact('data'));
    }

    public function broadcastemail(Request $request)
    {
        $this->validate($request,
            [
                'subject' => 'required',
                'emailMessage' => 'required'
            ]);

        $users = User::where('status', '1')->get();

        foreach ($users as $user)
        {

         $to = $user->email;
         $name = $user->name;
         $subject = $request->subject;
         $message = $request->emailMessage;

         send_email($to, $name, $subject, $message);
        }

        return back()->withSuccess('Mail Sent Successfuly');
    }
	
	public function newsletter()
    {

        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Broadcast Newsletter';
        return view('admin.users.newsletter', compact('data'));
    }
	
	
	public function unsubscribeNewsletter($unsubscribe)
    {
		
		if($user = User::where('username', base64_decode($unsubscribe))->first())
		{
			

			//die(print_r($user));

			$user->newsletter = 0;
			$user->save();


			//return redirect('/')->withSuccess('Profile Updated Successfully');
			return redirect('/feed')->with('success', 'Profile Updated Successfully');
		}
		else	
			return redirect('/feed')->with('success', 'User not found');
	}
	
	
	
	public function broadcastNewsletter(Request $request)
    {
       /* $this->validate($request,
            [
                'header' => 'required',
                'footer' => 'required'
            ]);
			*/

        //$users = User::where('status', '1')->get();
		//$users = User::where('id', 1611)->get();
		$users = User::where('email', $request->email)->get(); 
		
		//die(print_r($users));

        foreach ($users as $user)
        {

         $to = $user->email;//'blance@blayzer.com';
         $name = $user->name;
         $subject = "World Food Topics: ";//Farmer stress, U.S.-China Trade War & more
         $message = $request->emailMessage;
			
			
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
			
			//////////////////////////////
			//die(print_r($shares));

         send_newsletter($to, $name, $subject,  $shares[0],$shares[1], $shares[2], $shares[3],base64_encode($user->username));
        }

        return back()->withSuccess('Mail Sent Successfuly');
    }

    public function userPasschange(Request $request,$id)
    {
         $user = User::find($id);

        $this->validate($request,
            [
            'password' => 'required|string|min:6|confirmed'
            ]);
        if($request->password == $request->password_confirmation)
            {
                $user->password = bcrypt($request->password);
                $user->save();

                $msg =  'Password Changed By Admin. New Password is: '.$request->password;
                send_email($user->email, $user->username, 'Password Changed', $msg);
                $sms =  'Password Changed By Admin. New Password is: '.$request->password;
                send_sms($user->mobile, $sms);

                return back()->with('success', 'Password Changed');
            }
            else 
            {
                return back()->with('alert', 'Password Not Matched');
            }
    }

   
    public function statupdate(Request $request,$id)
    {
        $user = User::find($id);

        $this->validate($request,
            [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            //'mobile' => 'required|string|max:255',
            ]);
		$olduser_status = $user['status'];

        $user['name'] = $request->name ;
        $user['mobile'] = $request->mobile;
        $user['email'] = $request->email;
        $user['status'] = $request->status =="1" ?1:0;
        $user['emailv'] = $request->emailv =="1" ?1:0;
        $user['smsv'] = $request->smsv =="1" ?1:0;

        $user->save();

        $msg =  '
		<br>
		Welcome to AgWiki. <Br><br>

		Your account has been activated! Please be sure to check out Groups; and consider inviting others to join AgWiki.<Br><br>

		You are now a member of a social environment where farmers, ranchers, nutritionists, scientists and others across the global food chain come together to discover solutions related to food production. Good luck to us all. It\'s a worthy mission.
<Br><br>
		Sincerely,<Br><br>

		Randy Krotz<Br>
		CEO, Co-Founder<br> 

		';
       // notify($user, 'AgWiki Account Approved', $msg);
		
		if($olduser_status == 0 && $request->status == 1)
		{
		
			$data = array('to'=>$user['email']);
			Mail::send('emails.approved', $data, function($message) use ($data)
			{
				$message->from('no-reply@agwiki.com', "Agwiki");
				$message->subject("AgWiki Account Approved");
				$message->to([$data['to']]);
			});
		}

        return back()->withSuccess('User Profile Updated Successfuly');
    }

    public function banusers()
    {
        $users = User::where('status', '0')->orderBy('id', 'desc')->paginate(10);
        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Banned User';
        return view('admin.users.banned', compact('users', 'data'));
    }

    public function profileVerifiedUsers()

    {

        $users = User::where('verified', 1)->orderBy('id', 'DESC')->paginate(10);
        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Profile Verified Users';

        return view('admin.users.verified', compact('users', 'data'));

    }

    public function profileVerifyRequest()

    {

        $users = User::where('verified', -1)->paginate(10);
        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Request For Profile Verify';

        return view('admin.users.verify-request', compact('users', 'data'));

    }

    public function profileVerifyRequestAction(Request $request)

    {

        $request->validate([
           'id' => 'required|numeric'
        ]);

        $user = User::find($request->id);

        if (! $user) return redirect()->back()->withErrors('Bad Request');

        $user->verified = 1;
        $user->save();

        $text = 'Congratulation, Your Profile Verified Successfully.';
        notify($user, 'Profile Verified', $text);

        return redirect()->back()->withSuccess('Profile Verified Successfully');

    }

    public function profileVerifyRequestCancel(Request $request)

    {

        $request->validate([
           'id' => 'required|numeric'
        ]);

        $user = User::find($request->id);

        if (! $user) return redirect()->back()->withErrors('Bad Request');

        $user->verified = 0;
        $user->save();

        return redirect()->back()->withSuccess('Request Canceled Successfully');

    }

    public function superUser()

    {

        $user = User::find(9);
        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Edit Superuser';

        return view('admin.users.super', compact('data', 'user'));

    }

    public function updateSuperUser(Request $request)

    {

        $user = User::find(9);

        $request->validate([
            'name' => 'required|string',
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
            'workplace' => 'required',
            'gender' => 'required|in:MALE,FEMALE',
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->position = $request->position;
        $user->quote = $request->quote;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->work = $request->work;
        $user->workplace = $request->workplace;
        $user->gender = $request->gender;
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->password) {
            $request->validate([
                'password' => 'required|min:6|confirmed'
            ]);

            $user->password = Hash::make($request->password);

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
            $im->resize(380, 295);
            $im->save('assets/front/img/' . $avatar);
            if ($user->avatar != 'male.png' && $user->avatar != 'female.png') {
                @unlink('assets/front/img/' . $user->avatar);
            }
            $user->avatar = $avatar;
        }

        $user->save();

        return redirect()->back()->withSuccess('Profile Updated Successfully');

    }

}
