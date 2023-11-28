<?php

namespace App\Http\Controllers;

use App\Album;
use App\Comment;
use App\General;
use App\Group;
use App\Like;
use App\Interest;
use App\Post;
use App\Ads;
use App\Report;
use App\Share;
use App\User;
use App\View;
use App\rss_feeds;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

	public function __construct(){
		$Gset = General::first();
		$this->sitename = $Gset->title;
	}

	public function dashboard(){
		$data['sitename'] = $this->sitename;
		$data['page_title'] = 'DashBoard';
		$Gset = General::first();

        $total_users = User::count();
        $total_banned = User::where('status', 0)->count();
        $total_activate = User::where('status', 1)->count();
        $total_verified = User::where('verified', 1)->count();

        $total_posts = Post::count();
        $total_comments = Comment::count();
        $total_likes = Like::count();
        $total_shares = Share::count();

        $total_groups = Group::count();
        $total_views = View::count();
        $total_albums = Album::count();
        $total_reports = Report::count();

        $total_videos = Post::where('type', 'video')->count();
        $total_audios = Post::where('type', 'audio')->count();
        $total_pictures = Post::where('type', 'image')->count();
        $total_youtubes = Post::where('type', 'youtube')->count();

		return view('admin.dashboard', compact('total_videos', 'total_audios', 'total_pictures', 'total_youtubes', 'data', 'Gset', 'total_activate', 'total_banned', 'total_users', 'total_verified', 'total_posts', 'total_comments', 'total_shares', 'total_likes', 'total_groups', 'total_views', 'total_albums', 'total_reports'));
	}

	public function posts()

    {

        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'All Post';

       // $posts = Post::where('report', '>', 0)->orderBy('report', 'DESC')->paginate(10);
		$posts = Post::where('user_id', '!=' , 1)->orderBy('id', 'DESC')->get();

        return view('admin.post.index', compact('data', 'posts'));

    }
	
	public function rss()

    {

        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'RSS';

      $feeds = rss_feeds::orderBy('id', 'DESC')->get();//->paginate(10);

        return view('admin.rss', compact('data', 'feeds'));

    }
	
	public function ads()

    {

        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Ads';

      $ads = Ads::orderBy('id', 'DESC')->get();//->paginate(10);

        return view('admin.ads', compact('data', 'ads'));

    }


    public function postSingle(Post $post)

    {

        if (! $post) return redirect()->back()->withErrors('Post Not Found');

        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Post Of ' . $post->user->name;

        $reports = Report::where('post_id', $post->id)->paginate(10);

        return view('admin.post.single', compact('data', 'reports', 'post'));

    }

    public function postDelete(Request $request)

    {

        $request->validate([
            'id' => 'required|numeric'
        ]);

        $post = Post::find($request->id);

        if (! $post) return redirect()->back()->withErrors('Post Not Found');

        $post->delete();

        return redirect()->back()->withSuccess('Post Deleted Successfully');

    }
	
	public function postRSSDelete(Request $request)

    {

        $request->validate([
            'id' => 'required|numeric'
        ]);

        $feed = rss_feeds::find($request->id);

        if (! $feed) return redirect()->back()->withErrors('Feed Not Found');

        $feed->delete();

        return redirect()->back()->withSuccess('Feed Deleted Successfully');

    }
	
	
	public function postRSSAdd(Request $request)
    {
		$data['sitename'] = $this->sitename;
		 $data['page_title'] = 'New Feed';
		$interest = Interest::all()->sortBy('name');;
        return view('admin.rss-add', compact('data','interest'));


       
    }
	
	public function postRSSEdit(Request $request)
	{
		//die(print_r($request->post));
		
		$rss = rss_feeds::where('id',$request->post)->first();
		$data['sitename'] = $this->sitename;
		 $data['page_title'] = 'New Feed';
		$interest = Interest::all()->sortBy('name');
		$rss_interest_build = Interest::join('interest_rss_feeds', 'interests.id', '=', 'interest_rss_feeds.interest_id')->where('interest_rss_feeds.rss_feeds_id',$request->post)->get();
		
		//DB::select( DB::raw("SELECT * FROM some_table WHERE some_col = :somevariable"), array(   'somevariable' => $someVariable,
		
		foreach($rss_interest_build as $value)
		{
			$rss_interest[] = $value->interest_id;
		}
		
		//die(print_r($rss_interest));
		
        return view('admin.rss-edit', compact('data','interest','rss','rss_interest'));
		
	}
	
	public function postRSSStore(Request $request)
    {
		
		$param = $request->except(['_token','interest']);
		$rss_feeds = ((isset($request->id))?rss_feeds::whereId($request->id)->update($param):rss_feeds::create($param));
		
		if(isset($request->id))
			$rss_feeds = rss_feeds::whereId($request->id)->first();
		
		$rss_feeds->topics()->sync($request->interest);
		return redirect()->back()->withSuccess('Feed Successfully Saved');
	}
	
	
	
	
	
	
	
	//topics////
	
	public function topic()

    {

        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Topics';

        $topics = Interest::orderBy('name', 'ASC')->get();

        return view('admin.topic', compact('data', 'topics'));

    }
	
	
	public function topicDelete(Request $request)

    {

        $request->validate([
            'id' => 'required|numeric'
        ]);

        $topic = Interest::find($request->id);

        if (! $topic) return redirect()->back()->withErrors('Topic Not Found');

        $topic->delete();

        return redirect()->back()->withSuccess('Topic Deleted Successfully');

    }
	
	
	public function topicAdd(Request $request)
    {
		$data['sitename'] = $this->sitename;
		 $data['page_title'] = 'New Topic';
		
        return view('admin.topic-add', compact('data'));


       
    }
	
	public function topicEdit(Request $request)
	{
		//die(print_r($request->post));
		
		$topic = Interest::where('id',$request->post)->first();
		$data['sitename'] = $this->sitename;
		$data['page_title'] = 'New Feed';
		
		
        return view('admin.topic-edit', compact('data','topic'));
		
	}
	
	public function topicStore(Request $request)
    {
		
		$param = $request->except(['_token']);
		$topics = ((isset($request->id))?Interest::whereId($request->id)->update($param):Interest::create($param));
		
		
		return redirect()->back()->withSuccess('Topic Successfully Saved');
	}
	
	
	
	
	public function postAdsAdd(Request $request)
    {
		$data['sitename'] = $this->sitename;
		 $data['page_title'] = 'New Ad';
		
		$interest = Interest::all()->sortBy('name');
		
        return view('admin.ads-add', compact('data','interest'));


       
    }
	
	
	public function postAdsEdit(Request $request)
	{
		//die(print_r($request->post));
		
		$ads = Ads::where('id',$request->post)->first();
		$data['sitename'] = $this->sitename;
		$data['page_title'] = 'Edit Ad';
		$interest = Interest::all()->sortBy('name');
		$rss_interest_build = Interest::join('interest_ads', 'interests.id', '=', 'interest_ads.interest_id')->where('interest_ads.ads_id',$request->post)->get();
		
		
		foreach($rss_interest_build as $value)
		{
			$rss_interest[] = $value->interest_id;
		}
		
		
		
		
        return view('admin.ads-edit', compact('data','ads','interest','rss_interest'));
		
	}
	
	public function postAdsDelete(Request $request)

    {

        $request->validate([
            'id' => 'required|numeric'
        ]);

        $ads = Ads::find($request->id);

        if (! $ads) return redirect()->back()->withErrors('Ad Not Found');

        $ads->delete();

        return redirect()->back()->withSuccess('Ad Deleted Successfully');

    }
	
	public function postAdsStore(Request $request)
    {
		
		$param = $request->except(['_token','interest']);
		$ads = ((isset($request->id))?Ads::whereId($request->id)->update($param):Ads::create($param));
		
		if(isset($request->id))
			$ads = Ads::whereId($request->id)->first();
		
		$ads->topics()->sync($request->interest);
		return redirect()->back()->withSuccess('Ad Successfully Saved');
	}
	
	
	////////////
	
	
	//groups////
	
	public function group()

    {

        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Groups';

        $groups = Group::orderBy('name', 'ASC')->get();

        return view('admin.group', compact('data', 'groups'));

    }
	
	
	public function groupDelete(Request $request)

    {

        $request->validate([
            'id' => 'required|numeric'
        ]);

        $group = Group::find($request->id);

        if (! $group) return redirect()->back()->withErrors('Group Not Found');

        $group->delete();

        return redirect()->back()->withSuccess('Group Deleted Successfully');

    }
	
	
	public function groupAdd(Request $request)
    {
		$data['sitename'] = $this->sitename;
		 $data['page_title'] = 'New Topic';
		
        return view('admin.group-add', compact('data'));


       
    }
	
	public function groupEdit(Request $request)
	{
		
		$group = Group::where('id',$request->post)->first();
		$data['sitename'] = $this->sitename;
		$data['page_title'] = 'New Group';
		
		
        return view('admin.group-edit', compact('data','group'));
		
	}
	
	public function groupStore(Request $request)
    {
		
		$param = $request->except(['_token']);
		$groups = ((isset($request->id))?Group::whereId($request->id)->update($param):Group::create($param));
		
		
		return redirect()->back()->withSuccess('Group Successfully Saved');
	}
	
	////////////
	
	
	
	
	
	
	
	
	
	
	
	


    public function postCancel(Request $request)

    {

        $request->validate([
            'id' => 'required|numeric'
        ]);

        $post = Post::find($request->id);

        if (! $post) return redirect()->back()->withErrors('Post Not Found');

        $post->report = 0;
        $post->save();

        $post->reports()->delete();

        return redirect()->back()->withSuccess('Canceled Successfully');

    }

	public function changePass()

	{

		$data['sitename'] = $this->sitename;
		$data['page_title'] = 'Change Password';

		return view('admin.change-pass', compact('data'));

	}

	public function changePassUpdate(Request $r)

	{

		$r->validate([
			'old' => 'required',
			'password' => 'required|confirmed'
		]);

		$admin = Auth::guard('admin')->user();

		if (Hash::check($r->old, $admin->password)) {
			$admin->password = Hash::make($r->password);
			$admin->save();
			return redirect()->back()->withSuccess('Password Updated Successfully');
		} else {
			return redirect()->back()->with('alert', 'Wrong Old Password');
		}

		return redirect()->back()->with('alert', 'Unexpected Error. Please Try Again.');

	}

	public function logout()    {
		Auth::guard('admin')->logout();
		session()->flash('message', 'Just Logged Out!');
		return redirect('/admin');
	}
}
