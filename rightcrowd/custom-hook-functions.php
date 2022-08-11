<?php

/* remove news post comment support */
function my_prefix_comments_open( $open, $post_id ) {
    $post_type = get_post_type( $post_id );
    // allow comments for built-in "post" post type
    if ( $post_type == 'news' ) {
        return true;
    }
	
    // disable comments for any other post types
    return true;
}
add_filter( 'comments_open', 'my_prefix_comments_open', 10 , 2 );

/**
 * Add custom taxonomies for new post_type
 * Additional custom taxonomies can be defined here
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 */
add_action( 'init', 'add_news_taxonomy', 0 );
function add_news_taxonomy() {
  register_taxonomy('news_category', 'news', array(
    'hierarchical' => true,
    'labels' => array(
      'name' => _x( 'News categories', 'taxonomy general name' ),
      'singular_name' => _x( 'News Category', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search News categories' ),
      'all_items' => __( 'All News categories' ),
      'parent_item' => __( 'Parent News Category' ),
      'parent_item_colon' => __( 'Parent News Category:' ),
      'edit_item' => __( 'Edit News Category' ),
      'update_item' => __( 'Update News Category' ),
      'add_new_item' => __( 'Add New News Category' ),
      'new_item_name' => __( 'New News Category Name' ),
      'menu_name' => __( 'News categories' ),
    ),
    // Control the slugs used for this taxonomy
    'rewrite' => array(
      'slug'              => 'news-categories',
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'with_front'        => true,
      'hierarchical'      => true
    ),
  ));
}

/**
 * update checked news category with post meta and load custom bakery page builder
 */
add_action( 'save_post', 'set_post_default_category', 10,3 );
function set_post_default_category( $post_id, $post, $update ) {
  
  // Only set for post_type = news!
  if ( 'news' !== $post->post_type ) {
      return;
  }
  
  $tax_data = $_POST['tax_input']['news_category'];
  if ( empty($tax_data) ) {
    return;
  }
  
  $term_html = '';
  for ($i=0; $i < sizeof($tax_data); $i++) { 
    if ( (int)$tax_data[$i] !== 0 ) {
      $term_html .= '<a href='.get_term_link( (int)$tax_data[$i] ).' >'.get_term( (int)$tax_data[$i] )->name.'</a>, ';
    }
  }
  
  if ( !empty($term_html) ) {
    update_post_meta($post_id, 'current_news_categories', rtrim($term_html, ", "));
  }
  
}

add_filter('excerpt_length', 'set_excerpt_length');
function set_excerpt_length($length) {
  global $post;
  if ($post->post_type == 'news') {
    $length = 15;
  }
  return $length;
}

/* 
 * shortcode news board siderbar recent posts
 */
add_shortcode('trc_recent_posts', 'trc_recent_posts');
function trc_recent_posts(){

  $args = array(
    'posts_per_page'      => 5,
    'no_found_rows'       => true,
    'post_status'         => 'publish',
    'ignore_sticky_posts' => true,
  );

  $r = new WP_Query( $args );

  if ( ! $r->have_posts() ) {
    return;
  } ob_start(); ?>

  <div class="trc-r-posts-sidebar vc_column-inner">
    
  <ul class="trc-r-posts">
  <?php foreach ( $r->posts as $recent_post ) : ?>
    <?php
    $post_thum    = get_the_post_thumbnail_url( $recent_post->ID, 'post-thumbnail' );
    $post_thum    = !empty( $post_thum) ? $post_thum : home_url().'/wp-content/plugins/js_composer/assets/vc/vc_gitem_image.png';
    $post_title   = get_the_title( $recent_post->ID );
    $title        = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
    $aria_current = '';

    if ( get_queried_object_id() === $recent_post->ID ) {
      $aria_current = ' aria-current="page"';
    }
    ?>
    <li>
      <a href="<?php the_permalink( $recent_post->ID ); ?>"<?php echo $aria_current; ?>>
        
        <div class="vc_clearfix vc_col-sm-4">
          <div class="post-tumb">
            <img src='<?php echo $post_thum; ?>' width="100%" >
          </div>
        </div>

        <div class="vc_clearfix vc_col-sm-8">
          <div class="post-info">
            <span class="post-title"><h4><?php echo $title; ?></h4></span>
            <span class="post-date"><?php echo get_the_date( '', $recent_post->ID ); ?></span>
          </div>
        </div>
        </a>
    </li>
  <?php endforeach; ?>
  </ul>
  </div>

<?php 
  $output_string = ob_get_contents();
  ob_end_clean();
  return $output_string;
  wp_reset_postdata();
}

add_filter('admin_init', 'my_general_riskwarning');

function my_general_riskwarning(){
  register_setting('general', 'riskwarning', 'esc_attr');
  add_settings_field('riskwarning', '<label for="riskwarning">'.__('Risk Warning' , 'riskwarning' ).'</label>' , 'my_general_settings_riskwarning', 'general');
}
//Risk Warning Textarea
function my_general_settings_riskwarning()
{
    $value = get_option( 'riskwarning');
    $settings = array(
        'teeny' => true,
        'textarea_rows' => 5,
        'tabindex' => 0,
        'media_buttons' => false,
        'quicktags'=>false
    );
    wp_editor( html_entity_decode($value), 'riskwarning', $settings);
         
}