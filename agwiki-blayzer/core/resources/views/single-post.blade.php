@extends('layouts.auth')





@section('meta')

    <meta name="description" content="{{ $og_description }}">

    <meta property="og:url" content="{{ $og_url }}" />

    <meta property="og:type" content="website" />

    <meta property="og:title" content="{{ $og_title }}" />

    <meta property="og:description" content="{{ $og_description }}" />

    <meta property="og:image" content="{{ $og_image }}"/>

    <meta property="fb:admins" content="100003019746911" />

    <meta property="fb:app_id" content="153590602171657" />

    <meta property="og:image:width" content="200" />

    <meta property="og:image:height" content="200" />

    <meta property="og:site_name" content="crossposting" />



    <meta name="twitter:card" content="summary" />

    <meta name="twitter:title" content="{{ $og_title }}" />

    <meta name="twitter:description" content="{{ $og_description }}" />

    <meta name="twitter:image" content="{{ $og_image }}" />

@endsection









@section('content')



<div class="page-content">

    <div class="single-post-item post">

		@guest

		<div class="row nonLoggedInCallOutRow">

			<div class="col-md-12 bottom-10 center" >

			<a href="/"><img src="/assets/front/img/logo_md.png" style="display: block; margin-left: auto; margin-right:auto"  ></a>

			</div>

		</div>

		@endguest

		

		@if(isset(Auth::user()->id))

    	@if($post->user_id == Auth::user()->id )

                                    <ul class="postedit">

                                                                       <li>

                                    <a href="/posts/edit/{{$post->id}}"><i class="fas fa-edit" ></i></a>

                                    </li>

                                                                        <li>

                                    <span href="#" class="delete-post" data-post="{{$post->id}}" ><i class="fa fa-trash" ></i></span>

                                    </li>

                                    </ul>

                               @endif

		@endif

        <div class="thumb">

            <img class="profile-image" src="{{ asset('assets/front/img/' . optional($post->user)->avatar) }}" alt="{{ optional($post->user)->name }}">

        </div>

            <h4 class="name">



            @if(Auth::user())

            <a href="{{ route('profile', optional($post->user)->username) }}">{{ optional($post->user)->name }}</a>@if($post->group) &#8658;

                <a href="{{ route('user.groups', $post->group->slug) }}">{{ $post->group->name }}</a>@endif <span class="days"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $post->created_at->diffForHumans() }}</span>

             @else

             {{ optional($post->user)->name }}  <span class="days"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $post->created_at->diffForHumans() }}</span>

             @endif

            </h4>

            <div class="post-meta">

			 @php $postTopics=App\Post::postTopics($post->id); @endphp

                                @if($postTopics[0]->interests->count() > 0)

                                <span>TOPIC(S):

							@foreach($postTopics as $theinterest)

							@foreach($theinterest->interests as $myinterest)

							<a href="/feed?topic={{$myinterest->id}}">{{$myinterest->name}}</a>,

							@endforeach

							@endforeach

							</span> @endif

                            </div>



            <article>

                @if($post->type == 'article' || $post->type == 'feed')

                <p>

                    @auth



                        @if($post->scrabingcontent!='')

                            {!! str_replace('assets/front/', '../assets/front/',$post->scrabingcontent)  !!}

                        @else

                            {!! preg_replace('/(?:^|\s)#(\w+)/', ' <a href="' . url('tag') . '/$1" style="font-weight: bold;">#$1</a>', $post->content) !!}

                        @endif



                    @endauth

                    @guest

                        @if($post->scrabingcontent!='')

                            {!! str_replace('assets/front/', '../assets/front/',preg_replace('#<a.*?>(.*?)</a>#i', '\1', $post->scrabingcontent))  !!}

                        @else

                            {!! excerpt($post, 'login') !!}

                        @endif



                    @endguest

                    

                      @if($post->type=='feed' && Auth::user())

                        <p class="article-img"><a target="_blank" href="{{ $post->link }}" class="pull-right readmore">Read More</a>

                                </p>

                        @endif

                        

                       

                    

                    

                </p>

                @elseif($post->type == 'image')

                <p>

                    @auth

                    {!! preg_replace('/(?:^|\s)#(\w+)/', ' <a href="' . url('tag') . '/$1" style="font-weight: bold;">#$1</a>', $post->content) !!}

                    @endauth

                    @guest

                    {!! excerpt($post, 'login') !!}

                    @endguest

                </p>

                <img src="{{ asset('assets/front/content/' . $post->link) }}" class="img-responsive imgclickcls" data-toggle="modal" data-target="#imageModal">

                @elseif($post->type == 'video')

                <p>

                    @auth

                    {!! preg_replace('/(?:^|\s)#(\w+)/', ' <a href="' . url('tag') . '/$1" style="font-weight: bold;">#$1</a>', $post->content) !!}

                    @endauth

                    @guest

                    {!! excerpt($post, 'login') !!}

                    @endguest

                </p>

                <video class="player" playsinline controls id="{{ str_random(20) }}" style="width: 100%;">

                    <source src="{{ asset('assets/front/content/' . $post->link) }}" type="video/mp4">

                    </video>

                    @elseif($post->type == 'audio')

                    <p>

                        @auth

                        {!! preg_replace('/(?:^|\s)#(\w+)/', ' <a href="' . url('tag') . '/$1" style="font-weight: bold;">#$1</a>', $post->content) !!}

                        @endauth

                        @guest

                        {!! excerpt($post, 'login') !!}

                        @endguest

                    </p>

                    <audio class="player" controls id="{{ str_random(20) }}" style="width: 100%;">

                        <source src="{{ asset('assets/front/content/' . $post->link) }}" type="audio/mp3">

                        </audio>

                        @elseif($post->type == 'youtube')

                        <p>

                            @auth

                            {!! preg_replace('/(?:^|\s)#(\w+)/', ' <a href="' . url('tag') . '/$1" style="font-weight: bold;">#$1</a>', $post->content) !!}

                            @endauth

                            @guest

                            {!! excerpt($post, 'login') !!}

                            @endguest

                        </p>

                        <div class="plyr__video-embed player">

                            <iframe id="{{ str_random(20) }}" src="https://www.youtube.com/embed/{{ $post->link }}?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>

                        </div>

                        @elseif($post->type == 'vimeo')

                        <p>

                            @auth

                            {!! preg_replace('/(?:^|\s)#(\w+)/', ' <a href="' . url('tag') . '/$1" style="font-weight: bold;">#$1</a>', $post->content) !!}

                            @endauth

                            @guest

                            {!! excerpt($post, 'login') !!}

                            @endguest

                        </p>

                        <div class="plyr__video-embed player">

                            <iframe id="{{ str_random(20) }}" src="https://player.vimeo.com/video/{{ $post->link }}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>

                        </div>

                        @elseif($post->type == 'doc')





                            {!! preg_replace('/(?:^|\s)#(\w+)/', ' <a href="' . url('tag') . '/$1" style="font-weight: bold;">#$1</a>', $post->content) !!}

                            <div class="doc">

                              <div style="overflow: hidden;">

 {{ $post->link }}

                                   <a target="_blank" href="{{ asset('assets/front/content/' . $post->link) }}" class="top-10 pull-right button button-xs button-round-small shadow-small button-primary" download >Download</a>

                              </div>

                          </div>







                        @endif







                    </article>





                    <div class="post-addons likes">

					@if(Auth::user())

                      <ul>

                          <li>

                              <div class="single-input-wrapper">

                                  <i onClick="$(this).addClass('active');" class="fas fa-thumbs-up post-action like {{ $post->isLiked()?' active':'' }}" data-post="{{$post->id}}"><span class="i-span">({{App\Like::countLIke($post->id)}})</span></i>

                              </div>

                          </li>

                           <li style="width:50px;">

                            <div class="single-input-wrapper share-group home-page-share-btn group">

                                <a onClick="$( '.dropdown-content-{{$post->id}}' ).toggle();" href="javascript::void(0)" data-toggle="dropdown-content" class="dropdown-toggle">

                                    <i class="fa fa-share-square"></i> <span class="caret"></span>

                                </a>

                                <ul class="dropdown-content dropdown-content-{{$post->id}} groupShare">

                                    <li style="width:250px;">

                                        <a data-original-title="Timeline"   href="" target="_blank" >

                                           <a data-post="{{$post->id}}" onClick="$(this).addClass('active');$( '.dropdown-content-{{$post->id}}' ).toggle();" class="share"> Timeline</a>

                                        </a>

                                    </li>

                                    @if($groups && count($groups))

                                    @foreach($groups as $group)

                                    <li style="width:250px;">

                                        <a data-original-title="Group {{$group->name}}"   href="" target="_blank" >

                                           <a data-post="{{$post->id}}" data-group="{{$group->group->id}}" onClick="$(this).addClass('active');$( '.dropdown-content-{{$post->id}}' ).toggle();" class="share">Group {{$group->group->name}}</a>

                                        </a>

                                    </li>

                                    @endforeach

                                    @endif

                                </ul>

                            </div>

                        </li>



                          <li>

                              <div class="single-input-wrapper">

                                  <i onClick="$(this).addClass('active');" class="fas fa-star post-action favorite {{ $post->isFavorited()?' active':'' }}" data-post="{{$post->id}}">

<span class="i-span"> Bookmark</span></i>

                              </div>

                          </li>

                      </ul>

                      @endif

                      

                       @if(!Auth::user())

						

						<style type="text/css">

							@media (min-width: 1280px){

							#page {min-width: 920px;}

							}

						</style>

                       

                       <div class="row nonLoggedInCallOutRow">

                        <div class="col-md-2" ></div>

                        <div class="col-md-8 " style="text-align:center">

                        	<span class="nonLoggedInCallOut" >

								<h3 style="color:#2980B9; font-weight:bold;">Would you like to read more?</h3>

							</span>

                        </div>

                        <div class="col-md-2" ></div>

                        </div>

                       

                        <div class="row nonLoggedInCallOutRow">

                        

							<div class="col-md-5 col-md-offset-1" style="text-align:center">

							<div class="button button-s shadow-large button-round-small bg-blue2-dark top-10"  ><a style="color:white;" href="/login">SIGN UP. IT'S FREE!</a></div>

							</div>

							<div class="col-md-5 col-md-offset-1" style="text-align:center">

							<div class="button button-s shadow-large button-round-small bg-green1-dark top-10" ><a style="color:white;" href="/login">LOGIN HERE</a></div>

							</div>

                        

                        </div>

						

						 <div class="row nonLoggedInCallOutRow">

                        

							<div class="col-md-5 col-md-offset-1" style="text-align:center" >

								<div class="button "  ><a href="https://apps.apple.com/us/app/agwiki/id1484901745" class="appStorelink" target="_blank"><img src="/assets/front/img/apple-appstore-logo.png"></a></div>

							</div>

							<div class="col-md-5 col-md-offset-1" style="text-align:center" >

								<div class="button "  ><a href="https://play.google.com/store/apps/details?id=com.agwiki.app&hl=en_US" class="appStorelink" target="_blank"><img src="/assets/front/img/android-app-logo.png"></a></div>

							</div> 

                         

                        </div>

						

						

							

						<div class="row nonLoggedInCallOutRow">

                        <div class="col-md-2" ></div>

                        <div class="col-md-8 " style="text-align:center">

                        	<span class="nonLoggedInCallOut" >

								<h2 >AgWiki is…<br>



…agricultural conversation, news, and information. </h2>

							</span>

                        </div>

                        <div class="col-md-2" ></div>

                        </div>

						

						

						

						

                        

                        

                        <div class="row nonLoggedInCallOutRow">

                        <div class="col-md-2" ></div>

							

                        <div class="col-md-8 " style="text-align:center">

							

                        	<span class="nonLoggedInCallOut" ><h3 style="color:#2980B9; ;">   Select from 140 topics so you can read and engage in the subjects that are important to you. We are a global community of food producers, nutritionists, ag consultants, and researchers seeking to discover solutions to sustainability and world hunger. </h3></span>

                        </div>

                        <div class="col-md-2" ></div>

                        </div>

						

						

						

						

						

						<div class="row nonLoggedInCallOutRow">

                        <div class="col-md-2" ></div>

                        <div class="col-md-8 " style="text-align:center">

                        	<span class="nonLoggedInCallOut" >

								<h2 style="font-weight:bold;">Features</h2>

							</span>

                        </div>

                        <div class="col-md-2" ></div>

                        </div>

						

						

						 <div class="row nonLoggedInCallOutRow">

                        

							<div class="col-md-5 col-md-offset-1" style="text-align:center">
                            <i class="fa fa-users" aria-hidden="true"></i>
								<h3>Join groups</h3><p>Join groups with similar interests, geolocations, and topics.</p>

							</div>

							<div class="col-md-5 col-md-offset-1" style="text-align:center">
                                <i class="fa fa-file" aria-hidden="true"></i>
								<h3>Share documents</h3><p>Share research documents with your followers or in groups.</p>

							</div>

                        

                        </div>

						

						<div class="row nonLoggedInCallOutRow">

                        

							<div class="col-md-5 col-md-offset-1" style="text-align:center">
                                <i class="fa fa-comment-dots" aria-hidden="true"></i>
								<h3>Private messaging</h3><p>You can private message and share with anyone in the network.</p>

							</div>

							<div class="col-md-5 col-md-offset-1" style="text-align:center">
                                <i class="fas fa-newspaper" aria-hidden="true"></i>

								<h3>News</h3><p>News on all ag topics aggregated from around the world and always up-to-date.</p>

							</div>

                        

                        </div>

						

						<hr>

						

						

						<div class="col-md-12 mb-4">

							  <div class="card card-image" style="position: relative;background-image: url(https://scontent-ort2-2.xx.fbcdn.net/v/t1.0-9/61906424_2112051652427193_3874582833439703040_n.jpg?_nc_cat=101&_nc_oc=AQl9CTsyy9Fjsj1zm_JYtqvnXalBZl3XX9CgOPujXtDOtWlP6bvNTL6lKHY7kh-bqkA&_nc_ht=scontent-ort2-2.xx&oh=7e31ae629f289c6ab977690cc1b3e29a&oe=5EA6608A);">

								  <div class="text-white text-center d-flex align-items-center rgba-black-strong py-5 px-4" style="padding:20px;background-color: rgba(0,0,0,0.5);z-index: 10; top: 0; left: 0; width: 100%; height: 100%;">

									  <div>

										  <h3 class="purple-text" style="color:white"><i class="fas fa-newspaper"></i><strong> Newsletter</strong></h3>

										  <h5 class="card-title py-3 font-weight-bold " style="color:white"><strong>Signup for our newsletter and get our free ebook!</strong></h5>

										  

										  

										  <div class="row nonLoggedInCallOutRow" style="color:white">

                        

												<div class="col-md-7 col-md-offset-1" style="text-align:center">

													<h4>Stay current on our featured stories, events, and alerts from AgWiki.</h4>

												</div>

												<div class="col-md-3 " style="text-align:center">

													

													

													<form>





													<!-- MailChimp for WordPress v4.1.2 - https://wordpress.org/plugins/mailchimp-for-wp/ --><form id="mc4wp-form-1" class="mc4wp-form mc4wp-form-5152" method="post" data-id="5152" data-name="Newsletter"><div class="mc4wp-form-fields"><p>

														

														<input style="padding:8px;" class="post-input-field form-control input-style-1" type="email" name="EMAIL" placeholder="Your email address" required="">





														<p>

															<input class="button button-s shadow-large button-round-small bg-green1-dark top-10" type="submit" value="Sign up">

														</p><div style="display: none;"><input type="text" name="_mc4wp_honeypot" value="" tabindex="-1" autocomplete="off"></div><input type="hidden" name="_mc4wp_timestamp" value="1579189079"><input type="hidden" name="_mc4wp_form_id" value="5152"><input type="hidden" name="_mc4wp_form_element_id" value="mc4wp-form-1"></div><div class="mc4wp-response"></div></form>

													<!-- / MailChimp for WordPress Plugin -->	

													</form>

														



												</div>



											</div>

										  

										  

										  

										  

									  </div>

								  </div>

							  </div>

						  </div>

						

						

						

						

						

						

						

                        

                        @endif

                      

                      

                      

                      <ul class="socialShare">



                          <li>



                              <a data-original-title="Twitter"  href="{{ route('social.share', [$post->id, 'twitter']) }}" target="_blank" class="btn btn-twitter" data-placement="left">



                                  <i class="fab fa-twitter"></i>



                              </a>



                          </li>



                          <li>



                              <a data-original-title="Facebook"  href="{{ route('social.share', [$post->id, 'facebook']) }}" target="_blank" class="btn btn-facebook" data-placement="left">



                                  <i class="fab fa-facebook"></i>



                              </a>



                          </li>



                          <li>



                              <a data-original-title="LinkedIn"  href="{{ route('social.share', [$post->id, 'linkedin']) }}" target="_blank" class="btn btn-linkedin" data-placement="left">



                                  <i class="fab fa-linkedin"></i>



                              </a>



                          </li>





                        </ul>

                        <div class="clear"></div>

                        

                      



                    </div>





                </div>







        @auth



    <div class="comments-area">

        <h4 class="leave-comment-title">Leave A Comment</h4>

        <form id="comment_form" method="post" action="{{ route('user.comment.store') }}">

            @csrf

            <input type="hidden" name="urldataval" id="urldataval" value="">

             <div class="preloader" id="post-ajax-loader" style="display: none;">

                    <div class="progress">

                        <div id="prog" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;">

                        </div>

                    </div>

                </div>



            <div class="single-comment-input-area">

                <input type="hidden" name="comment_post" value="{{ $post->id }}">

                <div class="form-group">

                    <div class="bottom-content-comments-area">

                        <div class="left-content">



                        </div>

                    </div>

                    <div class="input-style-2">

                    <textarea id="commentbox"  style="width:100%;height:100px; line-height: 28px" class="post-input-field" name="comment_content" placeholder="Type your comments" ></textarea>

                    <input type="hidden" name="type" id="type">



																					<input type="hidden" name="link" id="link">

                                                                                    <input type="hidden" name="hrefurl" id="hrefurl" value="">

                    </div>

                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" id="urldatadiv" style="display:none;"></div>





                    					<div class="post-addons">



		                            			<ul class="right">



																	<li>



																			<div class="single-input-wrapper">



																					<label for="image">



																							<img src="/assets/front/css/img_upload.png" alt="Upload an Image">

																					</label>



																					<input type="file" name="image" id="image" style="display:none">



																			</div>



																	</li>



																	<!--<li>

																			<div class="single-input-wrapper">

																					<label for="video">

																							<img src="/assets/front/css/mov_upload.png" alt="Upload an Video">

																					</label>

																					<input type="file" name="video" id="video" style="display:none">

																			</div>

																	</li>-->

																	<li class="hidden-mobile">



																			<div class="single-input-wrapper">



																					<label for="doc">



																							<img src="/assets/front/css/doc_upload.png" alt="Upload an Document">

																					</label>



																					<input type="file" name="doc" id="doc" style="display:none">







																			</div>



																	</li>



		                                			</ul>



		                                    </div>



                						<div id="cont" style="display:none" class="instrant-upload-show">

                							<span class="cross"><i class="fa fa-times" aria-hidden="true"></i></span>

                                            <div class="contai">

                                            </div>

                                    	</div>

                                    <!-- image modal start change by dinesh -->

                                    <div class="modal fade" id="imageModal" role="dialog" style="display:none">

                                        <div id="imgmodalwidth" class="modal-dialog modal-lg" style="width:400px">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                </div>

                                                <div class="modal-body">

                                                    <img id="imagesrc" class="showimage img-responsive" src="" />

                                                </div>

                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

                                                </div>

                                            </div>

                                        </div>

                                    </div>



                    <div class="comments-submit-area">

                        <input class="button button-s shadow-large button-round-small bg-green1-dark top-10" type="submit" value="Submit">

                    </div>



                </div>



            </div>

        </form>

		<img style="display:none;width:50px;" id="loaderimg" src="{{ url('assets/front/img/loader.gif') }}">

        <h2 class="title">Comments (<span id="countComment">{{ $post->commentCount()?number_format_short($post->commentCount()):0 }}</span>)</h2>



        <div class="all-comments-warpper comment-list">





            @if(isset($comments))

            @foreach($comments as $comment)



            @if($comment->type == 'image')



            <div class="single-comment-show-item" data-id="{{ $comment->id }}">

                <div class="thumb">

                    <img class="profile-image" src="{{ asset('assets/front/img/' . optional($comment->user)->avatar) }}" alt="{{ optional($comment->user)->name }}">

                </div>

                <div class="content">

                    <h4><a href="{{ route('profile', optional($comment->user)->username) }}" class="name">{{ optional($comment->user)->name }}</a> @if(optional($comment->user)->verified == 1)<span class="varified"><i class="fa fa-check-circle"></i></span>@endif <span class="days">{{ $comment->created_at->format('M d, Y') }} at {{ $comment->created_at->format('g:i a') }}</span></h4>

                    <div class="descriptoin">

                        <img src="{{ asset('assets/front/content/' . $comment->link) }}" class="img-responsive">

                    </div>

                    @if($comment->user_id == Auth::user()->id || $post->user_id == Auth::user()->id || Auth::user()->id == 9)<a class="delete-comment" data-comment="{{ $comment->id }}" data-post="{{ $post->id }}"><i class="fa fa-trash" style="float:right"></i></a>@endif

                </div>

            </div>



              @elseif($comment->type == 'doc')

              <div class="single-comment-show-item" data-id="{{ $comment->id }}">

                    <p>{!! excerpt($comment) !!}</p><br>

                            <div class="doc">

                                           <div style="overflow: hidden;">

                                                {{ $comment->link }}

                                                <a target="_blank" href="{{ asset('assets/front/content/' . $comment->link) }}" class="top-10 pull-right button button-xs button-round-small shadow-small button-primary" download >Download</a>

                                           </div>

                             </div>

                             @if($comment->user_id == Auth::user()->id || $post->user_id == Auth::user()->id || Auth::user()->id == 9)<a class="delete-comment" data-comment="{{ $comment->id }}" data-post="{{ $post->id }}"><i class="fa fa-trash" style="float:right"></i></a>@endif

			  </div>



            @elseif($comment->type == 'youtube')

            <div class="single-comment-show-item" data-id="{{ $comment->id }}">

                <div class="thumb">

                    <img class="profile-image" src="{{ asset('assets/front/img/' . optional($comment->user)->avatar) }}" alt="{{ optional($comment->user)->name }}">

                </div>

                <div class="content">

                    <h4><a href="{{ route('profile', optional($comment->user)->username) }}" class="name">{{ optional($comment->user)->name }}</a> @if(optional($comment->user)->verified == 1)<span class="varified"><i class="fa fa-check-circle"></i></span>@endif <span class="days">{{ $comment->created_at->format('M d, Y') }} at {{ $comment->created_at->format('g:i a') }}</span></h4>

                    <div class="descriptoin">

                        <div class="plyr__video-embed player">

                            <iframe id="{{ str_random(20) }}" src="https://www.youtube.com/embed/{{ $comment->link }}?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>

                        </div>

                    </div>

                    @if($comment->user_id == Auth::user()->id || $post->user_id == Auth::user()->id || Auth::user()->id == 9)<a class="delete-comment" data-comment="{{ $comment->id }}" data-post="{{ $post->id }}"><i class="fa fa-trash" style="float:right"></i></a>@endif

                </div>

            </div>



            @elseif($comment->type == 'vimeo')

            <div class="single-comment-show-item" data-id="{{ $comment->id }}">

                <div class="thumb">

                    <img class="profile-image" src="{{ asset('assets/front/img/' . optional($comment->user)->avatar) }}" alt="{{ optional($comment->user)->name }}">

                </div>

                <div class="content">

                    <h4><a href="{{ route('profile', optional($comment->user)->username) }}" class="name">{{ optional($comment->user)->name }}</a> @if(optional($comment->user)->verified == 1)<span class="varified"><i class="fa fa-check-circle"></i></span>@endif <span class="days">{{ $comment->created_at->format('M d, Y') }} at {{ $comment->created_at->format('g:i a') }}</span></h4>

                    <div class="descriptoin">

                        <div class="plyr__video-embed player">

                            <iframe id="{{ str_random(20) }}" src="https://player.vimeo.com/video/{{ $comment->link }}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>

                        </div>

                    </div>

                    @if($comment->user_id == Auth::user()->id || $post->user_id == Auth::user()->id || Auth::user()->id == 9)<a class="delete-comment" data-comment="{{ $comment->id }}" data-post="{{ $post->id }}"><i class="fa fa-trash" style="float:right"></i></a>@endif

                </div>

            </div>



            @else

            <div class="single-comment-show-item" data-id="{{ $comment->id }}">

                <div class="thumb">

                    <img class="profile-image" src="{{ asset('assets/front/img/' . optional($comment->user)->avatar) }}" alt="{{ optional($comment->user)->name }}">

                </div>

                <div class="content">

                    <h4><a href="{{ route('profile', optional($comment->user)->username) }}" class="name">{{ optional($comment->user)->name }}</a> @if(optional($comment->user)->verified == 1)<span class="varified"><i class="fa fa-check-circle"></i></span>@endif <span class="days">{{ $comment->created_at->format('M d, Y') }} at {{ $comment->created_at->format('g:i a') }}</span></h4>

                    <div class="descriptoin">

                        <p>{!! $comment->content !!}</p>

                    </div>

                    @if($comment->user_id == Auth::user()->id || $post->user_id == Auth::user()->id || Auth::user()->id == 9)<a class="delete-comment" data-comment="{{ $comment->id }}" data-post="{{ $post->id }}"><i class="fa fa-trash" style="float:right"></i></a>@endif

                </div>

                <div style="clear:both"></div>

            </div>



             {{--<!--Comments of Comments-->





             <ul>

                <li>

                    <div class="single-post-action">

                        <span class="comment-of-comment" data-comment="commnetof{{ $comment->id }}"><i class="fa fa-comments"></i></span>

                        <a class="post-action-count"></a>

                    </div>

                </li>



            </ul>

            <div id="commnetof{{ $comment->id }}" style="display:none;">

                <form id="comment_form" method="post" action="{{ route('user.comment.store') }}">

                @csrf

                <div class="single-comment-input-area">

                    <input type="hidden" name="comment_post" value="{{ $post->id }}">

                    <input type="hidden" name="comment_comment" value="{{ $comment->id }}">

                    <div class="form-group">

                        <div class="bottom-content-comments-area">

                            <div class="left-content">



                            </div>

                        </div>

                        <textarea style="width:100%" name="comment_content" placeholder="Type your comments" class="emojionearea-editor"></textarea>

                        <div class="comments-submit-area">

                            <input class="comment-submit-btn" type="submit" value="Submit">

                        </div>

                    </div>



                </div>

             </form>

            </div>









     @php $commentCom = \App\Comment::where('post_id', $post->id)->where('comment_id',$comment->id)->get(); @endphp

<div class="row commnet-of-commen">

@foreach($commentCom as $comment)



<div class="col-md-10 col-md-offset-2">

    @if($comment->type == 'image')



        <div class="single-comment-show-item" data-id="{{ $comment->id }}">

            <div class="thumb">

                <img class="profile-image" src="{{ asset('assets/front/img/' . optional($comment->user)->avatar) }}" alt="{{ optional($comment->user)->name }}">

            </div>

            <div class="content">

                    <h4 ><a href="{{ route('profile', optional($comment->user)->username) }}" class="name">{{ optional($comment->user)->name }}</a> @if(optional($comment->user)->verified == 1)<span class="varified"><i class="fa fa-check-circle"></i></span>@endif <span class="days">{{ $comment->created_at->format('M d, Y') }} at {{ $comment->created_at->format('g:i a') }}</span></h4>

                <div class="descriptoin">

                    <img src="{{ asset('assets/front/content/' . $comment->link) }}" class="img-responsive">

                </div>

            </div>

        </div>



    @elseif($comment->type == 'youtube')

        <div class="single-comment-show-item" data-id="{{ $comment->id }}">

            <div class="thumb">

                <img class="profile-image" src="{{ asset('assets/front/img/' . optional($comment->user)->avatar) }}" alt="{{ optional($comment->user)->name }}">

            </div>

            <div class="content">

                    <h4 ><a href="{{ route('profile', optional($comment->user)->username) }}" class="name">{{ optional($comment->user)->name }}</a> @if(optional($comment->user)->verified == 1)<span class="varified"><i class="fa fa-check-circle"></i></span>@endif <span class="days">{{ $comment->created_at->format('M d, Y') }} at {{ $comment->created_at->format('g:i a') }}</span></h4>

                <div class="descriptoin">

                    <div class="plyr__video-embed player">

                        <iframe id="{{ str_random(20) }}" src="https://www.youtube.com/embed/{{ $comment->link }}?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>

                    </div>

                </div>

            </div>

        </div>



    @elseif($comment->type == 'vimeo')

        <div class="single-comment-show-item" data-id="{{ $comment->id }}">

            <div class="thumb">

                <img class="profile-image" src="{{ asset('assets/front/img/' . optional($comment->user)->avatar) }}" alt="{{ optional($comment->user)->name }}">

            </div>

            <div class="content">

                    <h4 ><a href="{{ route('profile', optional($comment->user)->username) }}" class="name">{{ optional($comment->user)->name }}</a> @if(optional($comment->user)->verified == 1)<span class="varified"><i class="fa fa-check-circle"></i></span>@endif <span class="days">{{ $comment->created_at->format('M d, Y') }} at {{ $comment->created_at->format('g:i a') }}</span></h4>

                <div class="descriptoin">

                    <div class="plyr__video-embed player">

                        <iframe id="{{ str_random(20) }}" src="https://player.vimeo.com/video/{{ $comment->link }}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>

                    </div>

                </div>

            </div>

        </div>



    @else

        <div class="single-comment-show-item" data-id="{{ $comment->id }}">

            <div class="thumb">

                <img class="profile-image" src="{{ asset('assets/front/img/' . optional($comment->user)->avatar) }}" alt="{{ optional($comment->user)->name }}">

            </div>

            <div class="content">

                    <h4 ><a href="{{ route('profile', optional($comment->user)->username) }}" class="name">{{ optional($comment->user)->name }}</a> @if(optional($comment->user)->verified == 1)<span class="varified"><i class="fa fa-check-circle"></i></span>@endif <span class="days">{{ $comment->created_at->format('M d, Y') }} at {{ $comment->created_at->format('g:i a') }}</span></h4>

                <div class="descriptoin">

                    <p>{!! $comment->content !!}</p>

                </div>

            </div>

        </div>



    @endif

       <div class="single-comment-show-item-separator"></div>



    </div>



@endforeach

</div>

         <!--Comments of Comments-->--}}







            @endif



                <div class="single-comment-show-item-separator"></div>



        @endforeach

@endif

            </div>

        </div>

    </div>







@endauth









@endsection

