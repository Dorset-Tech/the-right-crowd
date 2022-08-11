<?php
get_header();
global $themeum_options;
require_once get_template_directory()."/simple_html_dom.php";
$currentSiteID = get_current_blog_id();
if($currentSiteID == '1'){
	switch_to_blog(1);
	if(isset($_GET['preview']) && $_GET['preview'] == true) {
		$post = get_post($_GET['p']);
	}else{
		$url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
		$slug = explode('/',$url_path)[1];
		$post = get_page_by_path($slug, OBJECT, 'project');
	}
$thumbURL = getProjectImageThumbSrc($post->ID);
setup_postdata($post);

?>
<?php if (get_the_ID() > 0) : ?> 
        <?php
        $location = esc_attr(get_post_meta(get_the_ID(), "thm_location", true));
        $project_type = esc_attr(get_post_meta(get_the_ID(), "thm_type", true));
        //$website = esc_attr(get_post_meta(get_the_ID(), "thm_cowebsite", true));
        $website = esc_attr(get_post_meta(get_the_ID(), "thm_website_url", true));
		$video_source = esc_attr(get_post_meta(get_the_ID(), "thm_video_url", true));
		$isInvestNow = get_post_meta(get_the_ID(),'thm_trx_display_invest_button',true);
		$siteID = get_post_meta(get_the_ID(),'thm_site_id',true);
        if (isset($video_source) && $video_source) {

            $video = parse_url($video_source);
            switch ($video['host']) {
                case 'youtu.be':
                    $id = trim($video['path'], '/');
                    $src = '//www.youtube.com/embed/' . $id;
                    break;

                case 'www.youtube.com':
                case 'youtube.com':
                    parse_str($video['query'], $query);
                    $id = $query['v'];
                    $src = '//www.youtube.com/embed/' . $id;
                    break;

                case 'vimeo.com':
                case 'www.vimeo.com':
                    $id = trim($video['path'], '/');
                    $src = "//player.vimeo.com/video/{$id}";
            }
        }


        $output = '';
        $image_attached = esc_attr(get_post_meta($post->ID, 'thm_subtitle_images', true));
        if(!empty($image_attached)) { //(!empty($image_attached)) {
            $sub_img = wp_get_attachment_image_src($image_attached, 'blog-full');
            $output = 'style="background-image:url(' . esc_url($sub_img[0]) . ');background-size: cover;background-position: 50% 50%;padding: 100px 0;"';
            if (empty($sub_img[0])) {
                $output = 'style="background-color:' . esc_attr(get_post_meta(get_the_ID(), "thm_subtitle_color", true)) . ';padding: 100px 0;"';
                if (get_post_meta("thm_subtitle_color") == '') {
                    $output = thmtheme_call_sub_header();
                }
            }
        } else {
            $output = 'style="background-image:url(/wp-content/uploads/generic-bg.jpg);background-size: cover;background-position: 50% 50%;padding: 100px 0;"';

            /* if(get_post_meta("thm_subtitle_color") != "" ){
              $output = 'style="background-color:'.esc_attr(get_post_meta(get_the_ID(), "thm_subtitle_color", true)).';padding: 100px 0;"';
              } */
        }
		
        ?>

		  <?php //if (isMemberSubscribed(get_the_author_meta('ID'))) : // if check to see if owner of post is subscribed or not ?>
        <!-- start breadcrumbs -->
        <section class="project-breadcrumbs" <?php echo $output; ?>>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <?php //themeum_breadcrumbs();   ?>
                        <h1 class="wow fadeInDown company-page-title" data-wow-delay=".2s"><?php echo get_the_title(); ?></h1>
                    </div>
                </div>
            </div>
        </section>
        <!-- end breadcrumbs -->
        <!--<div class="subheader">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h4><?php // echo get_the_title(); ?></h4>
                    </div>
                </div>
            </div>        
        </div>-->

        <!-- start About this project -->
        
        <div id="about-project" class="newdes main-site-company-page">
            <div class="container">
                <div class = "row">
                    <div class = "col-sm-12">
                        <div class = "company-body-intro-bar">
                            <!-- Get logo -->
                            <?php
                            echo '<figure class = "company-intro-logo">';
                            if (has_post_thumbnail()) {
                                if (!empty(get_the_post_thumbnail(get_the_ID(), '', array('class' => 'img-responsive')))) {
                                    //echo get_the_post_thumbnail(get_the_ID(), '', array('class' => 'img-responsive'));
									echo '<img src="'.$thumbURL.'" class="img-responsive">';
                                } else{ ?>
                                    <img src="https://therightcrowd.com/wp-content/uploads/default-image.jpg" style="width: 100%;">
                                <?php }
                                
                            } else { ?>
                                <img src="https://therightcrowd.com/wp-content/uploads/default-image.jpg" style="width: 100%;">
                            <?php }
                            echo '</figure>';
                            ?>
                            <!-- Get location, website & Invest Button -->
                            <div class = "company-intro-details">
                                <p class = "company-intro-details-address"><i class="fa fa-map-marker"></i> <?php echo esc_attr($location); ?></p>
                                <?php 
                                    $investNowURL = get_post_meta(get_the_ID(),'thm_trx_display_invest_button_url',true);
                                ?>
                                <p class = "company-intro-details-website"><i class="fas fa-globe"></i><a id="coweblink" href="<?php echo $website ?>" target='_blank'><?php echo $website ?></a></p>
                                <?php if($isInvestNow == '1') { ?>
                                <?php 
                                    $investNowURL = get_post_meta(get_the_ID(),'thm_trx_display_invest_button_url',true);
                                    $investNowURL = $investNowURL != '' ? $investNowURL : 'javascript:void();';
                                ?>
                                <div class = "company-intro-details-opportunity" style="text-align: left;">
                                    <a href="<?php echo $investNowURL ?>" data-wow-delay=".1s" class="btn btn-primary wow fadeInDown">View opportunity</a>
                                </div>
                            <?php } ?>
                            <div>
                            

                        </div>
                    </div>
                </div>
                
                <div class="row company-content">
                    <div class="col-sm-6">
                        <?php 
                            $contenta = the_content();
                            if ($contenta == '') {
                                
                            } else { ?>
                            <div class="business_overview"><?php the_content(); ?></div>
                            <?php }
                        ?> 
                    </div>
                    <div class="col-sm-6">
                        <!-- <p> Video Area </p> -->
						<iframe width="420" height="315"
						src="<?php echo $src; ?>">
						</iframe> 
						
                    </div>
                </div>
                    <div class="row bttmsec">
                        <!-- NEWS SECTION -->
                        <div class = "company-news-posts">
                            <?php //$blogPosts = wp_get_recent_posts(array('numberposts' => '3','post_status' => 'publish')); ?>
                            <?php
                            /*$saved_campaign_update = get_post_meta(get_the_ID(), 'wpneo_campaign_updates', true);
                            $news = json_decode($saved_campaign_update, true);
                    		array_multisort(array_map('strtotime',array_column($news,'date')),
                    											SORT_DESC, 
                    											$news);*/
                    		$news = getProjectNews(get_the_ID());

                            ?>
                            <h4>News</h4>
                            <?php 
							if ($news && count($news) > 0) : ?>
                            <ul class="comment-list newsposts">
                                <?php foreach ($news as $newspost) : 
										$title = get_post_meta($newspost->ID,'title',true);
										$d = get_post_meta($newspost->ID,'date',true);
										$details = get_post_meta($newspost->ID,'details',true);
								?>
								
                                    <li class="comment byuser comment-author-tomknifton even thread-even depth-1">
                                        <div class="comment-intro">
                                            <span class='title'><?php echo $title ?></span><br/>
                                            <span class='date'><?php echo $d; //date('d/m/Y H:i ',$timestamp) ?></span><br/>
                                            <span class="description"><p><?php echo nl2br(substr($details, 0, 75)); ?></p></span>
                                            <span class="readmore"><a href="/news-updates/?postid=<?php echo get_the_ID(); ?>#news_<?php echo $newspost->ID ?>">read more</a></span>
                                        </div>
                                    </li> 
								<?php endforeach; ?>
                            </ul>
                            <?php
                            //if (isMemberSubscribed(get_the_author_meta('ID'))) { // @TODO membership code check 
                                add_query_arg(array('page_type' => 'update', 'postid' => get_the_ID()), $current_page);
                                ?>
                                <div class="showall_news"><a href="/news-updates/?postid=<?php echo get_the_ID(); ?>">Show All</a></div>
            <?php //} ?>
                        <?php else: ?>
                            <div class="sharerow">
                                <p class="locktext">No news articles found.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class = "company-discussions">
                        <h4>Discussions</h4>
        <?php
		global $withcomments;
$withcomments = 1;
            comments_template('/project_comments.php');
        ?>
                    </div> 
                    <?php //} ?>      
                </div>   
                <?php //if(isMemberSubscribed(get_the_author_meta('ID'))) : // disabling the check for now ?> 
		             <?php $moveTrading = (int) get_post_meta(get_the_ID(),'thm_trading_up',true); ?>
		             
		             <?php if(!$moveTrading) : ?>
				          <?php include_once(get_template_directory() . "/company-house.php"); ?>
				          <?php //include_once(get_template_directory() . "/trading-info.php"); ?>	
				       <?php else: ?>   
				          <?php //include_once(get_template_directory() . "/trading-info.php"); ?>
				          <?php include_once(get_template_directory() . "/company-house.php"); ?>
		             <?php endif; ?>
                <?php //else: ?>
                	<!--<div class="sharerow subscribe-text-box">
                                <p class="locktext"><a href="/service-packages/"><i class="fa fa-lock"></i> Subscribe to unlock Trading and Company House data.</a></p>
                            </div>-->
                <?php //endif; ?>
                <!--<div class="row warning-section">
                	<div class="col-md-12">
				         
				          <section class="warning" style="clear:both;">
				              <div>
				                  <div class="risk-warning">
				                      <h3>Important Info</h3>
				                      <p>Investors should be aware that there are risks to investing in shares of companies, especially if they are private companies as there may be little or no market for the shares to be traded and dividends are unlikely to be paid. Investments may go down as well as up and therefore investors may not recover their initial investment.</p>
				                      <a style="color: #00aed1;" href="/terms-conditions/risk-warning/">please click here to read the full important info</a>
				                  </div>
				              </div>
				          </section>
                	</div>
                </div>-->
                <!--  END OF SECTION : WARNING -->
					</div>
				</div>
            </div>
        </div><!-- end About this project -->
				
        <?php //else: // disabling this part for now ?>
        	<!--<div class="sharerow subscribe-text-box">  
                                <p class="locktext">Listing not found.</p>
                            </div>-->
		  <?php // endif; ?>	
	<?php restore_current_blog(); ?>
<?php else: ?>
    <?php get_template_part('post-format/content', 'none'); ?>
<?php endif; ?>

<div class="clearfix"></div>
<style>
    #fhTable .js-hidden, #fhTable .visuallyhidden{display:none;}
    .buyButton{text-align: right;}
    .shares-notforsale{display:none;}
    .sharesellPriceInput{width:75px;}
    #registeroffersection{display:none;}
    .project-breadcrumbs{float:left;width:100%;}
	.companies-house-table{height: 400px;overflow: hidden;overflow-y: auto;}
</style>
<?php 	
}else{

$invest_url = get_permalink(get_option('paypal_payment_checkout_page_id'))."?";
$isUserLoggedin = is_user_logged_in();
$metaKey = 'display_business_'.$currentSiteID;
$metaKeyApproval = 'display_business_approval_'.$currentSiteID;
$homeURL = home_url();
$siteType = get_option('site_type');
$businessType = get_option('business_type','product');
$parent = (int) get_option('parent_site');
if($isUserLoggedin) {
	$userInvestorType = get_user_meta(get_current_user_id(),'investor_type',true); 
}else{
	$userInvestorType = isset($_COOKIE['investor_type']) ? $_COOKIE['investor_type'] : '';
}
$u = wp_get_current_user();
$hashedU = md5($u->data->user_login);

if(!is_user_logged_in() && isset($_GET['sid']) && isset($_GET['c'])) {
	c_login($_GET['sid'],$_GET['c'],$_SERVER['SCRIPT_URI']);
}

//email_user_on_bank_transfer_payment(5,5507);

/*if($siteType == 'child' && $parent) {
	global $switched;
	switch_to_blog($parent);*/
//}elseif($siteType == 'master') {
	
//}

global $switched;
 	switch_to_blog(1);
if(isset($_GET['preview']) && $_GET['preview'] == true) {
	$post = get_post($_GET['p']);
}else{
$url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
$slug = explode('/',$url_path)[1];
$post = get_page_by_path( $slug, OBJECT, 'project' );
}

setup_postdata($post);

$thumb_attachmentID = get_post_meta($post->ID,'_thumbnail_id',true); 
$thumbURL = getProjectImageThumbSrc($post->ID); 

restore_current_blog();
$projectFiles = getProjectFiles($post->ID);

global $switched;
 	switch_to_blog(1); // 12642 s7 18th 7.10 am 
$postOwner = $post->post_author;
$userStripe = get_user_meta($postOwner,'stripe_user_id',true);
$postSiteID = get_post_meta($post->ID,'thm_site_id',true);
$contentNotice = (int) get_post_meta($post->ID,'thm_content_notice',true);

$isViewable = get_post_meta($post->ID,$metaKey,true);
$isApproved = get_post_meta($post->ID,$metaKeyApproval,true);

$companyInvestorType = get_post_meta($id,'thm_invester_type',false);

if($businessType == 'introducer' && (int) $postSiteID !== (int) $currentSiteID) {
	$productWhiteLabel = get_post_meta($post->ID,'thm_site_id',true);
	switch_to_blog($productWhiteLabel);
	$psiteurl = site_url()."/project/".$post->post_name."?sid=$currentSiteID&c=".$hashedU;
	$invest_url = get_permalink(get_option('paypal_payment_checkout_page_id'))."?sid=$currentSiteID&c=".$hashedU."&";
	restore_current_blog();
	
	wp_redirect($psiteurl);
}
$userPoints = getQuestionnairePoints(get_current_user_id());
if(isset($_GET['sid']) && $_GET['sid'] != '') {
	$gobackURL = $_SERVER['HTTP_REFERER'];
}	
?>
<?php if(isset($gobackURL) && $gobackURL != '') { ?>
<a href="<?php echo $gobackURL ?>" data-wow-delay=".1s" class="btn btn-primary wow fadeInDown" style="margin: 15px;"><?php _e('Go Back', 'themeum'); ?></a>
<?php 
}
if(!is_user_logged_in()){ ?>
	<section class="project-not-available">
		<div class="container">
			<div class="row">
				<p style="text-align: center; font-size: 20px; margin: 40px 0;">Sorry, This product is not available for non logged in users. Please log in or register by clicking <a href="/sign-up/">here</a></p>
			</div>
		</div>
	</section>
<?php //}else if((count($companyInvestorType) > 0 && !in_array($userInvestorType,$companyInvestorType)) ) { ?>
    <!--<section class="project-not-available">
		<div class="container">
			<div class="row">
				<p style="text-align: center; font-size: 20px; margin: 40px 0;">Sorry, This product is not suitable for your investor type.</p>
			</div>
		</div>
	</section> -->
<?php }else if($isViewable != 'yes'){ ?>
	<section class="project-not-available">
		<div class="container">
			<div class="row">
				<p style="text-align: center; font-size: 20px; margin: 40px 0;">Sorry, This product is not available.</p>
			</div>
		</div>
	</section>
	
<?php } else if($userPoints <= 15) { ?>
	<section class="project-not-available">
		<div class="container">
			<div class="row">
				<p style="text-align: center; font-size: 20px; margin: 40px 0;">Please note, your score is below a score of 15, this means you may not be approved for this investment and it is down to the discretionary of the company to accept you as an investor. You can <a href="/contact-us">contact us</a> for further discussion.</p>
				<p style="text-align: center; font-size: 20px;">Or</p>
					<ul class="nav" style="width: 200px; margin: 0 auto;">
						<li class="nav-myacount"><a href="/suitability-test/" style="text-align: center; background-color: #888; color: #fff;">Retake a Test</a></li>
					</ul>
				<p style="text-align: center; font-size: 20px; margin: 40px 0;"></p>	
			</div>
		</div>
		<style>
			.project-not-available .nav li a:hover {
				    background: #e29173;
			}
		</style>
	</section>
<?php  } else{
?>
<?php if (get_the_ID() > 0) : ?> 

    <?php //while (have_posts()) : the_post(); ?>

        <?php
        $post = get_post(get_the_ID());
        $location = esc_attr(get_post_meta(get_the_ID(), "thm_location", true));
        $project_type = esc_attr(get_post_meta(get_the_ID(), "thm_type", true));
        $funding_goal = esc_attr(get_post_meta(get_the_ID(), "thm_funding_goal", true));
        $video_source = esc_url(get_post_meta(get_the_ID(), 'thm_video_url', true));
        $themeum_reward = get_post_meta(get_the_ID(), 'themeum_reward', true);
        $unit_price = getSharePrice(get_the_ID(),''); //themeum_get_project_info(get_the_ID(), 'share_price');
        $siteID = get_post_meta(get_the_ID(),'thm_site_id',true);
        $teamMembers = get_post_meta(get_the_ID(), 'thm_team_members', true);
        $teamMembers = unserialize($teamMembers);
		$investmentType = get_post_meta(get_the_ID(), 'thm_investment_type', true);
		/*echo "<pre>";
		print_r($teamMembers);
		echo "</pre>";*/
        if (isset($video_source) && $video_source) {

            $video = parse_url($video_source);
            switch ($video['host']) {
                case 'youtu.be':
                    $id = trim($video['path'], '/');
                    $src = '//www.youtube.com/embed/' . $id;
                    break;

                case 'www.youtube.com':
                case 'youtube.com':
                    parse_str($video['query'], $query);
                    $id = $query['v'];
                    $src = '//www.youtube.com/embed/' . $id;
                    break;

                case 'vimeo.com':
                case 'www.vimeo.com':
                    $id = trim($video['path'], '/');
                    $src = "//player.vimeo.com/video/{$id}";
            }
        }


        $output = '';
        $image_attached = esc_attr(get_post_meta($post->ID, 'thm_subtitle_images', true));
        
        if (!empty($image_attached)) {
        		global $switched;
				switch_to_blog($siteID);
            $sub_img = wp_get_attachment_image_src($image_attached, 'blog-full');
            global $switched;
		 switch_to_blog(1); 
            $output = 'style="background-image:url(' . esc_url($sub_img[0]) . ');background-size: cover;background-position: 50% 50%;padding: 100px 0;"';
            
            if (empty($sub_img[0])) {
                $output = 'style="background-color:' . esc_attr(get_post_meta($post->ID, "thm_subtitle_color", true)) . ';padding: 100px 0;"';
                if (get_post_meta("thm_subtitle_color") == '') {
                    $output = thmtheme_call_sub_header();
                }
                
            }
        } else {
            $output = 'style="background-image:url(/wp-content/uploads/generic-bg.jpg);background-size: cover;background-position: 50% 50%;padding: 100px 0;"';

            /* if(get_post_meta("thm_subtitle_color") != "" ){
              $output = 'style="background-color:'.esc_attr(get_post_meta(get_the_ID(), "thm_subtitle_color", true)).';padding: 100px 0;"';
              } */
        }
        ?>
<?php  ?>
        <!-- start breadcrumbs -->
        <section class="project-breadcrumbs" <?php echo $output; ?>>
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <?php
                        echo '<figure>';
                        if ($thumbURL) {
                            //echo get_the_post_thumbnail(get_the_ID(), 'project-thumb', array('class' => 'img-responsive'));
                            ?>
                            <img src="<?php echo $thumbURL ?>" class="img-responsive" />
                            <?php
                        } else {
                            echo '<div class="no-image"></div>';
                        }

                        echo '</figure>';
                        ?>
                    </div>
                    <div class="col-sm-6">

        <?php //themeum_breadcrumbs();  ?>

                        <h2 class="wow fadeInDown" data-wow-delay=".2s"><?php echo get_the_title(); ?></h2>
                        <h4 class="wow fadeInDown" data-wow-delay=".5s"><?php echo esc_attr($location); ?></h4>
                    </div>

                    <div class="col-sm-3">
								<?php //if($userStripe && $userStripe != '') : ?>
								<?php //if((int)$currentSiteID != 29){ ?>
                        <a href="<?php echo $invest_url."project_id=" . get_the_ID(); ?>" data-wow-delay=".1s" class="btn btn-primary wow fadeInDown"><?php _e('Invest Now', 'themeum'); ?></a>
								<?php //} ?>
                        <?php //endif; ?>

        <!-- <a href="<?php echo get_permalink(get_option('user_profile_page_id')); ?>?user_id=<?php the_author_meta('ID'); ?>" data-wow-delay=".1s" class="btn btn-primary btn-profile wow fadeInDown"><?php _e('View Profile', 'themeum'); ?></a>-->
                    </div>
                </div>
            </div>
        </section>
        <!-- end breadcrumbs -->
        <div class="row compstats">
            <div class="container">
                <div class="col-md-12">
                    <div class="fund-progress bigbluebar"><div class="bar" style="width: <?php echo themeum_get_project_info(get_the_ID(), 'percent', array('exact' => true)); ?>%"></div></div>
                </div>
                <div class="col-fifth">
                    <p class="pledged">Pledged<span>£<?php
                            $funded = themeum_get_project_info(get_the_ID(), 'collected');
                            echo $funded ? $funded : 0;
                            ?></span></p>
                </div>
                <div class="col-fifth">
					<?php 
						$goalCurrency = get_post_meta(get_the_ID(),'goal_currency',true);
						if($goalCurrency == '') {
							$goalCurrency = 'GBP';
						}
					?>
                    <p class="ptarget">Target<span><?php echo get_currencies($goalCurrency).themeum_get_project_info(get_the_ID(), 'budget'); ?></span></p>
                </div>
				<?php 
					$equity = themeum_get_project_info(get_the_ID(), 'equity');
					if($equity != '') {
				?>
                <div class="col-fifth">
                    <p class="equity">Equity<span><?php echo $equity.'%'; ?></span></p>
                </div>
				<?php } ?>
				<?php if($unit_price != '') { ?>
                <div class="col-fifth">
                    <p class="sprice">
						Unit Price
					<span>
						<?php echo $unit_price; ?>
					</span></p>
                </div>
				<?php } ?>
                <div class="col-fifth">
                    <p class="raised">Raised<span><?php echo themeum_get_project_info(get_the_ID(), 'percent'); ?>%</span></p>
                </div>
            </div>
        </div>
        <!-- start About this project -->
        <div id="about-project rrr">
			<?php if($contentNotice && $currentSiteID != 41 && $currentSiteID != 55) { ?>
			<link href="<?php echo plugins_url('js_composer/assets/css/js_composer.min.css'); ?>" rel="stylesheet">
			<div class="container">
				<div class="vc_message_box vc_message_box-standard vc_message_box-rounded vc_color-info">
					<div class="vc_message_box-icon"><i class="fa fa-info-circle"></i>
					</div><p><strong>The content of this promotion has not been approved by an authorised person within the meaning of the Financial Services and Markets Act 2000. Reliance on this promotion for the purpose of engaging in any investment activity may expose an individual to a significant risk of losing all of the property or other assets invested.</strong></p>
				</div>
			</div>
			<?php } else if($currentSiteID == 41 || $currentSiteID == 55) { ?>
			<link href="<?php echo plugins_url('js_composer/assets/css/js_composer.min.css'); ?>" rel="stylesheet">
			<div class="container">
				<div class="vc_message_box vc_message_box-standard vc_message_box-rounded vc_color-info" style="text-align: center;">
					<div class="vc_message_box-icon"><i class="fa fa-info-circle"></i>
					</div>
					<p><strong>You could lose ALL of your money investing in this product. </strong></p> 
					<p><strong>This product is a High Risk Investment and is much riskier than a savings account. </strong></p> 
					<p><strong>ISA eligibility does not guarantee returns or protect you from losses.</strong></p>
				</div>
			</div>
			<?php } ?>
            <div class="container">
                <div class="row">
                    <!--<h1><?php echo get_the_title(); ?> Overview</h1>-->
                    <div class="col-md-12">
                        <ul class="nav nav-pills company-top-tabs">
                            <li class="active"><a href="#codet" data-toggle="pill">Company Details</a></li>
							<li><a href="#conews" data-toggle="pill">Company News</a></li>
                            <?php if ($isUserLoggedin) { ?>
                                <li><a href="#cocom" data-toggle="pill">Comments</a></li>
                                <li><a href="#cocoho" data-toggle="pill">Companies House</a></li>
        <?php } else { ?>
                                <li class='tab-disabled'>
                                    <a href="/sign-up">
                                        <img width="20px" height="20px" src="<?php bloginfo('template_url'); ?>/images/lock-blue-lighter.png" alt="section-locked">
                                        <span>Comments</span>
                                    </a>
                                </li>
                                <li class='tab-disabled'>
                                    <a href="/sign-up">
                                        <img width="20px" height="20px" src="<?php bloginfo('template_url'); ?>/images/lock-blue-lighter.png" alt="section-locked">
                                        <span>Companies House</span>
                                    </a>
                                </li>
        <?php } ?>
                        </ul>
                        <div class="tab-content">
                            <div id="codet" class="tab-pane fade in active">
                                <div class="col-md-6">
                                    <?php
                                    //if (is_single()) { ?>
                                    	<div class="intro">
                                       <?php the_content();
                                        ?>
                                        </div>
                                        <div class="breakdown">
                                        	  
                                            <div class="heading"><span class="screen-reader-text">Breakdown</span></div>
                                            <div class="info">
                                                <table>
                                                    <tbody>
														<?php 
														$investmentTerm = get_post_meta(get_the_ID(), 'thm_investment_term',true);
														if($investmentTerm != '') { ?>
														<tr>
                                                            <th>Investment Term:</th>
                                                            <td><?php echo $investmentTerm; ?></td>
                                                        </tr>
														<?php } ?>
														<?php 
														$isin = get_post_meta(get_the_ID(), 'thm_isin',true);
														if($isin != '') { ?>
														<tr>
                                                            <th>ISIN:</th>
                                                            <td><?php echo $isin; ?></td>
                                                        </tr>
														<?php } ?>
														<?php if(themeum_get_project_info(get_the_ID(), 'budget')) { ?>
															<tr>
																<th>Total Raise Amount:</th>
																<td><?php echo get_currencies($goalCurrency).themeum_get_project_info(get_the_ID(), 'budget'); ?></td>
															</tr>
															<tr>
																<th>Currency:</th>
																<td><?php echo $goalCurrency ?></td>
															</tr>
														<?php } ?>
														
														<?php 
															$investingInto = get_post_meta(get_the_ID(), 'thm_investing_into',false);
															if(count($investingInto) > 0) { ?>
															<tr>
																<th>Investment types offered:</th>
																<td><?php echo getInvestingIntoLabels($investingInto); ?></td>
															</tr>
														<?php } ?>
														<tr>
                                                            <th>Sector:</th>
                                                            <td><?php echo getProjectCats(get_the_ID()); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Location:</th>
                                                            <td><?php echo esc_attr($location); ?></td>
                                                        </tr>
                                                        <!--<tr>
                                                            <th>Total Raise Amount:</th>
                                                            <td>£<?php echo $funded ? $funded : 0; ?></td>
                                                        </tr>-->
                                                        
                                                        
                                                        <?php if($investmentType == 'equity') { ?>
															<?php if(themeum_get_project_info(get_the_ID(), 'equity')) { ?>
																<tr>
																	<th>Equity:</th>
																	<td><?php echo themeum_get_project_info(get_the_ID(), 'equity'); ?>%</td>
																</tr>
															<?php } ?>
                                                        <?php } ?>
														<?php if(get_post_meta(get_the_ID(), 'thm_minimum_investment',true) != '') { ?>
														<tr>
                                                            <th>Minimum Investment Amount</th>
                                                            <td><?php echo get_currencies($goalCurrency).get_post_meta(get_the_ID(), 'thm_minimum_investment',true); ?></td>
                                                        </tr>
														<?php } ?>
														<?php /*if(get_post_meta(get_the_ID(), 'thm_marketing',true) != '') { ?>
														<tr>
                                                            <th>Marketing Allowance:</th>
                                                            <td><?php echo get_post_meta(get_the_ID(), 'thm_marketing',true); ?>%</td>
                                                        </tr>
														<?php }*/ ?>
									<?php if($investmentType == 'bond' || $investmentType == 'loan' || $investmentType == 'fund') { ?> 
                                                        <tr>
                                                            <th>Coupon %:</th>
                                                            <td><?php echo get_post_meta(get_the_ID(), 'thm_coupon',true); ?>%</td>
                                                        </tr>
														<tr>
                                                            <th>Coupon Paid:</th>
                                                            <td><?php 
																$cpaid = get_post_meta(get_the_ID(), 'thm_coupon_paid',true); 
																if($cpaid == '') {
																	echo "Annually";	
																}else{
																	echo $cpaid == 'half_yearly' ? 'Half Yearly' : ucfirst($cpaid);	
																}
															?></td>
                                                        </tr>
                                                        <?php } ?>
                                                        <!--<tr>
                                                            <th>Days Left:</th>
                                                            <td>
                                                                <?php
                                                                if( $investmentType == 'equity') {
		                                                             $eDate = get_post_meta(get_the_ID(), 'thm_end_date', true);
		                                                             if ($eDate != '') {
		                                                                 $date1 = new DateTime();
		                                                                 $date2 = new DateTime($eDate);
		                                                                 echo $date2->diff($date1)->format('%a') . " days";
		                                                             } else {
		                                                                 echo "∞";
		                                                             }
                                                                }else{
                                                                	 $eDate = get_post_meta(get_the_ID(), 'thm_bond_expiry', true);
		                                                             if ($eDate != '') {
		                                                                 $date1 = new DateTime();
		                                                                 $date2 = new DateTime($eDate);
		                                                                 echo $date2->diff($date1)->format('%a') . " days";
		                                                             } else {
		                                                                 echo "∞";
		                                                             }
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>£-->
                                                        
														<?php if($unit_price != '') { ?>
															<?php if( $investmentType == 'equity') { ?>
																<tr>
																	<th>Unit Price:</th>
																	<td>
																		<?php echo $unit_price; ?>
																	</td>
																</tr>
															<?php }else{ ?>
																<tr>
																	<th>Unit Price:</th>
																	<td><?php echo $unit_price; ?></td>
																</tr>
															<?php } ?>
														<?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
										<?php
											$website = get_post_meta(get_the_ID(),'thm_website_url',true);
											if($website != '') { ?>
											<a href="<?php echo $website ?>" class="button outline gray padded" target="_blank">Visit Website</a>
										<?php } ?>
												<?php //if($userStripe && $userStripe != '') : ?>
												<?php //if((int)$currentSiteID != 29){ ?>
                                        <a href="<?php echo $invest_url."?project_id=" . get_the_ID(); ?>" data-wow-delay=".1s" class="btn btn-primary wow fadeInDown"><?php _e('Invest Now', 'themeum'); ?></a>
												<?php //} ?>
                                    <?php //endif; ?>    
                                    <?php //} ?>
                                </div>
                                
                                <div class="col-md-6">
        <?php if ($isUserLoggedin): ?>
            <?php if (isset($video_source) && $video_source) { ?>
                                            <div class="col-sm-12  wow fadeIn project-video">
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe class="embed-responsive-item" src="<?php echo esc_url($src); ?>"></iframe>
                                                </div>
                                            </div> 
                                        <?php
                                        } else {
                                            //echo '<img class="novidthumb" src="/wp-content/uploads/novidthumb.png"/>';
											echo '<img src="'.$thumbURL.'" class="img-responsive" />';
                                        }
                                        ?>
        <?php else: ?>
                                        <div class="no-video-found" style="background-image:url(/wp-content/uploads/novidthumb.png);background-position:33% center;">
                                            <div class="disabled-cover">
                                                <div class="container">
                                                    <img src="<?php bloginfo('template_url'); ?>/images/lock-white.png" width="75px" height="75px" alt="Register to view"><br>
                                                    <span>Please sign up to see<br> the full company overview</span>
                                                    <a href="/sign-up" class="trc-button">Sign Up</a>
                                                </div>
                                            </div>

                                        </div>
        <?php endif; ?>
                                </div>                                
                            </div>
							<div id="conews" class="tab-pane fade">
                                    <div class="container project-news"> 
										<div class="col-md-12">
										<?php $companyID = get_the_ID(); ?>
                                        <h4>News updates of company</h4>
										<?php
										$saved_campaign_update = get_post_meta($companyID, 'wpneo_campaign_updates', true);
										$news = $saved_campaign_update; //json_decode($saved_campaign_update,true);
										
										if(count($news) > 0) : ?>
											<ul class="comment-list newsposts">
											<?php foreach($news as $key => $newspost) : ?>
														<li class="comment byuser comment-author-tomknifton even thread-even depth-1" id="news_<?php echo $key ?>">										  		
															<div class="comment-intro">
																 <span class='title'><?php echo $newspost['title'] ?></span><br/>
																 <span class='date'><?php echo $newspost['date']; ?></span><br/>
																 <?php if($newspost['file_id']) { ?>
																 <span>
																	<a href="<?php echo wp_get_attachment_url($newspost['file_id']) ?>">Open Attachment</a><br/>
																	<?php 
																		$mem_image = wp_get_attachment_image_src($newspost['file_id'], 'project-thumb');
																	?>
																	<img src="<?php echo $mem_image[0] ?>" width="20%" />
																 </span>
																 <?php } ?>
																 <span class="description"><p><?php echo nl2br($newspost['details']); ?></p></span>
															</div>
														</li> 
											<?php endforeach; ?>
											</ul>
										<?php else: ?>
											<div class="sharerow">
											  <p class="locktext">No news articles found.</p>
										</div>
										<?php endif; ?>
										</div>
                                    </div>
                                </div>
                                    <?php if ($isUserLoggedin) { ?>
                                <div id="cocom" class="tab-pane fade">
                                    <div class="container project-comment"> 
                                        <?php //get_template_part( 'post-format/user-profile' ); ?> 
                                        <?php
                                        // if ( comments_open() || get_comments_number() ) {
                                        // if ( isset($themeum_options['blog-single-comment-en']) && $themeum_options['blog-single-comment-en'] ) {
                                        //comments_template();
                                        //}
                                        //}
										
										//comments_template('/project_comments.php');
										
										$comms = get_comments(array('post_id' => get_the_ID())); ?>
										<div id="comments" class="comments-area comments">
											<ul class="comment-list">
												<?php
													wp_list_comments( array(
														'style'       => 'ul',
														'short_ping'  => true,
														'callback' => 'format_comment_business_page',
														'avatar_size' => 48
													), $comms );
												?>
											</ul><!-- .comment-list -->
											<?php 
											$commenter = wp_get_current_commenter();
											$fields =  array(
												'author' => '<div class="col6 col6-input"><input id="author" name="author" type="text" placeholder="'. __( 'Name', 'themeum' ) .'" value="" size="30"' . esc_attr($aria_req) . '/>',
												'email'  => '<input id="email" name="email" type="text" placeholder="'. __( 'Email', 'themeum' ) .'" value="" size="30"' . esc_attr($aria_req) . '/>',
												'url'  => '<input id="url" name="url" type="text" placeholder="'. __( 'Website url', 'themeum' ) .'" value="" size="30"/></div>',
											);
											$comments_args = array(
												'fields' =>  $fields,
												'comment_notes_before'      => '',
												'comment_notes_after'       => '',
												'comment_field'             => '<div class="col6"><textarea id="comment" placeholder="'. __( 'Comment', 'themeum' ) .'" name="comment" aria-required="true"></textarea></div>',
												'label_submit'              => 'Send Comment',
												'title_reply' => 'Join the Discussion'
											);
											comment_form($comments_args,get_the_ID());
											
											?>
										</div>
                                    </div>
                                </div>
        <?php } ?>
                            <div id="cocoho" class="tab-pane fade">
                                <h3>Details</h3>
                                <div id="details" class="companies-house-content">
                                		<?php 
                                			$companyRegNo = get_post_meta(get_the_ID(), 'thm_coregno', true);
													$c = curl_init("https://find-and-update.company-information.service.gov.uk/company/$companyRegNo/filing-history");
													curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
													$html = curl_exec($c);
													if (curl_error($c))
														 die(curl_error($c));

													// Get the status code
													$status = curl_getinfo($c, CURLINFO_HTTP_CODE);
													curl_close($c);
													if($status == 200) {               
													$first_step = explode( '<table id="fhTable" class="full-width-table">' , $html );
													$second_step = explode("</table>" , $first_step[1] );

													echo '<table id="fhTable" class="full-width-table">';
													echo $second_step[0];
													echo '</table>';
													}else{
														echo "No Data Found";
													}
		                					?>
                                </div>
                            </div>
                        </div>
                        <div style="clear:both;"></div>
						<div class="files_section">
                        		<h3>Files</h3>
                        		<table>
								    		<tbody>
								    			<?php $noFiles = true; ?>
								    			<?php if($projectFiles['certificate']['id']) : 
								    				$certMetaKey = "certificate_".$projectFiles['certificate']['id'];
								    				$certChecked = get_post_meta($post->ID,$certMetaKey,true);
								    				if($certChecked == 'yes') {
								    				$noFiles = false;
								    			?>
										 			<tr>
										 				<td><a href="<?php echo $projectFiles['certificate']['path'] ?>" target="_blank">CERTIFICATE OF INCORPORATION</a></td>
										 			</tr>
										 			<?php } ?>
								    			<?php endif; ?>
								    			<?php if($projectFiles['article']['id']) : 
								    				$artMetaKey = "article_".$projectFiles['article']['id'];
								    				$artChecked = get_post_meta($post->ID,$artMetaKey,true);
								    				if($artChecked == 'yes') {
								    				$noFiles = false;
								    			?>
								    			<tr>
								    				<td><a href="<?php echo $projectFiles['article']['path'] ?>" target="_blank">ARTICLES OF ASSOCIATION</a></td>	
								    			</tr>
								    			<?php } ?>
								    			<?php endif; ?>
								    			<?php if($projectFiles['memorandum']['id']) : 
								    				$memoMetaKey = "article_".$projectFiles['memorandum']['id'];
								    				$memoChecked = get_post_meta($post->ID,$memoMetaKey,true);
								    				if($memoChecked == 'yes') {
								    				$noFiles = false;
								    			?>
								    			<tr>
								    				<td><a href="<?php echo $projectFiles['memorandum']['path'] ?>" target="_blank">MEMORANDUM OF ASSOCIATION</a></td>
								    			</tr>
								    			<?php } ?>
								    			<?php endif; ?>
								    			
								    			<?php if(count($projectFiles['banks']) > 0 ) : 
								    				?>
									 				<?php
									 				$i = 1;
									 				foreach($projectFiles['banks'] as $bankID => $bankPath) { 
										 				$bankMetaKey = "bank_".$bankID;
										 				$bankChecked = get_post_meta($post->ID,$bankMetaKey,true);
										 				if($bankChecked == 'yes') {
										 				$noFiles = false;
									 				?>
											 			<tr>
											 				<td><a href="<?php echo $bankPath ?>" target="_blank">Bank Statement <?php echo $i++; ?></a></td>
											 			</tr>
											 			<?php } ?>
									 				<?php	} ?>
								    			<?php endif; ?>
								    			
								    			<?php if(count($projectFiles['financials']) > 0 ) : 
								    				?>
									 				<?php
									 				$i = 1;
									 				foreach($projectFiles['financials'] as $fiID => $fiPath) { 
										 				$fiMetaKey = "financial_".$fiID;
										 				$fiChecked = get_post_meta($post->ID,$fiMetaKey,true);
										 				if($fiChecked == 'yes') { 
										 				$noFiles = false;
									 				?>
											 			<tr>
											 				<td><a href="<?php echo $fiPath ?>" target="_blank">Financial Report <?php echo $i++; ?></a></td>
											 			</tr>
											 			<?php } ?>
									 				<?php	} ?>
								    			<?php endif; ?>
								    			
								    			<?php 
												
												if(count($projectFiles['extraFiles']) > 0 ) : 
								    				$noFiles = false;
								    				?>
									 				<?php
									 				$i = 1;
									 				foreach($projectFiles['extraFiles'] as $exID => $exPath) { 
									 					$exMetaKey = "extra_".$exID;
										 				$exChecked = get_post_meta($post->ID,$exMetaKey,true);
										 				if($exChecked == 'yes') {
										 				$noFiles = false;
										 				$exFileTitleMetaKey = "extra_file_title_".$exID;
		             								$exFileTitle = get_post_meta($post->ID,$exFileTitleMetaKey,true);
													restore_current_blog();
													switch_to_blog($postSiteID);
													$filePost = get_post($exID);
													restore_current_blog();
													switch_to_blog(1);
													
									 				?>
											 			<tr>
											 				<td><a href="<?php echo $exPath ?>" target="_blank">
											 					<?php if($exFileTitle != '') { 
										 						echo $exFileTitle;	  
										 						}else{ ?>
											 					<?php echo $filePost->post_title; ?> 
											 					<?php } ?>
											 				</a></td>
											 			</tr>
											 			<?php 
											 			$i++;
											 			} ?>
									 				<?php	} ?>
								    			<?php endif; ?>
								    			
								    			<?php if($noFiles === true): ?>
								    				<tr><td colspan="2">No Files Uploaded</td></tr>
								    			<?php endif; ?>
								    		</tbody>
                        		</table>
                        		
                        </div> <!-- end of Files Section -->
						<?php if($noFiles === true) { ?>
							<style>
							.files_section {
								display:none;
							}
							</style>
						<?php } ?>
						<?php
						
                                   // if (is_single()) { ?>
                                <!--<div class="col-md-6 ideamarket-tabs">-->
                                	<section id="left-tabs" class="ui-tabs ui-corner-all ui-widget ui-widget-content ui-tabs-disabled">
                                            <div class="side-menu">
                                                <ul class="nav nav-pills">
                                                    <li class="active"><a href="#idea-tab" data-toggle="pill">Overview</a></li>
                                                    <?php if ($isUserLoggedin) { ?>
														<?php 
															$hiddenTabOptions = get_post_meta(get_the_ID(),'sections_to_hide',true);
														?>
														<!--<?php if(!in_array('market',$hiddenTabOptions)) { ?>
                                                        <li><a href="#market-tab" data-toggle="pill">Market</a></li>
														<?php } ?>-->
														<?php if(!in_array('team',$hiddenTabOptions)) { ?>
                                                        <li><a href="#team-tab" data-toggle="pill">Team</a></li>
														<?php } ?>
            <?php } else { ?>
														<!--<?php if(!in_array('market',$hiddenTabOptions)) { ?>
                                                        <li class='tab-disabled'>
                                                            <a href="/sign-up">
                                                                <img width="20px" height="20px" src="<?php bloginfo('template_url'); ?>/images/lock-blue-lighter.png" alt="section-locked">
                                                                <span>Market</span>
                                                            </a>
                                                        </li>
														<?php } ?>-->
														<?php if(!in_array('team',$hiddenTabOptions)) { ?>
                                                        <li class='tab-disabled'>
                                                            <a href="/sign-up">
                                                                <img width="20px" height="20px" src="<?php bloginfo('template_url'); ?>/images/lock-blue-lighter.png" alt="section-locked">
                                                                <span>Team</span>
                                                            </a>
                                                        </li>
														<?php } ?>
            <?php } ?>			
                                                </ul>
                                            </div>
                                            <div id="idea-tab" class="utab-pane fade in active">
                                            <?php echo get_post_meta(get_the_ID(), 'thm_business_idea', true); ?>
                                            </div>
                                                <?php if ($isUserLoggedin) { ?>
                                                <!--<div id="market-tab" class="tab-pane fade">
                <?php //echo get_post_meta(get_the_ID(), 'thm_business_market', true); ?>
                                                </div>-->
                                                <div id="team-tab" class="tab-pane fade">
                                                <?php 

                                                	if(count($teamMembers) > 0) {
														?>
														<strong>About the Project Creator:</strong>
														<p><?php echo get_post_meta(get_the_ID(),'thm_about',true); ?></p>
														<?php $pSiteID = get_post_meta(get_the_ID(),'thm_site_id',true); ?>
														<strong>Team Members:</strong>
														<?php
                                                		echo "<ul class='team_members'>";
                                                		foreach($teamMembers as $member){ ?>
                                                			<li>
                                                				<?php 
																//global $switched;
																switch_to_blog($pSiteID);
																$mem_image = wp_get_attachment_image_src($member['image'], 'project-thumb'); 
																restore_current_blog();
																switch_to_blog(1);
																?>
                                                				<div class='member_photo'>
                                                					<img src="<?php echo $mem_image[0] ?>" />
                                                				</div>
                                                				<div class='member_info'>
                                                					<span class='member_title'><?php echo $member['title'] ?></span>
                                                					<span class='member_name'><?php echo $member['name'] ?></span>
                                                				</div>
                                                				<div class='member_bio'>
                                                					<p><?php echo $member['bio'] ?></p>
                                                				</div>
                                                			</li>
                                                		<?php }
                                                		echo "</ul>";
                                                	}else{
                                                		echo "No Team Members found!";
                                                	}
                                                ?>
                                                </div>
                                        <?php } ?>
                                        </section>
                                <!--</div>-->
                                <?php //} ?>
                        
                        <!--<section class="contact_form" style="clear: both;">
									<div>
								        <div class="">
								            <h2>Contact</h2>
							<?php echo do_shortcode( '[contact-form-7 id="33" title="Contact Main"]' ); ?>
							</div></div>
						</section>-->
                        <!-- WARNING -->
                        <section class="warning" style="clear:both;">
								    <div>
								        <div class="risk-warning">
								            <h3>Risk Warning</h3>
								            <p>Investors should be aware that there are risks to investing in shares of companies, especially if they are private companies as there may be little or no market for the shares to be traded and dividends are unlikely to be paid. Investments may go down as well as up and therefore investors may not recover their initial investment.</p>
								            <a style="color: #00aed1;" href="/terms-conditions/risk-warning/">please click here to read the full risk warning</a>
								        </div>
								    </div>
								</section>
                        <!--  END OF SECTION : WARNING -->
                    </div>

                    <div class="col-md-8 project-info">
                        <div class="entry-meta wow fadeInDown" data-wow-delay=".1s">
                            <span class="entry-food"><?php echo get_the_term_list(get_the_ID(), 'project_tag', '<i class="fa fa-tags"></i> ', ', '); ?></span>
                            <span class="entry-money"><i class="fa fa-money"></i> <?php echo esc_attr($project_type); ?></span>
                        </div>
        <?php
        $result = get_post_meta(get_the_ID(), 'project_update');
        if (count($result) > 0):
            ?>
                            <div class="row project-updates">
                                <h2 class="main-title col-sm-12"><?php _e('Project Updates', 'themeum'); ?> (<?php echo count($result); ?>)</h2>
                                <?php
                                $i = 1;
                                foreach ($result as $value) {
                                    list($title, $content) = explode("*###*", $value);
                                    ?>
                                    <div class="col-sm-12">
                                        <div class="media each-update wow fadeIn">
                                            <div class="media-left">
                                                <div class="update-number"><?php echo esc_attr($i); ?></div>
                                            </div>
                                            <div class="media-body">
                                                <h3><?php echo esc_attr($title); ?></h3>
                                                <h4><?php _e('Update', 'themeum'); ?> #<?php echo esc_attr($i); ?> </h4>
                                                <p><?php echo esc_attr($content); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                $i++;
                            }
                            ?>
                            </div>
        <?php endif; ?>


                    </div>
                    <div class="col-md-4 project-status">
                        <div class="row">
                            <div class="col-md-12 col-sm-4 col-xs-12 wow fadeInRight" data-wow-delay=".1s">
                                <h2><?php echo themeum_get_project_info(get_the_ID(), 'investor'); ?></h2>
                                <h3><?php _e('Backer(s)', 'themeum'); ?></h3>
                            </div>
                            <div class="col-md-12 col-sm-4 col-xs-12 wow fadeInRight" data-wow-delay=".2s">
                                <h2><?php echo themeum_get_currency_symbol(); ?><?php echo themeum_get_project_info(get_the_ID(), 'budget'); ?></h2>
                                <h3><?php _e('Total Pledges', 'themeum'); ?></h3>
                            </div>
                            <div class="col-md-12 col-sm-4 col-xs-12 wow fadeInRight" data-wow-delay=".3s">
                                <h2><?php echo themeum_get_project_info(get_the_ID(), 'percent'); ?>%</h2>
                                <h3><?php _e('Funds Raised', 'themeum'); ?></h3>
                            </div>
                            <div class="col-sm-12 social-icon wow fadeInRight" data-wow-delay=".4s">

        <?php
        $permalink = get_permalink(get_the_ID());
        $titleget = get_the_title();
        ?>
                                <div class="social-button">
                                    <ul class="list-unstyled list-inline">
                                        <li>
                                            <a class="facebook" onClick="window.open('http://www.facebook.com/sharer.php?u=<?php echo esc_url($permalink); ?>', 'Facebook', 'width=600,height=300,left=' + (screen.availWidth / 2 - 300) + ',top=' + (screen.availHeight / 2 - 150) + ''); return false;" href="http://www.facebook.com/sharer.php?u=<?php echo esc_url($permalink); ?>"><i class="fa fa-facebook"></i></a>
                                        </li>
                                        <li>
                                            <a class="twitter" onClick="window.open('http://twitter.com/share?url=<?php echo esc_url($permalink); ?>&text=<?php echo str_replace(" ", "%20", $titleget); ?>', 'Twitter share', 'width=600,height=300,left=' + (screen.availWidth / 2 - 300) + ',top=' + (screen.availHeight / 2 - 150) + ''); return false;" href="http://twitter.com/share?url=<?php echo esc_url($permalink); ?>&text=<?php echo str_replace(" ", "%20", $titleget); ?>"><i class="fa fa-twitter"></i></a>
                                        </li>
                                        <li>
                                            <a class="g-puls" onClick="window.open('https://plus.google.com/share?url=<?php echo esc_url($permalink); ?>', 'Google plus', 'width=585,height=666,left=' + (screen.availWidth / 2 - 292) + ',top=' + (screen.availHeight / 2 - 333) + ''); return false;" href="https://plus.google.com/share?url=<?php echo esc_url($permalink); ?>"><i class="fa fa-google-plus"></i></a>
                                        </li>
                                        <li>
                                            <a class="linkedin" onClick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url($permalink); ?>', 'Linkedin', 'width=863,height=500,left=' + (screen.availWidth / 2 - 431) + ',top=' + (screen.availHeight / 2 - 250) + ''); return false;" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url($permalink); ?>"><i class="fa fa-linkedin"></i></a>
                                        </li>
                                        <li>
                                            <a class="pinterest" href='javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;http://assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());'><i class="fa fa-pinterest"></i></a>
                                        </li>

                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-12 startup-reward">
                                <div class="reward-title"><?php _e('Project Rewards', 'themeum'); ?></div>
        <?php
        if (is_array($themeum_reward)):
            foreach ($themeum_reward as $value) {
                ?>
                                        <div class="reward-child wow fadeInRight" data-wow-delay=".4s">
                                            <a href="<?php echo get_permalink(get_option('paypal_payment_checkout_page_id')); ?><?php echo "?project_id=" . get_the_ID(); ?><?php echo "&reward=" . $value['themeum_min']; ?>">
                                                <div class="reward-overlay">
                                                    <div class="reward-renge"><?php echo themeum_get_currency_symbol(); ?><?php echo $value['themeum_min']; ?> - <?php echo themeum_get_currency_symbol(); ?><?php echo $value['themeum_max']; ?></div>
                                                    <div class="reward-details">
                                        <?php echo $value['themeum_reward_data']; ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
            <?php } endif; ?>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div> <!-- end About this project -->



        <!-- Other Related Project -->
        <!--<div class="container">
            <div class="row">
                <div id="popular-ideas" class="col-sm-12 related-project-wrap">
                    <div class ="related-project-title"><?php _e('Other Companies You May Like', 'themeum'); ?></div>
                    <div class="row">
                        <?php
                        // The Query
						$metaKey = 'display_business_'.$currentSiteID;
                        query_posts(array(
                            'post_type' => 'project',
                            'posts_per_page' => 3,
							'post__not_in' => array(get_the_ID()),
							'meta_query' => array(
								array(
									'key'     => $metaKey,
									'value'   => 'yes',
									'compare' => '=',
								),
							),
                        ));
                        while (have_posts()) : the_post();
                        $orgURL = get_the_permalink();
								  $urlParsed = parse_url($orgURL);
								  $urlParts = explode("blog",$urlParsed['path']);
								  $postURL = $homeURL.$urlParts[1];
		  
                            $location2 = esc_attr(get_post_meta(get_the_ID(), "thm_location", true));
                            $funding_goal2 = esc_attr(get_post_meta(get_the_ID(), "thm_funding_goal", true));
                            $equityoffer = get_post_meta(get_the_ID(), "thm_equityoffer", true);
                            // -----------------
                            $cat_terms = wp_get_object_terms(get_the_ID(), 'project_category', array());
                            ?>
                            <div class="related-project col-sm-4 item wow fadeInUp" data-wow-duration="800ms" data-wow-delay="200ms">
                                <div class="testchris2 ideas-item">
                                    <div class="image">
                                        <div class="fund-progress"><div class="bar" style="width: <?php echo themeum_get_project_info(get_the_ID(), 'percent'); ?>%"></div></div>
                                        <?php
                                        echo '<a href="' . $postURL . '">';
                                        echo '<figure>';
                                        $imageURL = getProjectImageThumbSrc(get_the_ID());
                                        if ($imageURL) { ?>
					 										<img src="<?php echo $imageURL ?>" class="img-responsive" />
                                        <?php } else {
                                            echo '<div class="no-image"></div>';
                                        }

                                        echo '</figure>';
                                        echo '</a>';
                                        echo '</div>';

                                        echo '<div class="clearfix"></div>';

                                        echo '<div class="details">';
                                        echo '<h4><a href="' . $postURL . '">' . get_the_title() . '</a></h4>';
                                        echo '<p class="comsum"><a href="' . $postURL . '">' . get_the_excerpt() . '</a></p>';
                                        echo '<div class="country-name">' . esc_attr($location2) . '</div>';
                                        echo '<div class="project-type-lab pull-right">' . $cat_terms[0]->name . '</div>';
                                        echo '<div class="entry-meta">';
                                        // $output .= '<p>'.themeum_get_project_info(get_the_ID(),'percent').'%</p>';
                                        echo '<div class="comdetcol">Equity<span>' . ($equityoffer ? $equityoffer . '%' : 'N/A') . '</span></div>';
                                        echo '<div class="comdetcol">Investment<span>' . themeum_get_currency_symbol() . esc_attr($funding_goal) . '</span></div>';
                                        echo '<div class="comdetcol">Investors<span>0</span></div>';
                                        // $output .= themeum_get_ratting_data(get_the_ID());
                                        // $output .= '<span class="entry-food">'.get_the_term_list( get_the_ID(), 'project_tag', '<i class="fa fa-tags"></i> ', ', ' ).'</span>';
                                        echo '</div>';
                                        echo '</div> ';
                                        echo '</div>';
                                        ?>

                                    </div>
            <?php
        //endwhile;
        wp_reset_query();
        ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>-->

<?php else: ?>
    <?php get_template_part('post-format/content', 'none'); ?>
<?php endif;
} 
restore_current_blog();
switch_to_blog($currentSiteID);
?>
<div class="clearfix"></div>
<style>
#fhTable .js-hidden,
#fhTable .visuallyhidden {
display:none;
}
</style>
<?php
} // end of white label single business page 
get_footer();