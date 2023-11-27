<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <title><?php echo $__env->yieldContent('title',  'Home' ); ?> | <?php echo e($gnl->title); ?></title>
    <meta name="Title" Content="<?php echo $__env->yieldContent('title', 'Login'); ?> | <?php echo e($gnl->title); ?>">
    <meta name="robots" content="index,follow" /> 
    <meta name="Googlebot" content="index, follow, all" />
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('assets/front/img/icon.png')); ?>" />
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', 'Login'); ?> | <?php echo e($gnl->title); ?>"/>
    <meta property="og:site_name" content="<?php echo e($gnl->title); ?>"/>
     <?php echo $__env->yieldContent('meta'); ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/front/img/icon.png')); ?>"/>
   
    <?php echo $__env->make('custom.header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</head>
<body class="theme-light" data-highlight="blue2">
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5ZRS2J9"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<?php if(!strstr($_SERVER['REQUEST_URI'],'/message/')): ?>
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
	<?php endif; ?>

					<?php if(session('status')): ?>
                        <div class="alert-large" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

					<?php if($errors->any()): ?>
                    	 <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="alert-large" role="alert">
                          
                            <?php echo e($error); ?>

                            
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    
                   
                    
                    <?php if(session('alert')): ?>
                        <div class="alert-large" role="alert">
                            <?php echo e(session('alert')); ?>

                        </div>
                    <?php endif; ?>

 					<?php if(session('message')): ?>
                        <div class="alert-large" role="alert">
                            <?php echo e(session('message')); ?>

                        </div>
                    <?php endif; ?>
                     <?php if(session('success')): ?>
                     <div class="alert-large" role="alert">
                       <?php echo e(session('success')); ?>

                     </div>
                     <?php endif; ?>

	<?php if(!strstr($_SERVER['REQUEST_URI'],'/message/')): ?>
	<!--<div class="mysticky-welcomebar-fixed-wrap">
		<div class="mysticky-welcomebar-content">
		<p>Now <i>Anyone</i> can invest in the future of Agriculture – exclusively through Fundify. → </p>
		</div>
		<div class="mysticky-welcomebar-btn">
		<a target="_blank" href="https://fundify.com/s/agwikicom/d5cd3b38/pitch">INVEST NOW</a>
		</div>
		<!--<a href="javascript:void(0)" class="mysticky-welcomebar-close">X</a>-->
	<!--</div>-->
	<?php endif; ?>


<div id="page-preloader">
    <div class="loader-main"><div class="preload-spinner border-highlight"></div></div>
</div>


    <?php echo $__env->yieldContent('content'); ?>
<div class="site-footer" style="text-align: center; background-color: #ffffff; padding:20px 0">
<p><a href="/privacy/">Privacy</a> | <a href="/terms/">Terms</a></p>
<p>Copyright © <?php echo date('Y'); ?> AgWiki Inc.</p>
</div>
<?php echo $__env->make('custom.footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo e(asset('assets/front/pages/auth/vendor/select2/select2.min.js')); ?>"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>

				$(document).on('click', '.like', function (e) {
                    e.preventDefault();

                    var post = $(this).data('post');

                    $(this).addClass('actv');

                    $.ajax({
                        type:"POST",
                        url:"<?php echo e(route('user.like')); ?>",
                        data: {post_id: post, _token: '<?php echo e(csrf_token()); ?>'},
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
                        url:"<?php echo e(route('user.dislike')); ?>",
                        data: {post_id: post, _token: '<?php echo e(csrf_token()); ?>'},
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
                        url:"<?php echo e(route('user.topic')); ?>",
                        data: {topic_id: post, _token: '<?php echo e(csrf_token()); ?>'},
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
                        url:"<?php echo e(route('user.remtopic')); ?>",
                        data: {topic_id: post, _token: '<?php echo e(csrf_token()); ?>'},
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
                        url:"<?php echo e(route('user.favorite')); ?>",
                        data: {post_id: post, _token: '<?php echo e(csrf_token()); ?>'},
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
                        url: "<?php echo e(route('user.updateNotifyStatus')); ?>",
                        data: {
                            id: message,
							_token: '<?php echo e(csrf_token()); ?>'
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
                        url: "<?php echo e(route('user.updateNotifyStatusAll')); ?>",
                        data: {
                            //id: message,
							_token: '<?php echo e(csrf_token()); ?>'
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
				
			
			
			
			
			
            <?php if($errors->any()): ?>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            toastr.error("<?php echo e($error); ?>");
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <?php if(session('success')): ?>
            toastr.success("<?php echo e(session('success')); ?>");
            <?php endif; ?>

            <?php if(session('alert')): ?>
            toastr.warning("<?php echo e(session('alert')); ?>");
            <?php endif; ?>
        });
    })(jQuery);
</script>
<?php echo $__env->yieldContent('js'); ?>

 <script type="text/javascript" src="/assets/front/js/plugins.js" async></script>
<script type="text/javascript" src="/assets/front/js/custom.js" async></script>


	
</body>
</html>
