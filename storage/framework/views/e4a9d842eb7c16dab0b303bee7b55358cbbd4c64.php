<?php $__env->startSection('content'); ?>









    <div class="page-content">

        <div class="users">

          <?php if(isset($_GET['user'])): ?>

            <h2 class="people-user"><?php echo e($currentUser->name); ?></h2>

            <?php endif; ?>


			<div class="tab-controls tab-animated tabs-medium" data-tab-items="3" data-tab-active="bg-blue1-dark">




				<a href="#" data-tab-active data-tab="tab-1"> Following</a>

				<a href="#" data-tab="tab-2"> Followers</a>


                <?php if(!isset($_GET['user'])): ?>

				<a href="#" data-tab="tab-3"><i class="fas fa-plus-circle"></i> Find People</a>
                <?php endif; ?>


			</div>

<div class="clear"></div>

			<div class="tab-content" id="tab-1">



            	<?php if($isFollowedMe && count($isFollowedMe)): ?>

                <?php $__currentLoopData = $isFollowedMe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $following): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

				<?php if($following->user): ?>

				<div class="one-half center-text profile">

					<a href="/profile/<?php echo e($following->user->username); ?>">

						<img class="profile-picture small" alt="<?php echo e($following->user->name); ?>" src="<?php echo e(asset('assets/front/img/'. $following->user->avatar )); ?>">

						<div class="username"><?php echo e($following->user->name); ?></div>

						<div class="role"><?php if(isset($following->user->workplace)): ?><?php echo e($following->user->job($following->user->id)['name']); ?><?php endif; ?></div>

						<div class="location"><?php if($following->user->city): ?><?php echo e($following->user->city); ?>, <?php echo e($following->user->state); ?><?php endif; ?></div>

					</a>


					<?php if(!isset($_GET['user'])): ?>
                    <a class="top-10 button button-xs button-round-small bg-blue1-dark shadow-small" href="/message/<?php echo e($following->user->username); ?>"><i class="fa fa-comments post-action comment"></i> Message</a>

                    <br>
					<?php endif; ?>
				</div>


				<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                <?php endif; ?>


        <div class="clear"></div>

				<div class="divider divider-margins"></div>



				<div class="center-horizontal center-text">

					<!--<button type="submit" class="button button-m button-round-small bg-green2-dark shadow-small">

						Load 10 More <i class="fas fa-chevron-down"></i>

					</button>-->

				</div>

			</div>

			<div class="tab-content" id="tab-2">

				<?php if($StaticisFollowingMe && count($StaticisFollowingMe)): ?>

                <?php $__currentLoopData = $StaticisFollowingMe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $isfollowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				
				<?php if($isfollowing->user2): ?>

				<div class="one-half center-text profile">

					<a href="/profile/<?php echo e($isfollowing->user2->username); ?>">

						<img class="profile-picture small" alt="<?php echo e($isfollowing->user2->name); ?>" src="<?php echo e(asset('assets/front/img/' . $isfollowing->user2->avatar)); ?>">

						<div class="username"><?php echo e($isfollowing->user2->name); ?></div>

						<div class="role"><?php if(isset($isfollowing->user2->workplace)): ?><?php echo e($isfollowing->user2->job($isfollowing->user2->id)['name']); ?><?php endif; ?></div>

						<div class="location"><?php if($isfollowing->user2->city): ?><?php echo e($isfollowing->user2->city); ?>, <?php echo e($isfollowing->user2->state); ?><?php endif; ?></div>

					</a>
          <?php if(!isset($_GET['user2'])): ?>
                    <a class="top-10 button button-xs button-round-small bg-blue1-dark shadow-small" href="/message/<?php echo e($isfollowing->user2->username); ?>"><i class="fa fa-comments post-action comment"></i> Message</a>

                    <br>
          <?php endif; ?>

				</div>
				<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                <?php endif; ?>



				<div class="divider divider-margins"></div>



				<div class="center-horizontal center-text">

					<!--<button type="submit" class="button button-m button-round-small bg-green2-dark shadow-small">

						Load 10 More <i class="fas fa-chevron-down"></i>

					</button>-->

				</div>

			</div>

			<div class="tab-content" id="tab-3">

				<!--<div class="one-half fac fac-checkbox fac-green"><span></span>

					<input id="box1-fac-checkbox" type="checkbox" value="1" checked="">

					<label for="box1-fac-checkbox">Consultant</label>

				</div>

				<div class="one-half fac fac-checkbox fac-green"><span></span>

					<input id="box2-fac-checkbox" type="checkbox" value="1" checked="">

					<label for="box2-fac-checkbox">Farmer/Grower</label>

				</div>

				<div class="one-half fac fac-checkbox fac-green"><span></span>

					<input id="box3-fac-checkbox" type="checkbox" value="1" checked="">

					<label for="box3-fac-checkbox">Industry</label>

				</div>

				<div class="one-half fac fac-checkbox fac-green"><span></span>

					<input id="box4-fac-checkbox" type="checkbox" value="1" checked="">

					<label for="box4-fac-checkbox">Nutritionist</label>

				</div>

				<div class="one-half fac fac-checkbox fac-green"><span></span>

					<input id="box5-fac-checkbox" type="checkbox" value="1" checked="">

					<label for="box5-fac-checkbox">Researcher/Scientist</label>

				</div>-->







				<div class="search-box search-color shadow-tiny round-large bottom-20">

					<i class="fa fa-search"></i>

					<input type="text" placeholder="Search for People... " data-search="">

				</div>
				
				<div >
					Please search by name, city, or occupation
				</div>



				<div class="search-results disabled-search-list">

                <?php $__currentLoopData = $Users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>



					<div class="one-half center-text">

						<a href="/profile/<?php echo e($user->username); ?>" data-filter-item="<?php echo e($user->id); ?>" data-filter-name="<?php echo e(strtolower($user->name)); ?> <?php if(isset($user->workplace)): ?><?php echo e(strtolower($user->job($user->workplace)['name'])); ?><?php endif; ?> <?php echo e(strtolower($user->city)); ?> <?php echo e(strtolower($user->state)); ?>">

							<img class="profile-picture small" alt="<?php echo e($user->name); ?>" src="<?php echo e(asset('assets/front/img/' . $user->avatar)); ?>">

							<div class="username"><?php echo (($user->name!='')?$user->name:$user->username); ?> </div>

							<div class="role"><?php if(isset($user->workplace)): ?><?php echo e($user->job($user->workplace)['name']); ?><?php endif; ?></div>

							<div class="location"><?php if ($user->city!='') echo $user->city .','.  $user->state   ; ?></div>

						</a>

					</div>



				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


        <div class="clear"></div>



        <div class="divider divider-margins"></div>



				</div>

			</div>

		</div>

    </div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>