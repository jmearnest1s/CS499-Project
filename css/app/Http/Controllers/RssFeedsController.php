<?php

namespace App\Http\Controllers;

use App\rss_feeds;
use App\Post;
use App\Share;
use Image;
use Illuminate\Http\Request;

class RssFeedsController extends Controller
{
	
	
	public function feedtopic()
    {
		$filename = '/home/agwiki/public_html/core/public/csv/RSS Final  - rss.csv'; $delimiter = ',';
		echo $filename."<br>";
		$header = null;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== false)
		{
			while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
			{
				if (!$header)
					$header = $row;
				else
				{
					
					//die(print_r($row));
					$topics = explode(',',$row[7]);
					foreach($topics as $topic)
					{
						\DB::insert('insert into interest_rss_feeds (rss_feeds_id, interest_id) values (?, ?)', [$row[0], $topic]);//$data[] = array_combine($header, $row);
						echo $row[0]."-". $topic."<br>";
					}
				}
			}
			fclose($handle);
		}
	
		
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		
		//$feeds = rss_feeds::all();
		$feeds = rss_feeds::take(20)->inRandomOrder()->get();
		
		//die(print_r($feeds));
		
		
		foreach($feeds as $feed) {
		
			//get the q parameter from URL
			$q='';
			
			
			  $xml=($feed->url);
			
			echo $feed->url.'<Br>';
			$xmlDoc = new \DOMDocument();
			@$xmlDoc->load($xml);
			
			//get elements from "<channel>"
			@$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
			
			if(!empty($channel)){
				@$channel_title = $channel->getElementsByTagName('title')
				->item(0)->childNodes->item(0)->nodeValue;
			
				@$channel_link = $channel->getElementsByTagName('link')
				->item(0)->childNodes->item(0)->nodeValue;
				@$channel_desc = $channel->getElementsByTagName('description')
				->item(0)->childNodes->item(0)->nodeValue;
			
				//output elements from "<channel>"
				/*echo("<p><a href='" . $channel_link
				  . "'>" . $channel_title . "</a>");
				echo("<br>");
				echo($channel_desc . "</p>");*/
				
				//get and output "<item>" elements
				if($x=$xmlDoc->getElementsByTagName('item'));
				{
					for ($i=0; $i<=2; $i++) {
						
						if(!empty($x->item($i)->getElementsByTagName('title')) && $x->item($i)->getElementsByTagName('title')->length > 0)
						{
						  @$item_title=$x->item($i)->getElementsByTagName('title')
						  ->item(0)->childNodes->item(0)->nodeValue;
						}
						
						if(!empty($x->item($i)->getElementsByTagName('link')) && $x->item($i)->getElementsByTagName('link')->length > 0)
						{
						  @$item_link=$x->item($i)->getElementsByTagName('link')
						  ->item(0)->childNodes->item(0)->nodeValue;
						}
						
						if(!empty($x->item($i)->getElementsByTagName('description')) && $x->item($i)->getElementsByTagName('description')->length > 0)
						{
						  @$item_desc=$x->item($i)->getElementsByTagName('description')
						  ->item(0)->childNodes->item(0)->nodeValue;
						}
						if(!empty($x->item($i)->getElementsByTagName('pubDate')) && $x->item($i)->getElementsByTagName('pubDate')->length > 0)
						{
					  		@$item_pubDate=$x->item($i)->getElementsByTagName('pubDate')
					  				->item(0)->childNodes->item(0)->nodeValue;
						}
					  
					  /*&if($x->item($i)->getElementsByTagName('image'))
					  {
						 @$item_image=$x->item($i)->getElementsByTagName('image')
						->item(0)->childNodes->item(0)->nodeValue;
					  }*/
					  echo ("<p><a href='" . $item_link
					  . "'>" . $item_title . "</a>");
					  echo ("<br>");
					  echo ($item_desc . "</p>");
					  
					  $sentences = 2;
					 
					  if(!Post::where('link', $item_link)->first() && !Post::where('content' ,'like', '%' .$item_title. '%')->first())
					  {
						  
						  
						  
						  ///////////////
						  
						  
						   $tags = get_meta_tags($item_link);
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
								//@$html->loadHTML(file_get_contents($item_link));
						  
						  		
						  
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

								curl_setopt($ch,CURLOPT_URL,$item_link);
								curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
								curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36");
								$data = curl_exec($ch);
								curl_close($ch);
								@$html->loadHTML($data);

						  
						  
								
								//Get all meta tags and loop through them.
								foreach($html->getElementsByTagName('meta') as $meta) {
									if ($meta->hasAttribute('property') && 
											strpos($meta->getAttribute('property'), 'og:') === 0) {
										if($meta->getAttribute('property')=='og:image'){ 
											$image = $meta->getAttribute('content');
										}
										if($meta->getAttribute('property')=='og:title'){
											$title = $meta->getAttribute('content');
										}
										if($meta->getAttribute('property')=='og:description'){
											$description = $meta->getAttribute('content');
										}  
									}
								}

								//if ($image != '') {
										$html = '<div >';
										if ($image != '') {
											
											if(strstr($image,'https://') || strstr($image,'http://'))
											{
												//$imgcnt = file_get_contents($image);
											}
											else
											{
												$urlresult = parse_url($item_link);
												$imgcnt = file_get_contents($urlresult['scheme']."://".$urlresult['host'].'/'.$image);
											}
											
											$path = $image;
											
											
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
											}

											
											/*
											$path = 'assets/front/content/' . time() . 'image.' . substr($image, -3);;
											file_put_contents($path, $imgcnt);
											
											
											/////////////////////
			
											
											$tw_image = explode('/content/',$path);

											// open file a image resource
											$img = Image::make($path);


											$img->resize(120, null, function ($constraint) {
												$constraint->aspectRatio();
											});
											
											
											$ext = pathinfo(
											parse_url($tw_image[1], PHP_URL_PATH), 
											PATHINFO_EXTENSION
											); 
											
											
											
											if($ext == 'gif' || $ext == 'png' || $ext == 'jpg'|| $ext == 'jpeg' )
												$img->save('assets/front/content/twitter_'.$tw_image[1] );

											/////////////////////
											
											*/
											
											$html .= '<img src="/' . $path . '" style="width:100%;">';
										}
										$html .= '</div>';
										$html .= '<div >';
										if ($title != '') {
											//$html .= '<a href="' . $request->urllink . '" target="_blank"><h2>' . $title . '</h2>';
										}
										if ($description != '') {
											$html .= '<p>' . substr($description, 0, 90) . '...</p>';
										}
										$html .= '</a></div>';
										//echo $html . '!~' . $request->urllink;
										//exit();

									  ///////////////
									// if(isset($path)) 
									//	echo  '<img src="/' . $path . '" style="width:100%;">';
						  
						  			//echo $html;

								   $post = new Post();

								   $post['content'] = ((isset($path))?'<img src="/' . $path . '" style="width:100%;">':''). "<h2>".$item_title."</h2><p class='excerpt'>".implode('. ', array_slice(explode('.', strip_tags($item_desc)), 0, $sentences))."</p>";
								   $post['type'] = 'feed';
								   $post['user_id'] = 1;
								   $post['link'] = $item_link;
								   $post['pubDate'] = date( 'Y-m-d H:i:s',strtotime($item_pubDate));
									//die(print_r($feed->topics));
								   $post->save();
								   $post->interests()->attach($feed->topics);
								   echo "saved ". $item_link ."<br>";
								   unset($path);
								   $share = new Share();

								   $share['post_id'] = $post->id;
								   $share['user_id'] = 1;

								   $share->save();
								//}//end if image
					  }//end if exists
					  
					  
					}//end foreach
				}//end if
			
			}
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
     * @param  \App\rss_feeds  $rss_feeds
     * @return \Illuminate\Http\Response
     */
    public function show(rss_feeds $rss_feeds)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\rss_feeds  $rss_feeds
     * @return \Illuminate\Http\Response
     */
    public function edit(rss_feeds $rss_feeds)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\rss_feeds  $rss_feeds
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, rss_feeds $rss_feeds)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\rss_feeds  $rss_feeds
     * @return \Illuminate\Http\Response
     */
    public function destroy(rss_feeds $rss_feeds)
    {
        //
    }
}
