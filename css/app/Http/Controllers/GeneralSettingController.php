<?php

namespace App\Http\Controllers;

use App\General;
use Illuminate\Http\Request;
use Auth;
use Image;

class GeneralSettingController extends Controller
{

	public function __construct(){
		$Gset = General::first();
		$this->sitename = $Gset->title;
	}

	public function index(){
		$data['sitename'] = $this->sitename;
		return view('admin.loginform', compact('data'));
	}

	public function GenSetting(){
		$data['sitename'] = $this->sitename;
		$data['page_title'] = 'General Setting';
			$Gset = General::first();
		return view('admin.GeneralSettings', compact('data', 'Gset'));
	}

	public function UpdateGenSetting(Request $request){

			$gs = General::first();

			$gs->title = $request->title;
			$gs->vid = $request->vid;

            $gs->emailver = $request->emailver =="1" ?0:1 ;
            $gs->smsver = $request->smsver =="1" ?0:1 ;
            $gs->emailnotf = $request->emailnotf=="1" ?1:0;
            $gs->smsnotf = $request->smsnotf=="1" ?1:0;

			$gs->save();

			return redirect()->back()->withSuccess('Settings Updated Successfully.');

	}

	public function logo()

	{

		$data['sitename'] = $this->sitename;
		$data['page_title'] = 'Logo And Icon Setting';

		return view('admin.logo', compact('data'));

	}

	public function updateLogo(Request $r)

	{

		$r->validate([
			'logo' => 'image',
			'icon' => 'image'
		]);

		if($r->hasFile('logo')) {
			Image::make($r->file('logo'))->save('assets/front/img/logo.png');
		}

		if($r->hasFile('icon')) {
			Image::make($r->file('icon'))->resize(16, 16)->save('assets/front/img/icon.png');
		}

		return redirect()->back()->withSuccess('Updated Successfully.');

	}

	public function tp()

    {

        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Terms And Policy';

        return view('admin.tp', compact('data'));

    }

    public function tpUpdate(Request $request)

    {

        $request->validate([
            'tap' => 'required'
        ]);

        $gnl = General::first();

        $gnl->tap = $request->tap;
        $gnl->save();

        return redirect()->back()->withSuccess('Updated Successfully');

    }

    public function pp()

    {

        $data['sitename'] = $this->sitename;
        $data['page_title'] = 'Privacy Policy';

        return view('admin.pp', compact('data'));

    }

    public function ppUpdate(Request $request)

    {

        $request->validate([
           'pp' => 'required'
        ]);

        $gnl = General::first();

        $gnl->pp = $request->pp;
        $gnl->save();

        return redirect()->back()->withSuccess('Updated Successfully');

    }

}
