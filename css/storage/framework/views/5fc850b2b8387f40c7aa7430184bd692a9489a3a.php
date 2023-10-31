<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
 <script src="https://www.google.com/recaptcha/api.js"></script>


<style>
	.vcontainer {
  position: relative;
  overflow: hidden;
  width: 100%;
  padding-top: 56.25%; /* 16:9 Aspect Ratio (divide 9 by 16 = 0.5625) */
}

/* Then style the iframe to fit in the container div with full height and width */
.vresponsive-iframe {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  width: 100%;
  height: 100%;
}
</style>
<div>
	<div class="header header-fixed header-logo-app header-transparent">
		<a href="#" class="header-icon header-icon-1 color-white" data-menu="menu-agwiki"><i class="fas fa-bars"></i></a>
		<span class="header-title color-white"></span>
	</div>
	<div class="page-content-black"></div>
	<div>
		<div class="bg-black">
			<div data-height="cover" class="caption" style="margin-bottom: -30px">
				<div class="caption-center">
					<h1 class="brand"><img class="home" src="/assets/front/img//logo_white.png" alt="AgWiki Home Page"/></h1>
					<p class="boxed-text-large color-white opacity-80">
						We are a global community of farmers, ranchers, researchers, and nutritionists working together to solve world food problems socially.
					</p>
					<p class="boxed-text-large color-white opacity-80">
						
					</p>
					<p class="center-text color-white bottom-20">
						<a href="#" class="button button-primary button-l shadow-large right-20" data-menu="menu-signup">Sign Up</a>
						<a href="#" class="button button-next button-l shadow-large" data-menu="menu-signin">Login</a>
					</p>
					
					
					
					
					
				</div>
				
				<div class="caption-overlay bg-black opacity-70"></div>
				<div class="caption-bg" style="background-image:url(/assets/front/img//pictures/07t.jpg)"></div>
			</div>
			
			<p class="boxed-text-large color-white opacity-80" >
					<div class="container-fluid" style="background-color: #ffffff">
						
							<div class="row">
								<div class="col-md-2"></div>
									<div class="col-md-10">
														<div class="container-fluid" >
				
														<div class="row" style="color:#000000; background-color:#ffffff; padding:75px 5px">

															<div class="col-md-5" style="max-height: 300px">
																<p align="center">
																	<div class="vcontainer" >
																		<iframe class="vresponsive-iframe" width="560" height="315" src="https://www.youtube.com/embed/oeTRadyP3zc" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
																	</div>
																</p>
															</div>

															<style>
															
																.flex-center-vertically {
																	  display: flex;
																	  justify-content: center;
																	  flex-direction: column;
																	  height: 300px;
																	}
															</style>

															<div class="col-md-5" >
																<div class="flex-center-vertically">
																<h1 style="font-size: 1.9em;">A Messsage From Our CEO</h1>
																<p>AgWiki is a startup the is currently working to gain investors. If you wish to learn more, contact us at <a href="mailto:investor@agwiki.com">investor@agwiki.com</a> </p>
																<p><a target="_blank" href="https://go.agwiki.com">Learn more about AgWiki >></a></p>
																</div>
															</div>


														</div>


														
														</div>
										</div>
										<div class="col-md-2"></div>
								</div>
						
			
									<div class="row" style="background-color: #eeeeee; ">
									<div class="col-md-1"></div>
									<div class="col-md-10">
			
						
			
														<div class="row" style="background-color: #eeeeee; padding:43px 5px 0px">
															<div class="col-md-1">
															</div>
															
															<div class="col-md-10">
																<center><h2 style="color: black; font-size: 32px ">What Others Are Saying About AgWiki</h2></center>
															</div>
															<div class="col-md-1">
															</div>
														</div>

														<div class="row" style="background-color: #eeeeee; padding:50px 5px 0px ">
															<div class="col-md-2">
															</div>
															<div class="col-md-4">
																<p align="center">
																	<div class="vcontainer">
																		<iframe class="vresponsive-iframe" stlye="display:block" width="560" height="315" src="https://www.youtube.com/embed/VwCdThYAScU" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
																		
																	</div>
																</p>
															</div>
															
															

															<div class="col-md-4">

																<p align="center">
																	<div class="vcontainer">
																		<iframe class="vresponsive-iframe" width="560" height="315" src="https://www.youtube.com/embed/THcLzDfRZU0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
																		
																	</div>
																</p>
															</div>
															<div class="col-md-2">
															</div>
															
														</div>
										</div>
										<div class="col-md-1"></div>
										</div>
			
			
							</div>
						
					</p>
			
		</div>
	</div>
	<div class="menu-hider"></div>
</div>
<div id="menu-forgot" class="menu menu-box-bottom" data-menu-height="230" data-menu-effect="menu-over">
	<div class="content">
    	<form method="POST" action="<?php echo e(route('forgot.pass')); ?>">
                    <?php echo csrf_field(); ?>
		<h2 class="uppercase ultrabold top-20">Forgot Password?</h2>
		<p class="font-11 under-heading bottom-20">
			Let's get you back into your account. Enter your email to reset.
		</p>
		<div class="input-style has-icon input-style-1 input-required bottom-30">
			<span>Email</span>
			<em>(required)</em>
			<input type="email" name="email" placeholder="Email">
		</div>

		<input type="submit" class="button button-full button-m shadow-large button-round-small bg-blue1-dark top-20" value="SEND RECOVERY EMAIL">
        </form>
	</div>
</div>
<div id="menu-signin" class="menu menu-box-bottom" data-menu-height="70%" data-menu-effect="menu-over">
	<div class="content">
		<h1 class="uppercase ultrabold top-20">LOGIN</h1>
		<p class="font-11 under-heading bottom-20">
			Hello, stranger! Please enter your credentials below.
		</p>
		<form method="POST" action="<?php echo e(route('login')); ?>">
			<?php echo csrf_field(); ?>
			<div class="input-style input-style-1 input-required login">
				<label>Email</label>
				<em>(required)</em>
				<input type="email" class="form-control" name="username" id="username" placeholder="Email" value="<?php echo e(old('username')); ?>" required autofocus>
			</div>
			<div class="input-style input-style-1 input-required login">
				<label>Password</label>
				<em>(required)</em>
				<input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
			</div>
			<input type="checkbox" onclick="showPassword()"> Show Password
			<div class="top-30">
				<div class="one-half"><a href="<?php echo e(route('password.request')); ?>" data-menu="menu-forgot" class="left-text font-10">Forgot Password?</a></div>
				<div class="one-half last-column"><a data-menu="menu-signup" href="<?php echo e(route('register')); ?>" class="right-text font-10">Create Account</a></div>
			</div>
			<div class="clear"></div>
			<button type="submit" class="button button-full button-s shadow-large button-round-small bg-green1-dark top-10" style="width:100%">Login</button>
		</form>
		<div class="divider"></div>
		<a href="<?php echo e(url('/login/linkedin')); ?>" class="button bg-linkedin button-l shadow-large button-icon-left"><i class="fab fa-linkedin-in"></i> Log In With LinkedIn</a><br>
		<a href="<?php echo e(url('/login/facebook')); ?>" class="button bg-facebook button-l shadow-large button-icon-left"><i class="fab fa-facebook-f"></i> Log In With Facebook</a><br>
	</div>
</div>
<div id="menu-signup" class="menu menu-box-bottom" data-menu-height="92%" data-menu-effect="menu-parallax">
	<div class="content">
		<h1 class="uppercase ultrabold top-20">Register</h1>
		<p class="font-11 under-heading bottom-20">
			Don't have an account? Register below.
		</p>
		<form method="POST" id="regForm" action="<?php echo e(route('register')); ?>">
			<?php echo csrf_field(); ?>
			<input style="display: none" name="field_name" type="text">
			<?php if(isset($user)): ?>
			<input type="hidden" name="referral" value="<?php echo e($user->id); ?>">
			<?php endif; ?>
			<div class="input-style input-style-1 input-required login">
				<span>Email</span>
				<em>(required)</em>
				<input class="form-control" type="email" name="email" id="email" placeholder="Email" value="<?php echo e(old('email')); ?>" required>
			</div>
			
			<div class="input-style input-style-1 input-required login">
				<span>Password</span>
				<em>(required)</em>
				<input class="form-control" type="password" name="password" id="password" placeholder="Password" value="<?php echo e(old('password')); ?>" required>
			</div>
			
			
				<label for="tap" class="control-label">
					<input type="checkbox" name="tap" id="tap" value="1" required=""> Agree With <a href="/tap">Terms And Policy</a></label>
			
			<div class="top-20 bottom-20">
				<a href="#" data-menu="menu-signin" class="center-text font-11 color-gray2-dark">Already Registered? Sign In Here.</a>
			</div>
			<div class="clear"></div>
			<!--id="ajaxSubmit" -->
            <!--data-menu="welcome-screen"-->
            <button  type="submit" data-sitekey="6LcE85McAAAAAMWCElFtgLgZ2oYw7pBKvylF6fgr" data-callback='onSubmit' data-action='submit'   class="g-recaptcha button button-full button-s shadow-large button-round-small bg-blue2-dark top-10" style="width:100%">Register</button>
			<div class="clear"></div>
		</form>
		<div class="divider"></div>
		<a href="<?php echo e(url('/login/linkedin')); ?>" class="button bg-linkedin button-l shadow-large button-icon-left"><i class="fab fa-linkedin-in"></i> Log In With LinkedIn</a><br>
		<a href="<?php echo e(url('/login/facebook')); ?>" class="button bg-facebook button-l shadow-large button-icon-left"><i class="fab fa-facebook-f"></i> Log In With Facebook</a><br>
	</div>
</div>
<div id="welcome-screen" class="menu menu-box-bottom" data-menu-height="70%" data-menu-effect="menu-parallax">
	<div class="content">
		<h1 class="uppercase ultrabold top-20">Welcome!</h1>
		<p class="under-heading top-20">
			We have forwarded a link to your email that will take you to your <strong>new profile page</strong>.

		</p>
		<p>
			Once your profile is complete, we will be able to approve your new account.
		</p>
		<p>
			See you soon,<br>
			Team AgWiki

		</p>
		<div class="clear"></div>
	</div>
</div>
<div id="menu-agwiki" class="menu menu-box-left" data-menu-width="300" data-menu-effect="menu-parallax">
	<div class="nav nav-medium">
	    <a id="page-home" href="https://go.agwiki.com/#features" >
	        <i class="fas fa-globe-africa color-green1-dark"></i><span>About AgWiki</span><i class="fa fa-angle-right"></i>
	    </a>
	    <a id="page-components" href="https://go.agwiki.com/#leaders">
	        <i class="fas fa-users color-blue2-dark"></i><span>Thought Leaders</span><i class="fa fa-angle-right"></i>
	    </a>
	    <br>
		<div class="divider"></div>
	    <a id="page-menus" href="https://go.agwiki.com/contact" >
	        <i class="fab fa-youtube color-red1-dark"></i><span>Media</span><i class="fa fa-angle-right"></i>
	    </a>
	    <a id="page-site-pages" href="https://go.agwiki.com/contact#sponsor" >
	        <i class="fas fa-file-invoice-dollar color-mint-dark"></i><span>Advertise</span><i class="fa fa-angle-right"></i>
	    </a>
	    <a id="page-pageapps" href="https://go.agwiki.com/contact#sponsor" >
	        <i class="far fa-handshake color-dark1-dark"></i><span>Partners</span><i class="fa fa-angle-right"></i>
	    </a>
	    <a id="page-contact" href="https://go.agwiki.com/contact" >
	        <i class="fa fa-envelope color-blue2-dark"></i><span>Contact</span><i class="fa fa-angle-right"></i>
	    </a>

	    <div class="divider top-15"></div>
	    <p>Copyright <span class="copyright-year"></span> - AgWiki <?php echo date('Y'); ?>. All rights Reserved.</p>
	</div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script>
	
	   function onSubmit(token) {
		 document.getElementById("regForm").submit();
	   }

	
	$(document).ready(function(){

	   $('#ajaxSubmit').click(function(e){

	      e.preventDefault();

	      $.ajaxSetup({

	         headers: {

	             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

	         }

	     });

	      $.ajax({

	         url: "<?php echo e(route('register')); ?>",

	         method: 'post',

	         data: {

	            email: $('#email').val(),



	         },

	         success: function(result){

	            console.log(result);

	         }});

	      });

	   });



</script>
<script src="https://cdn.plyr.io/3.3.10/plyr.js"></script>
<script>
	const player = new Plyr('#player');
	function showPassword() {
	  var x = document.getElementById("password");
	  if (x.type === "password") {
		x.type = "text";
	  } else {
		x.type = "password";
	  }
	}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dash', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>