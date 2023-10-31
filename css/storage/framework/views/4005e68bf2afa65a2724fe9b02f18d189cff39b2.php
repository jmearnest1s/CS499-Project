 <?php $__env->startSection('content'); ?>

<?php header("Access-Control-Allow-Origin: *"); ?>




<div class="page-content " id='pulldown'>
	
    <div>
        <div class="posts">
            <div>
            		<div class="preloader" id="post-ajax-loader" style="display: none;">
                        <div class="progress">
                            <div id="prog" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;">
                            </div>
                        </div>
                    </div>
				<script src="/assets/front/js/pulltorefresh.js" type="text/javascript"></script>
				
				<h1></h1>
				
				<script type='text/javascript'>

					// Initialize 
					PullToRefresh.init({
					 mainElement: 'h1',
					 distThreshold: 120,
					 distMax: 140,
					 distReload: 110,
					 triggerElement: '#pulldown',
					 instructionsPullToRefresh: 'Pull down to refresh the page',
					 instructionsReleaseToRefresh: 'Release to refresh the page',
					 instructionsRefreshing: 'Refreshing the page',
					 refreshTimeout: 600,
					 onRefresh: function(){ 
					 // alert("Refresh");
						 location.reload();
					 }
					});
				</script>

				
                <form onsubmit="myButton.disabled = true; $('#postSpinner').css('display','block'); "  method="post" action="<?php echo e(route('user.post.store')); ?>">
                    <div class="post-meta">
						<a href="#" data-menu="menu-add-topic" alt="Add Topics" class="button outline">+/- Topics</a> <span class="topicList"></span>
            			<div class="clear"></div>
						<?php if(isset($_GET['topic'])): ?>
						<?php $oneinterest=App\Interest::getTopic($_GET['topic']); ?>
						<a alt="<?php echo e($oneinterest[0]->name); ?>" class="topic active" href="/feed"><i class="fas fa-minus-circle"></i> <?php echo e($oneinterest[0]->name); ?></a>
						<?php endif; ?>

                        <?php if(isset($_GET['fav'])): ?>
                        <a alt="Favorites" class="topic active" href="/feed"><i class="fas fa-minus-circle"></i> Favorites</a>
                        <?php endif; ?>

						<?php if(isset($_GET['rss'])): ?>
                        <a alt="RSS" class="topic active" href="/feed"><i class="fas fa-minus-circle"></i> RSS - <?php echo e($_GET['rss']); ?></a>
                        <?php endif; ?>

                        <div class="col-md-12" style="visibility:hidden; height:1px; overflow:hidden">
                            <label>Interests - this is just temporary </label>
                            <div class="form-group form-check crossposting-input input-style-1">
                                <?php $__currentLoopData = $interest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $int): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <label style="width:50%; float:left" for="interest<?php echo e($int->id); ?>"><?php echo e($int->name); ?></label>
                                    <input style="width:50%; float:left" type="checkbox" class="form-control input-style-1" name="interest[]" id="interest<?php echo e($int->id); ?>" value="<?php echo e($int->id); ?>">
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <!--<span>With: <a href="#"> <i class="fas fa-user-plus">
</i> </a>
</span>-->
                    </div>

                    <div class="post-input">
                        <div class="input-style input-style-2">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="urldataval" id="urldataval" value="">
                            <input type="hidden" name="hrefurl" id="hrefurl" value="">
                            <textarea class="post-input-field article-emoji-input textarea nice-scroll" placeholder="Paste a URL, video link, or enter your post..." name="article"></textarea>


                            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" id="urldatadiv" style="display:none;"></div>
                        </div>
                        <div class="post-addons">
                        	<ul class="right">
								<?php if(Auth::user()->id==1): ?>
                            	<li>
                        			<input type="checkbox" name="make_feed" value="1"> Make feed article?

                        		</li>
								<?php endif; ?>
                        		<li>
                        			<div class="single-input-wrapper" id="fileInput">
                        				<label for="image" class="upload">
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
                        				<input type="hidden" name="type" id="type">
                        				<input type="hidden" name="link" id="link">
                        			</div>
                        		</li>
                        		<!--<li>
                        			<div class="single-input-wrapper">

                        			    <label for="group">

                        			        <img src="/assets/front/css/group_upload.png" class="hidden-mobile" alt="Add to a Group"><span class="show-mobile">Add to Group</span>

                        			    </label>

                        			</div>

                        			</li>-->
								<li><i id="postSpinner" class="fa fa-spinner fa-spin fa-3x fa-fw" aria-hidden="true" style="display: none"></i></li>
                        		<li style="margin-bottom:5px">
                        			<div>
                        				<button name="myButton" id="postButton" style="background-color:#A0D468; padding:6px 12px;margin-top:2px;" type="submit" class="button">
                        				<span class="posting-submit-icon">
                        				<i class="fa fa-paper-plane"></i>
                        				</span>
                        				<span class="posting-submit-btn"> Post</span>
                        				</button>
										
                        			</div>
                        		</li>
								
                        	</ul>
							
                        </div>
						<div id="cont" style="display:none" class="instrant-upload-show">
							<span class="cross">
                <i class="fa fa-times" aria-hidden="true"></i>
              </span>
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
                                    <!-- image modal end change by dinesh -->
                </form>
                <img style="display:none;width:50px;" id="loaderimg" src="<?php echo e(url('assets/front/img/loader.gif')); ?>">
                </div>
                </div>
            </div>
            <div class="divider divider-margins"></div>
			<div class="row">
				<div id="searchDiv" class="col-md-7 col-lg-7 col-xs-7 col-sm-7">
				  <form action="/feed" method="get">
					<input type="input" value="<?php if(isset($_GET['search'])) $_GET['search']; ?>" name="search" placeholder="Search Posts">

					<?php if(isset($_GET['fav'])): ?>

						<input type="hidden" name="fav" value="<?php echo e($_GET['fav']); ?>">
					<?php endif; ?>


					<button id="feedSearch" type="submit" class="button button-m button-round-small bg-blue1-light shadow-small">
									<i class="fas fa-search"></i> Search
								</button>
				  </form>
				</div>
				
				<div id="topicDiv" class="col-md-5 col-lg-5 col-xs-5 col-sm-5">
					<select onchange="if (this.value) window.location.href=this.value">
						
						<option value="">Select Topic</option>
						<?php $__currentLoopData = App\Interest::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
						
						<option value="/feed?topic=<?php echo e($interest->id); ?>"><?php echo e($interest->name); ?></option>
						
						 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
					</select>
				</div>
			</div>
            <div class="post-loop-wrapper">
                <?php if($shares && count($shares)): ?>
                <div class="post-loop-inner infinite-scroll">
                    <?php $__currentLoopData = $shares; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $share): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($share->post && $share->post->group_id == 0): ?> <?php $post = $share->post; $res=App\User::getfollowandmutual($post->poststatus_id); ?> <?php $postTopics=App\Post::postTopics($post->id); ?>
					<?php if($post->type=='feed'): ?>
                    <div class="post">
						
                        <div>

							<?php
                            $theSharer = '';

                            if(isset($post->shares))
                            {

                                foreach($post->shares as $aShare) {
                                   //if($aShare->user_id == Auth::user()->id)
									//echo $aShare->user_id.' != '.$post->user_id.'<br>';
                                   if($aShare->user_id != $post->user_id)
									{
                                        $theSharer = $aShare;
										//echo 'the share user '.$theSharer->user_id.'<br>';
										$theSharerID = $theSharer->user_id;
									}
                                  }


                            }
                            ?>

                                <?php if($post->user_id == Auth::user()->id ): ?>
                                    <ul class="postedit">
                                                                       <li>
                                    <a href="/posts/edit/<?php echo e($post->id); ?>"><i class="fas fa-edit" ></i></a>
                                    </li>
                                                                        <li>
                                    <span href="#" class="delete-post" data-post="<?php echo e($post->id); ?>" ><i class="fa fa-trash" ></i></span>
                                    </li>
                                    </ul>
                                <?php elseif(!empty($theSharer)): ?>
                                        <?php if($theSharer->user_id == Auth::user()->id ): ?>
                                        <ul class="postedit">

                                            <li>
                                                <span href="#" class="delete-share" data-post="<?php echo e($post->id); ?>" ><i class="fa fa-trash" ></i></span>
                                            </li>
                                        </ul>


                                        <?php endif; ?>

                               <?php endif; ?>


							<?php if(isset($theSharerID) && is_object($theSharer)): ?>
							<?php //dd($theSharer); ?>
							<img data-user="<?php echo e($theSharer->user_id); ?>" src="<?php echo e(asset('assets/front/img/' . optional($theSharer->user)->avatar)); ?>" class="profile-image" alt="<?php echo e(optional($theSharer->user)->name); ?>">
                            <a href="<?php echo e(route('profile', optional($theSharer->user)->username)); ?>" class="user"><?php echo e(optional($theSharer->user)->name); ?></a>
                            <br>
                            <date>shared this <?php echo e($post->type); ?> <?php echo e($theSharer->created_at->diffForHumans()); ?></date>


							<?php endif; ?>

                            <div class="post-meta">

                                <?php if($postTopics[0]->interests->count() > 0): ?>
                                <span>TOPIC(S):
							<?php $__currentLoopData = $postTopics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theinterest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php $__currentLoopData = $theinterest->interests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $myinterest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<a href="/feed?topic=<?php echo e($myinterest->id); ?>"><?php echo e($myinterest->name); ?></a>,
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</span> <?php endif; ?>
                            </div>
                            <article>
                                <p class="pubDate">
                                  <span>
				    <?php if($post->pubDate ): ?>
				        <?php echo e(\Carbon\Carbon::parse($post->pubDate)->format('m/d/Y')); ?>

				    <?php else: ?>

                                    	<?php echo e(\Carbon\Carbon::parse($post->created_at)->format('m/d/Y')); ?>

				    <?php endif; ?>

                                  </span>
                                  <?php
                                    if(isset($post->link))
                              			{
                                      $baseURL = explode('/',$post->link);
                                      echo "SOURCE: <a class='urlSource' href='/feed?rss=".$baseURL[2]."'>".$baseURL[2]."</a>";
                                    }
                                  ?>

                                </p>
								
								
									
                                <p class="article-img">
									<!--<span class="url"><?php echo e($post->link); ?></span>-->
									
									<a href="/ajaxpage?url=<?php echo e($post->link); ?>" rel="modal:open"><?php echo str_replace('<br />','',$post->content); ?></a>
									<?php if(!strstr($post->content,'Read More') && !strstr($post->scrabingcontent,'Read More')): ?>

							
										<a href="/ajaxpage?url=<?php echo e($post->link); ?>" rel="modal:open" class="pull-right readmore rm1">Read More</a>
                                	<?php endif; ?>
								</p>
								
                            </article>
                        </div>
                        <div class="post-addons likes">
                                <ul>
                                    <li>
                                        <div class="single-input-wrapper">
                                            <i onClick="$(this).addClass('active');" class="fas fa-thumbs-up post-action like <?php echo e($post->isLiked()?' active':''); ?>" data-post="<?php echo e($post->id); ?>"><span class="i-span">(<?php echo e(App\Like::countLIke($post->id)); ?>)</span></i>
                                        </div>
                                    </li>
                                    <li style="width:50px;">
                                        <div class="single-input-wrapper share-group home-page-share-btn group">
                                            <a onClick="$( '.dropdown-content-<?php echo e($post->id); ?>' ).toggle();" href="javascript:void(0)" data-toggle="dropdown-content" class="dropdown-toggle">
                                                <i class="fa fa-share-square"></i> <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-content dropdown-content-<?php echo e($post->id); ?> groupShare">
                                                <li style="width:250px;">
                                                    <a data-original-title="Timeline"   href="" target="_blank" >
                                                       <a data-post="<?php echo e($post->id); ?>" onClick="$(this).addClass('active');$( '.dropdown-content-<?php echo e($post->id); ?>' ).toggle();" class="share"> Timeline</a>
                                                    </a>
                                                </li>
                                                <?php if($groups && count($groups)): ?>
                                                <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li style="width:250px;">
                                                    <a data-original-title="Group <?php echo e($group->name); ?>"   href="" target="_blank" >
                                                       <a data-post="<?php echo e($post->id); ?>" data-group="<?php echo e($group->group->id); ?>" onClick="$(this).addClass('active');$( '.dropdown-content-<?php echo e($post->id); ?>' ).toggle();" class="share">Group <?php echo e($group->group->name); ?></a>
                                                    </a>
                                                </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="single-input-wrapper">
                                            <i onClick="$(this).addClass('active');" class="fas fa-star post-action favorite <?php echo e($post->isFavorited()?' active':''); ?>" data-post="<?php echo e($post->id); ?>">
      <span class="i-span"> Bookmark</span></i>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="/post/<?php echo e($post->id); ?>#commentbox">Comments (<?php echo e(number_format_short($post->commentCount())); ?>)</a>
                                    </li>
                                </ul>


                                <ul class="socialShare">
                                    <li>
                                        <a data-original-title="Twitter"  href="<?php echo e(route('social.share', [$post->id, 'twitter'])); ?>" target="_blank" class="btn btn-twitter" data-placement="left">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-original-title="Facebook"  href="<?php echo e(route('social.share', [$post->id, 'facebook'])); ?>" target="_blank" class="btn btn-facebook" data-placement="left">
                                            <i class="fab fa-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-original-title="LinkedIn"  href="<?php echo e(route('social.share', [$post->id, 'linkedin'])); ?>" target="_blank" class="btn btn-linkedin" data-placement="left">
                                            <i class="fab fa-linkedin"></i>
                                        </a>
                                    </li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <?php else: ?>
                    <!--other posts-->
                    <?php if(! $share->user->isBlockedByMe(Auth::user()->id) && ! $post->user->isBlockedByMe(Auth::user()->id)): ?> <?php if( empty($res) || ( count($res)>0 && in_array($post->user_id,$res) || $post->user_id==Auth::user()->id)): ?> <?php $postTopics=App\Post::postTopics($post->id); ?>
                    <div class="post">

                        <div>

                        		 <?php

                            $theSharer = '';

                            if(isset($post->shares))
                            {

                                foreach($post->shares as $aShare) {
                                   //if($aShare->user_id == Auth::user()->id)
                                   if($aShare->user_id != $post->user_id)
                                        $theSharer = $aShare;
                                  }
                            }
                            ?>

                                <?php if($post->user_id == Auth::user()->id ): ?>
                                    <ul class="postedit">
                                                                       <li>
                                    <a href="/posts/edit/<?php echo e($post->id); ?>"><i class="fas fa-edit" ></i></a>
                                    </li>
                                                                        <li>
                                    <span href="#" class="delete-post" data-post="<?php echo e($post->id); ?>" ><i class="fa fa-trash" ></i></span>
                                    </li>
                                    </ul>
                                <?php elseif(!empty($theSharer)): ?>
                                        <?php if($theSharer->user_id == Auth::user()->id ): ?>
                                        <ul class="postedit">

                                            <li>
                                                <span href="#" class="delete-share" data-post="<?php echo e($post->id); ?>" ><i class="fa fa-trash" ></i></span>
                                            </li>
                                        </ul>
                                        <?php endif; ?>

                               <?php endif; ?>

                            <img src="<?php echo e(asset('assets/front/img/' . optional($share->user)->avatar)); ?>" class="profile-image" alt="<?php echo e(optional($share->user)->name); ?>">
                            <a href="<?php echo e(route('profile', optional($share->user)->username)); ?>" class="user"><?php echo e(optional($share->user)->name); ?></a>
                            <br>
                            <date>shared this <?php echo e($post->type); ?> <?php echo e($share->created_at->diffForHumans()); ?></date>



                                <?php if($share->user_id != $post->user->id): ?>

                                <div style="clear:both"></div>
                                <img style="width:35px;height: auto;" src="<?php echo e(asset('assets/front/img/' . optional($post->user)->avatar)); ?>" class="profile-image" alt="<?php echo e(optional($post->user)->name); ?>">
                                <a href="<?php echo e(route('profile', optional($post->user)->username)); ?>" class="user"><?php echo e(optional($post->user)->name); ?></a>
                                <date>shared this <?php echo e($post->type); ?> <?php echo e($post->created_at->diffForHumans()); ?></date>

                                <?php endif; ?>

                            <br> <?php if($postTopics[0]->interests->count() > 0): ?>
                            <span>Topics:
						<?php $__currentLoopData = $postTopics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theinterest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php $__currentLoopData = $theinterest->interests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $myinterest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<a href="/feed?topic=<?php echo e($myinterest->id); ?>"><?php echo e($myinterest->name); ?></a>,
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</span>
                            <!--<span>With: <a href="#">User Name</a>, <a href="#">Tagged User</a>, <a href="#"> <i class="fas fa-user-plus">
</i> </a>
</span>-->
                            <?php endif; ?>
                            <article>
                                <br> <?php if($post->type == 'article'): ?> <?php if($post->scrabingcontent!=''): ?>
                                <p class="scrabingcontent article-img"><?php echo $post->scrabingcontent; ?></p>
                                <?php else: ?>
                                <p class="article-img"><?php echo excerpt($post); ?></p>
                                <?php endif; ?> <?php elseif($post->type == 'image'): ?>
                                <p class="article-img"><?php echo excerpt($post); ?></p>
                                <br>
                                <img src="<?php echo e(asset('assets/front/content/' . $post->link)); ?>" class="img-responsive imgclickcls preload-image responsive-image" data-toggle="modal" data-target="#imageModal"> <?php elseif($post->type == 'video'): ?>
                                <p><?php echo excerpt($post); ?></p>
                                <br>
                                <video class="player" playsinline controls id="<?php echo e(str_random(20)); ?>" style="width: 100%;">
                                    <source src="<?php echo e(asset('assets/front/content/' . $post->link)); ?>" type="video/mp4">
                                </video>
                                <?php elseif($post->type == 'audio'): ?>
                                <p><?php echo excerpt($post); ?></p>
                                <br>
                                <audio class="player" controls id="<?php echo e(str_random(20)); ?>" style="width: 100%;">
                                    <source src="<?php echo e(asset('assets/front/content/' . $post->link)); ?>" type="audio/mp3">
                                </audio>
                                <?php elseif($post->type == 'youtube'): ?>
                                <p><?php echo excerpt($post); ?></p>
                                <br>
                                <div class="plyr__video-embed player">
                                    <iframe id="<?php echo e(str_random(20)); ?>" src="https://www.youtube.com/embed/<?php echo e($post->link); ?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay">
                                    </iframe>
                                </div>
                                <?php elseif($post->type == 'vimeo'): ?>
                                <p><?php echo excerpt($post); ?></p>
                                <br>
                                <div class="plyr__video-embed player">
                                    <iframe id="<?php echo e(str_random(20)); ?>" src="https://player.vimeo.com/video/<?php echo e($post->link); ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay">
                                    </iframe>
                                </div>
                                <?php elseif($post->type == 'doc'): ?>
                                <p><?php echo excerpt($post); ?></p>
                                <br>
                                <div class="doc">
                                   <div style="overflow: hidden;">
                                        <?php echo e($post->link); ?>

                                        <a target="_blank" href="<?php echo e(asset('assets/front/content/' . $post->link)); ?>" class="top-10 pull-right button button-xs button-round-small shadow-small button-primary" download >Download</a>
                                   </div>
                               </div>
                                <?php elseif($post->type == 'feed'): ?>
                                <p><?php echo excerpt($post); ?></p>
								<?php if(!strstr($post->content,'Read More') && !strstr($post->scrabingcontent,'Read More')): ?>

									<br>
									<a href="/ajaxpage?url=<?php echo e($post->link); ?>" rel="modal:open" class="pull-right readmore rm2" download>Read More</a> <?php endif; ?>
								<?php endif; ?>	
								
								
								
							</article>
							
                        </div>
                        <div class="post-addons likes">
                          <ul>
                              <li>
                                  <div class="single-input-wrapper">
                                      <i onClick="$(this).addClass('active');" class="fas fa-thumbs-up post-action like <?php echo e($post->isLiked()?' active':''); ?>" data-post="<?php echo e($post->id); ?>"><span class="i-span">(<?php echo e(App\Like::countLIke($post->id)); ?>)</span></i>
                                  </div>
                              </li>
                              <li style="width:50px;">
                                  <div class="single-input-wrapper share-group home-page-share-btn group">
                                      <a onClick="$( '.dropdown-content-<?php echo e($post->id); ?>' ).toggle();" href="javascript:void(0)" data-toggle="dropdown-content" class="dropdown-toggle">
                                          <i class="fa fa-share-square"></i> <span class="caret"></span>
                                      </a>
                                      <ul class="dropdown-content dropdown-content-<?php echo e($post->id); ?> groupShare">
                                          <li style="width:250px;">
                                              <a data-original-title="Timeline"   href="" target="_blank" >
                                                 <a data-post="<?php echo e($post->id); ?>" onClick="$(this).addClass('active');$( '.dropdown-content-<?php echo e($post->id); ?>' ).toggle();" class="share"> Timeline</a>
                                              </a>
                                          </li>
                                          <?php if($groups && count($groups)): ?>
                                          <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <li style="width:250px;">
                                              <a data-original-title="Group <?php echo e($group->name); ?>"   href="" target="_blank" >
                                                 <a data-post="<?php echo e($post->id); ?>" data-group="<?php echo e($group->group->id); ?>" onClick="$(this).addClass('active');$( '.dropdown-content-<?php echo e($post->id); ?>' ).toggle();" class="share">Group <?php echo e($group->group->name); ?></a>
                                              </a>
                                          </li>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                          <?php endif; ?>
                                      </ul>
                                  </div>
                              </li>
                              <li>
                                  <div class="single-input-wrapper">
                                      <i onClick="$(this).addClass('active');" class="fas fa-star post-action favorite <?php echo e($post->isFavorited()?' active':''); ?>" data-post="<?php echo e($post->id); ?>">
<span class="i-span"> Bookmark</span></i>
                                  </div>
                              </li>
                              <li>
                                  <a href="/post/<?php echo e($post->id); ?>#commentbox">Comments (<?php echo e(number_format_short($post->commentCount())); ?>)</a>
                              </li>
                          </ul>
                          <ul class="socialShare">

                              <li>
                                  <a data-original-title="Twitter"  href="<?php echo e(route('social.share', [$post->id, 'twitter'])); ?>" target="_blank" class="btn btn-twitter" data-placement="left">
                                      <i class="fab fa-twitter"></i>
                                  </a>
                              </li>
                              <li>
                                  <a data-original-title="Facebook"  href="<?php echo e(route('social.share', [$post->id, 'facebook'])); ?>" target="_blank" class="btn btn-facebook" data-placement="left">
                                      <i class="fab fa-facebook"></i>
                                  </a>
                              </li>
                              <li>
                                  <a data-original-title="LinkedIn"  href="<?php echo e(route('social.share', [$post->id, 'linkedin'])); ?>" target="_blank" class="btn btn-linkedin" data-placement="left">
                                      <i class="fab fa-linkedin"></i>
                                  </a>
                              </li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="divider divider-margins"></div>
                    <?php endif; ?> <?php endif; ?> <?php endif; ?> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    
                     <?php if(isset($ads[0]->link)): ?>
                    	<?php $__currentLoopData = $ads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        	<div class="post">
                                
                                <p class="adscontent">
                                <small>Advertisement</small>
                                	<a href="<?php echo e($ad->link); ?>" target="_blank"><img src="<?php echo e($ad->image); ?>" width="100%"></a>
                                    <div><?php echo e($ad->content); ?></div>
                                    <div style="text-align:right"><a href="<?php echo e($ad->link); ?>" target="_blank">Read More</a></div>
                                    
                                 </p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    
                    <div style="display:none"><?php echo e($shares->appends($_GET)->links()); ?></div>
                    <?php else: ?>
                    <div class="col-md-12 single text-center">No Post Found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div id="menu-share" class="menu menu-box-bottom" data-menu-height="310" data-menu-effect="menu-parallax">
            <div class="link-list link-list-1 content bottom-0">
                <a href="#" class="shareToFacebook">
                    <i class="font-18 fab fa-facebook color-facebook">
</i>
                    <span class="font-13">Facebook</span>
                    <i class="fa fa-angle-right">
</i>
                </a>
                <a href="#" class="shareToTwitter">
                    <i class="font-18 fab fa-twitter-square color-twitter">
</i>
                    <span class="font-13">Twitter</span>
                    <i class="fa fa-angle-right">
</i>
                </a>
                <a href="#" class="shareToLinkedIn">
                    <i class="font-18 fab fa-linkedin color-linkedin">
</i>
                    <span class="font-13">LinkedIn</span>
                    <i class="fa fa-angle-right">
</i>
                </a>
                <a href="#" class="shareToGooglePlus">
                    <i class="font-18 fab fa-google-plus-square color-google">
</i>
                    <span class="font-13">Google +</span>
                    <i class="fa fa-angle-right">
</i>
                </a>
                <a href="#" class="shareToWhatsApp">
                    <i class="font-18 fab fa-whatsapp-square color-whatsapp">
</i>
                    <span class="font-13">WhatsApp</span>
                    <i class="fa fa-angle-right">
</i>
                </a>
                <a href="#" class="shareToMail no-border">
                    <i class="font-18 fa fa-envelope-square color-mail">
</i>
                    <span class="font-13">Email</span>
                    <i class="fa fa-angle-right">
</i>
                </a>
            </div>
        </div>
        <div id="menu-2" class="menu menu-box-right" data-menu-width="75" data-menu-effect="menu-over">
            <div class="highlight-changer">
                <a href="#" data-change-highlight="red1">
                    <i class="fa fa-circle color-red1-dark">
</i>
                    <span class="color-red2-light">Red</span>
                </a>
                <a href="#" data-change-highlight="orange">
                    <i class="fa fa-circle color-orange-dark">
</i>
                    <span class="color-orange-light">Orange</span>
                </a>
                <a href="#" data-change-highlight="pink2">
                    <i class="fa fa-circle color-pink2-dark">
</i>
                    <span class="color-pink2-light">Pink</span>
                </a>
                <a href="#" data-change-highlight="magenta2">
                    <i class="fa fa-circle color-magenta2-dark">
</i>
                    <span class="color-magenta2-light">Purple</span>
                </a>
                <a href="#" data-change-highlight="blue2">
                    <i class="fa fa-circle color-blue2-dark">
</i>
                    <span class="color-blue2-light">Blue</span>
                </a>
                <a href="#" data-change-highlight="aqua">
                    <i class="fa fa-circle color-aqua-dark">
</i>
                    <span class="color-aqua-light">Aqua</span>
                </a>
                <a href="#" data-change-highlight="teal">
                    <i class="fa fa-circle color-teal-dark">
</i>
                    <span class="color-teal-light">Teal</span>
                </a>
                <a href="#" data-change-highlight="mint">
                    <i class="fa fa-circle color-mint-dark">
</i>
                    <span class="color-mint-light">Mint</span>
                </a>
                <a href="#" data-change-highlight="green2">
                    <i class="fa fa-circle color-green2-dark">
</i>
                    <span class="color-green2-light">Green</span>
                </a>
                <a href="#" data-change-highlight="green1">
                    <i class="fa fa-circle color-green1-dark">
</i>
                    <span class="color-green1-light">Grass</span>
                </a>
                <a href="#" data-change-highlight="yellow2">
                    <i class="fa fa-circle color-yellow2-dark">
</i>
                    <span class="color-yellow2-light">Sunny</span>
                </a>
                <a href="#" data-change-highlight="yellow1">
                    <i class="fa fa-circle color-yellow1-dark">
</i>
                    <span class="color-yellow1-light">Goldish</span>
                </a>
                <a href="#" data-change-highlight="brown1">
                    <i class="fa fa-circle color-brown1-dark">
</i>
                    <span class="color-brown1-light">Wood</span>
                </a>
                <a href="#" data-change-highlight="brown2">
                    <i class="fa fa-circle color-brown2-dark">
</i>
                    <span class="color-brown2-light">Earth</span>
                </a>
                <a href="#" data-change-highlight="dark1">
                    <i class="fa fa-circle color-dark1-dark">
</i>
                    <span class="color-dark1-light">Night</span>
                </a>
                <a href="#" data-change-highlight="dark2">
                    <i class="fa fa-circle color-dark2-dark">
</i>
                    <span class="color-dark2-light">Dark</span>
                </a>
                <a href="#" data-change-highlight="gray2">
                    <i class="fa fa-circle color-gray2-dark">
</i>
                    <span class="color-gray2-light">Gray</span>
                </a>
            </div>
        </div>
    </div>
    <div id="menu-forgot" class="menu menu-box-bottom" data-menu-height="70%" data-menu-effect="menu-over">
        <div class="content">
            <h2 class="uppercase ultrabold top-20">Forgot Password?</h2>
            <p class="font-11 under-heading bottom-20">
                Let's get you back into your account. Enter your email to reset.
            </p>
            <div class="input-style has-icon input-style-1 input-required bottom-30">
                <i class="input-icon fa fa-at">
</i>
                <span>Email</span>
                <em>(required)</em>
                <input type="email" placeholder="Email">
            </div>
            <a href="#" class="button button-full button-m shadow-large button-round-small bg-blue1-dark top-20">SEND RECOVERY EMAIL</a>
        </div>
    </div>
    <div id="menu-signin" class="menu menu-box-bottom" data-menu-height="500" data-menu-effect="menu-over">
        <div class="content">
            <h1 class="uppercase ultrabold top-20">LOGIN</h1>
            <p class="font-11 under-heading bottom-20">
                Hello, stranger! Please enter your credentials below.
            </p>
            <div class="input-style has-icon input-style-1 input-required">
                <i class="input-icon fa fa-at">
</i>
                <span>Email</span>
                <em>(required)</em>
                <input type="email" placeholder="Email">
            </div>
            <div class="input-style has-icon input-style-1 input-required">
                <i class="input-icon fa fa-lock font-11">
</i>
                <span>Password</span>
                <em>(required)</em>
                <input type="password" placeholder="Password">
            </div>
            <div class="top-30">
                <div class="one-half">
                    <a href="#" data-menu="menu-forgot" class="left-text font-10">Forgot Password?</a>
                </div>
                <div class="one-half last-column">
                    <a data-menu="menu-signup" href="#" class="right-text font-10">Create Account</a>
                </div>
            </div>
            <div class="clear">
            </div>
            <a href="dashboard.html" class="button button-full button-s shadow-large button-round-small bg-green1-dark top-10">LOGIN</a>
            <div class="divider">
            </div>
            <a href="#" class="button bg-linkedin button-l shadow-large button-icon-left">
                <i class="fab fa-linkedin-in">
</i> Log In With LinkedIn</a>
            <br>
            <a href="#" class="button bg-facebook button-l shadow-large button-icon-left">
                <i class="fab fa-facebook-f">
</i> Log In With Facebook</a>
            <br>
        </div>
    </div>
    <div id="menu-signup" class="menu menu-box-bottom" data-menu-height="300" data-menu-effect="menu-parallax">
        <div class="content">
            <h1 class="uppercase ultrabold top-20">Register</h1>
            <p class="font-11 under-heading bottom-20">
                Don't have an account? Register below.
            </p>
            <div class="input-style has-icon input-style-1 input-required">
                <i class="input-icon fa fa-at">
</i>
                <span>Email</span>
                <em>(required)</em>
                <input type="email" placeholder="Email">
            </div>
            <div class="top-20 bottom-20">
                <a href="#" data-menu="menu-signin" class="center-text font-11 color-gray2-dark">Already Registered? Sign In Here.</a>
            </div>
            <div class="clear">
            </div>
            <a href="#" class="button button-full button-s shadow-large button-round-small bg-blue2-dark top-10">Register</a>
        </div>
    </div>
    <?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>