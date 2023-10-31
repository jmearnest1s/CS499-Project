<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

<head>

    <title>{{ ((isset($page_title))?$page_title:'Home') }} | {{ $gnl->title }}</title>
    <meta name="Title" Content="{{ ((isset($page_title))?$page_title:'Home') }} | {{ $gnl->title }}">

    <meta name="robots" content="index,follow" /> 

    <meta name="Googlebot" content="index, follow, all" />

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/front/img/icon.png') }}" />

    <meta property="og:title" content="{{ ((isset($page_title))?$page_title:'Home') }} | {{ $gnl->title }}"/>

    <meta property="og:site_name" content="{{ $gnl->title }}"/>

     @yield('meta')

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('assets/front/img/icon.png') }}"/>

   

    @include('custom.header')

</head>

<body class="theme-light" data-highlight="blue2">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5ZRS2J9"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	@if(!strstr($_SERVER['REQUEST_URI'],'/message/'))
	<style>
		
		.post p.excerpt a {

		  /* These are technically the same, but use both */
		  overflow-wrap: break-word;
		  word-wrap: break-word;

		  -ms-word-break: break-all;
		  /* This is the dangerous one in WebKit, as it breaks things wherever */
		  word-break: break-all;
		  /* Instead use this non-standard one: */
		  word-break: break-word;

		  /* Adds a hyphen where the word breaks, if supported (No Blink) */
		  -ms-hyphens: auto;
		  -moz-hyphens: auto;
		  -webkit-hyphens: auto;
		  hyphens: auto;

		}
	
	
		.mysticky-welcomebar-fixed-wrap {
			min-height: 60px;
			padding: 20px 50px 0px 50px ;
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			height: 100%;
			background-color: #66E0A3;
			z-index: 10000;
			margin-bottom: 0px;
				
				
		}
		
		.mysticky-welcomebar-btn a {
			background-color: #000;
			font-family: inherit;
			color: #fff;
			border-radius: 4px;
			text-decoration: none;
			display: inline-block;
			vertical-align: top;
			line-height: 1.2;
			font-size: 16px;
			font-weight: 400;
			padding: 5px 20px;
			white-space: nowrap;
			margin-bottom: 27px;
			margin-left: 10px;
		}
		
		
		
		
		
		/*.sidebar {
    		margin-top: 100px;
		}*/
		
	</style>
	
	
	<!--<div class="mysticky-welcomebar-fixed-wrap">
		<div class="mysticky-welcomebar-content">
		<p>NOW LIVE: Our equity crowdfunding campaign is live on StartEngine! → </p>
		</div>
		<div class="mysticky-welcomebar-btn">
		<a target="_blank" href="https://startengine.com/agwiki">Learn More</a>
		</div>
		<!--<a href="javascript:void(0)" class="mysticky-welcomebar-close">X</a>-->
	<!--</div>-->

	@endif
					@if (session('status'))

                        <div class="alert-large status" role="alert">

                            {{ session('status') }}

                        </div>

                    @endif



					@if ($errors->any())

                    	 @foreach($errors->all() as $error)

                        <div class="alert-large error" role="alert">

                          

                            {{ $error }}

                            

                        </div>

                        @endforeach

                    @endif

                    

                   

                    

                    @if (session('alert'))

                        <div class="alert-large" role="alert">

                            {{ session('alert') }}

                        </div>

                    @endif



 					@if (session('message'))

                        <div class="alert-large message" role="alert">

                            {{ session('message') }}

                        </div>

                    @endif

                     @if(session('success'))

                     <div class="alert-large success" role="alert">

                       {{ session('success') }}

                     </div>

                     @endif











<div id="page-preloader">

    <div class="loader-main"><div class="preload-spinner border-highlight"></div></div>

</div>

<div id="page">



@if( Auth::user())

@include('custom.sidebar')

@endif

    @yield('content')

</div>

<div class="menu-hider"></div>





@include('custom.footer')





<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="/assets/front/js/jquery.jscroll.min.js"></script>

<script type='text/javascript' src="https://jakiestfu.github.io/Mention.js/javascripts/bootstrap-typeahead.js"></script>
	<script type='text/javascript'>(function(e){e.fn.extend({mention:function(t){this.opts={users:[],delimiter:"@",sensitive:true,queryBy:["name","username"],typeaheadOpts:{}};var n=e.extend({},this.opts,t),r=function(){if(typeof e=="undefined"){throw new Error("jQuery is Required")}else{if(typeof e.fn.typeahead=="undefined"){throw new Error("Typeahead is Required")}}return true},i=function(e,t){var r;for(r=t;r>=0;r--){if(e[r]==n.delimiter){break}}return e.substring(r,t)},s=function(e){var t;for(t in n.queryBy){if(e[n.queryBy[t]]){var r=e[n.queryBy[t]].toLowerCase(),i=this.query.toLowerCase().match(new RegExp(n.delimiter+"\\w+","g")),s;if(!!i){for(s=0;s<i.length;s++){var o=i[s].substring(1).toLowerCase(),u=new RegExp(n.delimiter+r,"g"),a=this.query.toLowerCase().match(u);if(r.indexOf(o)!=-1&&a===null){return true}}}}}},o=function(e){var t=this.query,r=this.$element[0].selectionStart,i;for(i=r;i>=0;i--){if(t[i]==n.delimiter){break}}var s=t.substring(i,r),o=t.substring(0,i),u=t.substring(r),t=o+n.delimiter+e+u;this.tempQuery=t;return t},u=function(e){if(e.length&&n.sensitive){var t=i(this.query,this.$element[0].selectionStart).substring(1),r,s=e.length,o={highest:[],high:[],med:[],low:[]},u=[];if(t.length==1){for(r=0;r<s;r++){var a=e[r];if(a.username[0]==t){o.highest.push(a)}else if(a.username[0].toLowerCase()==t.toLowerCase()){o.high.push(a)}else if(a.username.indexOf(t)!=-1){o.med.push(a)}else{o.low.push(a)}}for(r in o){var f;for(f in o[r]){u.push(o[r][f])}}return u}}return e},a=function(t){var r=this;t=e(t).map(function(t,i){t=e(r.options.item).attr("data-value",i.username);var s=e("<div />");if(i.image){s.append('<img class="mention_image" src="'+i.image+'">')}if(i.name){s.append('<b class="mention_name">'+i.name+"</b>")}if(i.username){s.append('<span class="mention_username"> '+n.delimiter+i.username+"</span>")}t.find("a").html(r.highlighter(s.html()));return t[0]});t.first().addClass("active");this.$menu.html(t);return this};e.fn.typeahead.Constructor.prototype.render=a;return this.each(function(){var t=e(this);if(r()){t.typeahead(e.extend({source:n.users,matcher:s,updater:o,sorter:u},n.typeaheadOpts))}})}})})(jQuery)</script>
	

<script>


					$(".alert-large").click(function(){

					  $(".alert-large").hide();

					});
					 $(".alert-large").fadeOut(2000);



				/*$(document).on('click', '.like', function (e) {

                    e.preventDefault();



                    var post = $(this).data('post');



                    $(this).addClass('actv');

					

					var result = this.match(/\((.*)\)/);

					alert(result[1]);



                    $.ajax({

                        type:"POST",

                        url:"{{ route('user.like') }}",

                        data: {post_id: post, _token: '{{ csrf_token() }}'},

                        success:function(data){

                            if (data.success && data.success == 1) {

                                if (data.message) {

                                  //  toastr.success(data.message);

                                }

                            }

                        }

                    });



                });*/

				

				

				

				

				$(document).ready(function() {				
					
					
					
					$(".post-input-field").mention({
						users: [
						@foreach(App\User::get() as $user)
						@if($user->name!='')
						{							
							name: '{{$user->name}}',
							username: '{{$user->username}}',
							image: '/assets/front/img/{{$user->avatar}}'
						},
						
						@endif
						@endforeach
						]
					});


			

					$(document).on('click', '.delete-comment', function(e) {

			

						e.preventDefault();

			

						var post = $(this).data('post');

						var comment = $(this).data('comment');

						$(this).addClass('removable');

			

						swal({

						  title: "Are you sure? You Want To Delete This Comment.",

						  text: "Once deleted, you will not be able to recover this comment.",

						  icon: "warning",

						  buttons: true,

						  dangerMode: true,

						})

						.then((willDelete) => {

						  if (willDelete) {

			

								$.ajax({

									data: {

										_token: '{{ csrf_token()}}',

										post_id: post,

										comment_id: comment

									},

									url: '{{ route('user.comment.delete') }}', 

									type: 'POST',

									success: function(response) {

										if (response.success && response.success == 1) {

			

											$('.removable').parent().parent().next('.sinlge-comment-show-item-separator').remove();

											$('.removable').parent().parent().slideUp().remove();

			

											swal("Comment has been deleted!", {

											  icon: "success",

											});

			

											var commentIncrease = $('#countComment').text();

											var result = parseInt(commentIncrease) - 1;

											$('#countComment').text(result);

			

										} else {

											$('.removable').removeClass('removable');

										}

									}

								});

						  }

						});

			

					});

			

				});

				

				

				

				

				$(document).on('click', '.dislike', function (e) {

                    e.preventDefault();



                    var post = $(this).data('post');



                    $(this).addClass('actv');



                    $.ajax({

                        type:"POST",

                        url:"{{ route('user.dislike') }}",

                        data: {post_id: post, _token: '{{ csrf_token() }}'},

                        success:function(data){

                            if (data.success && data.success == 1) {

                                if (data.message) {

                                  //  toastr.success(data.message);

                                }

                            }

                        }

                    });



                });

				

				$(document).on('click', '.topiclist', function (e) {

                    e.preventDefault();



                    var post = $(this).data('post');

					

					console.log(post);

					 $(this).removeClass('inactive');

                    $(this).addClass('active');

					$(this).hide('slow');
					
					$(this).find('i').remove();

					

					$('#activeTopicList').append(this);

					$(this).show('slow');

					 $(this).removeClass('topiclist');

					// $(this).prependTo('<i class="fas fa-minus-circle"></i>');

					// $('<i class="fas fa-minus-circle"></i>').insertBefore(this).find('span');

					 $(this).find('span').prepend('<i class="fas fa-minus-circle"></i>');

					$(this).addClass('remtopic');

					$(this).addClass('topic');

					



                    $.ajax({

                        type:"POST",

                        url:"{{ route('user.topic') }}",

                        data: {topic_id: post, _token: '{{ csrf_token() }}'},

                        success:function(data){

                            if (data.success && data.success == 1) {
								
								swal("Topic Added", {
                                            icon: "success",
                                        });


                                if (data.message) {

                                  //  toastr.success(data.message);

                                }

                            }

                        }

                    });



                });

				

				$(document).on('click', '.remtopic', function (e) {

                    e.preventDefault();



                    var post = $(this).data('post');

					

					console.log(post);

					 $(this).addClass('inactive');

                    $(this).removeClass('active');

					$(this).hide('slow');



                    $.ajax({

                        type:"POST",

                        url:"{{ route('user.remtopic') }}",

                        data: {topic_id: post, _token: '{{ csrf_token() }}'},

                        success:function(data){

                            if (data.success && data.success == 1) {

                                if (data.message) {

                                  //  toastr.success(data.message);

                                }

                            }

                        }

                    });



                });

				

				function passchecked(id){

					

					if ($('#interest'+id).prop("checked")) {

							// checked

						$('#interest'+id).prop('checked', false);	

						$('#searchint'+id).addClass('inactive');	

						$('#searchint'+id).css("background-color", "white");	

						//$('.topicList').html().remove( $('#searchint'+id+' span').html()+', ');

						var currentval = $('.topicList').html();

						currentval = currentval.replace($('#searchint'+id+' span').html()+', ','');

						//console.log(currentval);

						$('.topicList').html(currentval);

						

					}

					else{

						$('#interest'+id).prop('checked', true);

						$('#searchint'+id).removeClass('active');

						$('#searchint'+id).css("background-color", "#A0D468");

						$('.topicList').append( $('#searchint'+id+' span').html()+', ');

					}

				}

				function hide(element){

					  // element.style.display = 'none';

					  //$(element).hide();

					  $(element).removeClass('menu-active');

					  

					}

				

				

				$(document).on('click', '.intClk', function (e) {

                    e.preventDefault();



                    var post = $(this).data('post');

					

					console.log(post);

					 

                    



                     if ($(this).prop("checked")) {

							// checked

						$('#interest'+post).prop('checked', false);	

						$(this).addClass('inactive');				}

					else{

						$('#interest'+post).prop('checked', true);

						$(this).removeClass('active');

					}

                });

				

				

		

				

				$(document).on('click', '.favorite', function (e) {

                    e.preventDefault();



                    var post = $(this).data('post');



                    $.ajax({

                        type:"POST",
						context: this,
                        url:"{{ route('user.favorite') }}",

                        data: {post_id: post, _token: '{{ csrf_token() }}'},

                        success:function(data){

                            if (data.success && data.success == 1) {
								$(this).addClass('actv');

                                if (data.message) {

                                  //  toastr.success(data.message);

                                }

                            }
							else
							{
								$(this).removeClass('actv');
								$(this).removeClass('active');
							}

                        }

                    });



                });

				

				

				





    (function ($) {

        $(document).ready(function () {
			
			
				
				$('.manual-ajax').click(function(event) {
				  event.preventDefault();
					$('.modal').modal();
					/*$('.modal').show();
					//alert('here');
				  this.blur(); // Manually remove focus from clicked link.
				  //$.get("/ajaxpage?url="+this.href, function(html) {
					//$('.modal').html(html).modal();
				  //});
					$('.modal #iframe1').attr("src", "/ajaxpage?url="+this.href);
					*/
				});
			
			
				$(document).on('click', '.cross', function(e) {
					$('#cont').fadeOut();
					$('#type').val('');
					$('#link').val('');
					//location.reload(); 
				 });
		
				$(document).on('click', '.notification a', function(e) {
                    e.preventDefault();
					var href = e.currentTarget.getAttribute('href')
                    var message = $(this).closest('.notification').data('message');
					console.log('in change notificaiton '+message);

                    $.ajax({
                        type: "POST",
						context: href,
                        url: "{{ route('user.updateNotifyStatus') }}",
                        data: {
                            id: message,
							_token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            
								window.location= href;
                        }
                    })
                });
				
				
				$(document).on('click', '#readAlerts', function(e) {
                    //e.preventDefault();
                   // var message = $(this).closest('.notification').data('message');
					//console.log('in change notificaiton '+message);

                    $.ajax({
                        type: "POST",
						//context: this,
                        url: "{{ route('user.updateNotifyStatusAll') }}",
                        data: {
                            //id: message,
							_token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success && data.success == 1) {
								
								
								swal("All alerts cleared!", {
                                            icon: "success",
                                        });
										
								location.reload(); 

								
                            } else {
                                
                            }
                        }
                    })
                });
				
				
				
                    $(function() {
                                $('.infinite-scroll').jscroll({
                                            autoTrigger: true,
                                            loadingHtml: '<div class="section-box infinite-loading"><i class="fa fa-spinner fa-spin fa-3x fa-fw" aria-hidden="true"></i></div>',
                                            padding: 4500,nextSelector:'ul.pagination li.active + li a',contentSelector:'div.infinite-scroll',callback:function(){$('ul.pagination').remove();constplayers=Array.from(document.querySelectorAll('.player')).map(p=>new Plyr(p))}})});
                
                        
			
			
			
					function findUrls( text )
					{
						var source = (text || '').toString();
						var urlArray = [];
						var url;
						var matchArray;

						// Regular expression to find FTP, HTTP(S) and email URLs.
						var regexToken = /(((ftp|https?):\/\/)[\-\w@:%_\+.~#?,&\/\/=]+)|((mailto:)?[_.\w-]+@([\w][\w\-]+\.)+[a-zA-Z]{2,3})/g;

						// Iterate through any URLs in the text.
						while( (matchArray = regexToken.exec( source )) !== null )
						{
							var token = matchArray[0];
							urlArray.push( token );
						}

						return urlArray;
					}
			
			
			 		$('.post-input-field').on("paste", function(editor, event) {
                        //var value = this.getText();
						console.log('in paste');

						setTimeout(function(){
							//do what you need here


							var start_value = findUrls($('.post-input-field').val());
							var value = start_value[0];
							console.log(value);
							var urlpat = /^https?:\/\//i;
							if (urlpat.test(value)) {//.test(value)
								console.log('paste test is good');
								$("#loaderimg").show();
								$('#urldatadiv').css('display', 'none');
								setTimeout(function() {
									$.ajax({
										type: "POST",
										async: true,   // this will solve the problem
										url:"https://guteurls.de/ajax-online.php",//url:"https://guteurls.de/ajax.php/"+value+"?html=1&u="+value+"&r=https://agwiki.dev2.blayzer.com/urlpreview?url="+value+"&h=6458&e=&d=0&w=0",//url: "{{ route('urlpreview') }}?url="+value,//url: "{{ route('user.urllink.data') }}",
										data: {
											'u': value,
											'task':'loadBox',
											'email':'ajc02079et42xo1s4',
											_token: '{{ csrf_token() }}'
										},
										async: !1,
										success: function(data) {
											//console.log(data);
											console.log('link success');
											//var res = data.split("!~");
											var res = data.split("!~");
											
											console.log('begin clean res');
											res[0] = res[0].replace(/src=\".*?(http:\/\/[^\"]+)\"/g,'src=\"$1\"');
											
											// res[0] = res[0].replace('target="blank"', 'rel="modal:open');
											
											//res[0].split('<a').join('rel="modal:open');
											
											$('#urldatadiv').css('display', 'block');
											$('#urldatadiv').html(res[0]);
											
											$('#urldatadiv').find('h1').replaceWith(function() {
														return '<h2><a href="'+value+'">' + $(this).text() + '</a></h2>';
											  });
											
											$("#urldatadiv a").removeAttr('target');
											$("#urldatadiv a").attr("rel","modal:open");
											$("#urldatadiv a").attr("href", "/ajaxpage?url="+value);
											
											//console.log(res[0]);
											
											
											console.log("image src "+$('#urldatadiv img').attr('src'));
											console.log("iframe length "+$('#urldatadiv iframe').length);

										   if(  $('#urldatadiv img').attr('src')===undefined && ($('#urldatadiv iframe').length === 0 || $('#urldatadiv iframe').length === undefined ) )
										   { //value.indexOf("youtube") ||
											   
												setTimeout(function() {
													
															$("#loaderimg").show();


															   console.log('no image url for '+value)
															  $.ajax({
																	type: "POST",
																	async: true,   // this will solve the problem
																	url:"{{ route('user.urllink.data') }}",
																	data: {
																		urllink: value,
																		_token: '{{ csrf_token() }}'
																	},
																	async: !1,
																	success: function(data) {
																		
																		$("#loaderimg").hide();

																		var res = data.split("!~");
																		res[0] = res[0].replace(/src=\".*?(http:\/\/[^\"]+)\"/g,'src=\"$1\"');
																		$('#urldatadiv').css('display', 'block');
																		$('#urldatadiv').html(res[0]);
																		$('#urldatadiv').append( '<a href="/ajaxpage?url='+value+'" rel="modal:open" class="pull-right readmore">Read More</a><br>' );
																		$('#urldataval').val($('#urldatadiv').html());
																		

																	}
																});

												}, 1000);
											}
											else if(value.indexOf("youtube") >= 1){
													
												
												setTimeout(function() {
													
															$("#loaderimg").show();


															   console.log('in youtube '+value)
															  $.ajax({
																	type: "POST",
																	async: true,   // this will solve the problem
																	url:"{{ route('user.urllink.data') }}",
																	data: {
																		urllink: value,
																		_token: '{{ csrf_token() }}'
																	},
																	async: !1,
																	success: function(data) {
																		
																		$("#loaderimg").hide();

																		var res = data.split("!~");
																		res[0] = res[0].replace(/src=\".*?(http:\/\/[^\"]+)\"/g,'src=\"$1\"');
																		res[0] = res[0].replace(/<img[^>]*>/g,"");
																		$('#urldatadiv').css('display', 'block');
																		$('#urldatadiv h2').html(res[0]);
																		//$("#urldatadiv .article-img > img").remove();
																		//$('#urldatadiv').append( '<a href="/ajaxpage?url='+value+'" rel="modal:open" class="pull-right readmore">Read More</a><br>' );
																		$('#urldataval').val($('#urldatadiv').html());
																		

																	}
																});

												}, 1000);
												
												
												
											}
											
											
											$('#urldatadiv .guteurlsTop').remove(  );
											//$('#urldatadiv .guteurlsBox').remove(  );
											
											$('#urldatadiv').append( '<a href="/ajaxpage?url='+value+'" rel="modal:open"  class="pull-right readmore">Read More</a><br>' );
											
											$('#urldatadiv').css("margin-bottom","20px");
											
											$('#urldatadiv iframe').css("width","100%");
											$('#urldatadiv iframe').css("min-height","300px");
											
											
											$('#urldataval').val($('#urldatadiv').html());
											$('#hrefurl').val(value);//$('#hrefurl').val(res[1]);
											$("#loaderimg").hide();
											//var newtext = start_value.replace(value,'');
											//$('.post-input-field').val(newtext);
											
											
											//emoeditor[0].emojioneArea.html('');
											//$(".emojionearea-editor").html('');
											//$(".article-emoji-input").val('');
										}
									})
								}, 100);
								
								
								setTimeout(function(){ 
								
								console.log("image src "+$('#urldatadiv img').attr('src'));
								console.log("iframe length "+$('#urldatadiv iframe').length);
									
									
									
									if($('#urldatadiv img').attr('src')!==undefined && ($('#urldatadiv iframe').length === 0 || $('#urldatadiv iframe').length === undefined ))		   
									{
										console.log('getting new image');
										$.ajax({
											type: "POST",
											async: true,   // this will solve the problem
											url:"{{ route('user.urllink.data') }}",
											data: {
												image: $('#urldatadiv img').attr('src'),
												_token: '{{ csrf_token() }}'
											},
											async: !1,
											success: function(data) {
												//console.log(data);
												console.log('image link success');
												if (data !== 'fail')
												{

													$('#urldatadiv img').attr('src',data);
													$('#urldataval').val($('#urldatadiv').html());
													console.log('img swap complete');

												}
											}
										})
											
									}
								
								}, 2000);
								
								
								
							}
							else
							{
								console.log('no url');
								//alert('URL issue, please paste again');
								//$('.post-input-field').html('');
							}
						}, 500);
                    });
                    /*$('.post-input-field').on("keyup", function(editor, event) {
                        //var value = this.getText();
						var value = $(this).val();
                        if (value == "") {
                            $('#urldatadiv').css('display', 'none');
                            $('#urldatadiv').html('');
                            $('#urldataval').val('');
                            $('#hrefurl').val('')
                        }
                    });*/
			
			
			
			
			
			
			
			 $(document).on('click', '.like', function(e) {
                    e.preventDefault();
                    var post = $(this).data('post');
					console.log('in like');
                    $.ajax({
                        type: "POST",
						context: this,
                        url: "{{ route('user.like') }}",
                        data: {
                            post_id: post,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success && data.success == 1) {
								 $(this).addClass('actv');
									var myString = $(this).parent().find('span').prop('innerHTML');
									var result = myString.match(/\((.*)\)/);
									var currentValue = result[1];
									console.log(currentValue);
									var newValue = parseInt(currentValue.trim()) + 1;
									$(this).parent().find('span').prop('innerHTML', '(' + newValue + ')');

                                if (data.message) {
                                    toastr.success(data.message)
                                }
                            }
							else
							{
								
								$(this).removeClass('actv');
								$(this).removeClass('active');
									var myString = $(this).parent().find('span').prop('innerHTML');
									var result = myString.match(/\((.*)\)/);
									var currentValue = result[1];
									console.log(currentValue);
									var newValue = parseInt(currentValue.trim()) - 1;
									$(this).parent().find('span').prop('innerHTML', '(' + newValue + ')');
								
							}
                        }
                    })
                });
				
				
				
				
				
				
				@if(isset($_GET['topic']))
				 		$('.page-link').each(function() {
							var href = $(this).attr('href');
							console.log(href);
							if (href) {
								href += (href.match(/\?/) ? '&' : '?') + 'topic={{$_GET["topic"]}}';
								$(this).attr('href', href);
								console.log(href);
							}
						});
    			 @endif
                $(document).on('click', '.delete-post', function(e) {
                    e.preventDefault();
					console.log('in delete');
                    var post = $(this).data('post');
                    $(this).addClass('deletable');
                    swal({
                        title: "Are you sure? You Want To Delete This Post.",
                        text: "Once deleted, you will not be able to recover this post.",
                        icon: "warning",
                        buttons: !0,
                        dangerMode: !0,
                    }).then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    post_id: post
                                },
                                url: "{{ route('user.post.delete') }}",
                                type: 'POST',
                                success: function(response) {
                                    if (response.success && response.success == 1) {
                                        $('.deletable').parent().parent().parent().parent().slideUp().remove();
                                        swal("Poof! Post has been deleted!", {
                                            icon: "success",
                                        })
                                    } else {
                                        $('.deletable').removeClass('deletable')
                                    }
                                }
                            })
                        }
                    })
                });
				
				
				
				
				
				
				$(document).on('click', '.delete-share', function(e) {
                    e.preventDefault();
					console.log('in delete');
                    var post = $(this).data('post');
                    $(this).addClass('deletable');
                    swal({
                        title: "Are you sure? You Want To Delete This Share.",
                        text: "Once deleted, you will not be able to recover this share.",
                        icon: "warning",
                        buttons: !0,
                        dangerMode: !0,
                    }).then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    post_id: post
                                },
                                url: "{{ route('user.share.delete') }}",
                                type: 'POST',
                                success: function(response) {
                                    if (response.success && response.success == 1) {
                                        $('.deletable').parent().parent().parent().parent().slideUp().remove();
                                        swal("Poof! Share has been deleted!", {
                                            icon: "success",
                                        })
                                    } else {
                                        $('.deletable').removeClass('deletable')
                                    }
                                }
                            })
                        }
                    })
                });
				
				
                $(document).on('click', '#mobile-right-nav-icon', function() {
                    $('.mobile-nav-header-right .sidebar-area').css('display', 'block');
                    $(this).attr('id', 'mobile-right-nav-icon-opened')
                });
                $(document).on('click', '#mobile-right-nav-icon-opened', function() {
                    $('.mobile-nav-header-right .sidebar-area').css('display', 'none');
                    $(this).attr('id', 'mobile-right-nav-icon')
                });
                $('[data-toggle="tooltip"]').tooltip();
				
				
               
                $(document).on('click', '.share', function(e) {
                    e.preventDefault();
                    var post = $(this).data('post');
					var group = $(this).data('group');

                    $.ajax({
                        type: "POST",
						context: this,
                        url: "{{ route('user.share') }}",
                        data: {
                            post_id: post,
							group_id: group,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success && data.success == 1) {

								swal("Poof! Post has shared!", {
                                            icon: "success",
                                        });

								var myString = $(this).parent().find('span').prop('innerHTML');
								console.log(myString);
								myString = myString.replace("Share", "")
								var result = myString.match(/\((.*)\)/);
								var currentValue = result[1];
								console.log(currentValue);
								var newValue = parseInt(currentValue.trim()) + 1;
								console.log(newValue);
								$(this).parent().find('span').prop('innerHTML', ' Share(' + newValue + ')');
								$(this).addClass('active');


                                toastr.success("Successfully Shared On Your Profile")
                            } else {
                                //toastr.error("Unexpected Error! Please try Again.")
								swal({

								  title: "Share Failed: There may already be an aricle shared for this post",

								  
								  icon: "warning",

								 
								  dangerMode: true,

								})

                            }
                        }
                    })
                })
				
				
				
				
				
				
				
				            function upload(file, type) {
                                var formdata = new FormData();
                                formdata.append("_token", "{{ csrf_token() }}");
                                formdata.append("type", type);
                                formdata.append("file", file);
                                var ajax = new XMLHttpRequest();
                                ajax.upload.addEventListener("progress", progressHandler, !1);
                                ajax.addEventListener("load", completeHandler, !1);
                                ajax.addEventListener("error", errorHandler, !1);
                                ajax.addEventListener("abort", abortHandler, !1);
                                ajax.open("POST", "{{ route('file.store') }}");
                                ajax.send(formdata)
                            }
                            function progressHandler(event) {
                                var percent = (event.loaded / event.total) * 100;
                                $("#post-ajax-loader").fadeIn();
                                $("#prog").css('width', Math.round(percent) + '%');
                                $("#prog").text(Math.round(percent) + '%')
                            }
                            function completeHandler(event) {
                                var jso = JSON.parse(event.target.responseText);
                                if (jso.error) {
                                    $("#link").val(null);
                                    $('#type').val(null);
                                    $('input[type="file"]').val(null);
                                    $("#prog").css('width', '0%');
                                    $("#prog").text('0%');
                                    $("#prog").fadeOut();
                                    $('#post-ajax-loader').fadeOut();
                                    toastr.error(jso.error);
                                   // $('.emojionearea-editor').attr('placeholder', 'New Article')
                                } else {
                                    $("#prog").text("Upload Completed");
                                    $("#link").val(jso.link);
                                    $('#type').val(jso.type);
                                  //  $('.emojionearea-editor').attr('placeholder', 'Caption');
                                    $('input[type="file"]').val(null);
									swal("Uploaded Successfully. Just Post This", {
                                            icon: "success",
                                        });
                                   // toastr.success('Uploaded Successfully. Just Share This');
                                    if (jso.type == 'image') {
                                        $('#cont').fadeIn();
                                        $('#cont div').html('<img src="{{ url("assets/front/content/") }}/' + jso.link + '" class="img-responsive" style="width: 100%;margin-bottom: 20px;">');
                                    } else if (jso.type == 'video') {
                                        $('#cont').fadeIn();
                                        var html = '<video id="player" playsinline controls>\n' + '<source src="{{ url("assets/front/content/") }}/' + jso.link + '" type="video/mp4" id="player-src">\n' + '</video>';
                                        $('#cont div').html(html);
                                        const player = new Plyr('#player');
                                    } else if (jso.type == 'audio') {
                                        $('#cont').fadeIn();
                                        var html = '<audio id="player" controls> <source src="{{ url("assets/front/content/") }}/' + jso.link + '" type="audio/mp3" id="player-src"> < /audio>';$('#cont div').html(html);const player=new Plyr('#player')}
                                        setTimeout(function() {
                                            $("#prog").css('width', '0%');
                                            $("#prog").text('0%');
                                            $("#prog").fadeOut();
                                            $('#post-ajax-loader').fadeOut()
                                        }, 1500);
                                    }
									//else
									//{
									//	$('#cont').fadeIn();
                                   //     $('#cont div').html('<img src="/assets/front/css/doc_upload.png">');
									//}
                                }
                                function errorHandler(event) {
                                    $("#link").val(null);
                                    $('#type').val(null);
                                    $('input[type="file"]').val(null);
                                    $("#prog").css('width', '0%');
                                    $("#prog").text('0%');
                                    $("#prog").fadeOut();
                                    $('#post-ajax-loader').fadeOut();
                                    toastr.error('Upload Failed. Please Try Again.');
                                   // $('.emojionearea-editor').attr('placeholder', 'New Article')
                                }
                                function abortHandler(event) {
                                    $("#link").val(null);
                                    $('#type').val(null);
                                    $('input[type="file"]').val(null);
                                    $("#prog").css('width', '0%');
                                    $("#prog").text('0%');
                                    $("#prog").fadeOut();
                                    $('#post-ajax-loader').fadeOut();
                                    toastr.error('Upload Aborted. Please Try Again.');
                                 //   $('.emojionearea-editor').attr('placeholder', 'New Article')
                                }
				
			
			
			
			                    $(document).on('change', '#image', function(e) {
									console.log('in image');
									if (this.files.length) {
										console.log('in image length');
										var file = this.files[0];
										upload(file, 'image');
										$('#video').val(null);
										$('#audio').val(null);
										$('#youtube').val(null);
										$('#vimeo').val(null);
										$('#doc').val(null)
									}
								});
								$(document).on('change', '#video', function(e) {
									if (this.files.length) {
										var file = this.files[0];
										if (file.type === 'video/mp4') {
											upload(file, 'video');
											$('#image').val(null);
											$('#audio').val(null);
											$('#youtube').val(null);
											$('#vimeo').val(null);
											$('#doc').val(null)
										} else {
											toastr.warning('Only MP4 is supported')
										}
									}
								});
								$(document).on('change', '#doc', function(e) {
									if (this.files.length) {
										var file = this.files[0];
										$('#type').val('doc');
										upload(file, 'doc');
										$('#image').val(null);
										$('#video').val(null);
										$('#audio').val(null);
										$('#youtube').val(null);
										$('#vimeo').val(null)
									}
								});
			

            @if($errors->any())

            @foreach($errors->all() as $error)

            toastr.error("{{ $error }}");

            @endforeach

            @endif



            @if(session('success'))

            toastr.success("{{ session('success') }}");

            @endif



            @if(session('alert'))

            toastr.warning("{{ session('alert') }}");

            @endif

        });

    })(jQuery);

</script>

@yield('js')



 <script type="text/javascript" src="/assets/front/js/plugins.js" async></script>

<script type="text/javascript" src="/assets/front/js/custom.js" async></script>



@section('js')
    <script src="https://cdn.plyr.io/3.2.4/plyr.js">
    </script>
    <script type="text/javascript" src="https://cdn.rawgit.com/zenorocha/clipboard.js/v2.0.0/dist/clipboard.min.js">
    </script>
    <script src="/assets/front/twitter/emojionearea.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/smoothState.js/0.7.2/jquery.smoothState.min.js">
    </script>
    <script>
        (function($) {
            $(document).ready(function() {
                const players = Array.from(document.querySelectorAll('.player')).map(p => new Plyr(p));
                $(document).on('click', '.mail-share', function(e) {
                    e.preventDefault();
                    var post_id = $(this).data('id');
                    console.log(post_id)
                })
            })
        })(jQuery);
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js">
    </script>
   
    
    <script>
        (function($) {
            $(document).ready(function() {
                $(document).on('click', '#get_search-form', function(e) {
                    e.preventDefault();
                    $('.search-area-mobile').css('display', 'block');
                    $(this).attr('id', 'get_search_clicked')
                })
                $(document).on('click', '#get_search_clicked', function(e) {
                    e.preventDefault();
                    $('.search-area-mobile').css('display', 'none');
                    $(this).attr('id', 'get_search-form')
                })
                toastr.success("Post Published Successfully.")
            });
            $(document).on('click', '.imgclickcls', function() {
                var image = $(this).attr('src');
                var theImage = new Image();
                $(theImage).load(function() {
                    if (this.width >= 1000) {
                        $('#imgmodalwidth').css('width', '1050')
                    } else {
                        $('#imgmodalwidth').css('width', this.width + 50)
                    }
                });
                theImage.src = image;
                $("#imagesrc").attr("src", image)
            })
        })(jQuery);
    </script>
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5b028cce69cdbe02">
    </script>
    <script type="text/javascript">
       
                  /*  const emoeditor = $(".article-emoji-input").emojioneArea({
                        pickerPosition: "bottom",
                        filtersPosition: "bottom",
                    });
					*/
                   
                    $('[rel="tooltip"]').tooltip();
                    const players = Array.from(document.querySelectorAll('.player')).map(p => new Plyr(p));

                    $(document).on('change', '#audio', function(e) {
                        if (this.files.length) {
                            var file = this.files[0];
                            if (file.type === 'audio/mpeg' || file.type == 'audio/mp3') {
                                upload(file, 'audio');
                                $('#image').val(null);
                                $('#video').val(null);
                                $('#youtube').val(null);
                                $('#vimeo').val(null);
                                $('#doc').val(null)
                            } else {
                                toastr.warning('Only MP3 is supported')
                            }
                        }
                    });
                    $(document).on('click', 'label[for="youtube"]', function(e) {
                                swal("Enter Youtube Video ID (Like 2X9eJF1nLiY)", {
                                    content: "input",
                                }).then((value) => {
                                        if (value != '') {
                                            $('#youtube').val(value);
                                            $('#type').val('youtube');
                                            var html = '<div id="player" data-plyr-provider="youtube" data-plyr-embed-id="' + value + '"> < /div>\n';$('#cont div').html(html);const player=new Plyr('#player');$('#cont div').css('height','300px');$('#cont').fadeIn();$('.emojionearea-editor').attr('placeholder','Caption');$('#image').val(null);$('#video').val(null);$('#audio').val(null);$('#vimeo').val(null);$('#doc').val(null)}})});$(document).on('click','label[for="vimeo"]',function(e){swal("Enter Vimeo Video ID (Like 114042185)",{content:"input",}).then((value)=>{if(value!=''){$('#vimeo').val(value);$('#type').val('vimeo');var html='<div id="player" data-plyr-provider="vimeo" data-plyr-embed-id="'+value+'"> < /div>\n';$('#cont div').html(html);const player=new Plyr('#player');$('#cont div').css('height','300px').fadeIn();$('#cont').fadeIn();$('.emojionearea-editor').attr('placeholder','Caption');$('#image').val(null);$('#video').val(null);$('#audio').val(null);$('#youtube').val(null);$('#doc').val(null)}})});$(document).on('click','#cont span',function(e){$('#cont').fadeOut();$('#cont div').html('');$('.emojionearea-editor').attr('placeholder','New Article');var link=$('#link').val();if(link!=''){$.ajax({type:"POST",url:"{{ route('user.file.delete') }}",data:{link:link,_token:'{{ csrf_token() }}'}})}
                                            $('#type').val('')
                                        }); /*$(".article-emoji-input").emojioneArea({
                                        pickerPosition: "bottom",
                                        filtersPosition: "bottom",
                                    });*/ $(".nice-scroll").niceScroll({
                                        cursorcolor: "#07cb79",

                                        cursorwidth: "10px",
                                        background: "rgba(26, 39, 53, 0.3)",
                                        cursorborder: "1px solid aquamarine",
                                        cursorborderradius: 10
                                    })
                                });

    
	
	

	</script>


	
	
    @endsection
	
	

	


</body>

</html>

