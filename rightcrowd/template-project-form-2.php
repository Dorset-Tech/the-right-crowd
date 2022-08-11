<?php
/*
 * Template Name: Page Project Form 2
 */
get_header(); ?>

<?php
$actionUrl = admin_url('admin-ajax.php');
$siteUrl = esc_url(site_url());
$termsCondition = 'agreed'; //get_user_meta(get_current_user_id(),'tandc',true);
$isManager = isManager();
$isReadOnlyManager = in_array('read_only_manager',wp_get_current_user()->roles);
if(in_array('product_manager',wp_get_current_user()->roles)):
	$isManager = true;
	//$isReadOnlyManager = true;
endif;
$canAddProducts = in_array('add_products',wp_get_current_user()->roles);
$multiplaISA = get_option( 'multiple_isa', 'no' );
global $switched;
switch_to_blog(1);
?>
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
                 <p><?php _e('Please fill Business Name and Target.','themeum') ?></p>
             </div>
         </div>
    </div>
</div>

<!-- TEAM FIELDS TEMPLATE -->
<script id="team_fields" type="text/template">
	<div class='team-fields-wrapper' id='{{order_number}}'>
	   <span class='remove-team-member' data-order='{{order_number}}' style='cursor: pointer;' >Remove</span>
		<fieldset>
		<div class='team-field form-group'>
			<label>Title</label>
			<input type="text" name="team_member_title[{{order_number}}]" id='team_member_title{{order_number}}' />
		</div>
		<div class='team-field form-group'>
			<label>Name</label>
			<input type="text" name="team_member_name[{{order_number}}]" id='team_member_name{{order_number}}'/>
		</div>
		<!--<div class='team-field form-group'>
			<label>Image</label>
			<input type="file" name="team_member_file[{{order_number}}]" id='team_member_file{{order_number}}'/>
		</div>-->
		<div class="team-field form-group form-upload-image">
                                <label for="team-image{{order_number}}"><?php _e('Image','themeum') ?></label>
                                <input type="text" name="team-image[{{order_number}}]" class="form-control team_image_url{{order_number}}" id="team-image{{order_number}}">
                                <input type="hidden" name="team-image-id[{{order_number}}]" class="form-control team_upload_image_id{{order_number}}" id="team-image{{order_number}}">
                                <input type="button" id="cc-image-upload-file-button" class="team-image-upload" value="Upload Image" data-url="<?php echo esc_url(get_site_url()); ?>" data-team-order="{{order_number}}" />
                            </div>
		<div class='team-field form-group'>
			<label>Bio</label>
			<input type="text" name="team_member_bio[{{order_number}}]" id='team_member_bio{{order_number}}'/>
		</div>
		</fieldset>
	</div>
</script>

<!-- Bank Statement File Field Template-->
<script id="bank_file_field" type="text/template">
	<div class="bankFieldWrapper">							
		<input type="text" name="bank-file[{{order_number}}]" class="form-control bank_file_url{{order_number}}" id="bank-file{{order_number}}">
		<input type="hidden" name="bank-file-id[{{order_number}}]" class="form-control bank_file_id{{order_number}}" id="bank-file-id{{order_number}}">
		<input type="button" id="bank-file-button{{order_number}}" class="bank-file-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" data-team-order="{{order_number}}" />
	</div>
</script>

<!-- Financial Reports File Field Template-->
<script id="financial_file_field" type="text/template">
	<div class="financialFieldWrapper">
		<input type="text" name="financial-file[{{order_number}}]" class="form-control financial_file_url{{order_number}}" id="financial-file{{order_number}}">
		<input type="hidden" name="financial-file-id[{{order_number}}]" class="form-control financial_file_id{{order_number}}" id="financial-file-id{{order_number}}">
		<input type="button" id="financial-file-button{{order_number}}" class="financial-file-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" data-team-order="{{order_number}}" />
	</div>
</script>

<!-- Extra File Field Template-->
<script id="extra_file_field" type="text/template">
	<div class="extraFieldWrapper">
		 <input type="text" name="extra-file[{{order_number}}]" class="form-control extra_file_url{{order_number}}" id="extra-file{{order_number}}" />
		 <input type="hidden" name="extra-file-id[{{order_number}}]" class="form-control extra_file_id{{order_number}}" id="extra-file-id{{order_number}}">
		 <input type="button" id="extra-file-button{{order_number}}" class="extra-file-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" data-team-order="{{order_number}}" />
		 <label>Title <br/>
			<input type="text" width="150px" name="extra-file-title[{{order_number}}]" class="form-control extra_file_title{{order_number}}" id="extra-file-title{{order_number}}" />
		 </label>
	</div>
</script>

<section id="main" class="clearfix">
   <?php get_template_part('lib/sub-header')?>
    <div id="content" class="site-content container" role="main">
		<?php // STAGE ONE TO ACCESS TERMS AND CONDITIONS TO PROCEED FURTHER

			if($termsCondition == 'agreed') {
				
				// removed for now and put only is manager check
				//(get_option( 'site_type', '' ) == 'child' && get_option( 'allow_project_creation', 'yes' ) == 'yes')	|| (get_option( 'site_type', '' ) == 'master')
		?>
		
		<?php if(($isManager && !$isReadOnlyManager) || $canAddProducts) { // check if site is child and have permission to create company/project ?>

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
                                <i class="fa fa-edit"></i>
                                <?php _e('Business Info','themeum'); ?>
                            </p>
                            <ul class="list-unstyled list-inline">
                                <li></li>
                                <li></li>
                            </ul>
                        </div>

                        <!-- step 02 -->
                        <div class="step-01 wow fadeIn" data-wow-delay=".1s">
                            <p class="step-name text-center pull-left">
                                <i class="fa fa-file-text-o"></i>
                                <?php _e('Business Story','themeum'); ?>
                            </p>
                            <ul class="list-unstyled list-inline">
                                <li></li>
                                <li></li>
                            </ul>
                        </div>

                        <!-- step 03 -->
                        <div class="step-01 wow fadeIn" data-wow-delay=".2s">
                            <p class="step-name text-center pull-left">
                                <i class="fa fa-user"></i>
                                <?php _e('Due Diligence','themeum'); ?>
                            </p>
                            <ul class="list-unstyled list-inline">
                                <li></li>
                                <li></li>
                            </ul>
                        </div>

                        <!-- step final -->
                        <div class="final-step wow fadeIn" data-wow-delay=".4s">
                            <p class="step-name text-center">
                                <i class="fa fa-check"></i>
                                <?php _e('Publish','themeum'); ?>
                            </p>
                        </div>
                    </div> <!-- end progressbar -->

                    <!-- input form -->
                    <div class="col-sm-8 input-form">

								<?php if((isset($_GET['action']) && $_GET['action'] == 'edit') && (isset($_GET['postid']) && $_GET['postid'] != 0) ) {
									$postID = $_GET["postid"];
									$project = get_post($postID);
									if($project->post_author == get_current_user_id() || $isManager) {
										$allMetas = get_post_meta($postID);
										/*echo "<pre>";
										print_r($allMetas);
										echo "</pre>";*/
								?>
									<div class='user-registration'>
				                  <form name="project-submit-form" action="<?php echo esc_url($actionUrl) ?>" method="post" class="wow fadeIn" id="project-submit-form" enctype="multipart/form-data">

				                  <div class="project-submit-form form-show wow fadeIn" data-wow-delay=".2s">

				                      <div class="form-group has-feedback">
				                          <label for="project-title"><?php _e('Full Business Name','themeum') ?></label>
				                          <input type="hidden" name="new-project" id="new-project" value="new">
				                          <input type="hidden" name="action" value="new_project_add">
				                          <input type="hidden" name="url_red" id="redirect_url_add" value="<?php echo $siteUrl ?>">
				                          <input type="text" name="project-title" class="form-control" placeholder="Full Business Name" id="project-title" value="<?php echo $project->post_title ?>" />
				                          <span class="glyphicon glyphicon-ok form-control-feedback title-color"></span>
				                      </div>

				                      <div class="form-group has-feedback">
				                          <label for="brandname"><?php _e('Brand Name','themeum') ?></label>
				                          <input type="text" name="brandname" class="form-control" id="brandname" value="<?php echo $allMetas['thm_brandname'][0] ?>">
				                          <span class="glyphicon glyphicon-ok form-control-feedback brandname-color"></span>
				                      </div>

				                      <div class="form-group has-feedback">
				                          <label for="location"><?php _e('Location','themeum') ?></label>
				                          <input type="text" name="location" class="form-control" id="location" value="<?php echo $allMetas['thm_location'][0] ?>">
				                          <span class="glyphicon glyphicon-ok form-control-feedback location-color"></span>
				                      </div>

				                      <div class="form-group has-feedback">
				                          <label for="incorpdate"><?php _e('Incorporation Date','themeum') ?></label>
				                          <input type="text" name="incorpdate" class="form-control datepicker" id="incorpdate" value="<?php echo $allMetas['thm_incorpdate'][0] ?>">
				                          <span class="glyphicon glyphicon-ok form-control-feedback incorpdate-color"></span>
				                      </div>

				                      <div class="form-group has-feedback">
				                          <label for="coregno"><?php _e('Company Reg Number','themeum') ?></label>
				                          <input type="text" name="coregno" class="form-control" id="coregno" value="<?php echo $allMetas['thm_coregno'][0] ?>">
				                          <span class="glyphicon glyphicon-ok form-control-feedback coregno-color"></span>
				                      </div>
									  <!--<div class="form-group has-feedback">
										  <label for="isin"><?php //_e('ISIN Number','themeum') ?></label>
										  <input type="text" name="isin" class="form-control" id="isin" value="<?php // echo $allMetas['thm_isin'][0] ?>">
										  <span class="glyphicon glyphicon-ok form-control-feedback isin-color"></span>
									  </div>-->
									  
									  <div class="form-group has-feedback">
				                          <label for="website_url"><?php _e('Company Website','themeum') ?></label>
				                          <input type="text" name="website_url" class="form-control" id="website_url" value="<?php echo $allMetas['thm_website_url'][0] ?>">
				                          <span class="glyphicon glyphicon-ok form-control-feedback coregno-color"></span>
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
				                      <div class="form-group">
				                          <label for="project-category"><?php _e('Business Sector (Secondary)','themeum') ?></label>
				                          <select name="project-category-2" id="project-category-2">
				                              <option value="">N/A</option>
				                              <?php
				                              $all_cat = get_terms('project_category', array(
				                                  'hide_empty' => false,
				                              ));
				                              foreach ($all_cat as $value) {
				                                  echo '<option value="'.$value->slug.'"'.(isset($cat_terms[1]) && $cat_terms[1]->slug == $value->slug ? "selected" : "").'>'.$value->name.'</option>';
				                              }
				                              ?>
				                          </select>
				                      </div>
				                      <div class="form-group">
				                          <label for="project-category"><?php _e('Business Sector (Tertiary)','themeum') ?></label>
				                          <select name="project-category-3" id="project-category-3">
				                              <option value="">N/A</option>
				                              <?php
				                              $all_cat = get_terms('project_category', array(
				                                  'hide_empty' => false,
				                              ));
				                              foreach ($all_cat as $value) {
				                                  echo '<option value="'.$value->slug.'"'.(isset($cat_terms[2]) && $cat_terms[2]->slug == $value->slug ? "selected" : "").'>'.$value->name.'</option>';
				                              }
				                              ?>
				                          </select>
				                      </div>

											 <div class="form-group has-feedback">
												  <label for="investment-type"><?php _e('Investment Type','themeum') ?></label>
												  <select name="investment-type" id="investment-type">
													  <option value="equity" <?php echo $allMetas['thm_investment_type'][0] == 'equity' ? 'selected' : '' ?> >Equity</option>
													  <option value="bond" <?php echo $allMetas['thm_investment_type'][0] == 'bond' ? 'selected' : '' ?> >Bond</option>
													  <option value="loan" <?php echo $allMetas['thm_investment_type'][0] == 'loan' ? 'selected' : '' ?> >Loan</option>
													  <option value="fund" <?php echo $allMetas['thm_investment_type'][0] == 'fund' ? 'selected' : '' ?> >Fund</option>
												  </select>
											  </div>
											<div class="form-group" id="investment_term">
											   <label for="investment_term"><?php _e('Investment Term','themeum') ?></label>
												<input type="text" name="investment_term" id="investment_term" class="form-control" value="<?php echo $allMetas['thm_investment_term'][0]; ?>" >
											</div>
											<div class="form-group" id="price-per-unit">
											   <label for="share_price"><?php _e('Price per Unit','themeum') ?></label>
											   <div class="input-group currency-change">
												   <input type="text" name="share_price" id="share_price" value="<?php echo $allMetas['thm_share_price'][0] ?>" class="form-control" >
												   <div class="input-group-addon fund-goal">
													   <div class="input-group-addon fund-goal">
														   <!--<span>pence</span>-->
														   <?php  
																$currs = get_currencies();
														   ?>
														   <select name="share_currency" id="share_currency">
																<?php foreach($currs as $currKey => $cur) {  ?>
																	<option value="<?php echo $currKey ?>" <?php echo ($allMetas['share_currency'][0] == '') ? ($currKey == 'GBP' ? 'selected' : '') : ($allMetas['share_currency'][0] == $currKey ? 'selected' : '') ?> ><?php echo $currKey ?></option>
																<?php } ?>
														   </select>
													   </div>
												   </div>
											   </div>
										    </div>

				                      <!--<div class="form-group project-duration">
				                          <label for="duration"><?php _e('Investment Term','themeum') ?></label>
				                          <select name="duration" id="duration">
				                              <option value="30" <?php echo $allMetas['thm_duration'][0] == '30' ? 'selected' : '' ?> >30 Days</option>
				                              <option value="60" <?php echo $allMetas['thm_duration'][0] == '60' ? 'selected' : '' ?> >60 Days</option>
				                              <option value="90" <?php echo $allMetas['thm_duration'][0] == '90' ? 'selected' : '' ?> >90 Days</option>
				                          </select>
				                      </div>-->
											 <div class="form-group" id="marketing_allowance_field">
											   <label for="marketing_allowance"><?php _e('Marketing Allowance %','themeum') ?></label>
											   <div class="input-group currency-change">
												   <input type="text" name="marketing_allowance" id="marketing_allowance" value="<?php echo $allMetas['thm_marketing'][0] ?>" class="form-control" >
												   <div class="input-group-addon fund-goal">
													   <span>%</span>
												   </div>
											   </div>
										   </div>
					                   <div class="form-group" id="coupon_field">
					                       <label for="coupon"><?php _e('Coupon %','themeum') ?></label>
					                       <div class="input-group currency-change">
					                           <input type="text" name="coupon" id="coupon" value="<?php echo $allMetas['thm_coupon'][0] ?>" class="form-control" >
					                           <div class="input-group-addon fund-goal">
					                               <span>%</span>
					                           </div>
					                       </div>
					                   </div>
									   <div class="form-group" id="coupon_paid_field">
					                       <label for="coupon_paid"><?php _e('Coupon Paid','themeum') ?></label>
										   <select name="coupon_paid" id="coupon_paid">
											  <option value="monthly" <?php echo $allMetas['thm_coupon_paid'][0] == 'monthly' ? 'selected' : '' ?> >Monthly</option>
											  <option value="quarterly" <?php echo $allMetas['thm_coupon_paid'][0] == 'quarterly' ? 'selected' : '' ?> >Quarterly</option>
											  <option value="half_yearly" <?php echo $allMetas['thm_coupon_paid'][0] == 'half_yearly' ? 'selected' : '' ?> >Half Yearly</option>
											  <option value="annually" <?php echo $allMetas['thm_coupon_paid'][0] == 'annually' ? 'selected' : '' ?> >Annually</option>
											  <option value="maturity" <?php echo $allMetas['thm_coupon_paid'][0] == 'maturity' ? 'selected' : '' ?> >Maturity</option>
										   </select>
					                   </div>
					                   <div class="form-group" id="bond_expiry_field">
					                       <label for="bond_expiry"><?php _e('Expiry Date','themeum') ?></label>
					                       <div class="input-group currency-change">
					                           <input type="date" name="bond_expiry" id="bond_expiry" value="<?php echo $allMetas['thm_bond_expiry'][0] ?>" class="form-control" >
					                       </div>
					                   </div>

					                   <div class="form-group has-feedback">
				                          <label for="private-listing"><?php _e('Private Listing','themeum') ?></label>
				                          <select name="private-listing" id="private-listing">
				                              <option value="yes" <?php echo $allMetas['thm_private_listing'][0] == 'yes' ? 'selected' : '' ?>>Yes</option>
				                              <option value="no" <?php echo $allMetas['thm_private_listing'][0] == 'no' ? 'selected' : '' ?>>No</option>
				                          </select>
				                      </div>

				                      <div class="form-group" id="investment-target">
				                          <label for="duration"><?php _e('Investment Target','themeum') ?></label>
				                          <div class="input-group currency-change">
				                              <input type="text" name="investment-amount" id="investment-amount" class="form-control" value="<?php echo $allMetas['thm_funding_goal'][0] ?>">
				                              <div class="input-group-addon fund-goal">
				                                  <?php  
														$currs = get_currencies();
												   ?>
												   <select name="goal_currency" id="goal_currency">
														<?php foreach($currs as $currKey => $cur) {  ?>
															<option value="<?php echo $currKey ?>" <?php echo ($allMetas['goal_currency'][0] == '') ? ($currKey == 'GBP' ? 'selected' : '') : ($allMetas['goal_currency'][0] == $currKey ? 'selected' : '') ?> ><?php echo $currKey ?></option>
														<?php } ?>
												   </select>
				                              </div>
				                          </div>
				                      </div>
									  <?php //$allMetas['thm_investing_into'][0] = unserialize($allMetas['thm_investing_into'][0]); ?>
									  <div class="form-group">
				                          <label for="duration"><?php _e('Investment Types offered','themeum') ?></label>
				                          <div class="input-group currency-change investment-types-offered">
											  <input type="checkbox" name="investing_into[]" value="isa" <?php echo in_array('isa',$allMetas['thm_investing_into']) ? 'checked' : '' ?> /> ISA
											  <?php //if($multiplaISA == 'yes') { ?>
												  <input type="checkbox" name="investing_into[]" value="ifisa" <?php echo in_array('ifisa',$allMetas['thm_investing_into']) ? 'checked' : '' ?> /> IFISA
												  <input type="checkbox" name="investing_into[]" value="ssisa" <?php echo in_array('ssisa',$allMetas['thm_investing_into']) ? 'checked' : '' ?> /> SSISA
											  <?php //} ?>
											  <input type="checkbox" name="investing_into[]" value="equity" <?php echo in_array('equity',$allMetas['thm_investing_into']) ? 'checked' : '' ?> /> Equity
											  <input type="checkbox" name="investing_into[]" value="loan_note" <?php echo in_array('loan_note',$allMetas['thm_investing_into']) ? 'checked' : '' ?> /> Loan Note
											  <input type="checkbox" name="investing_into[]" value="fund" <?php echo in_array('fund',$allMetas['thm_investing_into']) ? 'checked' : '' ?> /> Fund
											  <input type="checkbox" name="investing_into[]" value="bond" <?php echo in_array('bond',$allMetas['thm_investing_into']) ? 'checked' : '' ?> /> Bond 	
				                          </div>
				                      </div>
									  <?php 
										//$allMetas['thm_invester_type'] = unserialize($allMetas['thm_invester_type'][0]); 
									  
									  ?>
									  <div class="form-group bank_details_fields" id="bank_details">
			                       <label for="bond_expiry"><?php _e('Bank Details','themeum') ?></label>
			                       <div class="input-group">
			                           <input placeholder="Name on Account" type="text" name="bank_details_name" id="bank_details_name" value="<?php echo $allMetas['bank_details_name'][0] ?>" class="form-control" >
			                           <input placeholder="Bank Name" type="text" name="bank_details_bank_name" id="bank_details_bank_name" value="<?php echo $allMetas['bank_details_bank_name'][0] ?>" class="form-control" >
			                           <input placeholder="Sort Code" type="text" name="bank_details_sort_code" id="bank_details_sort_code" value="<?php echo $allMetas['bank_details_sort_code'][0] ?>" class="form-control" >
			                           <input placeholder="Account Number" type="text" name="bank_details_account" id="bank_details_account" value="<?php echo $allMetas['bank_details_account'][0] ?>" class="form-control" >
			                           <input placeholder="IBAN" type="text" name="bank_details_iban" id="bank_details_iban" value="<?php echo $allMetas['bank_details_iban'][0] ?>" class="form-control" >
			                           <input placeholder="Swift/Bic" type="text" name="bank_details_swift" id="bank_details_swift" value="<?php echo $allMetas['bank_details_swift'][0] ?>" class="form-control" >
			                       </div>
			                   </div>
			                   <div class="form-group bank_details_fields" id="isa_bank_details">
			                       <label for="bond_expiry"><?php _e('ISA Bank Details','themeum') ?></label>
			                       <div class="input-group">
			                           <input placeholder="Name on Account" type="text" name="isa_bank_details_name" id="isa_bank_details_name" value="<?php echo $allMetas['isa_bank_details_name'][0] ?>" class="form-control" >
			                           <input placeholder="Bank Name" type="text" name="isa_bank_details_bank_name" id="isa_bank_details_bank_name" value="<?php echo $allMetas['isa_bank_details_bank_name'][0] ?>" class="form-control" >
			                           <input placeholder="Sort Code" type="text" name="isa_bank_details_sort_code" id="isa_bank_details_sort_code" value="<?php echo $allMetas['isa_bank_details_sort_code'][0] ?>" class="form-control" >
			                           <input placeholder="Account Number" type="text" name="isa_bank_details_account" id="isa_bank_details_account" value="<?php echo $allMetas['isa_bank_details_account'][0] ?>" class="form-control" >
			                           <input placeholder="IBAN" type="text" name="isa_bank_details_iban" id="isa_bank_details_iban" value="<?php echo $allMetas['isa_bank_details_iban'][0] ?>" class="form-control" >
			                           <input placeholder="Swift/Bic" type="text" name="isa_bank_details_swift" id="isa_bank_details_swift" value="<?php echo $allMetas['isa_bank_details_swift'][0] ?>" class="form-control" >
			                       </div>
			                   </div>
									  <div class="form-group">
											<label><?php _e('Who can Invest into this product?','themeum') ?></label>
											<div class="input-group currency-change">
											  <input type="checkbox" name="invester_type[]" value="High Net Worth Investor" <?php echo in_array('High Net Worth Investor',$allMetas['thm_invester_type']) ? 'checked' : '' ?> /> High Net Worth Investor
											  <input type="checkbox" name="invester_type[]" value="Sophisticated Investor" <?php echo in_array('Sophisticated Investor',$allMetas['thm_invester_type']) ? 'checked' : '' ?> /> Sophisticated Investor
											  <input type="checkbox" name="invester_type[]" value="Restricted Investor" <?php echo in_array('Restricted Investor',$allMetas['thm_invester_type']) ? 'checked' : '' ?> /> Restricted Investor
											</div>
									  </div>
									  <div class="form-group">
										<label for="minimum-investment-amount"><?php _e('Minimum Investment','themeum') ?></label>
										<div class="input-group currency-change">
											<input type="text" name="minimum-investment-amount" id="minimum-investment-amount" class="form-control" value="<?php echo $allMetas['thm_minimum_investment'][0] ?>" >
											<div class="input-group-addon fund-goal">
												<span id="min-inv-curr"><?php echo themeum_get_currency_code(); ?></span>
											</div>
										</div>
									  </div>
									  <div class="form-group"> 
										<label for="required-investment"><?php _e('Required Investment Increments','themeum') ?></label>
										<div class="input-group currency-change">
											<input type="text" name="required-investment" id="required-investment" class="form-control" value="<?php echo $allMetas['thm_required_investment'][0] ?>" >
											<div class="input-group-addon fund-goal">
												<span id="req-inv-curr"><?php echo themeum_get_currency_code(); ?></span>
											</div>
										</div>
									  </div>
				                      <div class="form-group has-feedback">
				                          <label for="equityoffer"><?php _e('Equity offered','themeum') ?></label>
				                          <input type="text" name="equityoffer" class="form-control" id="equityoffer" value="<?php echo $allMetas['thm_equityoffer'][0] ?>">
				                          <span class="glyphicon glyphicon-ok form-control-feedback equityoffer-color"></span>
				                      </div>

				                      <input type="hidden" name="min1" value="50" />


				                  </div>

				                  <div class="project-submit-form form-hide wow fadeIn" data-wow-delay=".2s">

				                      <div class="form-group has-feedback">
				                          <label for="project-video"><?php _e('Video URL','themeum') ?></label>
				                          <input type="text" name="video-url" class="form-control" placeholder="YouTube or Vimeo URL" id="project-video" value="<?php echo $allMetas['thm_video_url'][0] ?>">
				                          <span class="glyphicon glyphicon-ok form-control-feedback video-color"></span>
				                      </div>
											 <div class="form-group has-feedback">
				                          <label for="project-about"><?php _e('About the Project Creator','themeum') ?></label>
				                          <textarea id="project-about" name="about"><?php echo $allMetas['thm_about'][0] ?></textarea>
				                          <span class="glyphicon glyphicon-ok form-control-feedback about-color"></span>
				                      </div>
				                      <div class="form-group">
				                          <label><?php _e('Business Description','themeum') ?></label>
				                          <div>
				                              <?php
				                              $editor_id = 'description';
				                              $content = $project->post_content;
				                              wp_editor( $content, $editor_id );
				                              ?>
				                          </div>
				                      </div>

											<div class="form-group">
				                          <label><?php _e('Overview','themeum') ?></label>
				                          <div>
				                              <?php
				                              $editor_id = 'business_idea';
				                              $content = $allMetas['thm_business_idea'][0];
				                              wp_editor( $content, $editor_id );
				                              ?>
				                          </div>
				                      </div>

				                     <!--<div class="form-group">
				                          <label><?php _e('Market','themeum') ?></label>
				                          <div>
				                              <?php
				                              $editor_id = 'business_market';
				                              $content = $allMetas['thm_business_market'][0];
				                              //wp_editor( $content, $editor_id );
				                              ?>
				                          </div>
				                      </div>-->

											<!-- TEAM FIELDS  -->
										<div class="form-group" id="team_section">
				                          <label><?php _e('Team','themeum') ?></label>
				                          <div id="team_fields_section"></div>
				                          <a href="javascript:void(0)" id="add_team_fields">Add Member</a>
										</div>
				                  </div>

				                  <div class="project-submit-form form-hide wow fadeIn" data-wow-delay=".2s">
												<!-- Step 3 -->
				                      <div class="team-field form-group form-upload-image">
				                          <label for="team-image"><?php _e('Certificate of Incorporation','themeum') ?></label>
				                          <input type="text" name="cert-inc" class="form-control cert_inc_url" id="cert-inc">
				                          <input type="hidden" name="cert-inc-id" class="form-control cert_inc_id" id="cert-inc-id">
				                          <input type="button" id="cert-inc-upload-file-button" class="cert-inc-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" />
				                      </div>

				                      <div class="team-field form-group form-upload-image">
				                          <label for="team-image"><?php _e('Articles of Association','themeum') ?></label>
				                          <input type="text" name="article-inc" class="form-control article_inc_url" id="article-inc">
				                          <input type="hidden" name="article-inc-id" class="form-control article_inc_id" id="article-inc-id">
				                          <input type="button" id="article-inc-upload-file-button" class="article-inc-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" />
				                      </div>

				                      <div class="team-field form-group form-upload-image">
				                          <label for="team-image"><?php _e('Memorandum of Association','themeum') ?></label>
				                          <input type="text" name="memorandum" class="form-control memorandum_url" id="memorandum">
				                          <input type="hidden" name="memorandum-id" class="form-control memorandum_id" id="memorandum-id">
				                          <input type="button" id="memorandum-upload-file-button" class="memorandum-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" />
				                      </div>

				                      <hr/>
				                      <div class="form-group" id="bank_file_section">
				                          <label><?php _e('Bank Statements (last 3 months)','themeum') ?></label>
				                          <div id="bank_files_section"></div>
				                          <a href="javascript:void(0)" id="add_bank_file">Add More</a>
				                      </div>

											 <hr/>

											 <div class="form-group" id="financial_file_section">
				                          <label><?php _e('Financial Reports (last 3 years)','themeum') ?></label>
				                          <div id="financial_files_section"></div>
				                          <a href="javascript:void(0)" id="add_financial_file">Add More</a>
				                      </div>
				                      <div class="form-group" id="extra_file_section">
				                          <label><?php _e('Extra Files','themeum') ?></label>
				                          <div id="extra_files_section"></div>
				                          <a href="javascript:void(0)" id="add_extra_file">Add More</a>
				                      </div>
				                  </div>
											 <input id="form_step" type="hidden" name="form_step" value="<?php echo isset($allMetas['form_step']) ? $allMetas['form_step'][0] : 0 ?>" />
											 <input id="postID" type="hidden" name="postID" value='<?php echo $_GET["postid"] ?>' />
				                      <button id="back" class="btn btn-primary pull-left" type="button"><?php _e('Back','themeum') ?></button>
				                      <button id="next" class="btn btn-primary pull-right" type="button"><?php _e('Save and continue','themeum') ?></button>
				                      <button id="project-submit" class="btn btn-primary pull-right"><?php _e('Submit Business','themeum') ?></button>

				                  </form>
								  </div>
										<?php }else{ ?>
											<div><p>You don't have permission to perform this action.</p></div>
										<?php } ?>
								<?php }else if(true){ // end of edit form ?>

<div class='user-registration'>
								<!-- new business form -->
								<form name="project-submit-form" action="<?php echo esc_url($actionUrl) ?>" method="post" class="wow fadeIn" id="project-submit-form" enctype="multipart/form-data">

                        <div class="project-submit-form form-show wow fadeIn" data-wow-delay=".2s">

                            <div class="form-group has-feedback">
                                <label for="project-title"><?php _e('Full Business Name','themeum') ?></label>
                                <input type="hidden" name="new-project" id="new-project" value="new">
                                <input type="hidden" name="action" value="new_project_add">
                                <input type="hidden" name="url_red" id="redirect_url_add" value="<?php echo $siteUrl; ?>">
                                <input type="text" name="project-title" class="form-control" placeholder="Full Business Name" id="project-title">
                                <span class="glyphicon glyphicon-ok form-control-feedback title-color"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="brandname"><?php _e('Brand Name','themeum') ?></label>
                                <input type="text" name="brandname" class="form-control" id="brandname" value="">
                                <span class="glyphicon glyphicon-ok form-control-feedback brandname-color"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="location"><?php _e('Location','themeum') ?></label>
                                <input type="text" name="location" class="form-control" id="location" value="">
                                <span class="glyphicon glyphicon-ok form-control-feedback location-color"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="incorpdate"><?php _e('Incorporation Date','themeum') ?></label>
                                <input type="text" name="incorpdate" class="form-control datepicker" id="incorpdate" value="01/01/2018">
                                <span class="glyphicon glyphicon-ok form-control-feedback incorpdate-color"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="coregno"><?php _e('Company Reg Number','themeum') ?></label>
                                <input type="text" name="coregno" class="form-control" id="coregno">
                                <span class="glyphicon glyphicon-ok form-control-feedback coregno-color"></span>
                            </div>
							<!--<div class="form-group has-feedback">
							  <label for="isin"><?php _e('ISIN Number','themeum') ?></label>
							  <input type="text" name="isin" class="form-control" id="isin">
							  <span class="glyphicon glyphicon-ok form-control-feedback isin-color"></span>
							</div>-->
							<div class="form-group has-feedback">
								<label for="website_url"><?php _e('Company Website','themeum') ?></label>
								<input type="text" name="website_url" class="form-control" id="website_url">
								<span class="glyphicon glyphicon-ok form-control-feedback coregno-color"></span>
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
                                <label for="project-category"><?php _e('Business Sector (Primary)','themeum') ?></label>
                                <select name="project-category" id="project-category">
                                    <?php
                                    $all_cat = get_terms('project_category', array(
                                        'hide_empty' => false,
                                    )); print_r($all_cat);
                                    foreach ($all_cat as $value) {
                                        echo '<option value="'.$value->slug.'">'.$value->name.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="project-category"><?php _e('Business Sector (Secondary)','themeum') ?></label>
                                <select name="project-category-2" id="project-category-2">
                                    <option value="">N/A</option>
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
                            <div class="form-group">
                                <label for="project-category"><?php _e('Business Sector (Tertiary)','themeum') ?></label>
                                <select name="project-category-3" id="project-category-3">
                                    <option value="">N/A</option>
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

                            <!--<div class="form-group has-feedback">
                                <label for="project-tag"><?php _e('Project Tag','themeum') ?></label>
                                <input type="text" name="project-tag" class="form-control" placeholder="ex: food, arts" id="project-tag">
                                <span class="glyphicon glyphicon-ok form-control-feedback tag-color"></span>
                            </div>-->

                            <div class="form-group has-feedback">
                                <label for="investment-type"><?php _e('Investment Type','themeum') ?></label>
                                <select name="investment-type" id="investment-type">
                                    <option value="equity">Equity</option>
                                    <option value="bond">Bond</option>
                                    <option value="loan">Loan</option>
                                    <option value="fund">Fund</option>
                                </select>
                            </div>
							<div class="form-group" id="investment_term">
							   <label for="investment_term"><?php _e('Investment Term','themeum') ?></label>
								<input type="text" name="investment_term" id="investment_term" class="form-control" >
							</div>
							<div class="form-group" id="price-per-unit">
							   <label for="share_price"><?php _e('Price per Unit','themeum') ?></label>
							   <div class="input-group currency-change">
								   <input type="text" name="share_price" id="share_price" class="form-control" >
								   <div class="input-group-addon fund-goal">
									   <?php  
											$currs = get_currencies();
									   ?>
									   <select name="share_currency" id="share_currency">
											<?php foreach($currs as $currKey => $cur) {  ?>
												<option value="<?php echo $currKey ?>" <?php echo $currKey == 'GBP' ? 'selected' : '' ?>><?php echo $currKey ?></option>
											<?php } ?>
									   </select>
								   </div>
							   </div>
							</div>

							<!--<div class="form-group project-duration">
                                <label for="duration"><?php _e('Investment Term','themeum') ?></label>
                                <select name="duration" id="duration">
                                    <option value="30">30 Days</option>
                                    <option value="60">60 Days</option>
                                    <option value="90">90 Days</option>
                                </select>
                            </div>-->

                            <div class="form-group" id="marketing_allowance_field">
                                <label for="marketing_allowance"><?php _e('Marketing Allowance %','themeum') ?></label>
                                <div class="input-group currency-change">
                                    <input type="text" name="marketing_allowance" id="marketing_allowance" class="form-control" >
                                    <div class="input-group-addon fund-goal">
                                        <span>%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="coupon_field">
                                <label for="coupon"><?php _e('Coupon %','themeum') ?></label>
                                <div class="input-group currency-change">
                                    <input type="text" name="coupon" id="coupon" class="form-control" >
                                    <div class="input-group-addon fund-goal">
                                        <span>%</span>
                                    </div>
                                </div>
                            </div>
							<div class="form-group" id="coupon_paid_field">
							   <label for="coupon_paid"><?php _e('Coupon Paid','themeum') ?></label>
							   <select name="coupon_paid" id="coupon_paid">
								  <option value="monthly">Monthly</option>
								  <option value="quarterly">Quarterly</option>
								  <option value="half_yearly">Half Yearly</option>
								  <option value="annually" selected>Annually</option>
								  <option value="maturity">Maturity</option>
							   </select>
						   </div>
                            <div class="form-group" id="bond_expiry_field">
                                <label for="bond_expiry"><?php _e('Expiry Date','themeum') ?></label>
                                <div class="input-group currency-change">
                                    <input type="date" name="bond_expiry" id="bond_expiry" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="private-listing"><?php _e('Private Listing','themeum') ?></label>
                                <select name="private-listing" id="private-listing">
                                    <option value="yes">Yes</option>
                                    <option value="no" selected>No</option>
                                </select>
                            </div>
                            <div class="form-group" id="investment-target">
                                <label for="investment-amount"><?php _e('Investment Target','themeum') ?></label>
                                <div class="input-group currency-change">
                                    <input type="text" name="investment-amount" id="investment-amount" class="form-control" >
                                    <div class="input-group-addon fund-goal">
                                        <?php  
											$currs = get_currencies();
									   ?>
										   <select name="goal_currency" id="goal_currency">
												<?php foreach($currs as $currKey => $cur) {  ?>
													<option value="<?php echo $currKey ?>" <?php echo $currKey == 'GBP' ? 'selected' : '' ?>  ><?php echo $currKey ?></option>
												<?php } ?>
										   </select>
                                    </div>
                                </div>
                            </div>
							<div class="form-group">
							    <label><?php _e('Investment Types offered','themeum') ?></label>
							    <div class="input-group currency-change investment-types-offered">
								  <input type="checkbox" name="investing_into[]" value="isa" /> ISA
								  <?php //if($multiplaISA == 'yes') { ?>
									  <input type="checkbox" name="investing_into[]" value="ifisa" /> IFISA
									  <input type="checkbox" name="investing_into[]" value="ssisa" /> SSISA
								  <?php// } ?>
								  <input type="checkbox" name="investing_into[]" value="equity" /> Equity
								  <input type="checkbox" name="investing_into[]" value="loan_note"  /> Loan Note
								  <input type="checkbox" name="investing_into[]" value="fund" /> Fund
								  <input type="checkbox" name="investing_into[]" value="bond" /> Bond 	
							    </div>
						    </div>
						    
						    <div class="form-group bank_details_fields" id="bank_details">
			                       <label for="bond_expiry"><?php _e('Bank Details','themeum') ?></label>
			                       <div class="input-group">
			                           <input placeholder="Name on Account" type="text" name="bank_details_name" id="bank_details_name"  class="form-control" >
			                           <input placeholder="Bank Name" type="text" name="bank_details_bank_name" id="bank_details_bank_name"  class="form-control" >
			                           <input placeholder="Sort Code" type="text" name="bank_details_sort_code" id="bank_details_sort_code"  class="form-control" >
			                           <input placeholder="Account Number" type="text" name="bank_details_account" id="bank_details_account"  class="form-control" >
			                           <input placeholder="IBAN" type="text" name="bank_details_iban" id="bank_details_iban"  class="form-control" >
			                           <input placeholder="Swift/Bic" type="text" name="bank_details_swift" id="bank_details_swift"  class="form-control" >
			                       </div>
			                   </div>
			                   <div class="form-group bank_details_fields" id="isa_bank_details">
			                       <label for="bond_expiry"><?php _e('ISA Bank Details','themeum') ?></label>
			                       <div class="input-group">
			                           <input placeholder="Name on Account" type="text" name="isa_bank_details_name" id="isa_bank_details_name"  class="form-control" >
			                           <input placeholder="Bank Name" type="text" name="isa_bank_details_bank_name" id="isa_bank_details_bank_name"  class="form-control" >
			                           <input placeholder="Sort Code" type="text" name="isa_bank_details_sort_code" id="isa_bank_details_sort_code"  class="form-control" >
			                           <input placeholder="Account Number" type="text" name="isa_bank_details_account" id="isa_bank_details_account"  class="form-control" >
			                           <input placeholder="IBAN" type="text" name="isa_bank_details_iban" id="isa_bank_details_iban"  class="form-control" >
			                           <input placeholder="Swift/Bic" type="text" name="isa_bank_details_swift" id="isa_bank_details_swift"  class="form-control" >
			                       </div>
			                   </div>
						    
						    
							<div class="form-group">
							    <label><?php _e('Who can Invest into this product?','themeum') ?></label>
							    <div class="input-group currency-change">
								  <input type="checkbox" name="invester_type[]" value="High Net Worth Investor" /> High Net Worth Investor
								  <input type="checkbox" name="invester_type[]" value="Sophisticated Investor" /> Sophisticated Investor
								  <input type="checkbox" name="invester_type[]" value="Restricted Investor"  /> Restricted Investor
							    </div>
						    </div>
							<div class="form-group">
                                <label for="minimum-investment-amount"><?php _e('Minimum Investment','themeum') ?></label>
                                <div class="input-group currency-change">
                                    <input type="text" name="minimum-investment-amount" id="minimum-investment-amount" class="form-control" >
                                    <div class="input-group-addon fund-goal">
                                        <span id="min-inv-curr"><?php echo themeum_get_currency_code(); ?></span>
                                    </div>
                                </div>
                            </div>
							
							<div class="form-group"> 
								<label for="required-investment"><?php _e('Required Investment Increments','themeum') ?></label>
								<div class="input-group currency-change">
									<input type="text" name="required-investment" id="required-investment" class="form-control"  >
									<div class="input-group-addon fund-goal">
										<span id="req-inv-curr"><?php echo themeum_get_currency_code(); ?></span>
									</div>
								</div>
							  </div>

                            <div class="form-group has-feedback" id="equity_offered_field">
                                <label for="equityoffer"><?php _e('Equity offered','themeum') ?></label>
                                <input type="text" name="equityoffer" class="form-control" id="equityoffer">
                                <span class="glyphicon glyphicon-ok form-control-feedback equityoffer-color"></span>
                            </div>

                             <!--<div class="form-group">
                                <label><?php _e('Reward','themeum'); ?></label>
                                <div id="clone-form">
                                    <div class="auto-field">
                                        <label><?php _e('Range:','themeum'); ?></label>
                                        <input type="text" name="min1" placeholder="<?php _e('Minimum','themeum'); ?>" class="form-control min" ><i class="spce fa fa-minus"></i>
                                        <input type="text" name="max1" placeholder="<?php _e('Maximum','themeum'); ?>" class="form-control max" >
                                        <label class="reward-level"><?php _e('Reward1','themeum') ?></label>
                                        <textarea name="reward1" class="form-control"></textarea><hr/>
                                    </div>
                                </div>
                                <span id="add-more"><?php _e('Add More','themeum'); ?></span>
                            </div>-->
                            <input type="hidden" name="min1" value="50" />


                        </div>

                        <div class="project-submit-form form-hide wow fadeIn" data-wow-delay=".2s">

                            <div class="form-group has-feedback">
                                <label for="project-video"><?php _e('Video URL','themeum') ?></label>
                                <input type="text" name="video-url" class="form-control" placeholder="YouTube or Vimeo URL" id="project-video">
                                <span class="glyphicon glyphicon-ok form-control-feedback video-color"></span>
                            </div>
									 <div class="form-group has-feedback">
                                <label for="project-about"><?php _e('About the Project Creator','themeum') ?></label>
                                <textarea id="project-about" name="about"></textarea>
                                <span class="glyphicon glyphicon-ok form-control-feedback about-color"></span>
                            </div>
                            <div class="form-group">
                                <label><?php _e('Business Description','themeum') ?></label>
                                <div>
                                    <?php
                                    $editor_id = 'description';
                                    $content = '';
                                    wp_editor( $content, $editor_id );
                                    ?>
                                </div>
                            </div>

									<div class="form-group">
                                <label><?php _e('Overview','themeum') ?></label>
                                <div>
                                    <?php
                                    $editor_id = 'business_idea';
                                    $content = '';
                                    wp_editor( $content, $editor_id );
                                    ?>
                                </div>
                            </div>

                           <!--<div class="form-group">
                                <label><?php _e('Market','themeum') ?></label>
                                <div>
                                    <?php
                                    $editor_id = 'business_market';
                                    $content = '';
                                    //wp_editor( $content, $editor_id );
                                    ?>
                                </div>
                            </div>-->

									<!-- TEAM FIELDS  -->
									<div class="form-group" id="team_section">
                                <label><?php _e('Team','themeum') ?></label>
                                <div id="team_fields_section"></div>
                                <a href="javascript:void(0)" id="add_team_fields">Add Member</a>
                            </div>
                        </div>

                        <div class="project-submit-form form-hide wow fadeIn" data-wow-delay=".2s">
										<!-- Step 3 -->
                            <div class="team-field form-group form-upload-image">
                                <label for="team-image"><?php _e('Certificate of Incorporation','themeum') ?></label>
                                <input type="text" name="cert-inc" class="form-control cert_inc_url" id="cert-inc">
                                <input type="hidden" name="cert-inc-id" class="form-control cert_inc_id" id="cert-inc-id">
                                <input type="button" id="cert-inc-upload-file-button" class="cert-inc-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" />
                            </div>

                            <div class="team-field form-group form-upload-image">
                                <label for="team-image"><?php _e('Articles of Association','themeum') ?></label>
                                <input type="text" name="article-inc" class="form-control article_inc_url" id="article-inc">
                                <input type="hidden" name="article-inc-id" class="form-control article_inc_id" id="article-inc-id">
                                <input type="button" id="article-inc-upload-file-button" class="article-inc-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" />
                            </div>

                            <div class="team-field form-group form-upload-image">
                                <label for="team-image"><?php _e('Memorandum of Association','themeum') ?></label>
                                <input type="text" name="memorandum" class="form-control memorandum_url" id="memorandum">
                                <input type="hidden" name="memorandum-id" class="form-control memorandum_id" id="memorandum-id">
                                <input type="button" id="memorandum-upload-file-button" class="memorandum-upload" value="Upload File" data-url="<?php echo esc_url(get_site_url()); ?>" />
                            </div>

                            <hr/>
                            <div class="form-group" id="bank_file_section">
                                <label><?php _e('Bank Statements (last 3 months)','themeum') ?></label>
                                <div id="bank_files_section"></div>
                                <a href="javascript:void(0)" id="add_bank_file">Add More</a>
                            </div>

									 <hr/>

									 <div class="form-group" id="financial_file_section">
                                <label><?php _e('Financial Reports (last 3 years)','themeum') ?></label>
                                <div id="financial_files_section"></div>
                                <a href="javascript:void(0)" id="add_financial_file">Add More</a>
                            </div>

                            <div class="form-group" id="extra_file_section">
                                <label><?php _e('Extra Files','themeum') ?></label>
                                <div id="extra_files_section"></div>
                                <a href="javascript:void(0)" id="add_extra_file">Add More</a>
                            </div>
                        </div>
									 <input id="form_step" type="hidden" name="form_step" value='0' />
									 <input id="postID" type="hidden" name="postID" value='' />
                            <button id="back" class="btn btn-primary pull-left" type="button"><?php _e('Back','themeum') ?></button>
                            <button id="next" class="btn btn-primary pull-right" type="button"><?php _e('Save and continue','themeum') ?></button>
                            <button id="project-submit" class="btn btn-primary pull-right"><?php _e('Submit Business','themeum') ?></button>

                        </form>
</div>
								<?php }else{ // end of business new form ?>
									<div><p>You don't have permission to perform this action.</p></div>
								<?php } ?>


                    </div> <!-- end input form -->

                    <!-- startup sample -->
                    <?php if(isset($_GET['postid'])) { ?>

		                 <div id="popular-ideas" class="col-sm-4">
		                     <div class="ideas-item wow fadeIn">
		                         <div class="image">
		                             <figure>
		                                 <img src="<?php echo getProjectImageThumbSrc($_GET['postid']) ?>" class="img-responsive image-view" alt="">
		                                 <!--<figcaption>
		                                     <p>0%</p>
		                                     <p class="pull-left"><?php _e('Raised','themeum'); ?></p>
		                                    /*<ul class="list-unstyled list-inline rating">
		                                         <li><i class="fa fa-star"></i></li>
		                                         <li><i class="fa fa-star"></i></li>
		                                         <li><i class="fa fa-star"></i></li>
		                                         <li><i class="fa fa-star"></i></li>
		                                         <li><i class="fa fa-star"></i></li>
		                                     </ul> */
		                                 </figcaption> -->
		                             </figure>
		                         </div> <!-- end image -->

		                         <div class="clearfix"></div>

		                         <div class="details">
		                             <div class="country-name" id="auto-location"><?php echo $allMetas['thm_location'][0] ?></div>
		                             <h4 id="auto-title"><?php echo $project->post_title ?></h4>
		                             <div class="entry-meta">
		                                 <i class="fa fa-tags"></i> <span class="entry-food" id="auto-tag" ><?php echo isset($cat_terms[0]) ? $cat_terms[0]->name : 'No Category' ?></span>
		                                 <span class="entry-money"><i class="fa fa-money"></i> <?php _e('Total investment:','themeum'); ?> <strong id="auto-investment"><?php echo themeum_get_currency_symbol(); ?><?php echo $allMetas['thm_funding_goal'][0] ?></strong></span>
		                             </div>
		                             <div class="info" id="auto-description">
		                                 <p>
		                                    <?php echo $project->post_content ?>
		                                 </p>
		                             </div>
		                         </div>
		                     </div>
		                 </div> <!-- end startup sample -->
                    <?php }else{ ?>
                    		                    <div id="popular-ideas" class="col-sm-4">
                        <div class="ideas-item wow fadeIn">
                            <div class="image">
                                <figure>
                                    <img src="<?php echo  get_template_directory_uri(); ?>/images/preview.jpg" class="img-responsive image-view" alt="">
                                    <figcaption>
                                        <p>0%</p>
                                        <p class="pull-left"><?php _e('Raised','themeum'); ?></p>
                                       <!-- <ul class="list-unstyled list-inline rating">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                        </ul>-->
                                    </figcaption>
                                </figure>
                            </div> <!-- end image -->

                            <div class="clearfix"></div>

                            <div class="details">
                                <div class="country-name" id="auto-location"><?php _e('Location','themeum'); ?></div>
                                <h4 id="auto-title"><?php _e('Sample Title','themeum'); ?></h4>
                                <div class="entry-meta">
                                    <i class="fa fa-tags"></i> <span class="entry-food" id="auto-tag" > <?php _e('Finance','themeum'); ?></span>
                                    <span class="entry-money"><i class="fa fa-money"></i> <?php _e('Total investment:','themeum'); ?> <strong id="auto-investment"><?php echo themeum_get_currency_symbol(); ?>9800</strong></span>
                                </div>
                                <div class="info" id="auto-description">
                                    <p>
                                        <?php _e('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, repudiandae nisi in ea eaque ut dolorem obcaecati sit error quo facilis, officiis officia. Dignissimos fugiat, voluptatem, ipsa adipisci neque modi.','themeum'); ?>
                                    </p>
                                    <p>
                                        <?php _e('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, repudiandae nisi in ea eaque ut dolorem obcaecati sit error quo facilis, officiis officia. Dignissimos fugiat, voluptatem, ipsa adipisci neque modi.','themeum'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end startup sample -->
                    <?php } ?>
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
		  <?php }else{ ?>
		  		<div class="site_notice" style="padding-bottom: 25px;">This site does not have permission to create companies.</div>
		  <?php } ?>
			<?php }else{ // terms and conditions not agreed yet ?>
				<section id="project-form">
					<div class="container">
						<div class="row">
							<h3>Terms and Conditions</h3>

							<?php //echo "<pre>";
//print_r($_SERVER);
							?>
								<p style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif">Thank you for instructing The Right Crowd Limited to assist you in connection with the proposed fundraising project for the Company (the &ldquo;<span style="color:black"><strong>Project</strong></span>&rdquo;) subject to the Company being approved for listing as an investee member on The Right Crowd crowdfunding platform (&ldquo;<span style="color:black"><strong>Platform</strong></span>&rdquo;) by our executive board (&ldquo;<span style="color:black"><strong>Executive Board</strong></span>&rdquo;).&nbsp; This agreement (the &ldquo;<span style="color:black"><strong>Agreement</strong></span>&rdquo;) and our standard terms and conditions, which can be found at <a href="/terms-conditions/"><?php echo ($_SERVER['REQUEST_SCHEME'] ? $_SERVER['REQUEST_SCHEME'] : 'http').'://'.$_SERVER['HTTP_HOST']."/terms-conditions/" ?></a> (the &ldquo;<span style="color:black"><strong>Terms</strong></span>&rdquo;) set out the basis of our engagement by you (the &ldquo;<span style="color:black"><strong>Engagement</strong></span>&rdquo;).</span></span></span></p>


<p style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif">This Agreement incorporates the Terms.&nbsp; If there is any conflict between the terms of this Agreement and the Terms, the terms of this Agreement shall prevail. </span></span></span></p>



<p style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif">References in this Agreement to &ldquo;<strong>The Right Crowd</strong>&rdquo;, &ldquo;<span style="color:black"><strong>we</strong></span>&rdquo;, &ldquo;<span style="color:black"><strong>our</strong></span>&rdquo; and &ldquo;<span style="color:black"><strong>us</strong></span>&rdquo; shall mean The Right Crowd Limited.&nbsp; References in this Agreement to &ldquo;you&rdquo; shall mean the Company. </span></span></span></p>

<ol>
	<li><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span>Who are we? </span></span></span></span></span></li>
</ol>


<ol>
	<li style="list-style-type:none">
	<ol>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>We operate the website accessed via www.therightcrowd.com (the &ldquo;<strong>Site</strong>&rdquo;), through which members can seek or offer investment to other members of the Site (&ldquo;<strong>Members</strong>&rdquo;).&nbsp; </span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>The Right Crowd Limited is an appointed representative of Equity For Growth (Securities) Limited which is authorised and regulated by the Financial Conduct Authority with registration number 475953 .</span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>If you have any questions about the Site or anything set out in this Agreement or the Terms, please contact us by email to admin@therightcrowd.com.</span></span></span></span></span></span></li>
	</ol>
	</li>
	<li><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span>Executive board approval</span></span></span></span></span></li>
	<li><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span>Who are we engaged by?</span></span></span></span></span>
	<ol>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>We are engaged by the Company and will provide services in connection with the Project to the Company and no one else.&nbsp; </span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>By clicking the acceptance button at the end of this Agreement you will confirm your acceptance of the terms of this Agreement (&ldquo;<strong>Acceptance</strong>&rdquo; and &ldquo;<span style="color:black"><strong>Accepts</strong></span>&rdquo; shall be construed accordingly).</span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>You confirm that you, being the person who Accepts this Agreement, have the authority to bind the Company in relation to the Project in accordance with the Company&rsquo;s constitution.&nbsp;&nbsp; </span></span></span></span></span></span></li>
	</ol>
	</li>
	<li><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span>Period of engagement</span></span></span></span></span>
	<ol>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>Each company wishing to join the Platform as an investee company will be reviewed by our Executive Board.</span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>The Executive Board shall determine in their sole discretion whether the each applicant company is suitable for the Platform and their decision shall be binding on the applicant company.</span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>Our Engagement starts on the date of that you are notified by us that you have been accepted as an investee company on the Platform (the &ldquo;<span style="color:black"><strong>Commencement Date</strong></span>&rdquo;).&nbsp; Other than specifically set out in this Agreement, our Engagement shall terminate on completion of the Project.</span></span></span></span></span></span></li>
	</ol>
	</li>
	<li><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span>CLIENT CLASSIFICATION</span></span></span></span></span>
	<ol>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>The Company acknowledges that all services provided by us under this Agreement are subject to the FCA Rules.&nbsp; </span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>In providing its services, we have designated the Company as a Professional Client as defined in the FCA Rules. By being designated as a Professional Client, the Company does not have the benefit of certain protections otherwise available to a Retail Client.</span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>The Company has the right to request a different client categorisation. If the Company requests categorisation as an eligible counterparty and we agree to such categorisation, we would no longer be required by the applicable regulatory system to provide certain protections usually granted to Professional Clients. If the Company requests to be categorised as a Retail Client, thereby acquiring a higher level of regulatory protection, we would not be able to provide services to the Company on the terms of this Agreement. The Company agrees and acknowledges that it is responsible for keeping us informed about any change that could affect the Company&rsquo;s classification as a Professional Client.</span></span></span></span></span></span></li>
	</ol>
	</li>
	<li><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span>The Project and scope of services</span></span></span></span></span></li>
</ol>

<ol start="6">
	<li style="list-style-type:none">
	<ol>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>We will provide the services set out in Schedule 2 in relation to the Project (the &ldquo;<strong>Services</strong>&rdquo;). </span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>We shall not be obliged to provide to you any services other than the Services unless we agree in writing to do so.&nbsp; We shall not provide additional services without agreeing with you the additional fees applicable to our provision of the additional services. </span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>We will not: </span></span></span></span></span></span>
		<ol style="list-style-type:lower-alpha">
			<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>provide any advice to you in relation to the Offer (as defined below);</span></span></span></span></span></li>
			<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>provide any commercial, accounting, taxation or legal advice to you;</span></span></span></span></span></li>
			<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>provide any valuations;</span></span></span></span></span></li>
			<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>advise you on any personal or corporate tax matters; and/or</span></span></span></span></span></li>
			<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>advise any other party to the Offer in relation to their involvement with you.</span></span></span></span></span></li>
		</ol>
		</li>
	</ol>
	</li>
	<li><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span>The Offer</span></span></span></span></span>
	<ol>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>We have agreed the terms on which the Company is seeking an offer for investment (the &ldquo;<strong>Offer</strong>&rdquo;).&nbsp; We have set out details of those terms (the &ldquo;<strong>Offer Terms</strong>&rdquo;) in Schedule 1 attached hereto.&nbsp; </span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>Subject to you being approved as an investee company by our Executive Board, we will upload the Offer Terms onto the Site and the Offer Terms will be made available for review on the Site to all Members classified as &ldquo;Investor Members&rdquo;. Once the Offer Terms have been uploaded to the Site, they cannot be amended. </span></span></span></span></span></span></li>
	</ol>
	</li>
	<li><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span>Our Fees </span></span></span></span></span>
	<ol>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>In consideration for the provision of the Services, you agree to pay to us the Fee (as defined below).</span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>Our fee (the &ldquo;<strong>Fee</strong>&rdquo;) is contingent on you receiving a Successful Investment (as defined in the Terms).</span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>Upon you receiving a Successful Investment, the following fees shall become immediately payable from you to us: </span></span></span></span></span></span>
		<ol style="list-style-type:lower-alpha">
			<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>4.5% of the aggregate investment received by the Company pursuant to the Offer (the &ldquo;<span style="color:black"><strong>Total Investment</strong></span>&rdquo;);</span></span></span></span></span></li>
			<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>the grant or issue to us (or to a person nominated by us) of either:</span></span></span></span></span>
			<ol style="list-style-type:lower-roman">
				<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>warrants to subscribe for Shares (as defined in paragraph 8.5 below) at par value per Share for a to be determined number of Shares in the Company equal to 4.5% of the Total Investment; or </span></span></span></span></span></li>
				<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>a to be determined number of Shares in the Company equal to 4.5% of the Total Investment; or</span></span></span></span></span></li>
				<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>a mixture of Shares and warrants in the Company provided that the aggregate number of Shares issued and warrants granted do not exceed do not exceed a value equal to 4.5% of the Total Investment.</span></span></span></span></span></li>
			</ol>
			</li>
		</ol>
		</li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>You agree and accept that where warrants or Shares are to be granted or issued pursuant to paragraph 8.2(b) above, we shall have the right to decide in our sole discretion:</span></span></span></span></span></span>
		<ol style="list-style-type:lower-alpha">
			<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>whether we wish to be issued Shares or to receive warrants or to receive a mixture of both; and</span></span></span></span></span></li>
			<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>to nominate the person(s) to whom those shares and warrants are to be issued or granted.</span></span></span></span></span></li>
		</ol>
		</li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>In this agreement &ldquo;<span style="color:black"><strong>Shares</strong></span>&rdquo; means shares of the class of shares in the Company which is being offered to the investors as part of the fundraise on the Platform.</span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>Full details of the calculation of the Fee and our payment terms are set out in the Terms. </span></span></span></span></span></span></li>
	</ol>
	</li>
	<li><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span>limitation of liability </span></span></span></span></span>
	<ol>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>We would draw your attention to paragraph 8 of our Terms (Limitation of Liability) that sets out the basis on which we limit our liability to you and to others.&nbsp; </span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>Our liability, as outlined at paragraph 8 of our Terms, is limited to lower of the amount invested and &pound;100,000.&nbsp; For the avoidance of doubt, our maximum liability to you is the amount which you have invested subject to a cap of &pound;100,000. </span></span></span></span></span></span></li>
	</ol>
	</li>
	<li><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span>Termination</span></span></span></span></span></li>
</ol>

<p style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif">Without affecting any other right or remedy available to us, we may terminate our Engagement with immediate effect on giving written notice to you, if:</span></span></span></p>

<ol start="10">
	<li style="list-style-type:none">
	<ol>
		<li style="list-style-type:none">
		<ol style="list-style-type:lower-alpha">
			<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>you commit a material breach of this Agreement which is irremediable or (if such breach is remediable) you fail to remedy that breach within 20 days after being notified by us to do so; and</span></span></span></span></span></li>
			<li style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>the Company, suspends or threatens to suspend payment of its debts; it is unable to pay its debts as they all due; it admits inability to pay its debts or is deemed unable to pay its debts within the meaning of section 123 of the Insolvency Act 1986; it commences negotiations with all or any class of its creditors with a view to rescheduling any of its debts; or a petition is filed, notice given, a resolution passed or an order made for or in connection with its winding up.</span></span></span></span></span></li>
		</ol>
		</li>
	</ol>
	</li>
	<li><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span>COMMUNICATIONS BETWEEN US </span></span></span></span></span>
	<ol>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>&nbsp;Any notice given by you to us, or by us to you, will be deemed received and properly served at the time of transmission, if sent by e-mail or three days after the date of posting of any letter. In p<span style="font-family:Arial,sans-serif"><span style="color:black">roving the service of any notice, it will be sufficient to prove, in the case of a letter, that such letter was properly addressed, stamped and placed in the post and, in the case of an e-mail, that such e-mail was sent to the specified e-mail address of the addressee.</span></span></span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>A reference to writing or written in this Agreement and/or in the Terms, includes email. </span></span></span></span></span></span></li>
	</ol>
	</li>
	<li><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span>Your agreement</span></span></span></span></span>
	<ol>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>As confirmation that you agree to and accept the terms of this Agreement and the Terms and their application in relation to the Project, please click the Acceptance button at the end of this Agreement.&nbsp; Acceptance of the Agreement (incorporating the Terms) will create a legally binding agreement between you and us on the terms set out in this Agreement and the Terms.&nbsp; In any event, your continuing instructions or use of the Site for the Project in relation to the Project will amount to your acceptance of the terms of this Agreement (incorporating the Terms). </span></span></span></span></span></span></li>
		<li style="text-align:justify"><span style="font-size:11pt"><span><span><span style="font-family:Arial,sans-serif"><span style="color:black"><span>We will send you acknowledgment of receipt of your Acceptance by email. </span></span></span></span></span></span></li>
	</ol>
	</li>
</ol>

<p style="text-align:justify"><span style="font-size:11pt"><span><span style="font-family:Arial,sans-serif"><span style="color:black"><strong>ACCEPTANCE<br />
<br />
<input type="checkbox" name="tandc" value='yes' id="tandc" /> I accept the terms and conditions.</strong></span> </span></span></span></p>

<p>
<button id="terms-and-conditions" class="btn btn-primary pull-right" type="button"><?php _e('Accept and continue','themeum') ?></button>
</p>

						</div>
					</div>
				</section>
			<?php } ?>

    </div> <!--/#content-->
</section> <!--/#main-->
<?php get_footer(); ?>
<script>
team_order = 1;
bank_file_order = 1;
financial_file_order = 1;
extra_file_order = 1;

jQuery(function($){

	// default 
	if($(".investment-types-offered input[type=checkbox][value=isa]").is(':checked')){
		$("#isa_bank_details").show();
	}
	
	$('#team_section').on('click','#add_team_fields',function(){
		var teamFieldsHtml = $('#team_fields').html();
		teamFieldsHtml = teamFieldsHtml.replace(/{{order_number}}/g, team_order);
		$('#team_fields_section').append(teamFieldsHtml);
		team_order++;
	});

	// remove team member
	$('#team_section').on('click','.remove-team-member',function(){
		var orderNo = $(this).attr('data-order');
		$('#'+orderNo).remove();
	});

	$('#bank_file_section').on('click','#add_bank_file',function(){
		var bankFileHtml = $('#bank_file_field').html();
		bankFileHtml = bankFileHtml.replace(/{{order_number}}/g, bank_file_order);
		$('#bank_files_section').append(bankFileHtml);
		bank_file_order++;
	});
	$('#financial_file_section').on('click','#add_financial_file',function(){
		var financialFileHtml = $('#financial_file_field').html();
		financialFileHtml = financialFileHtml.replace(/{{order_number}}/g, financial_file_order);
		$('#financial_files_section').append(financialFileHtml);
		financial_file_order++;
	});

	$('#extra_file_section').on('click','#add_extra_file',function(){
		var extraFileHtml = $('#extra_file_field').html();
		extraFileHtml = extraFileHtml.replace(/{{order_number}}/g, extra_file_order);
		$('#extra_files_section').append(extraFileHtml);
		extra_file_order++;
	});

	$('body').on('change','#investment-type',function(){
		var InvT = $(this).val();
		if(InvT == 'bond' || InvT == 'loan' || InvT == 'fund') {
			/*var $option = $("<option/>", {
			 value: '',
			 text: 'N/A',
			 selected: true
		  });
		  $("#duration").prepend($option);*/

		  // add bond related fields
		  $('#marketing_allowance_field').show();
		  $('#coupon_field').show();
		  $('#coupon_paid_field').show();
		  $('#bond_expiry_field').show();
		  $('#equity_offered_field').hide(); // hide equity offered field
		}else{
		  $('#marketing_allowance_field').show();
		  $('#coupon_field').hide();
		  $('#coupon_paid_field').hide();
		  $('#bond_expiry_field').hide();
		  $('#equity_offered_field').show();
		  //$("#duration > option[value='']").remove();
		}
	});

	// submit terms and conditions
	$("body").on('click','#terms-and-conditions',function(){
		if($('#tandc').is(':checked')) {
			$.post({
			  url: "<?php echo 'https://'.$_SERVER['HTTP_HOST'].'/wp-admin/admin-ajax.php' //admin_url('admin-ajax.php'); ?>",
			  data: {	//'https://opes.conquestplanner.com/wp-admin/admin-ajax.php'; //
			  	action: "save_terms_conditions",
			  	value: 'agreed'
			  },
			  success: function(response){
			  	var res = JSON.parse(response);
			  	if(res.status === "true") {
					location.reload(true);
			  	}
			  }
			});
		}else{
			alert("Please accept the terms and conditions to continue.");
		}
	});
	
	var cv = $("#goal_currency").val();
	$("#min-inv-curr").html(cv);
	$("#req-inv-curr").html(cv);
	
	$('body').on('change','#goal_currency',function(){
		var v = $(this).val();
		$("#min-inv-curr").html(v);
		$("#req-inv-curr").html(v);
	});
	
	$('body').on('change','.investment-types-offered input[type=checkbox]',function(){
		var v = $(this).val();
		if(v === 'isa') {
			$("#isa_bank_details").toggle();
		}
	});
	
});

</script>
<style>
	#marketing_allowance_field,
	#coupon_field,
	#coupon_paid_field,
	#bond_expiry_field,
	#isa_bank_details {
		display: none;
	}
	.input-group input[type='checkbox'] {
		width: 15px !important;
    margin-top: 10px;
    vertical-align: -3px;
    height: 16px !important;
	}
	#price-per-unit .input-group-addon,
	#investment-target .input-group-addon {
		padding: 0;
		border: 0;
	}
	#goal_currency,
	#share_currency {
		width: 100px;
		height: 41px;
		margin-left: 5px;
	}
	
	
	
	.bank_details_fields input[type='text'] {
	margin-bottom: 6px !important;
	}
</style>
<?php restore_current_blog(); ?>
