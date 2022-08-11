<?php
//define('THEMEUMNAME', wp_get_theme()->get( 'Name' ));
define('THEMEUMNAME', 'startupidea');
define('THMCSS', get_template_directory_uri().'/css/');
define('THMJS', get_template_directory_uri().'/js/');

if((!class_exists('RWMB_Loader'))&&(!defined('RWMB_VER'))){
// Include the meta box script
require_once (get_template_directory().'/lib/meta-box/meta-box.php');
}
require_once (get_template_directory().'/lib/metabox.php');

// pdf library
require_once (get_template_directory().'/fpdi/vendor/autoload.php');
use setasign\Fpdi\Fpdi;

if( !function_exists('themeum_system_check') ):
    function themeum_system_check(){
        global $themeum_options;
        if(!isset($themeum_options['system-plugins'])){
            return true;
        }else{
            if( $themeum_options['system-plugins']=='startupidea' ){
                return true;
            }else{
                return false;
            }
        }
    }
endif;

function excerptLength($length) {
    return 20;
}
add_filter('excerpt_length', 'excerptLength');
/*-------------------------------------------------------
 *              Redux Framework Options Added
 *-------------------------------------------------------*/

global $themeum_options;

if ( !class_exists( 'ReduxFramework' ) ) {
    require_once( get_template_directory() . '/admin/framework.php' );
}
require_once( get_template_directory() . '/admin/import-functions.php' );

if ( !isset( $redux_demo ) ) {
    require_once( get_template_directory() . '/theme-options/admin-config.php' );
}

/*-------------------------------------------------------
 *              Login and Register
 *-------------------------------------------------------*/
require get_template_directory() . '/lib/login.php';
if(themeum_system_check()){
    require get_template_directory() . '/lib/registration.php';
}

/*-------------------------------------------*
 *              Register Navigation
 *------------------------------------------*/
register_nav_menus( array(
    'primary' => 'Primary Menu'
) );


/*-------------------------------------------*
 *              title tag
 *------------------------------------------*/
add_theme_support( 'title-tag' );

/*-------------------------------------------*
 *              navwalker
 *------------------------------------------*/
//Main Navigation
require_once( get_template_directory()  . '/lib/menu/admin-megamenu-walker.php');
require_once( get_template_directory()  . '/lib/menu/meagmenu-walker.php');
require_once( get_template_directory()  . '/lib/menu/mobile-navwalker.php');
//Admin mega menu
add_filter( 'wp_edit_nav_menu_walker', function( $class, $menu_id ){
    return 'Themeum_Megamenu_Walker';
}, 10, 2 );

/* account links */
add_filter ( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );
function misha_remove_my_account_links( $menu_links ){

    unset( $menu_links['edit-address'] ); // Addresses


    unset( $menu_links['dashboard'] ); // Dashboard
    //unset( $menu_links['payment-methods'] ); // Payment Methods
    unset( $menu_links['orders'] ); // Orders
    unset( $menu_links['downloads'] ); // Downloads
    unset( $menu_links['edit-account'] ); // Account details
    //unset( $menu_links['customer-logout'] ); // Logout

    return $menu_links;

}

/*-------------------------------------------*
 *              Startup Register
 *------------------------------------------*/
require_once( get_template_directory()  . '/lib/main-function/startup-register.php');
if(themeum_system_check()){
    require_once( get_template_directory()  . '/lib/main-function/themeum-dashboard.php');
    require_once( get_template_directory()  . '/account/request.php');
}




/*-------------------------------------------------------
 *          Themeum Core
 *-------------------------------------------------------*/
require_once( get_template_directory()  . '/lib/main-function/themeum-core.php');

/*--------------------------------------------------------------
 *      Project (Add New Project)
 *-------------------------------------------------------------*/
if(themeum_system_check()){
    require_once( get_template_directory()  . '/lib/main-function/project-helper.php');
    require_once( get_template_directory()  . '/lib/main-function/project-submit.php');
    require_once( get_template_directory()  . '/lib/main-function/project-update.php');
    require_once( get_template_directory()  . '/lib/main-function/personal-data.php');
}


/*--------------------------------------------------------------
 *                  AJAX login System
 *-------------------------------------------------------------*/
require_once( get_template_directory()  . '/lib/main-function/ajax-login.php');


/*--------------------------------------------------------------
 *  Theme Activation Hook (create login and registration page)
 *-------------------------------------------------------------*/
require_once( get_template_directory()  . '/lib/main-function/login-registration.php');

/*-------------------------------------------------------
*           Custom Widgets and VC shortocde Include
*-------------------------------------------------------*/
require_once( get_template_directory()  . '/lib/widgets/image_widget.php');
require_once( get_template_directory()  . '/lib/widgets/blog-posts.php');
require_once( get_template_directory()  . '/lib/widgets/follow_us_widget.php');
require_once( get_template_directory()  . '/lib/vc-addons/fontawesome-helper.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-action.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-address.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-button.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-feature-box.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-icons.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-image-caption.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-person.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-social-media.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-feature-items.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-heading.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-featured-title.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-partners.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-recent-blog.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-video-popup.php');


/*-------------------------------------------------------
*           Custom Message Box Generator
*-------------------------------------------------------*/

function themeum_message_generator($id,$header,$body){
    $output = '';
        $output .= '<div id="'.$id.'-msg" class="startup-modal modal fade">';
            $output .= '<div class="modal-dialog modal-md">';
                $output .= '<div class="modal-content">';
                    $output .= '<div class="modal-header">';
                        $output .= '<button type="button" id="'.$id.'-close" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>';
                        $output .= '<h4 class="modal-title">'.$header.'</h4>';
                    $output .= '</div>';
                     $output .= '<div class="modal-body text-center">';
                         $output .= '<p>'.$body.'</p>';
                     $output .= '</div>';
                 $output .= '</div>';
            $output .= '</div>';
        $output .= '</div>';
    return $output;
}

function themeum_get_post_author_id(){
    $post_id = $_GET['project_id'];
    $post = get_post( $post_id );
    if(isset($post->post_author)){
        return $post->post_author;
    }else{
        return 'abc';
    }
}

function themeum_my_project_id_list($id){
    global $wpdb;
    $args = array(
    'post_type'     => 'project',
    'author'        =>  $id,
    'post_status'   => 'publish',
    'orderby'       =>  'post_date',
    'order'         =>  'ASC',
    'posts_per_page'    => -1
    );
    $results_id = '';

    $myposts = get_posts( $args );
    foreach ( $myposts as $post ) : setup_postdata( $post );
             $results_id[] = $post->ID;
    endforeach;
    if(!is_array($results_id)){ $results_id[] = 123343434; }
    wp_reset_postdata();

    return $results_id;
}

// Pre Set Query
if (is_user_logged_in()) {
    add_action('pre_get_posts','themeum_get_user_own_media' );
}

function themeum_get_user_own_media($query){
    if ($query) {
        if (! empty($query->query['post_type'])) {
            if ($query->query['post_type'] == 'attachment') {
                $user = wp_get_current_user();
                $query->set('author', $user->ID);
            }
        }
    }
}


/*-------------------------------------------*
 *              woocommerce support
 *------------------------------------------*/

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}


function themeum_page_template_remove( $page_templates ) {
    if( !themeum_system_check() ){
        unset( $page_templates['template-dashboard.php'] );
        unset( $page_templates['template-donate.php'] );
        unset( $page_templates['template-project-form.php'] );
        unset( $page_templates['template-user-profile.php'] );
    }
    return $page_templates;
}
add_filter( 'theme_page_templates', 'themeum_page_template_remove' );


if(!function_exists('themeum_get_ratting_data_html')){
    function themeum_get_ratting_data_html($rate){
        $x =1;
        $html = '<ul class="list-unstyled list-inline">';
        for ($j=1; $j<=5; $j++) {
            if($j<=floor($rate)){
                $html .= '<li><i class="fa fa-star"></i></li>';
            }else{
                if($x==1){
                    if($rate-floor($rate)>=0.5){
                        $html .= '<li><i class="fa fa-star-half-o"></i></li>';
                    }else{
                        $html .= '<li><i class="fa fa-star-o"></i></li>';
                    }
                }else{
                    $html .= '<li><i class="fa fa-star-o"></i></li>';
                }
                $x++;
            }
        }

        $html .= '</ul>';
        return $html;
    }
}


# Shop Page Column Set
function loop_columns() {
return 3;
}
add_filter('loop_shop_columns', 'loop_columns');

add_filter('wpneo_crowdfunding_frontend_dashboard_menus','custom_account_menu_filter',15);
function custom_account_menu_filter($menus) {
    unset($menus['rewards']);
    if(in_array('customer',wp_get_current_user()->roles) || in_array('subscriber',wp_get_current_user()->roles)) {
        $menus['myshares'] = array(
            'tab'            => 'account',
            'tab_name'       => 'Share Holdings',
            'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/dashboard-myshares.php'
        );
        $menus['mycoupons'] = array(
            'tab'            => 'account',
            'tab_name'       => 'Coupons',
            'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/dashboard-mycoupons.php'
        );
    }
    if(isManager() && !in_array('read_only_manager',wp_get_current_user()->roles)) {
        if(!in_array('custodian',wp_get_current_user()->roles) && !in_array('product_manager',wp_get_current_user()->roles)) {
        $menus['users'] =
                array(
                    'tab'            => 'users',
                    'tab_name'       => __('All Users','wp-crowdfunding'),
                    'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/users.php'
                );
        $menus['user-investments'] =
                array(
                    'tab'            => 'user-investments',
                    'tab_name'       => '',
                    'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/user-investments.php'
                );
        }
      if(!in_array('custodian',wp_get_current_user()->roles) && !in_array('product_manager',wp_get_current_user()->roles)) {
      $menus['portfolio'] =
                array(
                    'tab'            => 'portfolio',
                    'tab_name'       => __('Portfolio','wp-crowdfunding'),
                    'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/portfolio.php'
                );
      }

      $menus['reports'] =
                array(
                    'tab'            => 'reports',
                    'tab_name'       => __('Reports','wp-crowdfunding'),
                    'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/reports.php'
                );

                if(get_current_blog_id() != 1) {
                    $menus['under-approval-investments'] =
                        array(
                            'tab'            => 'under-approval-investments',
                            'tab_name'       => __('Investments For Approval','wp-crowdfunding'),
                            'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/under-approval-investments.php'
                        );
                }

}

if(in_array('custodian',wp_get_current_user()->roles) || in_array('read_only_manager',wp_get_current_user()->roles)) {
    $menus['reports'] =
                array(
                    'tab'            => 'reports',
                    'tab_name'       => __('Reports','wp-crowdfunding'),
                    'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/reports.php'
                );
    unset($menus['user-investments']);
unset($menus['pledges']);
}

if(in_array('product_manager',wp_get_current_user()->roles)){
    //unset($menus['profile']);
    unset($menus['campaign']);
    unset($menus['backed_campaigns']);
    unset($menus['reorder']);
    unset($menus['dashboard']);
}
if(in_array('read_only_manager',wp_get_current_user()->roles)){
    unset($menus['portfolio']);
    unset($menus['users']);
}
    /*
    [pledges_received] => Array
        (
            [tab] => campaign
            [tab_name] => Pledges Received
            [load_form_file] => /var/www/vhosts/therightcrowd.co.uk/httpdocs/wp-content/plugins/wp-crowdfunding/includes/woocommerce/dashboard/order.php
        )

    [bookmark] => Array
        (
            [tab] => campaign
            [tab_name] => My Watch List
            [load_form_file] => /var/www/vhosts/therightcrowd.co.uk/httpdocs/wp-content/plugins/wp-crowdfunding/includes/woocommerce/dashboard/bookmark.php
        )

    [password] => Array
        (
            [tab] => account
            [tab_name] => Security
            [load_form_file] => /var/www/vhosts/therightcrowd.co.uk/httpdocs/wp-content/plugins/wp-crowdfunding/includes/woocommerce/dashboard/password.php
        )

    [payments] => Array
        (
            [tab] => campaign
            [tab_name] => Payments
            [load_form_file] => /var/www/vhosts/therightcrowd.co.uk/httpdocs/wp-content/plugins/wp-crowdfunding/addons/wallet/pages/payments.php
        )

    [deposits] => Array
        (
            [tab] => campaign
            [tab_name] => Deposits
            [load_form_file] => /var/www/vhosts/therightcrowd.co.uk/httpdocs/wp-content/plugins/wp-crowdfunding/addons/wallet/pages/deposits.php
        )
    */
    // unset for now
    unset($menus['pledges_received']);
    unset($menus['bookmark']);
    unset($menus['payments']);
    unset($menus['deposits']);

    if(in_array('read_only_manager',wp_get_current_user()->roles)) {
        $menus['non-investing'] = array(
            'tab'            => 'non-investing',
            'tab_name'       => __('Non Investing Clients','wp-crowdfunding'),
            'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/non-investing.php'
        );
    }

    $menus['pending-aml'] = array(
        'tab'            => 'pending-aml',
        'tab_name'       => false,
        'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/pending-aml.php'
    );

    $menus['inprogress-investments'] = array(
        'tab'            => 'inprogress-investments',
        'tab_name'       => false,
        'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/inprogress-investments.php'
    );

    $menus['completed-investments'] = array(
        'tab'            => 'completed-investments',
        'tab_name'       => false,
        'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/completed-investments.php'
    );

    return $menus;
}

/* function written to redirect my account pages to new dashboard pages - by gd */
function redirectCustom(){
    $urls = [ // NEW URL => OLD URL
       '/dashboard/' => '/my-account/',
        '/dashboard/?page_type=dashboard' => '/my-account/crowdfunding-dashboard/',
        '/dashboard/?page_type=profile' => '/my-account/profile/',
        '/dashboard/?page_type=campaign' => '/my-account/my-campaigns/',
        '/dashboard/?page_type=backed_campaigns' => '/my-account/backed-campaigns/',
        '/dashboard/?page_type=pledges_received' => '/my-account/pledges-received/'

    ];
    if(in_array('product_manager',wp_get_current_user()->roles)){
        //$urls['/pm-dashboard-investments'] = '/dashboard/?page_type=dashboard';
    }
    $curURL = $_SERVER['REQUEST_URI'];
    $toUrl = array_search($curURL,$urls);
    if($toUrl) {
        wp_redirect($toUrl);
        exit;
    }

}

redirectCustom();

function redirectAfterLogin() {
  return '/dashboard';
}

//add_filter('login_redirect', 'redirectAfterLogin');

add_filter('admin_init', 'my_general_settings_register_fields');

function my_general_settings_register_fields()
{
    register_setting('general', 'site_type', 'esc_attr');
    add_settings_field('site_type', '<label for="site_type">'.__('Site Type' , 'site_type' ).'</label>' , 'my_general_settings_site_type', 'general');

    // parent site
    register_setting('general', 'parent_site', 'esc_attr');
    add_settings_field('parent_site', '<label for="parent_site">'.__('Parent Site' , 'parent_site' ).'</label>' , 'my_general_settings_parent_site', 'general');

    // parent site
    register_setting('general', 'allow_project_creation', 'esc_attr');
    add_settings_field('allow_project_creation', '<label for="allow_project_creation">'.__('Allow adding of Investment' , 'allow_project_creation' ).'</label>' , 'my_general_settings_allow_project_creation', 'general');

    // setting for setting up if site is ISA or not
    register_setting('general', 'is_isa_site', 'esc_attr');
    add_settings_field('is_isa_site', '<label for="is_isa_site">'.__('Is this site ISA?' , 'is_isa_site' ).'</label>' , 'my_general_settings_is_isa_site', 'general');

    register_setting('general', 'multiple_isa', 'esc_attr');
    add_settings_field('multiple_isa', '<label for="is_isa_site">'.__('Has this white label has multiple ISAs?' , 'multiple_isa' ).'</label>' , 'my_general_settings_multiple_isa', 'general');

    register_setting('general', 'business_type', 'esc_attr');
    add_settings_field('business_type', '<label for="business_type">'.__('Business Type' , 'business_type' ).'</label>' , 'my_general_settings_business_type', 'general');

    register_setting('general', 'proceed_redirection', 'esc_attr');
    add_settings_field('proceed_redirection', '<label for="proceed_redirection">'.__('Proceed Redirection URL' , 'proceed_redirection' ).'</label>' , 'my_general_settings_proceed_redirection', 'general');

    register_setting('general', 'bcc_email', 'esc_attr');
    add_settings_field('bcc_email', '<label for="bcc_email">'.__('BCC Email' , 'bcc_email' ).'</label>' , 'my_general_settings_bcc_email', 'general');

    register_setting('general', 'isa_source', 'esc_attr');
    add_settings_field('isa_source', '<label for="isa_source">'.__('Is ISA available as source of funding?' , 'isa_source' ).'</label>' , 'my_general_settings_isa_source', 'general');
    
    register_setting('general', 'nda_required', 'esc_attr');
    add_settings_field('isa_source', '<label for="nda_required">'.__('Is NDA Required for members during signup?' , 'isa_source' ).'</label>' , 'my_general_settings_nda', 'general');
}

function my_general_settings_site_type()
{
    $value = get_option( 'site_type', '' );
    echo '<select name="site_type" id="site_type">
                <option value="">--SELECT--</option>
                <option value="master" '.($value == 'master' ? 'selected' : '').'>Master</option>
                <option value="child" '.($value == 'child' ? 'selected' : '').'>Child</option>
            </select>
    ';
}
function my_general_settings_parent_site()
{
    $parentSitevalue = get_option( 'parent_site', '' );
    $allSites = wp_get_sites();
    echo '<p><em>If site type is child only then this field will be considered</em></p>
            <select name="parent_site" id="parent_site">
                <option value="">--SELECT--</option>';
                foreach($allSites as $site) {
                    echo '<option value="'.$site['blog_id'].'" '.( $parentSitevalue == $site['blog_id'] ? 'selected' : '').'>'.$site['domain'].'</option>';
                }
    echo '</select>';
}
function my_general_settings_allow_project_creation()
{
    $value = get_option( 'allow_project_creation', 'yes' );
    echo '<p><em>Uncheck to disallow</em></p>
    <input type="checkbox" id="allow_project_creation" name="allow_project_creation" value="yes" '.( $value == 'yes' ? 'checked' : '').' />';
}

function my_general_settings_business_type()
{
    $value = get_option( 'business_type', '' );
    echo '<select name="business_type" id="business_type">
                <option value="">--SELECT--</option>
                <option value="introducer" '.($value == 'introducer' ? 'selected' : '').'>Introducer</option>
                <option value="product" '.($value == 'product' ? 'selected' : '').'>Product</option>
            </select>
    ';
}

function my_general_settings_multiple_isa() {
    $value = get_option( 'multiple_isa', 'no' );
    echo '<p><em>Uncheck if not multiple ISAs offered.</em></p>
    <input type="checkbox" id="multiple_isa" name="multiple_isa" value="yes" '.( $value == 'yes' ? 'checked' : '').' />';
}

function my_general_settings_is_isa_site()
{
    $value = get_option( 'is_isa_site', 'no' );
    echo '<p><em>Uncheck if not an ISA Site</em></p>
    <input type="checkbox" id="is_isa_site" name="is_isa_site" value="yes" '.( $value == 'yes' ? 'checked' : '').' />';
}

function my_general_settings_isa_source()
{
    $value = get_option( 'isa_source', 'yes' );
    echo '<p><em>Uncheck if ISA not available as source of funding</em></p>
    <input type="checkbox" id="isa_source" name="isa_source" value="yes" '.( $value == 'yes' ? 'checked' : '').' />';
}

function my_general_settings_proceed_redirection()
{
    $value = get_option( 'proceed_redirection');
    echo '<p><em>Enter the url.</em></p>
    <input type="text" id="proceed_redirection" name="proceed_redirection" value="" />';
}

function my_general_settings_bcc_email()
{
    $value = get_option( 'bcc_email');
    echo '<p><em>Enter BCC email</em></p>
    <input type="text" id="bccemail" name="bcc_email" value="'.(!empty($value) ? $value : '').'" />';
}

// setting to open NDA option on white label
function my_general_settings_nda()
{
    $value = get_option( 'nda_required', 'no' );
    echo '<p><em>Check if NDA is needed to be checked during signup</em></p>
    <input type="checkbox" id="nda_required" name="nda_required" value="yes" '.( $value == 'yes' ? 'checked' : '').' />';
}

if(get_current_blog_id() == 15) {
add_action( 'wp_footer', function(){
    echo '<script type="text/javascript">

 var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
 (function(){
 var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
 s1.async=true;
 s1.src="https://embed.tawk.to/5d2724e822d70e36c2a54182/default";
 s1.charset="UTF-8";
 s1.setAttribute("crossorigin","*");
 s0.parentNode.insertBefore(s1,s0);
 })();
    </script>';
  }, 100 );
}

/* get latest news */
function get_latest_news() {
 $url = "https://finance.yahoo.com/news/rssindex";

 $feeds = simplexml_load_file($url);
 $feed_count = count($feeds->channel->item);
 ob_start();

 ?>

     <div class="vc_row wpb_row vc_inner vc_row-fluid">

                    <!-- LOOPING SECTION -->

                     <?php

                     if($feed_count > 0){
             //for($i = 1; $i < count($feed_count);){
                       $i = 1;
             echo "<ul class='trc_stock_news'>";
                       foreach ($feeds->channel->item as $key => $item) {
                         # code...
               //echo $item->getTitle();
               if($i > 8) break;
               ?>
               <li>
               <a style="color: #000;text-decoration: underline;" href="<?php echo $item->link; ?>" title="<?php echo substr($item->title, 0,75); ?>" target="_blank">
                 <?php
                 echo substr($item->title, 0,75); ?>
               </a>
               </li>
               <?php
               $i++;
             }
             echo "</ul>";
           }

                     ?>
                    <!-- LOOPING SECTION -->

               </div>
<?php
$html = ob_end_flush();
$html;
}
add_shortcode('wiki_feed', 'get_latest_news');

function format_comment_business_page($comment, $args, $depth) {

       $GLOBALS['comment'] = $comment; ?>

        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
                <div class='comment_avatar'>
                    <?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
                </div>
            <div class="comment-intro">
                     <span><?php comment_text(); ?></span>
                <span>Posted by :
                    <?php printf(__('%s'), get_comment_author_link()) ?>
                </span>
            </div>

            <?php if ($comment->comment_approved == '0') : ?>
                <em><?php _e('Your comment is awaiting moderation.') ?></em><br />
            <?php endif; ?>

            <!--<div class="reply">
                <?php //comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
            </div>-->
        </li>
<?php }

add_action('save_post','c_redirect_page');
function c_redirect_page(){
    $type=  get_post_type();

    switch ($type){
    case "page":
        $url=  admin_url().'edit.php?post_type=page';
        wp_redirect($url);
        exit;
        break;
    }
}

add_action('wp_footer','setAdminBarSite');
function setAdminBarSite() {
    //restore_current_blog();
    //restore_current_blog();
    //restore_current_blog();
    $fSite = isset($GLOBALS['_wp_switched_stack'][0]) ? $GLOBALS['_wp_switched_stack'][0] : get_current_blog_id();
    //echo "<pre>";
    //print_r($GLOBALS['_wp_switched_stack']);
    unset ( $GLOBALS['_wp_switched_stack'] );
    $GLOBALS['switched'] = false;
    switch_to_blog($fSite);
    //echo get_current_blog_id();
}

//add_action('wp_footer','removeIdleCookie');
function removeIdleCookie() {
    $userid = get_current_user_id();
    if(!$userid) {
        ?>
        <script>
            function idleLogout() {
                var t;
                window.onload = resetTimer;
                window.onmousemove = resetTimer;
                window.onmousedown = resetTimer;  // catches touchscreen presses as well
                window.ontouchstart = resetTimer; // catches touchscreen swipes as well
                window.onclick = resetTimer;      // catches touchpad clicks as well
                window.onkeypress = resetTimer;
                window.addEventListener('scroll', resetTimer, true); // improved; see comments

                function yourFunction() {
                    // your function for too long inactivity goes here
                    // e.g. window.location.href = 'logout.php';
                    console.log("cookie removed - please refresh");
                    document.cookie = "investor_type=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
                    location.reload();
                }

                function resetTimer() {
                    clearTimeout(t);
                    t = setTimeout(yourFunction, 300000);  // time is in milliseconds
                }
            }
            idleLogout();

        </script>
        <?php
    }
}

function generateUserPDF($userid,$type,$data=array()){
    //use setasignFpdiFpdi;
    /*if($type == '' || $type == null) {
        return false;
    }*/

    $blogID = get_current_blog_id();
    
    $user = get_userdata($userid);
    switch_to_blog(1);
    $updir = wp_get_upload_dir();
    update_user_meta( $userid, 'new_statement', $blogID );
    restore_current_blog();
    /*if($type == 'sophisticated'){
        $cert = $updir['basedir'].'/statements/STATEMENT-FOR-SELF-CERTIFIED-SOPHISTICATED-INDIVIDUAL.pdf';
    }else{
        $cert = $updir['basedir'].'/statements/STATEMENT-FOR-SELF-CERTIFIED-HIGH-NET-WORTH-INDIVIDUAL.pdf';
    }*/
    $cert = $updir['basedir'].'/statements/CERTIFIED-STATEMENT.pdf';
	
    $name = ($data['first_name'] != '') ? $data['first_name'].' '.$data['last_name'] : $user->data->user_login;
    $pdf = new Fpdi();
    // add a page
    $pdf->AddPage();
    // set the source file
    $pagecount = $pdf->setSourceFile($cert);
    $tppl = $pdf->importPage(1);
    $pdf->useTemplate($tppl, 1, 1, null, null, true);
    $pdf->SetFont('Helvetica','',10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
    $pdf->SetTextColor(0,0,0); // RGB
    /*if($type == 'sophisticated'){
        $pdf->SetXY(37, 245); // X start, Y start in mm
        $pdf->Write(0, $name);

        $pdf->SetXY(25, 274); // X start, Y start in mm
        $pdf->Write(0, date('d/m/Y H:i:s'));
    }else{
        $pdf->SetXY(42, 210); // X start, Y start in mm
        $pdf->Write(0, $name);

        $pdf->SetXY(30, 242); // X start, Y start in mm
        $pdf->Write(0, date('d/m/Y H:i:s'));
    }*/
    $pdf->SetXY(45, 257); // X start, Y start in mm
    $pdf->Write(0, $name);

    $pdf->SetXY(35, 268); // X start, Y start in mm
    $pdf->Write(0, date('d/m/Y H:i:s'));
    $filename = $updir['basedir'].'/statements/statement_'.$userid.'_'.$blogID.'.pdf';
    
    ob_start();
    $pdf->Output($filename,'F');
    ob_end_clean();
}

function generateUserNdaPDF($userid, $data){
    $user = get_userdata($userid);
    // pdf library 
    switch_to_blog(1);
    $updir = wp_get_upload_dir();
    restore_current_blog();
    
    $cert = $updir['basedir'].'/NDA/NDA-unsigned.pdf';
    $name = ($data['first_name'] != '') ? $data['first_name'].' '.$data['last_name'] : $user->data->user_login; 
    
    $pdf = new Fpdi();
    // add a page
    $pdf->AddPage();
    // set the source file
    $pagecount = $pdf->setSourceFile($cert);
    $tppl = $pdf->importPage(1);
    $pdf->useTemplate($tppl, 1, 1, null, null, true);
    $pdf->SetFont('Helvetica','',10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
    $pdf->SetTextColor(0,0,0); // RGB 
    
    $pdf->AddPage();
    $tppl2 = $pdf->importPage(2);
    $pdf->useTemplate($tppl2, 1, 1, null, null, true);
    $pdf->SetFont('Helvetica','',10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
    $pdf->SetTextColor(0,0,0); // RGB 
    
    $pdf->SetXY(42, 161); // X start, Y start in mm
    $pdf->Write(0, $name);
    
    $pdf->SetXY(42, 166); // X start, Y start in mm
    $pdf->Write(0, date('d/m/Y')); 
    
    $pdf->SetXY(42, 171); // X start, Y start in mm
    $pdf->Write(0, date('H:i:s')); 
    
    $filename = $updir['basedir'].'/NDA/nda_'.$userid.'.pdf';
    ob_start();
    $pdf->Output($filename,'F');  
    ob_end_clean();
}

function c_login($sid = null, $c= null, $path = null) {
  if(isset($sid) && isset($c)) {
      global $wpdb;
      $c = esc_attr($_GET['c']);
      $q = "SELECT ID from ".$wpdb->base_prefix."users WHERE MD5(`user_login`) = '$c'";
      $res = $wpdb->get_row($q);
      if($res) {
        $uid = $res->ID;
        $user = get_userdata($uid);
        $user_id = $uid;
        wp_set_current_user($user_id, $user->data->user_login);
        wp_set_auth_cookie($user_id);
        $_SESSION['source_invest_site'] = $_GET['sid'];
        wp_redirect($path);
      }
  }
}
//add_action('init', 'c_login');
add_filter( 'admin_bar_menu', 'remove_admin_menu_bar_items' );
function remove_admin_menu_bar_items ($wp_toolbar) {
    global $user_ID;

    if ( in_array('custodian',wp_get_current_user()->roles) && !in_array('administrator',wp_get_current_user()->roles) ) {
        $wp_toolbar->remove_node( 'my-sites' );
        $wp_toolbar->remove_node( 'wp-logo' );
        $wp_toolbar->remove_node( 'new-content' );
        $wp_toolbar->remove_node( 'edit-profile' ); //remove "edit my profile" under "howdy"
        $wp_toolbar->remove_node( 'search' );  // remove the search element
    }
    return $wp_toolbar;
}

function remove_menu_pages() {

    global $user_ID;

    if ( in_array('custodian',wp_get_current_user()->roles) && !in_array('administrator',wp_get_current_user()->roles) ) {
       remove_menu_page('edit-comments.php'); // Comments
       remove_menu_page('wpcf7'); // Contact Form 7 Menu
       remove_menu_page('vc-welcome');
       remove_menu_page('vc-grid-item');
       remove_menu_page('tools.php');
       remove_menu_page('users.php');
    }

}
add_action( 'admin_init', 'remove_menu_pages' );
add_filter( 'auto_update_plugin', '__return_false' );

function hide_admin_bar(){
    if(in_array('custodian',wp_get_current_user()->roles)) {
        return false;
    }
    //return true;
}
add_filter( 'show_admin_bar', 'hide_admin_bar' );



/************************************/

function redirectToPasswordResetPage() {
        //
        if(isset($_GET['submitted']) && $_GET['submitted'] === 'yes') {
            $_SESSION['reset_password_attempt'] = (int) $_SESSION['reset_password_attempt'] + 1;
            /*echo "<pre>";
            print_r($_SESSION);
            exit;*/
            //wc_add_notice( __( "Reset email link has been sent on your email", 'gateway' ), 'success' );
            if(isset($_SESSION['reset_password_attempt']) && $_SESSION['reset_password_attempt'] >= 1 ) {
                unset($_SESSION['reset_password_attempt']);
                //wp_redirect(home_url('password-reset'));
                //exit;
            }
        //$_SESSION['reset_password_attempt']++;

        }
}
add_action( 'template_redirect', 'redirectToPasswordResetPage' );


function fetch_top_gainers_and_losers(){
	global $wpdb;
    $shareResult = $wpdb->get_results("SELECT *,(SELECT `company_name` FROM `pjcfn2_companies` WHERE companyc=`pjCfN2_share`.`share_code`) as company_name, (SELECT `london_name` FROM `pjcfn2_companies` WHERE companyc=`pjCfN2_share`.`share_code`) as london_name FROM `pjCfN2_share` ORDER BY `percentage` DESC LIMIT 5");

    $topGainersHtml = '<table summary="This data table contains latest risers BUKAC" id="risersResultsTable" class="table table-striped mobile-load"><thead><tr><th class="w_55"><b>Company</b></th><th class="w_15" style="text-align:center"><b>Code</b></th><th class="w_15" style="text-align:right"><b>Price</b></th><th class="w_15"><b>Chg</b></th><th class="w_15"><b>Time</b></th></tr></thead><tbody>';
    if(!empty($shareResult)):
        foreach($shareResult as $share):

            $topGainersHtml .= "<tr>
            <td>".$share->company_name."</td>
            <td>".($share->london_name!='' ? $share->london_name : $share->share_code)."</td>
            <td>".$share->price."</td>
            <td>".$share->percentage."%</td>
            <td>".date("H:i",$share->time)."</td>
            </tr>";
            // $topGainersHtml .= "<tr>
            // <td>".($share->share_code!='' ? $share->share_code : "----")."</td>
            // <td>GBX</td>
            // <td>".$share->price."</td>
            // <td>".$share->percentage."%</td>
            // <td>".date("H:i",$share->time)."</td>
            // </tr>";
        endforeach;
    endif;
    $topGainersHtml .= "</tbody></table>";

    $shareResult = $wpdb->get_results("SELECT *,(SELECT `company_name` FROM `pjcfn2_companies` WHERE companyc=`pjCfN2_share`.`share_code`) as company_name, (SELECT `london_name` FROM `pjcfn2_companies` WHERE companyc=`pjCfN2_share`.`share_code`) as london_name FROM `pjCfN2_share` ORDER BY `percentage` ASC LIMIT 5");
    $topLosersHtml = '<table summary="This data table contains latest risers BUKAC" id="risersResultsTable" class="table table-striped mobile-load"><thead><tr><th class="w_55"><b>Company</b></th><th class="w_15" style="text-align:center"><b>Code</b></th><th class="w_15" style="text-align:right"><b>Price</b></th><th class="w_15"><b>Chg</b></th><th class="w_15"><b>Time</b></th></tr></thead><tbody>';
    if(!empty($shareResult)):
        foreach($shareResult as $share):

            $topLosersHtml .= "<tr>
            <td>".$share->company_name."</td>
            <td>".($share->london_name!='' ? $share->london_name : $share->share_code)."</td>
            <td>".$share->price."</td>
            <td>".$share->percentage."%</td>
            <td>".date("H:i",$share->time)."</td>
            </tr>";;
        endforeach;
    endif;
    $topLosersHtml .= "</tbody></table>";
    echo json_encode(['top_gainers' => $topGainersHtml,'top_losers' => $topLosersHtml]);exit;
}
// TOP GAINERS AND LOOSERS 
// OLD JQUERY 
// function fetch_top_gainers_and_losers(){
//     $topGainersHtml = get_option('top_gainers');
//     $topLosersHtml = get_option('top_loosers');
//     echo json_encode(['top_gainers' => $topGainersHtml,'top_losers' => $topLosersHtml]);exit;
// }

add_action('wp_ajax_fetch_top_gainers_and_losers', 'fetch_top_gainers_and_losers');
add_action('wp_ajax_nopriv_fetch_top_gainers_and_losers', 'fetch_top_gainers_and_losers');

// function for Stock gainer and looser in Home Page
add_shortcode( 'testcode', 'testshortfun' );
function wpb_gainloose() { 
    global $wpdb;
    $shareResult = $wpdb->get_results("SELECT *,(SELECT `company_name` FROM `pjcfn2_companies` WHERE companyc=`pjCfN2_share`.`share_code`) as company_name, (SELECT `london_name` FROM `pjcfn2_companies` WHERE companyc=`pjCfN2_share`.`share_code`) as london_name FROM `pjCfN2_share` ORDER BY `percentage` DESC LIMIT 5");

    //print_r($shareResult);
    //$topGainersHtml = '<table summary="This data table contains latest risers BUKAC" id="risersResultsTable" class="table table-striped mobile-load"><thead><tr><th class="w_55"><b>Code</b></th><th class="w_15" style="text-align:center"><b>Currency</b></th><th class="w_15" style="text-align:right"><b>Price</b></th><th class="w_15"><b>Chg</b></th><th class="w_15"><b>Time</b></th></tr></thead><tbody>';
    $topGainersHtml = '<table summary="This data table contains latest risers BUKAC" id="risersResultsTable" class="table table-striped mobile-load"><thead><tr><th class="w_55"><b>Company</b></th><th class="w_55"><b>Code</b></th><th class="w_15" style="text-align:right"><b>Price</b></th><th class="w_15"><b>Chg</b></th><th class="w_15"><b>Time</b></th></tr></thead><tbody>';
    if(!empty($shareResult)):
        foreach($shareResult as $share):

            $topGainersHtml .= "<tr>
            <td>".$share->company_name."</td>
            <td>".($share->london_name!='' ? $share->london_name : $share->share_code)."</td>
            <td>".$share->price."</td>
            <td>".$share->percentage."%</td>
            <td>".date("H:i",$share->time)."</td>
            </tr>";
        endforeach;
    endif;
    $topGainersHtml .= "</tbody></table>";
    $gainerlooser = '<div class="vc_row-fluid stockgainer"><div class="stockgainer wpb_column vc_column_container">
                        <h2 style="text-align: center;margin-bottom:22px;">
                            <label>Stock Gainers</label>
                        </h2>
                        <div class="tabledata" id="stockgainers_data">
                            '.$topGainersHtml.'
                        </div>
                    </div>';
    $shareResult = $wpdb->get_results("SELECT *,(SELECT `company_name` FROM `pjcfn2_companies` WHERE companyc=`pjCfN2_share`.`share_code`) as company_name, (SELECT `london_name` FROM `pjcfn2_companies` WHERE companyc=`pjCfN2_share`.`share_code`) as london_name FROM `pjCfN2_share` ORDER BY `percentage` ASC LIMIT 5");
    $topLosersHtml = '<table summary="This data table contains latest risers BUKAC" id="risersResultsTable" class="table table-striped mobile-load"><thead><tr><th class="w_55"><b>Company</b></th><th class="w_55"><b>Code</b></th><th class="w_15" style="text-align:right"><b>Price</b></th><th class="w_15"><b>Chg</b></th><th class="w_15"><b>Time</b></th></tr></thead><tbody>';
    if(!empty($shareResult)):
        foreach($shareResult as $share):

            $topLosersHtml .= "<tr>
            <td>".$share->company_name."</td>
            <td>".($share->london_name!='' ? $share->london_name : $share->share_code)."</td>
            <td style='color:#ad0c0c'>".$share->price."</td>
            <td style='color:#ad0c0c'>".$share->percentage."%</td>
            <td>".date("H:i",$share->time)."</td>
            </tr>";
        endforeach;
    endif;
    $topLosersHtml .= "</tbody></table>";
    $gainerlooser .='<div class="stockloser wpb_column vc_column_container stockgainer">
                        <h2 style="text-align: center;margin-bottom:22px;">
                            <label>Stock Losers</label>
                        </h2>
                        <div class="tabledata" id="stockloosers_data">
                            '.$topLosersHtml.'
                        </div>
                        </div>
                    </div>';
                    
    return $gainerlooser;
}
/* OLD CODE 05-12-2021
function wpb_gainloose() { 
    $topGainersHtml = get_option('top_gainers');
    $topLosersHtml = get_option('top_loosers');
    
    $gainerlooser .= '<div class="vc_row-fluid stockgainer"><div class="stockgainer wpb_column vc_column_container">
                        <h2 style="text-align: center;margin-bottom:22px;">
                            <label>Stock Gainers</label>
                        </h2>
                        <div class="tabledata" id="stockgainers_data">
                            '.$topGainersHtml.'
                        </div>
                    </div>';
    $gainerlooser .='<div class="stockloser wpb_column vc_column_container stockgainer">
                        <h2 style="text-align: center;margin-bottom:22px;">
                            <label>Stock Losers</label>
                        </h2>
                        <div class="tabledata" id="stockloosers_data">
                            '.$topLosersHtml.'
                        </div>
                        </div>
                    </div>';
                return $gainerlooser;
}
*/
add_shortcode('gainlosshtml', 'wpb_gainloose'); 

add_shortcode( 'insert_share_data', 'insertsharedata' );

function insertsharedata(){
        global $wpdb;
        // update gainers loosers options 
        $c = curl_init("https://cdn.cboe.com/api/global/european_indices/constituent_quotes/BUKAC.json");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($c);
        if (curl_error($c))
            die(curl_error($c));

        // Get the status code
        $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);
        $arr = json_decode($html);

     
        if(!empty($arr)):
            $wpdb->query('TRUNCATE TABLE `pjCfN2_share`');
                foreach($arr->data as $arri):
                    //print_r($arri);
                    $stock = array(
                        "share_code"=>$arri->symbol,
                        "company"   =>"",
                        "price"     =>number_format($arri->current_price, 2, '.', ' '),
                        "percentage"=>number_format($arri->price_change_percent, 2, '.', ' '),
                        "time"      =>strtotime($arri->last_trade_time),

                    );
                    $wpdb->insert('pjCfN2_share', $stock);
                endforeach;
        endif; 
}

/* OLD FUNCTION 
function insert_share_data(){   
    // update gainers loosers options 
    $c = curl_init("https://www.cboe.com/europe/indices/dashboard/BUKAC/#summary");
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $html = curl_exec($c);
    if (curl_error($c))
        die(curl_error($c));

    // Get the status code
    $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
    curl_close($c);
echo $html;exit;
    //$gainerlooser = $html;

    if($status == 200) {               
        $first_step = explode( '<table summary="This data table contains latest risers BUKAC" id="risersResultsTable" class="table table-striped">' , $html );
        $second_step = explode("</table>" , $first_step[1] );
        //$gainerlooser .= $first_step[0];
        //$gainerlooser .= $first_step[1];
        $gainer = '<table summary="This data table contains latest risers BUKAC" id="risersResultsTable" class="table table-striped mobile-load">';
        $gainer .= $second_step[0];
        $gainer .= '</table>';
        
        $first_step = explode( '<table summary="This data table contains fallers data BUKAC" id="fallersResultsTable" class="table table-striped">' , $html );
        $second_step = explode("</table>" , $first_step[1] );
        $looser = '<table summary="This data table contains fallers data BUKAC" id="fallersResultsTable" class="table table-striped mobile-load">';
        $looser .= $second_step[0];
        $looser .= '</table>';
        
        update_option('top_gainers',$gainer);
        update_option('top_loosers',$looser);
    }
}*/

// add_action('wp_ajax_insert_share_data', 'insert_share_data');
// add_action('wp_ajax_nopriv_insert_share_data', 'insert_share_data');

function sharemarket_data_callback() 
{ ?>


    <script type="application/javascript">
        jQuery( document ).ready(function() {
            getTopGainersAndLosers();
            setInterval(getTopGainersAndLosers, 60000); //300000 MS == 5 minutes
                function getTopGainersAndLosers() {
                    jQuery.ajax({
                        type: "post",
                        url: '<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php',
                        data: { 'action': 'fetch_top_gainers_and_losers'},
                        success: function(response){
                            var resObj = JSON.parse(response);
                            jQuery("#stockgainers_data").html(resObj.top_gainers);
                            jQuery("#stockloosers_data").html(resObj.top_losers);
                            //jQuery(".wpl_data").html(response);
                        }
                    });
                }
        });
    </script> 
<?php }

add_action( 'wp_footer', 'sharemarket_data_callback' );

function cc_mime_types($mimes) {

     

    // New allowed mime types.

  $mimes['pdf'] = 'application/pdf';

    return $mimes;

}

add_filter( 'upload_mimes', 'cc_mime_types' );

function generateUserApplicationPDF($userid, $data){ // for 74 site id only 
    $user = get_userdata($userid);
    // pdf library 
    switch_to_blog(1);
    $updir = wp_get_upload_dir();
    restore_current_blog();
    
    $cert = $updir['basedir'].'/pp09spv_applications/application-unsigned.pdf';
    $name = ($data['first_name'] != '') ? $data['first_name'].' '.$data['last_name'] : $user->data->user_login; 
    
    $invID = $data['inv_id'];
    $amount = isset($data['inv_amount']) ? $data['inv_amount'] : 0;
    $pdf = new Fpdi();
    // add a page
    $pdf->AddPage();
    // set the source file
    $pagecount = $pdf->setSourceFile($cert);
    $tppl = $pdf->importPage(1);
    $pdf->useTemplate($tppl, 1, 1, null, null, true);
    $pdf->SetFont('Helvetica','',10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
    $pdf->SetTextColor(0,0,0); // RGB 
    
    $pdf->AddPage();
    $tppl2 = $pdf->importPage(2);
    $pdf->useTemplate($tppl2, 1, 1, null, null, true);
    $pdf->SetFont('Helvetica','',10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
    $pdf->SetTextColor(0,0,0); // RGB 
    
    $pdf->SetXY(134, 211); // X start, Y start in mm
    $pdf->Write(0, $amount);
    
    $pdf->SetXY(133, 229); // X start, Y start in mm
    $pdf->Write(0, $name);
    
    $pdf->SetXY(135, 247); // X start, Y start in mm
    $pdf->Write(0, date('d/m/Y'));
    
    $pdf->SetXY(155, 247); // X start, Y start in mm
    $pdf->Write(0, date('H:i:s')); 
    
    $filename = $updir['basedir'].'/pp09spv_applications/application_'.$userid.'_'.$invID.'.pdf';
    ob_start();
    $pdf->Output($filename,'F');  
    ob_end_clean();
}
 
// shortcode code ends here

add_filter( 'vc_grid_item_shortcodes', 'company_news_add_grid_shortcodes' );
function company_news_add_grid_shortcodes( $shortcodes ) {
 $shortcodes['vc_company_news'] = array(
 'name' => __( 'Company', 'my-text-domain' ),
 'base' => 'vc_company_news',
 'category' => __( 'Content', 'my-text-domain' ),
 'description' => __( 'Just outputs Company name and link', 'my-text-domain' ),
 'post_type' => Vc_Grid_Item_Editor::postType(),
  );
 return $shortcodes;
}
 
add_shortcode( 'vc_company_news', 'vc_company_news_render' );
function vc_company_news_render() {
    /*$pid = "{{post_data:ID}}";
    echo $pid;
    $pid = preg_match_all('/[0-9]+/', $pid, $matches);;
    var_dump($pid);
    $pid = intval($pid);
    var_dump($pid);
    $p = get_post($pid);*/
    
    //$company = get_post($p->post_parent);
 //return '<a href="'.$pid.'">'.get_the_title($p->post_parent).'</a>';
}


require_once (get_template_directory().'/custom-hook-functions.php');

// This will occur when the comment is posted
function plc_comment_post( $incoming_comment ) {
 
// convert everything in a comment to display literally
$incoming_comment['comment_content'] = htmlspecialchars($incoming_comment['comment_content']);
 
// the one exception is single quotes, which cannot be #039; because WordPress marks it as spam
$incoming_comment['comment_content'] = str_replace( "'", '&amp;amp;amp;amp;amp;amp;amp;apos;', $incoming_comment['comment_content'] );
 
return( $incoming_comment );
}
 
// This will occur before a comment is displayed
function plc_comment_display( $comment_to_display ) {
 
// Put the single quotes back in
$comment_to_display = str_replace( '&amp;amp;amp;amp;amp;amp;amp;apos;', "'", $comment_to_display );
 
return $comment_to_display;
}
 
add_filter( 'preprocess_comment', 'plc_comment_post', '', 1 );
add_filter( 'comment_text', 'plc_comment_display', '', 1 );
add_filter( 'comment_text_rss', 'plc_comment_display', '', 1 );
add_filter( 'comment_excerpt', 'plc_comment_display', '', 1 );
// Stops WordPress from trying to automatically make hyperlinks on text:
remove_filter( 'comment_text', 'make_clickable', 9 );


add_shortcode( 'testcode', 'testshortfun' );
function testshortfun() {
    global $wpdb;
        // update gainers loosers options 
        $c = curl_init("https://cdn.cboe.com/api/global/european_indices/constituent_quotes/BUKAC.json");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($c);
        if (curl_error($c))
            die(curl_error($c));

        // Get the status code
        $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);
        $arr = json_decode($html);
        if(!empty($arr)):
            $wpdb->query('TRUNCATE TABLE `pjCfN2_share`');
                foreach($arr->data as $arri):
                    //print_r($arri);
                    $stock = array(
                        "share_code"=>$arri->symbol,
                        "company"   =>"",
                        "price"     =>number_format($arri->price_change, 2, '.', ' '),
                        "percentage"=>number_format($arri->price_change_percent, 2, '.', ' '),
                        "time"      =>strtotime($arri->last_trade_time),

                    );
                    $wpdb->insert('pjCfN2_share', $stock);
                endforeach;
        endif;

}

//To enable the login process for the accessbond.co.uk
add_shortcode('login-accessbond','shortcode_function');
function shortcode_function() {

    if(isset($_POST['wp-submit'])):
        print_r($_POST);
        exit;
    endif;
    

    ?>
<form id="loginform" action="" method="post">
            
            <p class="login-username">
                <label for="user_login">Username or Email</label>
                <input type="text" name="log" id="user_login" class="input" value="" size="20">
            </p>
            <p class="login-password">
                <label for="user_pass">Password</label>
                <input type="password" name="pwd" id="user_pass" class="input" value="" size="20">
            </p>
            
            <p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever"> Remember Me</label></p>
            <p class="login-submit">
                <input type="submit" name="wp-submit" id="wppb-submit" class="button button-primary" value="Log In">
                <input type="hidden" name="redirect_to" value="https://accessbond.co.uk/sign-up/">
            </p>
            <input type="hidden" name="wppb_login" value="true">
            <input type="hidden" name="wppb_form_location" value="page">
            <input type="hidden" name="wppb_request_url" value="https://accessbond.co.uk/sign-up/">
            <input type="hidden" name="wppb_lostpassword_url" value="">
            <input type="hidden" name="wppb_redirect_priority" value="">
            <input type="hidden" name="wppb_referer_url" value="https://accessbond.co.uk/">
            <input type="hidden" id="CSRFToken-wppb" name="CSRFToken-wppb" value="71c00925f3"><input type="hidden" name="_wp_http_referer" value="/sign-up/">
            <input type="hidden" name="wppb_redirect_check" value="true">
            
        </form>
    <?php
}


function my_project_updated_send_email( $post_id, $post, $is_updated ) {
  if ( get_post_type( $post_id ) == 'news' && !empty($_POST) && $_POST['company_selected']>0) {
    if($is_updated){
        global $wpdb;
            $table_name = $wpdb->prefix.'posts';
            $data_update = array('post_parent' => $_POST['company_selected']);
            $data_where = array('ID' => $post_id);

            $wpdb->update($table_name , $data_update, $data_where);
            update_post_meta( $post_id, 'title', $post->post_title );
            update_post_meta( $post_id, 'details', $post->post_content);
            $d = get_post_meta($post_id,'date',true);
            if($d==""):
                update_post_meta( $post_id, 'date', date('d/m/Y H:i'));
            endif;
            
    }
       
  }
}
 add_action( 'save_post', 'my_project_updated_send_email',10,3 );



// function save_award_category( $post_id, $post, $update, $post_before ) {
// echo "M I HERE";
// exit;

//     if ( get_post_type( $post_id ) == 'news' ) {

//             print_r($post);
//             exit;

      

//     }
            
// }

// add_action( 'wp_after_insert_post', 'save_award_category', 10, 4 );