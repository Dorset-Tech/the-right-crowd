<?php 
    $output = ''; 
    $sub_img = array();
    global $post;
    global $themeum_options;

    if(!function_exists('thmtheme_call_sub_header')){
        function thmtheme_call_sub_header(){
            global $themeum_options;
            if(isset($themeum_options['blog-banner']['url'])){
                $output = 'style="background-image:url('.esc_url($themeum_options['blog-banner']['url']).');background-size: cover;background-position: 50% 50%;padding: 80px 0;"';
                return $output;
            }else{
                 $output = 'style="background-color:'.esc_attr($themeum_options['blog-subtitle-bg-color']).';padding: 80px 0;"';
                 return $output;
            }
        }
    }
    
    if( isset($post->post_name) ){
        if(!empty($post->ID)){ 
            $image_attached = esc_attr(get_post_meta( $post->ID , 'thm_subtitle_images', true ));
            if(!empty($image_attached)){
                $sub_img = wp_get_attachment_image_src( $image_attached , 'blog-full'); 
                $output = 'style="background-image:url('.esc_url($sub_img[0]).');background-size: cover;background-position: 50% 50%;padding: 80px 0;"';
                if(empty($sub_img[0])){
                    $output = 'style="background-color:'.esc_attr(get_post_meta(get_the_ID(),"thm_subtitle_color", true)).';padding: 80px 0;"';
                    if(get_post_meta("thm_subtitle_color") == ''){
                        $output = thmtheme_call_sub_header();
                    }
                }
            }else{
				if(get_post_meta("thm_subtitle_color") != "" ){
					$output = 'style="background-color:'.esc_attr(get_post_meta(get_the_ID(),"thm_subtitle_color", true)).';padding: 80px 0;"';
				}else{
					$output = thmtheme_call_sub_header();
				}
            }
        }else{
            $output = thmtheme_call_sub_header();
        }
    }else{
        $output = thmtheme_call_sub_header();
    }
?>
<?php if (!is_front_page()) { ?>

<div class="sub-title" <?php echo $output;?>>
	<div class="left-section"></div>
	<div class="mask"></div>
	<div class="right-section"></div>
    <div class="container">
        <div class="sub-title-inner">
            <h1><?php 
                if(function_exists('is_shop')){
                    if( is_shop() || is_singular('product') ){
                        echo $themeum_options['shop-slug'];
                    }else{
                        the_title();
                    }
                }else{
                    the_title();
                }
            ?>
            </h1>
            <?php themeum_breadcrumbs(); ?>
        </div>
    </div>
</div>

<?php } ?>
