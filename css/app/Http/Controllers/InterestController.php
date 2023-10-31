<?php

namespace App\Http\Controllers;
use App\Interest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		$interests = Auth::user()->interests()->orderBy('name')->get();
		//dd($interests);
		
		$passinterest = array();
		
		foreach($interests as $loopinterest)
		{
			$passinterest[] = $loopinterest->id;
		}
		
		$alltopics = Interest::whereNotIn('id', $passinterest)->orderBy('name')->get();
		$page_title = 'Topics';
		
		//die($interests);
		return view('topics', compact('alltopics', 'interests','page_title'));
    }
	
	public function processTopic(Request $request)

    {
		
		if(\DB::insert('insert into interest_user (user_id, interest_id) values (?, ?)', [Auth::user()->id, $request->topic_id]))
		{
			
			
			
				//mautic///////////////////////


				$email = Auth::user()->email;


				$segment = 7;

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
			
			
			
		  
		   return response()->json([
            'success' => 1
        	]);
		}
		else
		{
			return response()->json([
            'success' => 0
        	]);	
		}
	}
	
	public function processTopicRem(Request $request)

    {
		
		if(\DB::delete('delete from interest_user where user_id =? and interest_id=?', [Auth::user()->id, $request->topic_id]))
		{
		  
		   return response()->json([
            'success' => 1
        	]);
		}
		else
		{
			return response()->json([
            'success' => 0
        	]);	
		}
	}
	
	

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
