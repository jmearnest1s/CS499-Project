<?php

if(isset(Auth::user()->id))

{

$count=App\User::StaticunreadMessageCount(Auth::user()->id);

$messages=App\User::StaticunreadMessages(Auth::user()->id);

$notifications = App\User::StaticgetLatestNotifications(Auth::user()->id);

$countN = App\User::StaticunreadNotificationsCount(Auth::user()->id);

//die(print_r($notifications));

// $messages=array_merge($messages, $notifications);



?>

<div id="footer-menu" class="bg-green1-light">

	<a href="/home"><i class="fas fa-th"></i><span>Dashboard</span></a>

	<a href="#" data-menu="menu-alerts"><i class="fas fa-bell"></i><span>Alerts</span>
	<?php if($countN>0): ?><span class="badge"><?php echo e($countN); ?></span><?php endif; ?></a>

	<a href="#" data-menu="menu-messages"><i class="fas fa-envelope"></i><span>Messages</span>
	<?php if($count>0): ?><span class="badge"><?php echo e($count); ?></span><?php endif; ?></a>

</div>

<div id="menu-alerts" class="menu menu-box-bottom" data-menu-height="70%" data-menu-effect="menu-over">

	<div id="readAlerts" style="cursor:pointer">Mark all as read</div>

	<div class="messages">

		<?php if($notifications && count($notifications)>0): ?>
		<?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(isset($notification)): ?>
		<div data-message="<?php echo e($notification->id); ?>" class="notification item <?php echo e((($notification->status == 0)?"active":"read")); ?> ">

			<?php
			//$notification->status = 1;
			//$notification->save();
			//|| $notification->post->type == 'article'
			?>
			<?php if($notification->type == 'post'  ): ?>
			<?php if($notification->post): ?>

			<a href="/profile/<?php echo e($notification->post->user->username); ?>/"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .$notification->post->user->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . $notification->post->user->avatar)); ?>"> </a>
			 <span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>

			<span>
				<a href="/profile/<?php echo e($notification->post->user->username); ?>/">
					<h4 class="name"><?php echo e(optional($notification->post->user)->name); ?> </h4>
				</a>
			</span>

			<span class="notify-name">
            <a href="<?php echo e(route('user.post.single', $notification->post->id)); ?>">
            <i class="fas fa-newspaper" aria-hidden="true"></i> Posted a new <?php echo e($notification->post->type); ?>.</a>
			</span>
			<?php endif; ?>
			<?php elseif($notification->type == 'follow'): ?>
			<?php
			$follower = App\User::find($notification->by_id);
			?>
			<?php if($follower): ?>

			<a href="/profile/<?php echo e($follower->username); ?>"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .$follower->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . $follower->avatar)); ?>"></a>
				<span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
			<span>
				<a href="<?php echo e(route('profile', $follower->username)); ?>">
					<h4 class="name"><?php echo e($follower->name); ?> </h4>
				</a>
			</span>

			<?php if($follower->verified == 1): ?><span class="verified"><i class="fas fa-check-circle"></i></span><?php endif; ?> <span class="notify-name"><i class="fas fa-users" aria-hidden="true"></i> following you.</span>

			<?php endif; ?>

			<?php elseif($notification->type == 'group'): ?>

			<?php if($notification->post && $notification->post->group): ?>

			<a href="/profile/<?php echo e($notification->post->user->username); ?>"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .($notification->post->user)->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . ($notification->post->user)->avatar)); ?>"> </a>
<span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
			<span>

				<a href="/profile/<?php echo e($notification->post->user->username); ?>">

					<h4 class="name"><?php echo e(optional($notification->post->user)->name); ?> </h4>

				</a>

			</span>

			<?php if(optional($notification->post->user)->verified == 1): ?><span class="verified"><i class="fas fa-check-circle"></i></span><?php endif; ?>
            <a href="<?php echo e(route('user.post.single', $notification->post->id)); ?>">
                <span class="notify-name">

                <i class="fas fa-newspaper" aria-hidden="true"></i> Posted a new <?php echo e($notification->post->type); ?> in <?php echo e($notification->post->group->name); ?>.</span>
            </a>

			<?php endif; ?>

			<?php elseif($notification->type == 'group_request'): ?>

			<?php

			$invitie = App\User::find($notification->to_id);

			?>

			<?php if($invitie && $notification->group): ?>

			<a href="/profile/<?php echo e($invitie->username); ?>"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .$invitie->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . $invitie->avatar)); ?>"> </a>
<span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
			<span>

				<a href="<?php echo e(route('user.groups', $notification->group->slug)); ?>">

					<h4 class="name"><?php echo e($invitie->name); ?></h4>

				</a>

			</span>

			<?php if($invitie->verified == 1): ?><span class="verified"><i class="fas fa-check-circle"></i></span><?php endif; ?> <span class="notify-name"><i class="fas fa-users" aria-hidden="true"></i> asked to join <?php echo e($notification->group->name); ?>.</span>

			<?php endif; ?>

			<?php elseif($notification->type == 'group_accepted'): ?>

			<?php

			$invitie = App\User::find($notification->to_id);
            $invitor = App\User::find($notification->by_id);

			?>

			<?php if($invitie && $notification->group): ?>

			<a href="/profile/<?php echo e($invitor->username); ?>"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .$invitor->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . $invitor->avatar)); ?>"> </a>
<span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
			<span>

				<a href="<?php echo e(route('user.groups', $notification->group->slug)); ?>">

					<h4 class="name"><?php echo e($invitor->name); ?></h4>

				</a>

			</span>

			<?php if($invitie->verified == 1): ?><span class="verified"><i class="fas fa-check-circle"></i></span><?php endif; ?> <span class="notify-name"><i class="fas fa-users" aria-hidden="true"></i> accepted you to <?php echo e($notification->group->name); ?>.</span>

			<?php endif; ?>

			<?php elseif($notification->type == 'like'): ?>

			<?php

			$liker = App\User::find($notification->by_id);

			?>

			<?php if($liker && $notification->post): ?>

			<a href="/profile/<?php echo e($liker->username); ?>"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .$liker->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . $liker->avatar)); ?>"> </a>
<span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
			<span>

				<a href="/profile/<?php echo e($liker->username); ?>">

					<h4 class="name"><?php echo e($liker->name); ?></h4>

				</a>

			</span>

			<?php if($liker->verified == 1): ?><span class="verified"><i class="fas fa-check-circle"></i></span><?php endif; ?> <span class="notify-name"><a href="<?php echo e(route('user.post.single', $notification->post->id)); ?>"><i class="fas fa-thumbs-up" aria-hidden="true"></i> liked your post.</a></span>

			<?php endif; ?>

			<?php elseif($notification->type == 'group_invite'): ?>

			<?php

			$inviter = App\User::find($notification->by_id);

			?>

			<?php if($inviter && $notification->group): ?>

			<a href="/profile/<?php echo e($inviter->username); ?>"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .$inviter->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . $inviter->avatar)); ?>"> </a>
<span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
			<span>

				<a href="<?php echo e(route('user.groups', $notification->group->slug)); ?>">

					<h4 class="name"><?php echo e($inviter->name); ?></h4>

				</a>

			</span>

			<?php if($inviter->verified == 1): ?><span class="verified"><i class="fas fa-check-circle"></i></span><?php endif; ?> <span class="notify-name"><i class="fas fa-users" aria-hidden="true"></i> <a href="/groups/<?php echo e($notification->group->slug); ?>">invited you to join <?php echo e($notification->group->name); ?></a>.</span>

			<?php endif; ?>

			<?php elseif($notification->type == 'share'): ?>

			<?php

			$sharer = App\User::find($notification->by_id);

			?>

			<?php if($sharer): ?>

			<a href="/profile/<?php echo e($sharer->username); ?>"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .$sharer->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . $sharer->avatar)); ?>"> </a>
<span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
			<span>

				<a href="<?php echo e(route('profile', $sharer->username)); ?>">

					<h4 class="name"><?php echo e($sharer->name); ?> <?php if($sharer->verified == 1): ?><span class="verified"><i class="fas fa-check-circle"></i></span><?php endif; ?></h4>

				</a>

			</span>

			<span class="notify-name">

            <a href="<?php echo e(route('user.post.single', $notification->post_id)); ?>"><i class="fas fa-share-square" aria-hidden="true"></i> shared your post.</a>

            </span>

			<?php endif; ?>

			<?php elseif($notification->type == 'birthday'): ?>

			<?php

			$birthdayOf = App\User::find($notification->by_id);

			?>

			<?php if($birthdayOf): ?>

			<a href="/profile/<?php echo e($birthdayOf->username); ?>"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .$birthdayOf->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . $birthdayOf->avatar)); ?>"> </a>
<span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
			<span>

				<a href="<?php echo e(route('profile', $birthdayOf->username)); ?>">

					<h4 class="name"><?php echo e($birthdayOf->name); ?> <?php if($birthdayOf->verified == 1): ?><span class="verified"><i class="fas fa-check-circle"></i></span><?php endif; ?> </h4>

				</a>

			</span>

			<span class="notify-name"><i class="fas fa-birthday-cake" aria-hidden="true"></i> has a birthday today.</span>

			<?php endif; ?>

			<?php elseif($notification->type == 'comment'): ?>

				<?php

				$commenter = App\User::find($notification->by_id);

				?>


				<a href="/profile/<?php echo e($commenter->username); ?>"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .$commenter->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . $commenter->avatar)); ?>"> </a>
<span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
				<span>

					<a href="/profile/<?php echo e($commenter->username); ?>">

						<h4 class="name"><?php echo e($commenter->name); ?></h4>

					</a>

				</span>

					<?php if($commenter->verified == 1): ?><span class="verified"><i class="fas fa-check-circle"></i></span><?php endif; ?> <span class="notify-name">

						<a href="<?php echo e(route('user.post.single', $notification->post_id)); ?>"><i class="fas fa-comment" aria-hidden="true"></i> commented on a post that you are following.</a>

						</span>





			<?php elseif($notification->type == 'userTag'): ?>


				<a href="/profile/<?php echo e($notification->post->user->username); ?>"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .$notification->post->user->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . $notification->post->user->avatar)); ?>"> </a>
<span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
				<span>
					<a href="/profile/<?php echo e($notification->post->user->username); ?>">
						<h4 class="name"><?php echo e(optional($notification->post->user)->name); ?> </h4>
					</a>
				</span>
						<span class="notify-name">

						<a href="<?php echo e(route('user.post.single', $notification->post_id)); ?>"><i class="fas fa-comment" aria-hidden="true"></i> Mentioned you in this <?php echo e($notification->post->type); ?></a>

						</span>



		<?php elseif($notification->type == 'userTagComment'): ?>


				<a href="/profile/<?php echo e(@$notification->user->username); ?>"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .@$notification->user->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . @$notification->post->user->avatar)); ?>"> </a>
<span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
				<span>
					<a href="/profile/<?php echo e($notification->user->username); ?>">
						<h4 class="name"><?php echo e(optional($notification->user)->name); ?> </h4>
					</a>
				</span>
						<span class="notify-name">

						<a href="<?php echo e(route('user.post.single', @$notification->post_id)); ?>"><i class="fas fa-comment" aria-hidden="true"></i> Mentioned you in this <?php echo e(@$notification->post->type); ?></a>

						</span>




			<?php else: ?>


				<a href="/profile/<?php echo e($notification->post->user->username); ?>"><img class="profile-image" src="<?php echo e(asset('assets/front/img/' .$notification->post->user->avatar)); ?>" data-src="<?php echo e(asset('assets/front/img/' . $notification->post->user->avatar)); ?>"> </a>
<span class="messageDate"><?php echo e(\Carbon\Carbon::parse($notification->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
				<span>
					<a href="/profile/<?php echo e($notification->post->user->username); ?>">
						<h4 class="name"><?php echo e(optional($notification->post->user)->name); ?> </h4>
					</a>
				</span>
						<span class="notify-name">

						<a href="<?php echo e(route('user.post.single', $notification->post_id)); ?>"><i class="fas fa-comment" aria-hidden="true"></i> <?php echo e($notification->type); ?>d this <?php echo e($notification->post->type); ?></a>

						</span>


			<?php endif; ?>





		<?php endif; ?>
		</div>

		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

		<?php else: ?>

	<div class="single-notification-items">

		<h4 class="not-found">No Notifications Found</h4>

	</div>

	<?php endif; ?>

</div>

</div>

<div id="menu-messages" class="menu menu-box-bottom" data-menu-height="70%" data-menu-effect="menu-over">

	<div class="messages">

		<?php if($messages && count($messages)>0): ?>

		<?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

		<div class="<?php echo e((($message->status == 0)?"active":"read")); ?> ">


		<div class="single-notification-items ">
			<a href="/profile/<?php echo e($message->fromUser->username); ?>/">
			<img class="profile-image" class="profile-image" src="<?php echo e(asset('assets/front/img/' . optional($message->fromUser)->avatar)); ?>">
			</a>
			<div class="message-from">

				<a href="<?php echo e(route('message', optional($message->fromUser)->username)); ?>/#messagePost" class="name">

					 <span> <?php echo e(optional($message->fromUser)->name); ?></span><br>
					 <span class="messageDate"><?php echo e(\Carbon\Carbon::parse($message->created_at,'America/Chicago')->format('m/d/Y h:ia' )); ?></span><br>
 					<span class="notify-name"><i class="fas fa-envelope" aria-hidden="true"></i> <?php echo e(str_limit(optional($message)->content, 20, '...')); ?></span>



					</p>



				</a>

			</div>

		</div>

	</div>

	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

	<?php else: ?>
    	<div class="single-notification-items">

		<h4 class="not-found">No New Messages Found</h4>

	</div>
	<?php endif; ?>

</div>

</div>

<div id="menu-warning" class="menu menu-box-bottom" data-menu-height="70%" data-menu-effect="menu-over">

	<h1 class="center-text top-30"><i class="fas fa-3x fa-briefcase-medical color-red2-dark"></i></h1>

	<h1 class="center-text uppercase ultrabold top-30">New Features</h1>

	<p class="boxed-text-large">

		We've added some cool new features. Wanna see them? <br>

		<a href="#">CLICK HERE</a>

	</p>

	<a href="http://go.agwiki.com/support" class="button button-center-medium button-s shadow-large button-round-small bg-red1-light">I Need Support</a>

</div>



<?php $interest=App\Interest::allTopics(); ?>



<div id="menu-add-topic" class="menu menu-box-bottom" data-menu-effect="menu-parallax">

            <div class="content">

                <h1 class="uppercase ultrabold top-20">Add Topic</h1>

                <p class="font-11 under-heading bottom-20">

                    Start typing the topics you want to assign to this post.

                </p>
								<p> Selected Topics: <span class="topicList"></span><p>


                <div class="search-box search-color shadow-tiny round-large bottom-20">

                    <i class="fas fa-search">

</i>

                    <input type="text" placeholder="Search for topics... " data-search="">

                </div>

                <div class="search-results disabled-search-list">

                    <div class="link-list link-list-2 link-list-long-border">

                        <?php $__currentLoopData = $interest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $int): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <a href="#" onClick="passchecked(<?php echo e($int->id); ?>)" id="searchint<?php echo e($int->id); ?>" data-post="<?php echo e($int->id); ?>" data-filter-item="<?php echo e($int->id); ?>" data-filter-name="<?php echo e(strtolower($int->name)); ?> " class="intClk">

                            <span><?php echo e($int->name); ?></span>

                        </a>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>

                </div>

                <div class="clear">

                </div>

                <a href="#" class="close-menu button button-full button-s shadow-large button-round-small bg-blue2-dark top-10">Finished</a>

            </div>

        </div>



<?php

}

?>

<script>
/* if ('serviceWorker' in navigator) {
    console.log("Will the service worker register?");
    navigator.serviceWorker.register('service-worker.js')
      .then(function(reg){
        console.log("Yes, it did.");
     }).catch(function(err) {
        console.log("No it didn't. This happened:", err)
    });
 }*/
</script>


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-100705616-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-100705616-1');
</script>
