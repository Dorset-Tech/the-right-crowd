<?php
/*
 * Template Name: Company News
 */
get_header(); ?>

<section id="main" class="clearfix">
   <?php get_template_part('lib/sub-header')?>
    <div id="content" class="site-content" role="main">
    	<div class="container">
        <?php /* The loop */ ?>
        <?php while ( have_posts() ): the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
				<a class="wp-crowd-btn wp-crowd-btn-primary c-news-add" href="/dashboard" style="margin-left: 10px; display: block; width: 200px; text-align: center;">Go to Dashboard</a>
				<br/><br/>
				<div class="company-news-wrapper">
					<?php $companyID = $_GET['postid']; 
						if($companyID > 0) :
							?>
							<h4>News updates of company : <a href="<?php echo get_the_permalink($companyID); ?>"><?php echo get_the_title($companyID); ?></a></h4>
							<?php
							//$saved_campaign_update = get_post_meta($companyID, 'wpneo_campaign_updates', true);
							$news = getProjectNews($companyID); //json_decode($saved_campaign_update,true);
						
							
							//usort($news,'newsSort');
							//$news = newsSort($news);
							/*array_multisort(array_map('strtotime',array_column($news,'date')),
											SORT_DESC, 
											$news);*/
							
							if(false) { // @TODO add if not premium member check 
								$news = array_slice($news, -3, 3, true);
							}
							if($news) : ?>
                    			<ul class="comment-list newsposts">
                    			<?php foreach($news as $newspost) : 
										$title = get_post_meta($newspost->ID,'title',true);
										$date = get_post_meta($newspost->ID,'date',true);
										$details = get_post_meta($newspost->ID,'details',true);
										$docs = get_attached_media( '', $newspost->ID );
										$do = [];
										if(count($docs) > 0) {
											foreach($docs as $d ) {
												$do[] = "<a style='color: #5063AB !important;' target='_blank' href='".$d->guid."'>".$d->post_title."</a>";
											}
										}
								?>
											<li class="comment byuser comment-author-tomknifton even thread-even depth-1" id="news_<?php echo $newspost->ID ?>">										  		
												<div class="comment-intro">
											  		 <span class='title' style="font-weight: bold;"><?php echo $title ?></span><br/>
											  		 <span class='date'><?php echo $date; //date('d/m/Y H:i',strtotime($newspost['date'])) ?></span><br/>
													 <?php if(get_current_user_id()) : ?>
														<span class="description"><p><?php echo nl2br($details); ?></p></span>
														<?php if(count($do) > 0) { ?>
															<span>Attachments: </span>
															<span><?php echo (count($do) > 0 ? implode(", ",$do) : ' None ' ) ?></span>
														<?php } ?>
													 <?php else: ?>
														<span class="description"><p><?php echo substr(nl2br($details),0,175).'...'; ?></p></span>
														<a href="/sign-up">Register to read more.</a>
													 <?php endif; ?>
												</div>
										 	</li> 
                    			<?php endforeach; ?>
                    			</ul>
                    		<?php else: ?>
                    			<div class="sharerow">
	                              <p class="locktext">No news articles found.</p>
	                      	</div>
                    		<?php endif; 	
						endif;
					?>
				</div>
            </div>

            <?php // comment_template(); ?>

        <?php endwhile; ?>
        </div> <!--/.container-->
    </div> <!--/#content-->
</section> <!--/#main-->
<?php get_footer();
