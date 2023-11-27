<?php

                           if(isset(Auth::user()->id))

                           {

                            $count=App\User::StaticunreadMessageCount(Auth::user()->id);

                            //$messages=App\User::StaticunreadMessages(Auth::user()->id);



                            //$notifications = App\User::StaticgetLatestNotifications(Auth::user()->id);

                            $countN = App\User::StaticunreadNotificationsCount(Auth::user()->id);

                            //die(print_r($notifications));

                           // $messages=array_merge($messages, $notifications);



                           //$count = $count+$countN;

                           }

                        ?>



<div class="sidebar shadow-medium">

        <div data-height="cover" class="caption bottom-0" id="innerSidebar" >

            <div class="top-30">

				<div class="landing-header">

					<a href="/"><img src="/assets/front/img/logo_md.png" alt="AgWiki, Solving World Food Problems Socially"></a>

				</div>



				<div class="landing-icons color-theme">

          <a href="/feed">
						<i class="bg-blue2-light shadow-icon-large far fa-newspaper"></i>
						<em class="color-theme">Feed</em>
					</a>

					<a href="/interests">
						<i class="bg-green1-dark shadow-icon-large far fa-file-alt"></i>
						<em class="color-theme">Topics</em>
					</a>

					<a href="/groups">
						<i class="bg-yellow2-dark shadow-icon-large fas fa-users"></i>
						<em class="color-theme">Groups</em>
					</a>

          <a href="/profile/<?php echo e(Auth::user()->username); ?>">
						<i class="bg-dark2-dark shadow-icon-large fas fa-user-alt"></i>
						<em class="color-theme">Profile</em>
					</a>

          <a href="/feed?fav=1" >
            <i class="bg-yellow1-light shadow-icon-large fas fa-star"></i>
            <em class="color-theme">Bookmarks</em>
          </a>

					<a href="/peoples">
						<i class="bg-orange-light shadow-icon-large fas fa-user-check"></i>
						<em class="color-theme">People</em>
					</a>

					<a href="/commodities" >
						<!--<span class="badge">new!</span>-->
						<i class="bg-mint-light shadow-icon-large far fa-chart-bar"></i>
						<em class="color-theme">Commodities</em>
					</a> 

          <a href="/weather">
						<i class="bg-blue1-light shadow-icon-large fas fa-sun"></i>
            
            <em class="color-theme">Weather</em>
					</a>

          <!-- <a href="/marketplace" class="inactive">
            <i class="bg-orange-dark shadow-icon-large fas fa-store"></i>
            <em class="color-theme">Marketplace</em>
          </a> -->

					<a href="#" data-menu="menu-alerts">
						<i class="bg-gradient-red1 shadow-icon-large fas fa-bell"></i>
						<?php if($countN>0): ?><span class="badge"><?php echo e($countN); ?></span><?php endif; ?>
						<em class="color-theme">Alerts</em>
					</a>

					<a href="#" data-menu="menu-messages">
							<i class="bg-gradient-blue2 shadow-icon-large fas fa-envelope"></i>
							<?php if($count>0): ?><span class="badge"><?php echo e($count); ?></span><?php endif; ?>
							<em class="color-theme">Messages</em>
					</a>

					<a href="https://go.agwiki.com/#features" target="_blank">
						<i class="bg-purple shadow-icon-large fas fa-info-circle"></i>
						 <em class="color-theme">About</em>
					</a>
					<!-- <a href="#" data-menu="menu-warning">
						<i class="bg-white shadow-icon-large fas fa-briefcase-medical" style="color:red"></i>
						<em class="color-theme">Support</em>
					</a> -->
					<a href="https://education.agwiki.com/" target="_blank">
						<i class="bg-green1-dark shadow-icon-large fas fa-laptop-code"></i>
						 <em class="color-theme">Education</em>
					</a>


				</div>
                <div class="padding-10 clear" >
                
                	<a href="mailto:?subject=<?php echo rawurlencode(htmlspecialchars_decode("I'd like to share this site with you"));?>&body=<?php echo rawurlencode(htmlspecialchars_decode("Please join AgWiki.com.  Together we can solve the world's food problems."));?>" style="width:100%; text-align:center" class="btn btn-success button button-m button-round-small bg-blue1-dark shadow-small" >Invite Friends</a>
                
					
                </div>

				<div class="padding-10 clear" >
					<h4>People to Follow</h4>
                    <?php if($topAuthors && count($topAuthors)): ?>
                    	<?php $__currentLoopData = $topAuthors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usero): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($usero->id != Auth::user()->id && ! $usero->isFollowedMe() && ! $usero->isBlockedByMe(Auth::user()->id)): ?>
                            <div class="one-half center-text" >
                                <a href="/profile/<?php echo e($usero->username); ?>" class="user shadow-small">
                                    <img alt="<?php echo e($usero->name); ?>" title="<?php echo e($usero->name); ?>" src="<?php echo e(asset('assets/front/img/' . $usero->avatar)); ?>">
                                </a>
                                <a href="/profile/<?php echo e($usero->username); ?>">
									<div class="username"><?php echo e($usero->name); ?></div>
									<div class="role"><?php if(isset($usero->workplace)): ?><?php echo e($usero->job($usero->id)['name']); ?><?php endif; ?></div>
									<div class="location"><?php if($usero->city): ?><?php echo e($usero->city); ?>, <?php echo e($usero->state); ?><?php endif; ?></div>
								</a>
        </div>
				<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

					<div class="clear center-text">
						<button onClick="location.reload(); " type="submit" class=" top-30 button button-m button-round-small bg-blue2-dark shadow-small">
							Refresh <i class="fas fa-spinner"></i>
						</button>
					</div>
				</div>
				<div class="divider divider-margins"></div>
				<div class="landing-footer">
					<div class="site-footer">
                    <p><a href="/privacy/">Privacy</a> | <a href="/terms/">Terms</a></p>
                    <p>Copyright © <?php echo date('Y');?> AgWiki Inc.</p>
                    </div>
				</div>
				<br><br><br>
			</div>
		</div>
</div>
