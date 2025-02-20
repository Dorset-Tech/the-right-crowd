<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


do_action( 'wpneo_before_crowdfunding_single_campaign' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
?>

<div class="wpneo-wrapper">
    <div class="wpneocf-container">
        <div id="primary" class="content-area">
            <div id="content" class="site-content" role="main">
                <div class="wpneo-list-details">

					<?php do_action( 'wpneo_before_main_content' ); ?>
                    <div itemscope itemtype="http://schema.org/ItemList" id="campaign-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php do_action( 'wpneo_before_crowdfunding_single_campaign_summery' ); ?>
                        <div class="wpneo-campaign-summary">
                            <div class="wpneo-campaign-summary-inner" itemscope itemtype="http://schema.org/DonateAction">
								<?php do_action( 'wpneo_crowdfunding_single_campaign_summery' ); ?>
                            </div><!-- .wpneo-campaign-summary-inner -->
                        </div><!-- .wpneo-campaign-summary -->
						<?php do_action( 'wpneo_after_crowdfunding_single_campaign_summery' ); ?>
                        <meta itemprop="url" content="<?php the_permalink(); ?>" />
                    </div><!-- #campaign-<?php the_ID(); ?> -->
					<?php do_action( 'wpneo_after_crowdfunding_single_campaign' ); ?>
					<?php do_action( 'wpneo_after_main_content' ); ?>

                </div>
            </div><!-- #content -->
        </div><!-- #primary -->
    </div>

</div>
