<?php

namespace App\Http\Controllers;

use App\General;
use Illuminate\Http\Request;
use App\Etemplate;

class EtemplateController extends Controller
{
    public function index()
    {
        $temp = Etemplate::first();
        $data['sitename'] = General::first()->title;
        $data['page_title'] = 'Email Settings';
        return view('admin.email', compact('temp', 'data'));
    }
    public function smsApi()
    {
    	$temp = Etemplate::first();
        $data['sitename'] = General::first()->title;
        $data['page_title'] = 'Sms Settings';
        return view('admin.sms', compact('temp', 'data'));
    }

    public function update(Request $request)
    {
        $temp = Etemplate::first();

        $this->validate($request,
               [
                'esender' => 'required',
                'emessage' => 'required'
                ]);

        $temp['esender'] = $request->esender;
        $temp['emessage'] = $request->emessage;

        $temp->save();

        return back()->with('success', 'Email Settings Updated Successfully!');
    }
    public function smsUpdate(Request $request)
    {
        $temp = Etemplate::first();

        $this->validate($request,
               [
                'smsapi' => 'required',
                ]);
        $temp['smsapi'] = $request->smsapi;

        $temp->save();

        return back()->with('success', 'SMS Api Updated Successfully!');
    }
}
