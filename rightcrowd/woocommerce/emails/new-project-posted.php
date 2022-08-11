<?php
/**
 * New company posted email
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


?>

<!--<?php // do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php // printf( esc_html__( 'Dear User,', 'woocommerce' )); ?></p>
<p><?php //printf( esc_html__( 'New company \'%s\' has been posted on %s', 'woocommerce' ), esc_html( wp_specialchars_decode( $project->post_title ), ENT_QUOTES ), esc_html( $project->blogname, ENT_QUOTES ) ); ?></p>
<p>Click on the following link to see the company: <br/>
<?php // echo $project->postURL ?></p>

<p><?php //esc_html_e( 'We look forward to seeing you soon.', 'woocommerce' ); ?></p>

<?php
//do_action( 'woocommerce_email_footer', $email );

$message = "Dear User, <br/> New Company ".(esc_html( wp_specialchars_decode( $project->post_title ), ENT_QUOTES ))." has been posted on ".(esc_html( $project->blogname, ENT_QUOTES ));
$message .= "<br/> Click on the following link to see the company: <br/>";
$message .= $project->postURL." <br/>";
$message .= "We look forward to seeing you soon.<br/>";

$siteID = get_post_meta($post_id,'thm_site_id',true);
switch_to_blog($siteID);
	$body = "<div style='width:600px;margin:auto;background-color:#ffffff;border-radius: 3px!important;
		background-color: #ffffff;
		border: 1px solid #dedede;'>"; // main wrapper 
		  $body .= getEmailHeader();   
		  $body .= "<div style='padding: 36px 48px; background: #52B7EA; color: #fff;'><h1>".$subject."</h1></div>";
		  $body .= "<div style='padding: 48px;'>"; // content
			$body .= $message;
		  $body .= "</div>"; // end of content
		  $body .= getEmailFooter();
	$body .= "</div>"; // end of main wrapper
	  
	echo $body;
restore_current_blog();

?>
