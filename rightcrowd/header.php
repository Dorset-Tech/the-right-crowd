<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php
global $themeum_options;

?>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
	if(isset($themeum_options['favicon'])){ ?>
		<link rel="shortcut icon" href="<?php echo esc_url($themeum_options['favicon']['url']); ?>" type="image/x-icon"/>
	<?php }else{ ?>
		<link rel="shortcut icon" href="<?php echo esc_url(get_template_directory_uri().'/images/plus.png'); ?>" type="image/x-icon"/>
	<?php } ?>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <link rel="stylesheet" id="MarkProFont" href="/wp-content/themes/rightcrowd/css/MarkPro.css" type="text/css" media="all">
	<?php wp_head(); ?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-QK8GV820KL"></script>
<script src="https://kit.fontawesome.com/fb4999382f.js" crossorigin="anonymous"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-QK8GV820KL');
</script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/58ad9caa5e0c3809ffb1d0d4/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();

</script>
<!--End of Tawk.to Script-->
</head>

 <?php
     if ( isset($themeum_options['boxfull-en']) ) {
      $layout = esc_attr($themeum_options['boxfull-en']);
     }else{
        $layout = 'fullwidth';
     }
 ?>
<body <?php body_class( $layout.'-bg' ); ?>>
	<div id="page" class="hfeed site <?php echo esc_attr($layout); ?>">
		<header id="masthead" class="site-header header" role="banner">
			<div id="header-container">
				<div id="navigation" class="container">
                    <div class="row">
                        <div class="col-md-2">
        					<div class="navbar-header">
        						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
        						</button>
                                <div class="logo-wrapper">
        	                       <a class="navbar-brand" href="<?php echo site_url(); ?>">
        		                    	<?php
        									if (isset($themeum_options['logo']))
        								   {

        										if($themeum_options['logo-text-en']) {
        											echo esc_html($themeum_options['logo-text']);
        										}
        										else
        										{
        											if(!empty($themeum_options['logo'])) {
        											?>
        												<img class="enter-logo img-responsive" src="<?php echo esc_url($themeum_options['logo']['url']); ?>" alt="" title="">
        											<?php
        											}else{
        												echo esc_html(get_bloginfo('name'));
        											}
        										}
        								   }
        									else
        								   {
        								    	echo esc_html(get_bloginfo('name'));
        								   }
        								?>
        		                     </a>
                                </div>
        					</div>
                        </div>

                        <div class="col-md-8">

                            <div id="main-menu" class="hidden-sm hidden-xs ">

                                <?php
								/*if(get_current_blog_id() == '1') {
									if(is_user_logged_in()) {
										if ( has_nav_menu( 'primary' ) ) {
											wp_nav_menu(  array(
												'theme_location' => 'primary',
												'container'      => '',
												'menu_class'     => 'nav',
												'fallback_cb'    => 'wp_page_menu',
												'depth'          => 3,
												'walker'         => new Megamenu_Walker()
												)
											);
										}
									}
								}else{*/
									if ( has_nav_menu( 'primary' ) ) {
										wp_nav_menu(  array(
											'theme_location' => 'primary',
											'container'      => '',
											'menu_class'     => 'nav',
											'fallback_cb'    => 'wp_page_menu',
											'depth'          => 3,
											'walker'         => new Megamenu_Walker()
											)
										);
									}
								//}
                                ?>
                            </div><!--/#main-menu-->
							

                        </div>

                        <div class="col-md-2 search">
							<?php
							global $blog_id;
							
							if ($blog_id == 1) {
							
							if(!is_user_logged_in()){
                                echo '<a class="headerbut signup" href="/sign-up">Sign Up / Login</a>';
                            } else {
                                echo '<a class="headerbut" href="/my-account/">My Account</a>';
                            }
							}
                            ?>
							
							<form method="get" id="searchformhead" action="/">
                            <input type="text" class="form-control" name="s" id="shead" placeholder="Search">
                            <button id="headsearchbut" class="search_form_but"> <i class="fa fa-search"></i> </button>
                            </form>
                            

                        </div>

                        <div id="mobile-menu" class="visible-sm visible-xs">
                            <div class="collapse navbar-collapse">
                                <?php
                                if ( has_nav_menu( 'primary' ) ) {
                                    wp_nav_menu( array(
                                        'theme_location'      => 'primary',
                                        'container'           => false,
                                        'menu_class'          => 'nav navbar-nav',
                                        'fallback_cb'         => 'wp_page_menu',
                                        'depth'               => 3,
                                        'walker'              => new wp_bootstrap_mobile_navwalker()
                                        )
                                    );
                                }
                                ?>
                            </div>
                        </div><!--/.#mobile-menu-->
                    </div><!--/.row-->
				</div><!--/.container-->
			</div>

		</header><!--/#header-->
        <!-- sign in form -->
        <section id="sign-form">
             <div id="sign-in" class="modal fade">
                <div class="modal-dialog modal-lg">
                     <div class="modal-content">
                         <div class="modal-header">
                             <i class="fa fa-close close" data-dismiss="modal"></i>
                         </div>
                         <div class="modal-body text-center">
                             <h3><?php _e('Welcome','themeum'); ?></h3>
                             <p><?php _e('Share your idea, put up your feet, stay awhile.',''); ?></p>
                             <form id="login" action="login" method="post">
                                <div class="login-error alert alert-info" role="alert"></div>
                                <input type="text"  id="username" name="username" class="form-control" placeholder="<?php _e('User Name','themeum'); ?>">
                                <input type="password" id="password" name="password" class="form-control" placeholder="<?php _e('Password','themeum'); ?>">
                                <input type="submit" class="btn btn-default btn-block submit_button"  value="Login" name="submit">
                                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><strong><?php _e('Forgot password?','themeum'); ?></strong></a>
                                <p><?php _e('Not a member?','themeum'); ?> <a href="<?php echo esc_url(get_permalink(get_option('wpneo_registration_page_id'))); ?>"><strong><?php _e('Join today','themeum'); ?></strong></a></p>
                                <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>
        </section> <!-- end sign-in form -->