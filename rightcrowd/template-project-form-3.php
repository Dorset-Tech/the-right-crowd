<?php
/*
 * Template Name: Page Project Form 3
 */
get_header(); ?>

<div id="welcome-msg" class="modal fade">
    <div class="modal-dialog modal-md">
         <div class="modal-content">
             <div class="modal-header">
                 <i class="fa fa-close close" data-dismiss="modal" id="form-submit-close"></i>
             </div>
             <div class="modal-body text-center">
                 <h3><?php _e('Thank you','themeum') ?></h3>
                 <p><?php _e('Your Business is submitted for review.','themeum') ?></p>
             </div>
         </div>
    </div> 
</div>

<div id="error-msg" class="modal fade">
    <div class="modal-dialog modal-md">
         <div class="modal-content">
             <div class="modal-header">
                 <i class="fa fa-close close" data-dismiss="modal"></i>
             </div>
             <div class="modal-body text-center">
                 <h3><?php _e('Error','themeum') ?></h3>
                 <p><?php _e('Please fill Business Name.','themeum') ?></p>
             </div>
         </div>
    </div> 
</div>

<div id="desc-error-msg" class="modal fade">
    <div class="modal-dialog modal-md">
         <div class="modal-content">
             <div class="modal-header">
                 <i class="fa fa-close close" data-dismiss="modal"></i>
             </div>
             <div class="modal-body text-center">
                 <h3><?php _e('Error','themeum') ?></h3>
                 <p>
                 	<?php _e('Please write atleast 500 characters in Business Overview.','themeum') ?><br/>
                 	<span><span>
                 </p>
             </div>
         </div>
    </div> 
</div>

<script id="shares_fields" type="text/template">
	<div class='sharerow shares-fields-wrapper' id='{{order_number}}'>
	   <div>
	   	<span class='remove-shares' data-order='{{order_number}}' style='cursor: pointer;' >Remove</span>
	   </div>
	   <div class='shr1 shares-field'>
			First Name of Shareholder
			<input type="text" name="shares_fname[{{order_number}}]" id='shares_fname{{order_number}}' />
		</div>
		<div class='shr1 shares-field'>
			Surname of Shareholder
			<input type="text" name="shares_lname[{{order_number}}]" id='shares_lname{{order_number}}' />
		</div>
		<div class='shr2 shares-field'>
			Email of Shareholder
			<input type="email" name="shares_email[{{order_number}}]" id='shares_email{{order_number}}' />
		</div>
		<div class='shr3 shares-field'>
			Number of Shares
			<input type="text" name="shares_numbers[{{order_number}}]" id='shares_numbers{{order_number}}' />
		</div>
		<div class='shr3 shares-field form-group'>
			Purchase Price
			<input type="text" name="shares_purchase_price[{{order_number}}]" id='shares_purchase_price{{order_number}}'/>
		</div>
		<div class='shr4 shares-field form-group'>
			Date Purchased
			<input type="date" name="shares_purchase_date[{{order_number}}]" id='shares_purchase_date{{order_number}}'/>
		</div>
	
	</div>
</script>

<script id="bond_fields" type="text/template">
	<div class='sharerow shares-fields-wrapper' id='bond_{{order_number}}'>
	   <div>
	   	<span class='remove-bonds' data-order='{{order_number}}' style='cursor: pointer;' >Remove</span>
	   </div>
	   <div class='shr1 shares-field'>
			First Name of Shareholder
			<input type="text" name="bonds_fname[{{order_number}}]" id='bonds_fname{{order_number}}' />
		</div>
		<div class='shr1 shares-field'>
			Surname of Shareholder
			<input type="text" name="bonds_lname[{{order_number}}]" id='bonds_lname{{order_number}}' />
		</div>
		<div class='shr2 shares-field'>
			Email of Shareholder
			<input type="email" name="bonds_email[{{order_number}}]" id='bonds_email{{order_number}}' />
		</div>
		<div class='shr3 shares-field'>
			Coupon %
			<input type="text" name="bonds_coupon[{{order_number}}]" id='bonds_coupon{{order_number}}' />
		</div>
		<div class='shr3 shares-field'>
			Expires
			<input type="date" name="bonds_expires[{{order_number}}]" id='bonds_expires{{order_number}}' />
		</div>
		<div class='shr3 shares-field'>
			Number of Shares
			<input type="text" name="bonds_numbers[{{order_number}}]" id='bonds_numbers{{order_number}}' />
		</div>
		
		<div class='shr3 shares-field form-group'>
			Purchase Price
			<input type="text" name="bonds_purchase_price[{{order_number}}]" id='bonds_purchase_price{{order_number}}'/>
		</div>
		<div class='shr3 shares-field form-group'>
			Date Purchased
			<input type="date" name="bonds_purchase_date[{{order_number}}]" id='bonds_purchase_date{{order_number}}'/>
		</div>
		<div class='shr4 shares-field form-group'>
			Status
			<select name="bonds_status[{{order_number}}]" id='bonds_status{{order_number}}'>
				<option value="forsale">For Sale</option>
				<option value="notforsale">Not For Sale</option>
			</select>
		</div>
	
	</div>
</script>


<!-- Bank Statement File Field Template-->
<script id="bank_file_field" type="text/template">
								
                                <input type="text" name="bank-file[{{order_number}}]" class="form-control bank_file_url{{order_number}}" id="bank-file{{order_number}}">
                                <input type="hidden" name="bank-file-id[{{order_number}}]" class="form-control bank_file_id{{order_number}}" id="bank-file-id{{order_number}}">
                                <input type="button" id="bank-file-button" class="bank-file-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" data-team-order="{{order_number}}" />

</script>

<!-- Financial Reports File Field Template-->
<script id="financial_file_field" type="text/template">
								
                                <input type="text" name="financial-file[{{order_number}}]" class="form-control financial_file_url{{order_number}}" id="financial-file{{order_number}}">
                                <input type="hidden" name="financial-file-id[{{order_number}}]" class="form-control financial_file_id{{order_number}}" id="financial-file-id{{order_number}}">
                                <input type="button" id="financial-file-button" class="financial-file-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" data-team-order="{{order_number}}" />

</script>

<section id="main" class="clearfix">
   <?php //get_template_part('lib/sub-header')?>
   <div class="featuredimag" style="background:url('https://thesharehub.co.uk/wp-content/uploads/dashboard.jpg') no-repeat;">
            <div class="container">
                <h2>Add your business</h2>
            </div>
        </div>
        <!--<div class="subheader">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h4>Add your business</h4>
                    </div>
                </div>
            </div>        
        </div>-->
    <div id="content" class="site-content container" role="main">


        <?php while ( have_posts() ): the_post(); ?>
        

        <!-- start project form -->
        <section id="project-form">
            <div class="container">
                <div class="row">
                <?php if( get_current_user_id()!= 0 ): ?>
                               
                    <div class="clearfix"></div>
                    <!-- form progressbar -->
                    <div id="progressbar" class="col-sm-12">
                       <!--  step 01 -->
                        <div class="step-01 active wow fadeIn">
                            <p class="step-name text-center pull-left">
                                <!-- <i class="fa fa-edit"></i> -->
                                <?php _e('Business Info','themeum'); ?>
                            </p>
                            <ul class="list-unstyled list-inline">
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
						<div class="step-01 wow fadeIn" data-wow-delay=".1s">
                            <p class="step-name text-center pull-left">
                                <!-- <i class="fa fa-file-text-o"></i> -->
                              <?php _e('Shares','themeum'); ?>
                            </p>
                            <ul class="list-unstyled list-inline">
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                        <div class="step-01 wow fadeIn latone" data-wow-delay=".2s">
                            <p class="step-name text-center pull-left">
                                <!-- <i class="fa fa-user"></i> -->
                                <?php _e('Due Diligence','themeum'); ?>
                            </p>
                            <ul class="list-unstyled list-inline">
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
						<div class="final-step wow fadeIn" data-wow-delay=".4s">
                            <p class="step-name text-center">
                                <!-- <i class="fa fa-check"></i> -->
                                <?php _e('Publish','themeum'); ?>
                            </p>
                        </div>
                    </div> <!-- end progressbar -->

                    <!-- input form -->
                    <div class="col-sm-12 input-form">
                       
								<?php if((isset($_GET['action']) && $_GET['action'] == 'edit') && (isset($_GET['postid']) && $_GET['postid'] != 0) ) { 
									$postID = $_GET["postid"];
									$project = get_post($postID);
									if($project->post_author == get_current_user_id()) {
										$allMetas = get_post_meta($postID);
										/*echo "<pre>";
										print_r($allMetas);
										echo "</pre>";*/
								?>	
				                  <form name="project-submit-form" action="<?php echo esc_url(admin_url('admin-ajax.php')) ?>" method="post" class="wow fadeIn" id="project-submit-form-3" enctype="multipart/form-data">
				                  
				                  <div class="project-submit-form form-show wow fadeIn" data-wow-delay=".2s">
											 <div class="col-sm-8">
				                      <div class="form-group has-feedback">
				                          <label for="project-title"><?php _e('Full Business Name','themeum') ?></label>
				                          <input type="hidden" name="new-project" id="new-project" value="new">
				                          <input type="hidden" name="action" value="new_main_project_add">
				                          <input type="hidden" name="url_red" id="redirect_url_add" value="<?php echo esc_url(site_url()); ?>">
				                          <input type="text" name="project-title" class="form-control" placeholder="Full Business Name" id="project-title" value="<?php echo $project->post_title ?>" />
				                          <span class="glyphicon glyphicon-ok form-control-feedback title-color"></span>
				                      </div>

				                      <div class="form-group has-feedback">
				                          <label for="location"><?php _e('Location','themeum') ?></label>
				                          <input type="text" name="location" class="form-control" id="location" value="<?php echo $allMetas['thm_location'][0] ?>">
				                          <span class="glyphicon glyphicon-ok form-control-feedback location-color"></span>
				                      </div>
                                      
                                        <!----------->
                                         <div class="form-group has-feedback">
                                            <label for="email"><?php _e('Email','themeum') ?></label>
                                            <input type="email" name="email" class="form-control" id="email" value="<?php echo @$allMetas['thm_email'][0] ?>">
                                            <span class="glyphicon glyphicon-ok form-control-feedback email-color"></span>
                                        </div>
                                        
                                        <div class="form-group has-feedback">
                                            <label for="telephone"><?php _e('Telephone','themeum') ?></label>
                                            <input type="text" name="telephone" class="form-control" id="telephone" value="<?php echo @$allMetas['thm_telephone'][0] ?>">
                                            <span class="glyphicon glyphicon-ok form-control-feedback telephone-color"></span>
                                        </div>
                                        <!----------->
				                      
				                      <div class="form-group has-feedback">
				                          <label for="coregno"><?php _e('Company Reg Number','themeum') ?></label>
				                          <input type="text" name="coregno" class="form-control" id="coregno" value="<?php echo $allMetas['thm_coregno'][0] ?>">
				                          <span class="glyphicon glyphicon-ok form-control-feedback coregno-color"></span>
				                      </div>
									  
									  <div class="form-group has-feedback">
				                          <label for="isin"><?php _e('ISIN Number','themeum') ?></label>
				                          <input type="text" name="isin" class="form-control" id="isin" value="<?php echo $allMetas['thm_isin'][0] ?>">
				                          <span class="glyphicon glyphicon-ok form-control-feedback isin-color"></span>
				                      </div>
				                      
										<div class="form-group has-feedback">
				                          <label for="cowebsite"><?php _e('Company URL','themeum') ?></label>
				                          <input type="text" name="cowebsite" class="form-control" id="cowebsite" value="<?php echo $allMetas['thm_cowebsite'][0] ?>">
				                          <span class="glyphicon glyphicon-ok form-control-feedback cowebsite-color"></span>
				                      </div>
				                      
									  <div class="form-group has-feedback">
				                          <label for="project-video"><?php _e('Video URL','themeum') ?></label>
				                          <input type="text" name="video-url" class="form-control" placeholder="YouTube or Vimeo URL" id="project-video" value="<?php echo $allMetas['thm_video_url'][0] ?>">
				                          <span class="glyphicon glyphicon-ok form-control-feedback video-color"></span>
				                      </div>
									  
				                      <div class="form-group has-feedback form-upload-image">
				                          <label for="project-image"><?php _e('Image/Logo','themeum') ?></label>
				                          <input type="text" name="project-image" class="form-control upload_image_url" id="project-image">
				                          <input type="hidden" name="project-image-id" class="form-control upload_image_id" id="project-image">
				                          <input type="button" id="cc-image-upload-file-button" class="custom-upload image-upload" value="Upload Image" data-url="<?php echo esc_url(get_site_url()); ?>" />
				                      </div>

				                      <div class="form-group has-feedback form-upload-image">
				                          <label for="banner-image"><?php _e('Banner Image','themeum') ?></label>
				                          <input type="text" name="banner-image" class="form-control banner_image_url" id="banner-image">
				                          <input type="hidden" name="banner-image-id" class="form-control banner_image_id" id="banner-image">
				                          <input type="button" id="cc-image-upload-file-button" class="banner-upload image-upload" value="Upload Image" data-url="<?php echo esc_url(get_site_url()); ?>" />
				                      </div>
											<?php 
											$cat_terms = wp_get_object_terms($postID, 'project_category', array());
											?>
				                      <div class="form-group has-feedback">
				                          <label for="project-category"><?php _e('Business Sector (Primary)','themeum') ?></label>
				                          <select name="project-category" id="project-category">
				                              <?php 
				                              $all_cat = get_terms('project_category', array(
				                                  'hide_empty' => false,
				                              )); 
				                              foreach ($all_cat as $value) {
				                                  echo '<option value="'.$value->slug.'" '.($cat_terms[0]->slug == $value->slug ? "selected" : "") .' >'.$value->name.'</option>';
				                              }
				                              ?>
				                          </select>
				                      </div>
				                      <div class="form-group has-feedback">
				                          <label for="offering-structure"><?php _e('Offering Structure','themeum') ?></label>
				                          <select name="offering-structure" id="offering-structure">
											  <option value="none" <?php echo $allMetas['thm_offering'][0] == 'none' ? 'selected' : ''  ?> >None</option>
				                              <option value="units" <?php echo $allMetas['thm_offering'][0] == 'units' ? 'selected' : ''  ?> >Units</option>
											  <option value="ordinary" <?php echo $allMetas['thm_offering'][0] == 'ordinary' ? 'selected' : ''  ?> >Ordinary</option>
				                              <option value="preference" <?php echo $allMetas['thm_offering'][0] == 'preference' ? 'selected' : ''  ?> >Preference</option>
				                              <option value="bond" <?php echo $allMetas['thm_offering'][0] == 'bond' ? 'selected' : ''  ?>>Bond</option>
				                              <option value="both" <?php echo $allMetas['thm_offering'][0] == 'both' ? 'selected' : ''  ?>>Bonds and Shares</option>
				                          </select>
				                      </div>
				                      <div class="form-group">
				                          <label><?php _e('Business Overview','themeum') ?></label>
				                          <div>
				                              <?php
				                              $editor_id = 'description';
				                              $content = $project->post_content;
				                              //wp_editor( $content, $editor_id, array( 'media_buttons' => false ) );
				                              ?>
											  <textarea name="description" id="description" maxlength="500" minlength="300"><?php echo $content ?></textarea>
				                              <!--<div id="the-count">
												<span id="currentNum"></span>
												<span id="charNum">/ 300</span>
											  </div>-->
				                          </div>
				                      </div>
				                      <div class="form-group custom_check_style"> 
		 			                        <label class="preemption">
												<input type='checkbox' name="pre_emption" value="1" <?php echo $allMetas['thm_pre_emption'][0] ? 'checked' : '' ?> /> 
												<?php _e('Requires pre-emption rights','themeum') ?>
											</label>
								          </div>
								          
								          </div>
								          <!-- startup sample -->
                    <div id="popular-ideas" class="col-sm-4">
                        <div class="ideas-item wow fadeIn">
                            <div class="image">
                                <figure>
                                    <img src="<?php echo  get_template_directory_uri(); ?>/images/preview.jpg" class="img-responsive image-view" alt="">
                                </figure>
                            </div> <!-- end image -->

                            <div class="clearfix"></div>

                            <div class="details">
                                <div class="country-name" id="auto-location"><?php _e('Location','themeum'); ?></div>
                                <h4 id="auto-title"><?php _e('Sample Title','themeum'); ?></h4>
                                <div class="info" id="auto-description">
                                    <p>
                                        <?php _e('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, repudiandae nisi in ea eaque ut dolorem obcaecati sit error quo facilis, officiis officia. Dignissimos fugiat, voluptatem, ipsa adipisci neque modi.','themeum'); ?>
                                    </p>
                                </div>
                            </div> 
                        </div>
                    </div> <!-- end startup sample -->
				                  </div>

				                  <div class="project-submit-form form-hide wow fadeIn indepth-shares" id="indepth-shares" data-wow-delay=".2s">
				                      <!-- step 2 -->
				                      <div class="form-group" id="shares_section" style="display:none;">
												  <h3>Shares</h3>	
						                    <div id="shares_fields_section" class="shareselbox">
		
						                    </div>
						                    <a style="display:none;" href="javascript:void(0);" id="add_shares_fields">Add Row</a>
						                    
						                    <div class="upload_shares">
						                    	
						                    		<div class="shares-field form-group">
																<label>Please upload your companies shareholder information in an Excel/CSV file - <a id="download-sample" href="<?php echo get_template_directory_uri() ?>/shareholders_upload_sample.csv">Download template here</a>.</label>
																<input type="file" name="shares_file" id="shares_file">
														</div>
														<p>OR</p>
														<div class="shares-field form-group">
						                    			<label><a href="javascript:void(0)" id="addmanually">Manually Add Shareholders</a></label>
						                    		</div>
						                    </div>
											</div>
											
											<!-- BONDS Section  -->
											<div class="form-group" id="bonds_section" style="display:none;">
												  <h3>Investor</h3>
						                    <div id="bonds_fields_section" class="shareselbox">
		
						                    </div>
						                    <a style="display:none;" href="javascript:void(0);" id="add_bonds_fields">Add Row</a>
						                    
						                    <div class="upload_bonds">
						                    	<div class="shares-field form-group">
													<label>Please upload your company investor information in an Excel/CSV file - <a id="download-bonds-sample" href="<?php echo get_template_directory_uri() ?>/shareholders_upload_bond_sample.csv">Download template here</a>.</label>
													<input type="file" name="bonds_file" id="bonds_file">
												</div>
												<p>OR</p>
												<div class="shares-field form-group">
						                    	<label><a href="javascript:void(0)" id="bonds_addmanually">Manually Add Investors</a></label>
						                    </div>
						                </div>
								</div>
				                  </div>

				                  <div class="project-submit-form form-hide wow fadeIn" data-wow-delay=".2s">
												<!-- Step 3 -->
											<div class="team-field form-group form-upload-image">
				                          <label for="team-image"><?php _e('Articles of Association','themeum') ?></label>
				                          <input type="text" name="article-inc" class="form-control article_inc_url" id="article-inc">
				                          <input type="hidden" name="article-inc-id" class="form-control article_inc_id" id="article-inc-id">
				                          <input type="button" id="article-inc-upload-file-button" class="article-inc-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" />
				                      </div>	
				                      <div class="team-field form-group form-upload-image">
				                          <label for="team-image"><?php _e('Certificate of Incorporation','themeum') ?></label>
				                          <input type="text" name="cert-inc" class="form-control cert_inc_url" id="cert-inc">
				                          <input type="hidden" name="cert-inc-id" class="form-control cert_inc_id" id="cert-inc-id">
				                          <input type="button" id="cert-inc-upload-file-button" class="cert-inc-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" />
				                      </div>
				                      
				                  </div>
											 <input id="form_step" type="hidden" name="form_step" value="<?php echo isset($allMetas['form_step']) ? $allMetas['form_step'][0] : 0 ?>" />	
											 <input id="postID" type="hidden" name="postID" value='<?php echo $_GET["postid"] ?>' />
											 <div class="buttons_wrapper">
				                      <button id="back-3" class="btn btn-primary pull-left" type="button"><?php _e('Back','themeum') ?></button>
									  <button id="next-3" class="btn btn-primary pull-right" type="button"><?php _e('Save and continue','themeum') ?></button>
				                      <button id="project-submit" class="btn btn-primary pull-right"><?php _e('Submit Business','themeum') ?></button>
									  <button id="project-submit-loader" class="btn btn-primary pull-right hidden" ><i class="fa fa-spinner fa-spin"></i> Loading, Please wait..</button>
				                      </div>

				                  </form>
										<?php }else{ ?>
											<div><p>You don't have permission to perform this action.</p></div>
										<?php } ?>
								<?php }else{ // end of edit form ?>
								
								
								<!-- new business form -->
								<form name="project-submit-form" action="<?php echo esc_url(admin_url('admin-ajax.php')) ?>" method="post" class="wow fadeIn" id="project-submit-form-3" enctype="multipart/form-data">
                        
                        <div class="project-submit-form form-show wow fadeIn" data-wow-delay=".2s">
									 <div class="col-sm-8">		 
                            <div class="form-group has-feedback">
                                <label for="project-title"><?php _e('Full Business Name','themeum') ?></label>
                                <input type="hidden" name="new-project" id="new-project" value="new">
                                <input type="hidden" name="action" value="new_main_project_add">
                                <input type="hidden" name="url_red" id="redirect_url_add" value="<?php echo esc_url(site_url()); ?>">
                                <input type="text" name="project-title" class="form-control" placeholder="Full Business Name" id="project-title">
                                <span class="glyphicon glyphicon-ok form-control-feedback title-color"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="location"><?php _e('Location','themeum') ?></label>
                                <input type="text" name="location" class="form-control" id="location" value="">
                                <span class="glyphicon glyphicon-ok form-control-feedback location-color"></span>
                            </div>
                            
                            <!----------->
                             <div class="form-group has-feedback">
                                <label for="email"><?php _e('Email','themeum') ?></label>
                                <input type="email" name="email" class="form-control" id="email" value="">
                                <span class="glyphicon glyphicon-ok form-control-feedback email-color"></span>
                            </div>
                            
                            <div class="form-group has-feedback">
                                <label for="telephone"><?php _e('Telephone','themeum') ?></label>
                                <input type="text" name="telephone" class="form-control" id="telephone" value="">
                                <span class="glyphicon glyphicon-ok form-control-feedback telephone-color"></span>
                            </div>
                            <!----------->
                            <div class="form-group has-feedback">
                                <label for="coregno"><?php _e('Company Reg Number','themeum') ?></label>
                                <input type="text" name="coregno" class="form-control" id="coregno">
                                <span class="glyphicon glyphicon-ok form-control-feedback coregno-color"></span>
                            </div>
                            
							  <div class="form-group has-feedback">
								  <label for="isin"><?php _e('ISIN Number','themeum') ?></label>
								  <input type="text" name="isin" class="form-control" id="isin">
								  <span class="glyphicon glyphicon-ok form-control-feedback isin-color"></span>
							  </div>
									 <div class="form-group has-feedback">
		                          <label for="cowebsite"><?php _e('Company URL','themeum') ?></label>
		                          <input type="text" name="cowebsite" class="form-control" id="cowebsite">
		                          <span class="glyphicon glyphicon-ok form-control-feedback cowebsite-color"></span>
		                      </div>

		                     <div class="form-group has-feedback">
                                <label for="project-video"><?php _e('Video URL','themeum') ?></label>
                                <input type="text" name="video-url" class="form-control" placeholder="YouTube or Vimeo URL" id="project-video">
                                <span class="glyphicon glyphicon-ok form-control-feedback video-color"></span>
                            </div>
							
                            <div class="form-group has-feedback form-upload-image">
                                <label for="project-image"><?php _e('Image/Logo','themeum') ?></label>
                                <input type="text" name="project-image" class="form-control upload_image_url" id="project-image">
                                <input type="hidden" name="project-image-id" class="form-control upload_image_id" id="project-image">
                                <input type="button" id="cc-image-upload-file-button" class="custom-upload image-upload" value="Upload Image" data-url="<?php echo esc_url(get_site_url()); ?>" />
                            </div>

                            <div class="form-group has-feedback form-upload-image">
                                <label for="banner-image"><?php _e('Banner Image','themeum') ?></label>
                                <input type="text" name="banner-image" class="form-control banner_image_url" id="banner-image">
                                <input type="hidden" name="banner-image-id" class="form-control banner_image_id" id="banner-image">
                                <input type="button" id="cc-image-upload-file-button" class="banner-upload image-upload" value="Upload Image" data-url="<?php echo esc_url(get_site_url()); ?>" />
                            </div>

                            <div class="form-group has-feedback">
                                <label for="project-category"><?php _e('Business Sector','themeum') ?></label>
                                <select name="project-category" id="project-category">
                                    <?php 
                                    $all_cat = get_terms('project_category', array(
                                        'hide_empty' => false,
                                    )); 
                                    foreach ($all_cat as $value) {
                                        echo '<option value="'.$value->slug.'">'.$value->name.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="offering-structure"><?php _e('Offering Structure','themeum') ?></label>
                                <select name="offering-structure" id="offering-structure">
									<option value="none">None</option>
                                    <option value="units">Units</option>
									<option value="ordinary">Ordinary</option>
                                    <option value="preference">Preference</option>
                                    <option value="bond">Bond</option>
                                    <option value="both">Bonds and Shares</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php _e('Business Overview','themeum') ?></label>
                                <div>
                                    <?php
                                    $editor_id = 'description';
                                    $content = '';
                                    //wp_editor( $content, $editor_id, array( 'media_buttons' => false ) );
                                    ?>
									<textarea name="description" id="description" minlength="0" maxlength="5000"></textarea>	
									
									<!--<div id="the-count">
										<span id="currentNum"></span>
										<span id="charNum">/ 300</span>
									</div>-->
                                </div>
                            </div>
                            <div class="form-group custom_check_style">
 			                        <label class="preemption">
										<input type='checkbox' name="pre_emption" value="1" />  
										<?php _e('Requires pre-emption rights','themeum') ?>
									</label>
				                </div>
				                </div>
				                
				                <!-- startup sample -->
                    <div id="popular-ideas" class="col-sm-4">
                        <div class="ideas-item wow fadeIn">
                            <div class="image">
                                <figure>
                                    <img src="<?php echo  get_template_directory_uri(); ?>/images/preview.jpg" class="img-responsive image-view" alt="">
                                </figure>
                            </div> <!-- end image -->

                            <div class="clearfix"></div>

                            <div class="details">
                                <div class="country-name" id="auto-location"><?php _e('Location','themeum'); ?></div>
                                <h4 id="auto-title"><?php _e('Sample Title','themeum'); ?></h4>
                                <div class="info" id="auto-description">
                                    <p>
                                        <?php _e('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, repudiandae nisi in ea eaque ut dolorem obcaecati sit error quo facilis, officiis officia. Dignissimos fugiat, voluptatem, ipsa adipisci neque modi.','themeum'); ?>
                                    </p>
                                </div>
                            </div> 
                        </div>
                    </div> <!-- end startup sample -->
                        </div>

                        <div class="project-submit-form form-hide wow fadeIn" data-wow-delay=".2s">
										<!-- step 2 -->
										<div class="form-group" id="shares_section" style="display:none;">
											  <!-- <h3>Shares</h3>	 -->
				                       <div id="shares_fields_section" class="shareselbox">
	
				                       </div>
				                       <a style="display:none;" href="javascript:void(0);" id="add_shares_fields">Add Row</a>
				                       
				                       <div class="upload_shares">
				                       	<div class="progressbarWrapper">
												<span id="greenBar"></span>
												</div>
				                       		<div class="shares-field form-group">
															<label>Please upload your companies shareholder information in an Excel/CSV file - <a id="download-sample" href="<?php echo get_template_directory_uri() ?>/shareholders_upload_sample.csv">Download template here</a>.</label>
															<input type="file" name="shares_file" id="shares_file">
													</div>
													<div class="shares-field form-group">
														<p style="float: left;margin-right: 7px;font-size: 15px;text-transform: capitalize !important;color: #5063ab !important;">Or</p>
				                       			<label><a href="javascript:void(0)" id="addmanually">Manually Add Shareholders</a></label>
				                       		</div>
				                       </div>
										</div>
										
										<!-- BONDS Section  -->
										<div class="form-group" id="bonds_section" style="display:none;">
											  <h3>Investors</h3>
				                       <div id="bonds_fields_section" class="shareselbox">
	
				                       </div>
				                       <a style="display:none;" href="javascript:void(0);" id="add_bonds_fields">Add Row</a>
				                       
				                       <div class="upload_bonds">
				                       		<div class="shares-field form-group">
															<label>Please upload your company investor information in an Excel/CSV file - <a id="download-bonds-sample" href="<?php echo get_template_directory_uri() ?>/shareholders_upload_bond_sample.csv">Download template here</a>.</label>
															<input type="file" name="bonds_file" id="bonds_file">
													</div>
													<p>OR</p>
													<div class="shares-field form-group">
				                       			<label><a href="javascript:void(0)" id="bonds_addmanually">Manually Add Investors</a></label>
				                       		</div>
				                       </div>
										</div>
                        </div>

                        <div class="project-submit-form form-hide wow fadeIn" data-wow-delay=".2s">
										<!-- Step 3 -->
									<div class="team-field form-group form-upload-image">
		                          <label for="team-image"><?php _e('Articles of Association','themeum') ?></label>
		                          <input type="text" name="article-inc" class="form-control article_inc_url" id="article-inc">
		                          <input type="hidden" name="article-inc-id" class="form-control article_inc_id" id="article-inc-id">
		                          <input type="button" id="article-inc-upload-file-button" class="article-inc-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" />
		                      </div>	
                            <div class="team-field form-group form-upload-image">
                                <label for="team-image"><?php _e('Certificate of Incorporation','themeum') ?></label>
                                <input type="text" name="cert-inc" class="form-control cert_inc_url" id="cert-inc">
                                <input type="hidden" name="cert-inc-id" class="form-control cert_inc_id" id="cert-inc-id">
                                <input type="button" id="cert-inc-upload-file-button" class="cert-inc-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" />
                            </div>
                        </div>
									 <input id="form_step" type="hidden" name="form_step" value='0' />	
									 <input id="postID" type="hidden" name="postID" value='' />
									 
									 <div class="buttons_wrapper">
                            <button id="back-3" class="btn btn-primary pull-left" type="button"><?php _e('Back','themeum') ?></button>
                            <button id="next-3" class="btn btn-primary pull-right" type="button"><?php _e('Save and continue','themeum') ?></button>
                            <button id="project-submit" class="btn btn-primary pull-right"><?php _e('Submit Business','themeum') ?></button>
							<button id="project-submit-loader" class="btn btn-primary pull-right hidden" ><i class="fa fa-spinner fa-spin"></i> Loading, Please wait..</button>
                            </div>

                        </form>
								
								<?php } // end of business new form ?>
								
								
								
                    </div> <!-- end input form -->

                <?php else: ?>
                    <div class="col-sm-4 col-sm-offset-4 text-center">
                        <div class="alert alert-danger text-center" role="alert"><?php _e('Login to add a business','themeum') ?></div>
                    </div>
                    <?php
                        if ( shortcode_exists( 'custom_login' ) ) {
                          echo do_shortcode( '[custom_login]' );
                        }
                    ?>
                <?php endif; ?>
                </div>
            </div>
        </section>
        <!-- end project form -->
                     
        <?php endwhile; ?>

    </div> <!--/#content-->
</section> <!--/#main-->

<?php get_footer(); ?>
<script>
shares_order = 1;
bonds_order = 1;
jQuery(function($){
	
	/*$('#description').keyup(function() {
    
	  var characterCount = $(this).val().length,
		  current = $('#currentNum'),
		  maximum = $('#charNum'),
		  theCount = $('#the-count');
		
	  current.text(characterCount);
	 
	  
	  if (characterCount < 70) {
		current.css('color', '#666');
	  }
	  if (characterCount > 70 && characterCount < 90) {
		current.css('color', '#6d5555');
	  }
	  if (characterCount > 90 && characterCount < 100) {
		current.css('color', '#793535');
	  }
	  if (characterCount > 100 && characterCount < 120) {
		current.css('color', '#841c1c');
	  }
	  if (characterCount > 120 && characterCount < 139) {
		current.css('color', '#8f0001');
	  }
	  
	  if (characterCount >= 140) {
		maximum.css('color', '#8f0001');
		current.css('color', '#8f0001');
		theCount.css('font-weight','bold');
	  } else {
		maximum.css('color','#666');
		theCount.css('font-weight','normal');
	  }
	  
		  
	});*/
	
	if($('#offering-structure').val() != 'bond' && $('#offering-structure').val() != 'both'){
		// set download sample file to bond
		//$('#download-sample').attr('href',"<?php echo get_template_directory_uri() ?>/shareholders_upload_bond_sample.csv");
		$('#shares_section').show();

	}
	
	
	$('body').on('change','#offering-structure',function(){
		if($(this).val() == 'both') {
			$('#shares_section').show();
			$('#bonds_section').show();
		}else if($(this).val() == 'bond') {
			$('#bonds_section').show();
			$('#shares_section').hide();
		}else{
			$('#shares_section').show();
			$('#bonds_section').hide();
		}
	});
	
	$('#shares_section').on('click','#add_shares_fields',function(){
		var sharesFieldsHtml = $('#shares_fields').html();
		sharesFieldsHtml = sharesFieldsHtml.replace(/{{order_number}}/g, shares_order);
		$('#shares_fields_section').append(sharesFieldsHtml); 
		shares_order++;
	});
	
	$('#bonds_section').on('click','#add_bonds_fields',function(){
		var bondsFieldsHtml = $('#bond_fields').html();
		
		bondsFieldsHtml = bondsFieldsHtml.replace(/{{order_number}}/g, bonds_order);
		$('#bonds_fields_section').append(bondsFieldsHtml); 
		bonds_order++;
	});
	
	// remove
	$('#shares_section').on('click','.remove-shares',function(){
		var orderNo = $(this).attr('data-order');
		$('#'+orderNo).remove(); 
	});
	$('#bonds_section').on('click','.remove-bonds',function(){
		var orderNo = $(this).attr('data-order');
		$('#bond_'+orderNo).remove(); 
	});
	
	$(document).on('click','#addmanually',function(){
		var addEl = $('#add_shares_fields');
		addEl.toggle();
		if(addEl.is(":visible")) {
			addEl.trigger('click');
		}
	});
	
	$(document).on('click','#bonds_addmanually',function(){
		var addEl = $('#add_bonds_fields');
		addEl.toggle();
		if(addEl.is(":visible")) {
			addEl.trigger('click');
		}
	});
});
</script>
<style>
.custom_check_style input {
	 display: inline-block;
    vertical-align: middle;
    margin-top: 0px;
}
.custom_check_style label {
    display: inline-block;
    margin-left: 5px;
}
.shares-field {
width: 18%;
}
.shares-field input, select {
width: 90px;
}
.buttons_wrapper {
	clear: both;
}
form.compat-item, .attachment-display-settings {
	display:none;
}
</style>