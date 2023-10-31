<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>@yield('title',  'Home' ) | {{ $gnl->title }}</title>
    <meta name="Title" Content="@yield('title', 'Login') | {{ $gnl->title }}">
    <meta name="robots" content="index,follow" /> 
    <meta name="Googlebot" content="index, follow, all" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/front/img/icon.png') }}" />
    <meta property="og:title" content="@yield('title', 'Login') | {{ $gnl->title }}"/>
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
	@endif

					@if (session('status'))
                        <div class="alert-large" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

					@if ($errors->any())
                    	 @foreach($errors->all() as $error)
                        <div class="alert-large" role="alert">
                          
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
                        <div class="alert-large" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                     @if(session('success'))
                     <div class="alert-large" role="alert">
                       {{ session('success') }}
                     </div>
                     @endif

	@if(!strstr($_SERVER['REQUEST_URI'],'/message/'))
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


<div id="page-preloader">
    <div class="loader-main"><div class="preload-spinner border-highlight"></div></div>
</div>


    @yield('content')
<div class="site-footer" style="text-align: center; background-color: #ffffff; padding:20px 0">
<p><a href="/privacy/">Privacy</a> | <a href="/terms/">Terms</a></p>
<p>Copyright © 2019 AgWiki Inc.</p>
</div>
@include('custom.footer')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="{{ asset('assets/front/pages/auth/vendor/select2/select2.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>

				$(document).on('click', '.like', function (e) {
                    e.preventDefault();

                    var post = $(this).data('post');

                    $(this).addClass('actv');

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
				
				$(document).on('click', '.topic', function (e) {
                    e.preventDefault();

                    var post = $(this).data('post');
					
					console.log(post);
					 $(this).removeClass('inactive');
                    $(this).addClass('active');

                    $.ajax({
                        type:"POST",
                        url:"{{ route('user.topic') }}",
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
				
				$(document).on('click', '.remtopic', function (e) {
                    e.preventDefault();

                    var post = $(this).data('post');
					
					console.log(post);
					 $(this).addClass('inactive');
                    $(this).removeClass('active');

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
						$('#searchint'+id).css("background-color", "white");			}
					else{
						$('#interest'+id).prop('checked', true);
						$('#searchint'+id).removeClass('active');
						$('#searchint'+id).css("background-color", "#A0D468");
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

                    $(this).addClass('actv');

                    $.ajax({
                        type:"POST",
                        url:"{{ route('user.favorite') }}",
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
				
				
				


    (function ($) {
        $(document).ready(function () {
			
			
			$(document).on('click', '.notification a', function(e) {
                    //e.preventDefault();
                    var message = $(this).closest('.notification').data('message');
					//console.log('in change notificaiton '+message);

                    $.ajax({
                        type: "POST",
						//context: this,
                        url: "{{ route('user.updateNotifyStatus') }}",
                        data: {
                            id: message,
							_token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success && data.success == 1) {

								
                            } else {
                                
                            }
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


	
</body>
</html>
