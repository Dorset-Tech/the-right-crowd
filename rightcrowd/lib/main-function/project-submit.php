<?php 
 function new_project_add() {

                $title = $attachment_id = $project_category = $user_id = $project_tag = $project_type = $start_date = $end_date = $location = $investment_amount = $video_url = $description = $sub_image_1 = $sub_image_2 = $sub_description_1 = $sub_description_2 = $about = $banner_image_id = $business_idea = $business_market = '';
                $website = '';
				$minimumInvestment = ''; //0.50; // stripe's minimum limit
				$requiredInvestment = '';
				
                $currentSiteID = get_current_blog_id();
			
					 $metaKey = 'display_business_'.$currentSiteID;
					 $isManager = isManager();
					 /*$siteType = get_option('site_type');
					$parent = (int) get_option('parent_site');
					if($siteType == 'child' && $parent) {
					 global $switched;
					 switch_to_blog($parent);
					}elseif($siteType == 'master') {
						global $switched;
						 switch_to_blog(1);
					}*/
					$user_id = get_current_user_id();
		      
					 
                if(isset($_POST['project-category'])){ $project_category = $_POST['project-category']; }
                if(isset($_POST['project-category-2'])){ $project_category_2 = $_POST['project-category-2']; }
                if(isset($_POST['project-category-3'])){ $project_category_3 = $_POST['project-category-3']; }
                if(isset($_POST['project-tag'])){ $project_tag = $_POST['project-tag']; }
                if(isset($_POST['project-title'])){ $title = $_POST['project-title']; }
                if(isset($_POST['project-image-id'])){ $attachment_id = $_POST['project-image-id']; }
                if(isset($_POST['project-type'])){ $project_type = $_POST['project-type']; }
                if(isset($_POST['start-date'])){ $start_date = $_POST['start-date']; }
                if(isset($_POST['end-date'])){ $end_date = $_POST['end-date']; }
                if(isset($_POST['location'])){ $location = $_POST['location']; }
                if(isset($_POST['investment-amount'])){ $investment_amount = $_POST['investment-amount']; }
                if(isset($_POST['video-url'])){ $video_url = $_POST['video-url']; }
                if(isset($_POST['description'])){ $description = esc_attr($_POST['description']); }
                if(isset($_POST['business_idea'])){ $business_idea = $_POST['business_idea']; }
                //if(isset($_POST['business_market'])){ $business_market = $_POST['business_market']; }
                if(isset($_POST['brandname'])){ $brandname = $_POST['brandname']; }
                if(isset($_POST['incorpdate'])){ $incorpdate = $_POST['incorpdate']; }
                if(isset($_POST['coregno'])){ $coregno = $_POST['coregno']; }
                if(isset($_POST['duration'])){ 
                		$duration = $_POST['duration']; 
                	}else{ 
                		$duration = ''; 
                	}
                if(isset($_POST['equityoffer'])){ $equityoffer = $_POST['equityoffer']; }
                if(isset($_POST['investment-type'])){ $investmentType = $_POST['investment-type']; }
                if(isset($_POST['marketing_allowance'])){ $marketing = $_POST['marketing_allowance']; }
                if(isset($_POST['coupon'])){ $coupon = $_POST['coupon']; }
				if(isset($_POST['coupon_paid'])){ $coupon_paid = $_POST['coupon_paid']; }
                if(isset($_POST['bond_expiry'])){ $bondExpiry = $_POST['bond_expiry']; }
                if(isset($_POST['private-listing'])){ $privateListing = $_POST['private-listing']; }
				if(isset($_POST['website_url'])){ $website = $_POST['website_url']; }
				if(isset($_POST['minimum-investment-amount'])){ $minimumInvestment = $_POST['minimum-investment-amount']; }
				if(isset($_POST['required-investment'])){ $requiredInvestment = $_POST['required-investment']; }
				$investingInto = array();
				if(isset($_POST['investing_into'])){ $investingInto = $_POST['investing_into']; }
				
				$investerType = array();
				if(isset($_POST['invester_type'])){ $investerType = $_POST['invester_type']; }
				
				// set default to 1
				$share_price = 1;
				if(isset($_POST['share_price'])){ $share_price = $_POST['share_price']; }

// TEAM members output to save in meta
$teamOutput = [];
if(isset($_POST['team_member_title'])) {
	foreach($_POST['team_member_title'] as $orderKey => $value) {
		$teamOutput[$orderKey] = array(
			'title' => $value,
			'name' => isset($_POST['team_member_name'][$orderKey]) ? $_POST['team_member_name'][$orderKey] : '',
			'bio' => isset($_POST['team_member_bio'][$orderKey]) ? $_POST['team_member_bio'][$orderKey] : '',
			'image' => isset($_POST['team-image-id'][$orderKey]) ? $_POST['team-image-id'][$orderKey] : 0
		);
	}
}
$teamMembers = serialize($teamOutput); 

// bank statements serialization
$bankOutput = [];
if(isset($_POST['bank-file-id'])) {
	foreach($_POST['bank-file-id'] as $orderKey => $value) {
		if($value != '' && $value > 0) {
			$bankOutput[] = $value;
		}
	}
}
//$bankFiles = count($bankOutput) > 0 ? serialize($bankOutput) : '';

// financial reports serialization
$financialOutput = [];
if(isset($_POST['financial-file-id'])) {
	foreach($_POST['financial-file-id'] as $orderKey => $value) {
		if($value != '' && $value > 0) {
			$financialOutput[] = $value;
		}
	}
}
//$financialFiles = count($financialOutput) > 0 ? serialize($financialOutput) : '';

$extraOutput = [];
$extraTitleOutput = [];
if(isset($_POST['extra-file-id'])) {
	foreach($_POST['extra-file-id'] as $orderKey => $value) {
		if($value != '' && $value > 0) {
			$extraOutput[] = $value;
			if(isset($_POST['extra-file-title'][$orderKey]) && $_POST['extra-file-title'][$orderKey] != '') {
				$extraTitleOutput[$value] = $_POST['extra-file-title'][$orderKey];
			}
		}
	}
}

                $output = array();
                for ($i=1; $i <=50 ; $i++) { 
                    if( isset( $_POST['min'.$i] ) && isset( $_POST['max'.$i] ) && isset( $_POST['reward'.$i] ) ){
                        $arr = '';
                        $arr['themeum_min'] = $_POST['min'.$i];
                        $arr['themeum_max'] = $_POST['max'.$i];
                        $arr['themeum_reward_data'] = $_POST['reward'.$i];
                        $output[] = $arr;
                    }
                }

                if(isset($_POST['about'])){ $about = $_POST['about']; }
                if(isset($_POST['banner-image-id'])){ $banner_image_id = $_POST['banner-image-id']; }

                $cat = explode(' ',$project_category );
                
                $tag = explode(',',$project_tag );

                if( $title != "" ){
                    if( is_numeric( $investment_amount ) ){
                    	global $switched;
						switch_to_blog(1); // company saved to master site (main site)	
						
                        /*if( $start_date != "" ){
                            if( $end_date != "" ){*/
                           	  $postStatus = 'draft';
                           	  if($_POST['form_step'] == 2) {
                           	  	$postStatus = 'pending';
                           	  }
							 
                            	  if($_POST['form_step'] == 0 && $_POST['postID'] == '') {	 
		                             $post = array(
		                               'post_type'           => 'project',
		                               'post_title'          => $title,
		                               'post_content'        => $description,
		                               'post_status'         => $postStatus,
		                               'post_author'         => $user_id,
		                             );
		                             $post_id = wp_insert_post( $post );
                                }else{
                                	  $post_id = (int) $_POST['postID'];	
                                	  $post = array(
                                	  	 'ID' => $post_id,
		                               'post_title'          => $title,
		                               'post_content'        => $description,
		                               'post_status' => $postStatus
		                             );
		                             wp_update_post( $post );
                                }
								switch_to_blog(1);
								
                                if(isset($project_category_2)) {	
											 	$cat2 = explode(' ',$project_category_2 );
											 	wp_set_object_terms( $post_id , $cat2, 'project_category',true );
											 }
											 if(isset($project_category_3)) {	
											 	$cat3 = explode(' ',$project_category_3 );
											 	wp_set_object_terms( $post_id , $cat3, 'project_category',true );
											 }
                                wp_set_object_terms( $post_id , $cat, 'project_category',true );
                                wp_set_object_terms( $post_id , $tag, 'project_tag',true );
                                
                                if(is_int($post_id)){
                                			 // calculating start date and end date
                                			 if($duration != ''){ 
                                			 	$durationToConvert = "P$duration"."D";
                                			 	$date = new DateTime(); // Y-m-d
                                			 	$startDate = $date->format('Y-m-d');
													 	$date->add(new DateInterval($durationToConvert));
													 	$endDate = $date->format('Y-m-d');
													 }else{
													 	$startDate = '';
													 	$endDate = '';
													 }
										//echo "here before - ".get_current_blog_id();
										if(isset($attachment_id) && $attachment_id && $attachment_id != '') {
											
                                        	update_post_meta($post_id, '_thumbnail_id', esc_attr($attachment_id));
                                        }
										
                                        update_post_meta($post_id, 'thm_type', esc_attr($project_type));
                                        update_post_meta($post_id, 'thm_start_date', $startDate);
                                        update_post_meta($post_id, 'thm_end_date', $endDate);
                                        update_post_meta($post_id, 'thm_location', esc_attr($location));
                                        update_post_meta($post_id, 'thm_brandname', esc_attr($brandname));
                                        update_post_meta($post_id, 'thm_incorpdate', esc_attr($incorpdate));
                                        update_post_meta($post_id, 'thm_coregno', esc_attr($coregno));
										$metaKeyOrder = 'project_order_'.$currentSiteID;
										update_post_meta($post_id, $metaKeyOrder, '0');
                                        if($investmentType == 'equity') {
                                        	update_post_meta($post_id, 'thm_equityoffer', esc_attr($equityoffer));
                                        }
                                        
                                        update_post_meta($post_id, 'thm_duration', esc_attr($duration));
                                        update_post_meta($post_id, 'thm_funding_goal', esc_attr($investment_amount));
										update_post_meta($post_id, 'thm_minimum_investment', esc_attr($minimumInvestment));
										update_post_meta($post_id, 'thm_required_investment', esc_attr($requiredInvestment));
                                        update_post_meta($post_id, 'thm_video_url', esc_url($video_url));
                                        update_post_meta($post_id, 'themeum_reward', maybe_unserialize(serialize($output)) );
                                        update_post_meta($post_id, 'thm_about', esc_html($about));
													 if(isset($banner_image_id) && $banner_image_id && $banner_image_id != '') {
                                        	update_post_meta($post_id, 'thm_subtitle_images', esc_attr($banner_image_id));
                                        }
                                        update_post_meta($post_id, 'thm_percentage', get_option('donate_page_percentage'));
                                        update_post_meta($post_id, 'thm_business_idea', $business_idea);
                                        //update_post_meta($post_id, 'thm_business_market', $business_market);
										
										// update bank details
										/*
										<input placeholder="Name on Account" type="text" name="bank_details_name" id="bank_details_name"  class="form-control" >
			                           <input placeholder="Bank Name" type="text" name="bank_details_bank_name" id="bank_details_bank_name"  class="form-control" >
			                           <input placeholder="Sort Code" type="text" name="bank_details_sort_code" id="bank_details_sort_code"  class="form-control" >
			                           <input placeholder="Account Number" type="text" name="bank_details_account" id="bank_details_account"  class="form-control" >
			                           <input placeholder="IBAN" type="text" name="bank_details_iban" id="bank_details_iban"  class="form-control" >
			                           <input placeholder="Swift/Bic" type="text" name="bank_details_swift" id="bank_details_swift"  class="form-control" >
										*/
										
										update_post_meta($post_id,'bank_details_name',esc_attr($_POST['bank_details_name']));
										update_post_meta($post_id,'bank_details_bank_name',esc_attr($_POST['bank_details_bank_name']));
										update_post_meta($post_id,'bank_details_sort_code',esc_attr($_POST['bank_details_sort_code']));
										update_post_meta($post_id,'bank_details_account',esc_attr($_POST['bank_details_account']));
										update_post_meta($post_id,'bank_details_iban',esc_attr($_POST['bank_details_iban']));
										update_post_meta($post_id,'bank_details_swift',esc_attr($_POST['bank_details_swift']));

										update_post_meta($post_id,'isa_bank_details_name',esc_attr($_POST['isa_bank_details_name']));
										update_post_meta($post_id,'isa_bank_details_bank_name',esc_attr($_POST['isa_bank_details_bank_name']));
										update_post_meta($post_id,'isa_bank_details_sort_code',esc_attr($_POST['isa_bank_details_sort_code']));
										update_post_meta($post_id,'isa_bank_details_account',esc_attr($_POST['isa_bank_details_account']));
										update_post_meta($post_id,'isa_bank_details_iban',esc_attr($_POST['isa_bank_details_iban']));
										update_post_meta($post_id,'isa_bank_details_swift',esc_attr($_POST['isa_bank_details_swift']));										
										
                                        if(count($teamOutput) > 0) {
											$oldT = get_post_meta($post_id,'thm_team_members',true);
											if($oldT != '') {
												$oldTeamMembers = unserialize($oldT);
												$newTeamMembers = $teamOutput; //unserialize($teamMembers);
												$teamM = array_merge($oldTeamMembers,$newTeamMembers);
											}else{
												$teamM = $teamOutput;
											}
											$teamM = serialize($teamM);
											delete_post_meta($post_id,'thm_team_members');
                                        	update_post_meta($post_id, 'thm_team_members', $teamM);
                                        }
                                        if($_POST['cert-inc-id'] != '' && $_POST['cert-inc-id'] != 0) {
                                        	update_post_meta($post_id, 'thm_certificate', (int) esc_attr($_POST['cert-inc-id']));
                                        }
                                        if($_POST['article-inc-id'] != '' && $_POST['article-inc-id'] != 0) {
                                        	update_post_meta($post_id, 'thm_article', (int) esc_attr($_POST['article-inc-id']));
                                        }
                                        if($_POST['memorandum-id'] != '' && $_POST['memorandum-id'] != 0) {
                                        	update_post_meta($post_id, 'thm_memorandum', (int) esc_attr($_POST['memorandum-id']));
                                        }
                                        update_post_meta($post_id, 'form_step', (int) esc_attr($_POST['form_step']));
										update_post_meta($post_id, 'thm_marketing', esc_attr($marketing));
                                        update_post_meta($post_id, 'thm_investment_type', esc_attr($investmentType));
										update_post_meta($post_id, 'thm_share_price', esc_attr($share_price));
										
										if(esc_attr($_POST['share_currency']) != '') {
											$share_currency = esc_attr($_POST['share_currency']);
										}else{
											$share_currency = 'GBP';
										}
										update_post_meta($post_id, 'share_currency', $share_currency);
										
										if(esc_attr($_POST['goal_currency']) != '') {
											$goal_currency = esc_attr($_POST['goal_currency']);
										}else{
											$goal_currency = 'GBP';
										}
										update_post_meta($post_id, 'goal_currency', $goal_currency);
										
										update_post_meta($post_id, 'thm_investing_into', $investingInto);
										
										delete_post_meta($post_id,'thm_investing_into');
										if(count($investingInto) > 0) {
											foreach($investingInto as $inTo) { 
												add_post_meta($post_id, 'thm_investing_into', $inTo);
											}
										}
										
										delete_post_meta($post_id,'thm_invester_type');
										if(count($investerType) > 0) {
											foreach($investerType as $it) { 
												add_post_meta($post_id, 'thm_invester_type', $it);
											}
										}
                                        if($investmentType == 'bond' || $investmentType == 'loan') {
                                        	update_post_meta($post_id, 'thm_coupon', esc_attr($coupon));
                                        	update_post_meta($post_id, 'thm_bond_expiry', esc_attr($bondExpiry));
                                        }
										update_post_meta($post_id, 'thm_coupon_paid', esc_attr($coupon_paid));
                                        update_post_meta($post_id, 'thm_site_id', esc_attr($currentSiteID));
                                        update_post_meta($post_id, 'fromparent', 'no');
                                        update_post_meta($post_id, 'thm_private_listing', $privateListing);
										update_post_meta($post_id, 'thm_website_url', esc_url($website));
                                        if($privateListing == 'yes') {
                                        	update_post_meta($post_id,$metaKey,'no');
                                        }else{
                                        	update_post_meta($post_id,$metaKey,'yes');
                                        }
                                        //  bank statements saving
                                        if(count($bankOutput) > 0) {
                                        	
                                        		$oldBanks = get_post_meta($post_id, 'thm_bank_statements', true);
												if($oldBanks != '' && count($oldBanks) > 0) {
													$bankOutput = array_merge($oldBanks,$bankOutput);
												}
                                        		
                                        		update_post_meta($post_id, 'thm_bank_statements', $bankOutput);
                                        	
                                        }	
                                        
                                        //  financial report files 
                                        if(count($financialOutput) > 0) {
												$oldFinancials = get_post_meta($post_id,'thm_extra_files',true);
												if($oldFinancials != '' && count($oldFinancials) > 0) {
													$financialOutput = array_merge($oldFinancials,$financialOutput);
												}
                                        		update_post_meta($post_id, 'thm_financial_reports', $financialOutput);
                                        }
                                        
                                        //  extra files 
                                        if(count($extraOutput) > 0) {
												$oldExtra = get_post_meta($post_id,'thm_extra_files',true);
												if($oldExtra != '' && count($oldExtra) > 0) {
													$extraOutput = array_merge($oldExtra,$extraOutput);
												}
                                        		update_post_meta($post_id, 'thm_extra_files', $extraOutput);
                                        		if(count($extraTitleOutput) > 0 ) {
		                                     		foreach($extraTitleOutput as $fileID => $titleVal) {
		                                     			$titleMetaKey = "extra_file_title_".$fileID; 	
		                                     			update_post_meta($post_id,$titleMetaKey,$titleVal);
		                                     		} 
                                        		}
                                        }
                                       //echo "here at end - ".get_current_blog_id(); 
                                     $returnArr = array(
                                     	'post_id' => $post_id
                                     );   
                                     echo json_encode($returnArr);   
                                     exit;
                                }
                            /*}
                        }*/
                    }
                }
		//restore_current_blog();
		//switch_to_blog($currentSiteID);

    }

add_action('wp_ajax_new_project_add', 'new_project_add');
add_action('wp_ajax_nopriv_new_project_add', 'new_project_add');  


// MAIN PROJECT ADDITION WITH SHARES

 function new_main_project_add() {

                $title = $attachment_id = $project_category = $user_id = $project_tag = $project_type = $start_date = $end_date = $location = $investment_amount = $video_url = $description = $sub_image_1 = $sub_image_2 = $sub_description_1 = $sub_description_2 = $about = $banner_image_id = $business_idea = $business_market = $isin = '';
                $user_id = get_current_user_id();

                if(isset($_POST['project-category'])){ $project_category = $_POST['project-category']; }
                if(isset($_POST['project-title'])){ $title = $_POST['project-title']; }
                if(isset($_POST['project-image-id'])){ $attachment_id = $_POST['project-image-id']; }
                if(isset($_POST['project-type'])){ $project_type = $_POST['project-type']; }
                if(isset($_POST['location'])){ $location = $_POST['location']; }                
                if(isset($_POST['description'])){ $description = esc_attr($_POST['description']); }
                if(isset($_POST['coregno'])){ $coregno = $_POST['coregno']; }
				if(isset($_POST['isin'])){ $isin = $_POST['isin']; }
                if(isset($_POST['cowebsite'])){ $cowebsite = $_POST['cowebsite']; }
                if(isset($_POST['banner-image-id'])){ $banner_image_id = $_POST['banner-image-id']; }
                
                if(isset($_POST['email'])){ $u_email = $_POST['email']; }
                if(isset($_POST['telephone'])){ $telephone = $_POST['telephone']; }

                if(isset($_POST['pre_emption'])){ $pre_emption = $_POST['pre_emption']; }else{ $pre_emption = 0;}
					 if(isset($_POST['offering-structure'])){ $offering = $_POST['offering-structure']; }else{ $offering = 'ordinary';}	
//$sharesOutput = serialize($sharesOutput); 
/*echo "<pre>";
print_r($_FILES);
print_r($_POST);
exit;*/
                $cat = explode(' ',$project_category );
                
                if( $title != "" ){
                  	  if($_POST['form_step'] == 0 && $_POST['postID'] == '') {	 
                          $post = array(
                            'post_type'           => 'project',
                            'post_title'          => $title,
                            'post_content'        => $description,
                            'post_status'         => 'pending',
                            'post_author'         => $user_id,
                          );
                          $post_id = wp_insert_post( $post );
                          // send company creation email notification to owner of company
                          $u = get_userdata($user_id);
                          wp_send_email_on_new_company(array('to' => $u->user_email));
                          wp_send_admin_email_on_new_company(array('subject' => "$title has registered."));
                          
                          // make trade status to closed by default
                          update_post_meta($post_id,'tradestatus','closed');
                       }else{
                       	  $post_id = (int) $_POST['postID'];	
                       	  $post = array(
                       	  	 'ID' => $post_id,
                            'post_title'          => $title,
                            'post_content'        => $description
                          );
                          wp_update_post( $post );
                       }
                       
                       wp_set_object_terms( $post_id , $cat, 'project_category',true );
                       
                       if(is_int($post_id)){
                       			 if(isset($attachment_id) && $attachment_id > 0) {	
                               	update_post_meta($post_id, '_thumbnail_id', esc_attr($attachment_id));
                               }
                               update_post_meta($post_id, 'thm_type', esc_attr($project_type));
                               update_post_meta($post_id, 'thm_location', esc_attr($location));
                               update_post_meta($post_id, 'thm_coregno', esc_attr($coregno));
							   update_post_meta($post_id, 'thm_isin', esc_attr($isin));
                               update_post_meta($post_id, 'thm_cowebsite', esc_attr($cowebsite));
                               update_post_meta($post_id, 'thm_pre_emption', esc_attr($pre_emption));	
                               update_post_meta($post_id, 'thm_offering', esc_attr($offering));
                               
                               update_post_meta($post_id, 'thm_email', esc_attr($u_email));
                               update_post_meta($post_id, 'thm_telephone', esc_attr($telephone));
                               
                               if(isset($banner_image_id) && $banner_image_id > 0) {	
                               	update_post_meta($post_id, 'thm_subtitle_images', esc_attr($banner_image_id));
                               }
                               if(isset($_POST['cert-inc-id']) && $_POST['cert-inc-id'] > 0) {	
                               	update_post_meta($post_id, 'thm_certificate', (int) esc_attr($_POST['cert-inc-id']));
                               }
                               if(isset($_POST['article-inc-id']) && $_POST['article-inc-id'] > 0) {	
                               	update_post_meta($post_id, 'thm_article', (int) esc_attr($_POST['article-inc-id']));
                               }

                               
                               update_post_meta($post_id, 'form_step', (int) esc_attr($_POST['form_step']));
                               
                               // START OF SHARES UPLOAD AND MANUAL FORM SAVING
                               // add shares to post
                               // Shares output to save in meta
                              if($offering != 'bond') { 
		                        	if($_FILES['shares_file']['error'] === 0 ) {
		                        		// upload excel file here 
												 $file = $_FILES['shares_file']['tmp_name'];
												 $handle = fopen($file, "r");
												 $c = 0;
												 $colC = 0;
												 $err = array(
												 			'msg' => 'Headers of uploaded file should match with the sample file provided.',
												 			'status' => 'false'
												 		);
												 while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
												 {
													if($colC==0) {
													 	//if($filesop[0] != "First Name of Shareholder") { 
													 	if(stripos($filesop[0],'first') === false) { // loosing strict checking 
													 		echo json_encode($err);
													 		exit;
													 	}
													 	//if($filesop[1] != 'Surname of Shareholder') {
													 	if(stripos($filesop[1],'surname') === false) {
													 		echo json_encode($err);
													 		exit;
													 	}
													 	/*if($filesop[2] != 'Phone Number') {
													 		echo json_encode($err);
													 		exit;
													 	}*/
													 	//if($filesop[2] != 'Email of Shareholder') {
													 	if(stripos($filesop[2],'email') === false) {
													 		echo json_encode($err);
													 		exit;
													 	}
													 	if(stripos($filesop[3],'held') === false) {
															echo json_encode($err);
															exit;
														}
														//if($filesop[4] != 'Purchase Price (in pence)') {
														if(stripos($filesop[4],'price') === false) {
															echo json_encode($err);
															exit;
														}
														
														if(stripos($filesop[5],'holdings') === false) {
															echo json_encode($err);
															exit;
														}
													 	/*if($filesop[6] != 'Date Purchased') {
													 		echo json_encode($err);
													 		exit;
													 	}*/
													 	
													 	$colC++;
													 	continue;
												 	}
												 	if($colC > 0) {
												 	$fname = esc_attr(trim($filesop[0]));
												 	$lname = esc_attr(trim($filesop[1]));
												 	$email = $filesop[2];
												 	$numberShares = $filesop[3];
												 	$buyPrice =	$filesop[4];
													$holdingsType =	$filesop[5] != '' ? $filesop[5] : 'share';
													$investmentAmount = $filesop[6];
												 	
													if($numberShares > 0) {
														if(filter_var($email,FILTER_VALIDATE_EMAIL)){
														
														 	$eparts = explode("@",$email);
															$uname = $eparts[0];
															$user_id = username_exists( $uname );
															$userIDEmail = email_exists($email);
															if($user_id > 0) {
																$uname = $uname.rand(pow(10, 3-1), pow(10, 3)-1);
															}
															//if ( !$user_id) {
																if(!$userIDEmail) {
																	$random_password = wp_generate_password(12, false );
																	$user_id = wp_create_user( $uname, $random_password, $email );
																	if($user_id > 0) {
																		wp_update_user(array(
																			'ID' => $user_id,
																			'display_name' => $fname. " ".$lname,
																			'first_name' => $fname,
																			'last_name' => $lname
																		));
																		//update_user_meta($user_id,'phone_number',$phone);
																		wp_new_shareholder_notification($user_id,$random_password,$title);
																	}
																}else{
																	$user_id = $userIDEmail;
																}
														} else { //no email
															$uname = strtolower($fname.$lname);
															$user_id = username_exists( $uname );
															if($user_id > 0) {
																$uname = $uname.rand(pow(10, 3-1), pow(10, 3)-1);
															}
																	$random_password = wp_generate_password(12, false );
																	$user_id = wp_create_user( $uname, $random_password);
																	if($user_id > 0) {
																		wp_update_user(array(
																			'ID' => $user_id,
																			'display_name' => $fname. " ".$lname,
																			'first_name' => $fname,
																			'last_name' => $lname
																		));
																		//update_user_meta($user_id,'phone_number',$phone);
																		//wp_new_shareholder_notification($user_id,$random_password,$title);
																	}
																



														}
													//} 
													$shareargs = array('post_type'=>'shares','post_parent'=>$post_id,'post_status'=>'publish','post_author'=>$user_id);
												$shareargs['post_title'] = 'Shares of company '.$title;
												$share_id = wp_insert_post( $shareargs );
												if($share_id > 0) {
													update_post_meta($share_id, 'thm_businessid', $post_id);
													update_post_meta($share_id, 'thm_user_id', $user_id);
													update_post_meta($share_id, 'thm_user_email', esc_attr($email));
													update_post_meta($share_id, 'thm_user_firstname', esc_attr($fname));
													update_post_meta($share_id, 'thm_user_surname', esc_attr($lname));
													update_post_meta($share_id, 'thm_noshares', esc_attr($numberShares));
													update_post_meta($share_id, 'thm_sellingprice', '0');
													update_post_meta($share_id, 'investment_amount', $investmentAmount);
													update_post_meta($share_id, 'thm_purchaseprice', round(esc_attr($buyPrice),2));
													//update_post_meta($share_id, 'thm_purchasedate', esc_attr($datePurchased));
													update_post_meta($share_id, 'thm_status', 'notforsale');
													update_post_meta($share_id, 'thm_offering', esc_attr($offering));	
													update_post_meta($share_id, 'thm_share_type', $holdingsType);
												}
												unset($share_id);
												} } // end of if checking number of shares
												 } // end of file
												 	
		                        	}else{ 
												if(isset($_POST['shares_numbers'])) {
													foreach($_POST['shares_numbers'] as $orderKey => $value) {
														if($value != '') {
															$sharesData = array(
															'share_numbers' => $value,
															'share_purchase_price' => isset($_POST['shares_purchase_price'][$orderKey]) ? $_POST['shares_purchase_price'][$orderKey] : '',
															'share_purchase_date' => isset($_POST['shares_purchase_date'][$orderKey]) ? $_POST['shares_purchase_date'][$orderKey] : '',
															'share_status' => isset($_POST['shares_status'][$orderKey]) ? $_POST['shares_status'][$orderKey] : ''
														);
															// check and create if share holder is exists in our users database
															if((isset($_POST['shares_fname'][$orderKey]) && $_POST['shares_fname'][$orderKey] != '')
																&& (
																	isset($_POST['shares_email'][$orderKey]) 
																	&& $_POST['shares_email'][$orderKey] != '' 
																	&& filter_var($_POST['shares_email'][$orderKey],FILTER_VALIDATE_EMAIL)
																	)
																) {

																$eparts = explode("@",$_POST['shares_email'][$orderKey]);
																$uname = $eparts[0];
																$user_id = username_exists( $uname );
																if($user_id > 0) {
																	$uname = $uname.rand(pow(10, 3-1), pow(10, 3)-1);
																}
																$userIDEmail = email_exists($_POST['shares_email'][$orderKey]);
																//if ( !$user_id) {
																	if(!$userIDEmail) {
																		$random_password = wp_generate_password(12, false );
																		$user_id = wp_create_user( $uname, $random_password, $_POST['shares_email'][$orderKey] );
																		if($user_id > 0) {
																			wp_insert_user(array(
																				'ID' => $user_id,
																				'display_name' => $_POST['shares_fname'][$orderKey] . " ". $_POST['shares_lname'][$orderKey],
																				'first_name' => esc_attr($_POST['shares_fname'][$orderKey]),
																				'last_name' => esc_attr($_POST['shares_lname'][$orderKey])
																			));
																			wp_new_shareholder_notification($user_id,$random_password,$title);
																		}
																	}else{
																		$user_id = $userIDEmail;
																	}
																//} 
															}
															$shareargs = array('post_type'=>'shares','post_parent'=>$post_id,'post_status'=>'publish','post_author'=>$user_id);
															$shareargs['post_title'] = 'Shares of company '.$title;
															$share_id = wp_insert_post( $shareargs );
															if($share_id > 0) {
																update_post_meta($share_id, 'thm_businessid', $post_id);
																update_post_meta($share_id, 'thm_user_id', $user_id);
																update_post_meta($share_id, 'thm_user_email', esc_attr($_POST['shares_email'][$orderKey]));
																update_post_meta($share_id, 'thm_user_firstname', esc_attr($_POST['shares_fname'][$orderKey]));
																update_post_meta($share_id, 'thm_user_surname', esc_attr($_POST['shares_lname'][$orderKey]));
																
																if($offering == 'bond') {
																	update_post_meta($share_id, 'thm_coupon', esc_attr($_POST['shares_coupon'][$orderKey]));
																	update_post_meta($share_id, 'thm_expires', esc_attr($_POST['shares_expires'][$orderKey]));
																}
																update_post_meta($share_id, 'thm_noshares', esc_attr($sharesData['share_numbers']));
																update_post_meta($share_id, 'thm_sellingprice', '0');
																update_post_meta($share_id, 'thm_purchaseprice', round(esc_attr($sharesData['share_purchase_price']),2));
																update_post_meta($share_id, 'thm_purchasedate', esc_attr($sharesData['share_purchase_date']));
																update_post_meta($share_id, 'thm_status', 'notforsale');
																update_post_meta($share_id, 'thm_offering', esc_attr($offering));
																update_post_meta($share_id, 'thm_share_type', 'share');		
															}
															unset($share_id);
															unset($sharesData);
														}
													}
												}
											} // end of shares saving / uploading
											}
											
										// bonds saving and upload 
										if($offering == 'bond' || $offering == 'both') { 
		                        	if($_FILES['bonds_file']['error'] === 0 ) {
		                        		// upload excel file here 
												 $file = $_FILES['bonds_file']['tmp_name'];
												 $handle = fopen($file, "r");
												 $c = 0;
												 $colC = 0;
												 $err = array(
												 			'msg' => 'Headers of uploaded file should match with the sample file provided.',
												 			'status' => 'false'
												 		);
												 while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
												 {
													if($colC==0) {
													 	//if($filesop[0] != "First Name of Shareholder") { 
													 	if(stripos($filesop[0],'first') === false && stripos($filesop[0],'name') === false) { // loosing strict checking 
													 		echo json_encode($err);
													 		exit;
													 	}
													 	//if($filesop[1] != 'Surname of Shareholder') {
													 	if(stripos($filesop[1],'surname') === false) {
													 		echo json_encode($err);
													 		exit;
													 	}
													 	/*if($filesop[2] != 'Phone Number') {
													 		echo json_encode($err);
													 		exit;
													 	}*/
													 	//if($filesop[2] != 'Email of Shareholder') {
													 	if(stripos($filesop[2],'email') === false) {
													 		echo json_encode($err);
													 		exit;
													 	}
													 	//if($filesop[3] != 'Number of Shares') {
													 	if(stripos($filesop[3],'number') === false) {
													 		echo json_encode($err);
													 		exit;
													 	}
													 	//if($filesop[4] != 'Purchase Price (in pence)') {
													 	if(stripos($filesop[4],'price') === false) {
													 		echo json_encode($err);
													 		exit;
													 	}
													 	/*if($filesop[6] != 'Date Purchased') {
													 		echo json_encode($err);
													 		exit;
													 	}*/
													 	
													 	if($filesop[5] != 'Coupon %') {
													 		echo json_encode($err);
													 		exit;
													 	}
													 	if($filesop[6] != 'Expires') {
													 		echo json_encode($err);
													 		exit;
													 	}
													 	
													 	$colC++;
													 	continue;
												 	}
												 	if($colC > 0) {
												 	$fname = esc_attr(trim($filesop[0]));
												 	$lname = esc_attr(trim($filesop[1]));
												 	//$phone = $filesop[2];
												 	$email = $filesop[2];
												 	$numberShares = $filesop[3];
												 	$buyPrice =	$filesop[4];
												 	//$datePurchased = $filesop[6];
												 	
											 		$coupon = $filesop[5];
											 		$expires = $filesop[6];
												 	
												 	
													if($numberShares > 0) {
														if(filter_var($email,FILTER_VALIDATE_EMAIL)){
														
														 	$eparts = explode("@",$email);
															$uname = $eparts[0];
															$user_id = username_exists( $uname );
															$userIDEmail = email_exists($email);
															if($user_id > 0) {
																$uname = $uname.rand(pow(10, 3-1), pow(10, 3)-1);
															}
															//if ( !$user_id) {
																if(!$userIDEmail) {
																	$random_password = wp_generate_password(12, false );
																	$user_id = wp_create_user( $uname, $random_password, $email );
																	if($user_id > 0) {
																		wp_update_user(array(
																			'ID' => $user_id,
																			'display_name' => $fname. " ".$lname,
																			'first_name' => $fname,
																			'last_name' => $lname
																		));
																		//update_user_meta($user_id,'phone_number',$phone);
																		wp_new_shareholder_notification($user_id,$random_password,$title);
																	}
																}else{
																	$user_id = $userIDEmail;
																}
														} else { //no email
															$uname = strtolower($fname.$lname);
															$user_id = username_exists( $uname );
															if($user_id > 0) {
																$uname = $uname.rand(pow(10, 3-1), pow(10, 3)-1);
															}
																	$random_password = wp_generate_password(12, false );
																	$user_id = wp_create_user( $uname, $random_password);
																	if($user_id > 0) {
																		wp_update_user(array(
																			'ID' => $user_id,
																			'display_name' => $fname. " ".$lname,
																			'first_name' => $fname,
																			'last_name' => $lname
																		));
																		//update_user_meta($user_id,'phone_number',$phone);
																		//wp_new_shareholder_notification($user_id,$random_password,$title);
																	}
																
														}
													//} 
													$shareargs = array('post_type'=>'shares','post_parent'=>$post_id,'post_status'=>'publish','post_author'=>$user_id);
												$shareargs['post_title'] = 'Bonds of company '.$title;
												$share_id = wp_insert_post( $shareargs );
												if($share_id > 0) {
													update_post_meta($share_id, 'thm_businessid', $post_id);
													update_post_meta($share_id, 'thm_user_id', $user_id);
													update_post_meta($share_id, 'thm_user_email', esc_attr($email));
													update_post_meta($share_id, 'thm_user_firstname', esc_attr($fname));
													update_post_meta($share_id, 'thm_user_surname', esc_attr($lname));
													update_post_meta($share_id, 'thm_noshares', esc_attr($numberShares));
													update_post_meta($share_id, 'thm_sellingprice', '0');
													update_post_meta($share_id, 'thm_purchaseprice', round(esc_attr($buyPrice),2));
													//update_post_meta($share_id, 'thm_purchasedate', esc_attr($datePurchased));
													update_post_meta($share_id, 'thm_status', 'notforsale');
													update_post_meta($share_id, 'thm_offering', esc_attr($offering));	
													update_post_meta($share_id, 'thm_coupon', esc_attr($coupon));
													update_post_meta($share_id, 'thm_expires', esc_attr($expires));
													update_post_meta($share_id, 'thm_share_type', 'bond');
												}
												unset($share_id);
												} } // end of if checking number of shares
												 } // end of file
												 	
		                        	}else{ 
												if(isset($_POST['bonds_numbers'])) {
													foreach($_POST['bonds_numbers'] as $orderKey => $value) {
														if($value != '') {
															$sharesData = array(
															'share_numbers' => $value,
															'share_purchase_price' => isset($_POST['bonds_purchase_price'][$orderKey]) ? $_POST['bonds_purchase_price'][$orderKey] : '',
															'share_purchase_date' => isset($_POST['bonds_purchase_date'][$orderKey]) ? $_POST['bonds_purchase_date'][$orderKey] : '',
															'share_status' => isset($_POST['bonds_status'][$orderKey]) ? $_POST['bonds_status'][$orderKey] : ''
														);
															// check and create if share holder is exists in our users database
															if((isset($_POST['bonds_fname'][$orderKey]) && $_POST['bonds_fname'][$orderKey] != '')
																&& (
																	isset($_POST['bonds_email'][$orderKey]) 
																	&& $_POST['bonds_email'][$orderKey] != '' 
																	&& filter_var($_POST['bonds_email'][$orderKey],FILTER_VALIDATE_EMAIL)
																	)
																) {

																$eparts = explode("@",$_POST['bonds_email'][$orderKey]);
																$uname = $eparts[0];
																$user_id = username_exists( $uname );
																if($user_id > 0) {
																	$uname = $uname.rand(pow(10, 3-1), pow(10, 3)-1);
																}
																$userIDEmail = email_exists($_POST['bonds_email'][$orderKey]);
																//if ( !$user_id) {
																	if(!$userIDEmail) {
																		$random_password = wp_generate_password(12, false );
																		$user_id = wp_create_user( $uname, $random_password, $_POST['bonds_email'][$orderKey] );
																		if($user_id > 0) {
																			wp_insert_user(array(
																				'ID' => $user_id,
																				'display_name' => $_POST['bonds_fname'][$orderKey] . " ". $_POST['bonds_lname'][$orderKey],
																				'first_name' => esc_attr($_POST['bonds_fname'][$orderKey]),
																				'last_name' => esc_attr($_POST['bonds_lname'][$orderKey])
																			));
																			wp_new_shareholder_notification($user_id,$random_password,$title);
																		}
																	}else{
																		$user_id = $userIDEmail;
																	}
																//} 
															}
															$shareargs = array('post_type'=>'shares','post_parent'=>$post_id,'post_status'=>'publish','post_author'=>$user_id);
															$shareargs['post_title'] = 'Bonds of company '.$title;
															$share_id = wp_insert_post( $shareargs );
															if($share_id > 0) {
																update_post_meta($share_id, 'thm_businessid', $post_id);
																update_post_meta($share_id, 'thm_user_id', $user_id);
																update_post_meta($share_id, 'thm_user_email', esc_attr($_POST['bonds_email'][$orderKey]));
																update_post_meta($share_id, 'thm_user_firstname', esc_attr($_POST['bonds_fname'][$orderKey]));
																update_post_meta($share_id, 'thm_user_surname', esc_attr($_POST['bonds_lname'][$orderKey]));
																
																if($offering == 'bond') {
																	update_post_meta($share_id, 'thm_coupon', esc_attr($_POST['bonds_coupon'][$orderKey]));
																	update_post_meta($share_id, 'thm_expires', esc_attr($_POST['bonds_expires'][$orderKey]));
																}
																update_post_meta($share_id, 'thm_noshares', esc_attr($sharesData['share_numbers']));
																update_post_meta($share_id, 'thm_sellingprice', '0');
																update_post_meta($share_id, 'thm_purchaseprice', round(esc_attr($sharesData['share_purchase_price']),2));
																update_post_meta($share_id, 'thm_purchasedate', esc_attr($sharesData['share_purchase_date']));
																update_post_meta($share_id, 'thm_status', 'notforsale');
																update_post_meta($share_id, 'thm_offering', esc_attr($offering));		
																update_post_meta($share_id, 'thm_share_type', 'bond');
															}
															unset($share_id);
															unset($sharesData);
														}
													}
												}
											} // end of shares saving / uploading
											}
										// end of bonds saving and upload
										
                               
                            $returnArr = array(
								'post_id' => $post_id,
								'status' => 'true'
							 );   
							 echo json_encode($returnArr);   
							 exit;
                       }
                }


    }
add_action('wp_ajax_new_main_project_add', 'new_main_project_add');
add_action('wp_ajax_nopriv_new_main_project_add', 'new_main_project_add');  
