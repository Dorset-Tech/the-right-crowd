<?php
function themeum_get_project_info($post_id,$type,$options = array()){

    // themeum_get_project_info(get_the_ID(),'budget');
    // themeum_get_project_info(get_the_ID(),'percent');
    // themeum_get_project_info(get_the_ID(),'collected');
    // themeum_get_project_info(get_the_ID(),'investor_number');
    // themeum_get_project_info(get_the_ID(),'equity');
    // themeum_get_project_info(get_the_ID(),'share_price');

    if( $type=='budget' ){          // Return the total Budget
        $budget = esc_attr(get_post_meta($post_id,"thm_funding_goal",true));  // Get the total Budget
        return $budget;
    }elseif( $type == 'percent' ){  // Return the Funding Percentage
        $budget = esc_attr(get_post_meta($post_id,"thm_funding_goal",true));
        global $wpdb;
        $result = $wpdb->get_row( $wpdb->prepare("SELECT SUM(meta_value) as total FROM ".$wpdb->prefix."postmeta WHERE post_id = ANY(SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = '%s'  AND meta_value = '%d') AND meta_key = '%s'", 'themeum_investment_project_id', $post_id, 'themeum_investment_amount'));
        $funding = 0;
          if($budget != "" && $budget != 0 ){
            if(isset($result->total)){
              if( $result->total != 0 && is_numeric($result->total)){
              	 if(isset($options['exact']) && $options['exact'] === true){
              	 	$funding = number_format(($result->total/$budget)*100,2);
              	 }else{
                	$funding =  floor((($result->total)/$budget)*100);
                }
              }
            }
          }
          return $funding;
    }elseif( $type == 'collected' ){ // return Total collected fund
        global $wpdb;
        $result = $wpdb->get_row( $wpdb->prepare("SELECT SUM(meta_value) as total FROM ".$wpdb->prefix."postmeta WHERE post_id = ANY(SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = '%s'  AND meta_value = '%d') AND meta_key = '%s'", 'themeum_investment_project_id', $post_id, 'themeum_investment_amount'));
        return $result->total;
    }elseif($type == 'equity') {
    	$equityoffer = get_post_meta(get_the_ID(), "thm_equityoffer", true);
    	return $equityoffer;
    }elseif($type == 'share_price') {
    	$sharePrice = get_post_meta(get_the_ID(), "thm_share_price", true);
    	return $sharePrice;
    }elseif($type == 'days_remaining'){
    	$eDate = get_post_meta(get_the_ID(), 'thm_end_date', true);
       if ($eDate != '') {
           $date1 = new DateTime();
           $date2 = new DateTime($eDate);
           return $date2->diff($date1)->format('%a');
       } else {
           return "∞";
       }
    }
    else{ // Return Investor Number
        $args = array(
            'post_type' => 'investment',
            'meta_key' => 'themeum_investment_project_id',
            'meta_value' => $post_id,
            'meta_compare' => '=',
            'orderby' => 'meta_value',
            'order' => 'ASC'
          );
        $events = new WP_Query($args);
        $investor_num = count($events->posts); // Total Number of investor
        return $investor_num;
    }

}


/*--------------------------------------------------------------
 *      Project Get Currency Symbol & Code (Add New Project)
 *-------------------------------------------------------------*/
function themeum_get_currency_symbol(){
    $currency_array = array('AUD' => '$','BRL' => 'R$','CAD' => '$','CZK' => 'K??','DKK' => 'kr.','EUR' => '€','HKD' => 'HK$','HUF' => 'Ft','ILS' => '₪','JPY' => '¥','MYR' => 'RM','MXN' => 'Mex$','NOK' => 'kr','NZD' => '$','PHP' => '₱','PLN' => 'zł','GBP' => '£','RUB' => '₽','SGD' => '$','SEK' => 'kr','CHF' => 'CHF','TWD' => '角','THB' => '฿','TRY' => 'TRY','USD' => '$');
    $symbol = '';
    $currency_type = esc_attr(get_option('paypal_curreny_code'));
    if (array_key_exists( $currency_type , $currency_array)) {
        $symbol = $currency_array[$currency_type];
    }else{
         $symbol = '$';
    }
    return $symbol;
}
function themeum_get_currency_code(){
    $code = '';
    $currency_type = esc_attr(get_option('paypal_curreny_code'));
    if($currency_type == ''){
        $code = 'USD';
    }else{
        $code = $currency_type;
    }
    return $code;
}

function get_currencies($code = null){
    //$currency_array = array('AUD' => '$','BRL' => 'R$','CAD' => '$','CZK' => 'KĿ','DKK' => 'kr.','EUR' => '€','HKD' => 'HK$','HUF' => 'Ft','ILS' => '₪','JPY' => '¥','MYR' => 'RM','MXN' => 'Mex$','NOK' => 'kr','NZD' => '$','PHP' => '₱','PLN' => 'zł','GBP' => '£','RUB' => '₽','SGD' => '$','SEK' => 'kr','CHF' => 'CHF','TWD' => '角','THB' => '฿','TRY' => 'TRY','USD' => '$');
    $currency_array = array('GBP' => '£','USD' => '$', 'EUR' => '€');
	if($code) {
		return isset($currency_array[$code]) ? $currency_array[$code] : '£';
	}
    return $currency_array;
}

function getLowerCurrencyCode($code = null) {
	$currency_array = array('GBP' => 'pence','USD' => 'cent', 'EUR' => 'euro cent');
	if($code) {
		return isset($currency_array[$code]) ? $currency_array[$code] : '£';
	}
    return $currency_array;
}


/*--------------------------------------------------------------
 *      Project Get Rat option (Add New Project)
 *-------------------------------------------------------------*/
function themeum_get_ratting_data($post_id){

    $output_arr =  array();
    $html = '';
    $output = get_post_meta($post_id,'project_ratting');
    $i = 0;
    if(is_array($output)){
        foreach ($output as $value) {
            $var = explode('*###*',$value);
            $output_arr[] = $var[0];
            $i = $i + $var[0];
        }
        if( $i != 0 ){
            $i = ceil($i/count($output_arr));
        }
    }

    $html = '<ul class="list-unstyled list-inline">';
    for ($j=1; $j <=5 ; $j++) {
        if($j<=$i){
            $html .= '<li><i class="fa fa-star"></i></li>';
        }else{
            $html .= '<li><i class="fa fa-star-o"></i></li>';
        }
    }
    $html .= '</ul>';

return $html;
}

/*************** Get All Category names of Project  ***************************/
function getProjectCats($post_id) {
	$cat_terms = wp_get_object_terms(get_the_ID(), 'project_category', array());
	if(count($cat_terms) > 0) {
		$cats = [];
		foreach($cat_terms as $c) {
			$cats[] = $c->name;
		}
		if(count($cats) > 0) {
			return implode(', ',$cats);
		}
	}
	return '';
}

/* Investment Info */
function getInvestmentInfo($investmentID) {
	$prefix = 'themeum_';
	$metas = [
		'project_name',
		'invest_id',
		'investor_user_id',
		'investment_project_id',
		'shares',
		'investment_amount',
		'payment_id',
		'payment_method',
		'investment_date',
		'status_all',
		'share_price',
		'source_of_wealth',
		'transfertype',
		'investing_into',
		'name_isa_provider',
		'address1_isa_provider',
		'address2_isa_provider',
		'city_isa_provider',
		'state_isa_provider',
		'account_isa_provider',
		'another_account_isa_provider',
		'another_account',
		'zip_isa_provider',
		'full_part_isa_transfer',
		'investor_user_ref',
		'received_date'
	];
	$info = [];
	foreach($metas as $meta) {
		if($meta == 'investment_project_id'){
			$metaVal = get_post_meta($investmentID,$prefix.$meta,true);
			if($metaVal == '') {
				$invPost = get_post($investmentID);
				$info[$meta] = $invPost->post_parent;
			}else{
				$info[$meta] = $metaVal;
			}
		}else{
			$info[$meta] = get_post_meta($investmentID,$prefix.$meta,true);
		}
	}

	return $info;
}

// SHARES FUNCTIONS
function getRealLabel($labelkey) {
	$status = array(
		'forsale' => 'For Sale',
		'notforsale' => 'Hold'
	);

	return isset($status[$labelkey]) ? $status[$labelkey] : '';
}

function getAllShares($companyID,$newparams = array()){
	// defaults
	$arr = array('orderby'=>'cheapest','status' => 'forsale');
	$params = array_merge($arr,$newparams);
	$args = array(
            'post_type' => 'shares',
            //'post_parent' => $companyID,
            'post_status' => 'publish',
            'posts_per_page' => -1,

          );
	$businessa = array(
				   array(
					   'key' => 'thm_businessid',
					   'value' => $companyID,
					   'compare' => '=',
				   )
			   );
   if(isset($params['status']) && $params['status'] != '') {
   	$args['meta_query'] = array(
        array(
            'key' => 'thm_status',
            'value' => $params['status'],
            'compare' => '='
        ),
        array(
					   'key' => 'thm_businessid',
					   'value' => $companyID,
					   'compare' => '=',
				   )
    	);
   } else {
   	$args['meta_query'] = array(
				   array(
					   'key' => 'thm_businessid',
					   'value' => $companyID,
					   'compare' => '=',
				   )
    	);
   }
   if(isset($params['user_id']) && $params['user_id'] > 0) {
   	$args['author'] = $params['user_id'];
   }
   if(isset($params['orderby']) && $params['orderby'] == 'cheapest') {
   	$args['meta_key'] = 'thm_purchaseprice';
		$args['orderby'] = 'meta_value';
		$args['order'] = 'ASC';
   }
   if(isset($params['orderby']) && $params['orderby'] == 'date_purchased') {
   	$args['meta_key'] = 'thm_purchasedate';
		$args['orderby'] = 'meta_value';
		$args['order'] = 'ASC';
	}

   $shares = new WP_Query($args);

   return count($shares->posts) > 0 ? $shares->posts : false;
}

function getSellPrice($shareid){
	$sellPrice = get_post_meta($shareid,'thm_sellingprice',true);
	if($sellPrice == '') {
		$companyID = wp_get_post_parent_id($shareid);
		$sellPrice = get_post_meta($companyID,'thm_initialsellprice',true);
		return $sellPrice ? $sellPrice : 0;
	}
	return $sellPrice;
}

function getTotalSharesInfos($companyID, $avg = true) {
	$shares = getAllShares($companyID);
	$array = array(
		'total_shares' => 0,
		'total_price' => 0,
		'total_share_price' => 0
	);

	if($shares) {
		$allprice = [];
		$allshareprice = [];
		foreach($shares as $share) {
			$metas = get_post_meta($share->ID);
			if($metas['thm_noshares'][0] != '') {
				$array['total_shares'] += $metas['thm_noshares'][0];
				$allprice[] = $metas['thm_noshares'][0] * getSellPrice($share->ID);//$metas['thm_purchaseprice'][0];
				$allshareprice[] = getSellPrice($share->ID);//$metas['thm_purchaseprice'][0];
			}
		}

		$array['total_price'] = number_format(array_sum($allprice),2);
		$array['total_share_price'] = number_format(array_sum($allshareprice),2);

		if($avg === true) {
			$array['total_price'] = number_format(array_sum($allprice) / count($allprice),2);
			$array['total_share_price'] = number_format(array_sum($allshareprice) / count($allshareprice),2);
		}

	}
	return $array;
}

function getCheapestSharesInfos($companyID) {
	$shares = getAllShares($companyID);
	$array = [];
	$return = [
		'share_price' => 0,
		'total_shares' => 0
	];
	if($shares) {
		$allprice = [];
		$allshareprice = [];
		foreach($shares as $share) {
			$metas = get_post_meta($share->ID);
			if($metas['thm_noshares'][0] != '') {
				$array[getSellPrice($share->ID)] += $metas['thm_noshares'][0];
				//$allprice[getSellPrice($share->ID)][] = $metas['thm_noshares'][0] * getSellPrice($share->ID);//$metas['thm_purchaseprice'][0];
				$allshareprice[getSellPrice($share->ID)][] = getSellPrice($share->ID);//$metas['thm_purchaseprice'][0];
			}
		}

		$min = min(array_keys($array));
		$return['share_price'] = $min;
		$return['total_shares'] = $array[$min];
	}
	return $return;
}

function getShareHolders($companyID, $count=true) {
	$shares = getAllShares($companyID,array('status' => ''));
	$holders = [];
	if(count($shares) > 0 ) {
		  foreach($shares as $s) {
			$investorID = get_post_meta($s->ID,'thm_user_id',true);
		  	$holders[] = $investorID;
		  }
		  $holders = array_unique($holders);
	}
	if($count == true) {
		return count($holders);
	}
	return $holders;
}

function getSharePriceSegments($companyID) {
	$shares = getAllShares($companyID);
	$segments = ['total_shares'=>0,'range'=>[]];
	if($shares) :
		foreach($shares as $share) {
			$metas = get_post_meta($share->ID);
			if($metas['thm_noshares'][0] != '') {
				$segments['range'][$share->ID]['shares'] = $metas['thm_noshares'][0];
				$segments['range'][$share->ID]['price'] = getSellPrice($share->ID); //$metas['thm_purchaseprice'][0];
				$segments['total_shares'] += $metas['thm_noshares'][0];
			}
		}
	endif;
	//ksort($segments['range']);
	//set the range

	return $segments;
}

function getPriceByRangeOfShares($shares,$range){
	$p = [];
	if(is_array($range)) {
		foreach($range as $sh_id => $r) {
			if($shares > 0) {
				if($shares > $r['shares']){
					$p[$sh_id] = $r;
					$shares = $shares - $r['shares'];
				}else{
					$p[$sh_id] = $r;
					$shares = $shares - $r['shares'];
					break;
				}
			}
		}
	}
	return $p;
}

function getOfferPrices($companyID, $params = [], $all = false) {
	if($companyID > 0) {
		$offerEncoded = get_post_meta($companyID, 'offer_prices', true);
		$offerPrices = json_decode($offerEncoded,true);
		if(isset($params['user_id']) && $params['user_id'] > 0) {
			return isset($offerPrices[$params['user_id']]) ? $offerPrices[$params['user_id']] : false;
		}
		if($all === true) {
			return $offerPrices;
		}
		return current($offerPrices);
	}
	return false;
}

function getMatchedOfferUsers($value, $stack) {
	$array = [];
	if($value > 0 && is_array($stack)) {
		foreach($stack as $uid => $val) {
			if($val == $value) {
				$array[] = $uid;
			}
		}
	}
	return $array;
}

function hasPublishedShare($uid) {
	$args = array(
		'post_type' => 'shares',
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => 'thm_user_id',
				'value' => $uid,
				'compare' => '='
			),
		)
	);
	$posts = get_posts($args);
	if(count($posts) > 0) {
		return true;
	}
	return false;
}

function getQuestionnaireInfo($quest = null, $option = null, $structureOnly = false) {
	$array = array(
		'q_invested_before' => array(
			'none' => array(
				'label' => 'None',
				'section' => 'Investment Experience',
				'points' => 0
			),
			'savings_deposits' => array(
				'label' => 'Savings and Deposits',
				'section' => 'Investment Experience',
				'points' => 1
			),
			'bonds' => array(
				'label' => 'Bonds',
				'section' => 'Investment Experience',
				'points' => 2
			),
			'peer_lending' => array(
				'label' => 'Peer to Peer lending or unit trusts',
				'section' => 'Investment Experience',
				'points' => 3
			),
			'stocks_shares' => array(
				'label' => 'Stocks and Shares',
				'section' => 'Investment Experience',
				'points' => 4
			),
			'derivatives' => array(
				'label' => 'Derivatives',
				'section' => 'Investment Experience',
				'points' => 5
			)
		),
		'q_financial_experience' => array(
			'less_1' => array(
				'label' => '<1 Year',
				'section' => 'Investment Experience',
				'points' => 1
			),
			'1_3' => array(
				'label' => '1 to 3 Years',
				'section' => 'Investment Experience',
				'points' => 2
			),
			'3_5' => array(
				'label' => '3 to 5 Years',
				'section' => 'Investment Experience',
				'points' => 3
			),
			'more_5' => array(
				'label' => '>5 Years',
				'section' => 'Investment Experience',
				'points' => 4
			),
		),
		'q_individual_investments' => array(
			'less_2' => array(
				'label' => '<2',
				'section' => 'Investment Experience',
				'points' => 0
			),
			'3' => array(
				'label' => '3',
				'section' => 'Investment Experience',
				'points' => 1
			),
			'3_6' => array(
				'label' => '3 to 6',
				'section' => 'Investment Experience',
				'points' => 2
			),
			'more_6' => array(
				'label' => '>6',
				'section' => 'Investment Experience',
				'points' => 3
			),
		),
		'q_education_level' => array(
			'none' => array(
				'label' => 'None',
				'section' => 'Education and Employment',
				'points' => 0
			),
			'primary_secondary' => array(
				'label' => 'Primary/Secondary School or equivalent',
				'section' => 'Education and Employment',
				'points' => 1
			),
			'a_level' => array(
				'label' => 'A Level or equivalent',
				'section' => 'Education and Employment',
				'points' => 2
			),
			'degree' => array(
				'label' => 'Degree or equivalent',
				'section' => 'Education and Employment',
				'points' => 3
			),
			'postgraduate' => array(
				'label' => 'Postgraduate',
				'section' => 'Education and Employment',
				'points' => 4
			),
		),
		'q_employment' => array(
			'unemployed' => array(
				'label' => 'I am either unemployed or retired and have been for the last 5 years',
				'section' => 'Education and Employment',
				'points' => 0
			),
			'no_dealing' => array(
				'label' => 'I have no dealing with financial services/investments',
				'section' => 'Education and Employment',
				'points' => 1
			),
			'some_dealing' => array(
				'label' => 'I have some dealing with financial services/investments',
				'section' => 'Education and Employment',
				'points' => 2
			),
			'working' => array(
				'label' => 'I work in a financial services/investments business',
				'section' => 'Education and Employment',
				'points' => 3
			)
		),
		'q_income' => array(
			'less_10000' => array(
				'label' => '< £10000 or equivalent',
				'section' => 'Financial Status',
				'points' => 0
			),
			'10000_20000' => array(
				'label' => '£10000 to <£20000 or equivalent',
				'section' => 'Financial Status',
				'points' => 1
			),
			'20000_30000' => array(
				'label' => '£20000 to < £30000 or equivalent',
				'section' => 'Financial Status',
				'points' => 2
			),
			'30000_50000' => array(
				'label' => '£30000 to £50000 or equivalent',
				'section' => 'Financial Status',
				'points' => 3
			),
			'more_50000' => array(
				'label' => '> £50000 or equivalent',
				'section' => 'Financial Status',
				'points' => 4
			)
		),
		'q_assets' => array(
			'no_assets' => array(
				'label' => 'No net assets',
				'section' => 'Financial Status',
				'points' => 0
			),
			'less_50000' => array(
				'label' => '< £50000 or equivalent',
				'section' => 'Financial Status',
				'points' => 1
			),
			'50000_150000' => array(
				'label' => '£50000 to < £150000 or equivalent',
				'section' => 'Financial Status',
				'points' => 2
			),
			'150000_250000' => array(
				'label' => '£150000 to £250000 or equivalent',
				'section' => 'Financial Status',
				'points' => 3
			),
			'more_250000' => array(
				'label' => '> £250000 or equivalent',
				'section' => 'Financial Status',
				'points' => 4
			)
		),
		'q_expenses_per' => array(
			'more_95' => array(
				'label' => '>95%',
				'section' => 'Financial Status',
				'points' => 0
			),
			'80_95' => array(
				'label' => '80% to 95%',
				'section' => 'Financial Status',
				'points' => 1
			),
			'50_80' => array(
				'label' => '50% to 80%',
				'section' => 'Financial Status',
				'points' => 2
			),
			'20_50' => array(
				'label' => '20% to 50%',
				'section' => 'Financial Status',
				'points' => 3
			),
			'less_20' => array(
				'label' => '<20%',
				'section' => 'Financial Status',
				'points' => 4
			)
		),
		'q_objective' => array(
			'capital_preservation' => array(
				'label' => 'Capital preservation',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 0
			),
			'income' => array(
				'label' => 'Income',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 1
			),
			'income_growth' => array(
				'label' => 'Income and growth',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 2
			),
			'growth' => array(
				'label' => 'Growth',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 3
			),
		),
		'q_intend_investment' => array(
			'more_80' => array(
				'label' => '>80%',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 0
			),
			'20_80' => array(
				'label' => '20% to 80%',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 1
			),
			'10_less_20' => array(
				'label' => '10% to < 20%',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 2
			),
			'5_less_10' => array(
				'label' => '5% to < 10%',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 3
			),
			'less_5' => array(
				'label' => '< 5%',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 4
			),
		),
		'q_position' => array( //,,,
			'access_required_full_return_with_interest' => array(
				'label' => 'It is critical that all my investment is returned to me together with the full offered interest and I require quick and easy access to the funds that I am investing.',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 0
			),
			'access_not_required_but_full_return_with_interest' => array(
				'label' => 'I do not require quick and easy access to the funds that I am investing but it is very important that all my investment is returned to me together with the full offered interest',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 1
			),
			'access_not_required_return_at_end_term' => array(
				'label' => 'I do not require quick and easy access to the funds that I am investing but it is very important that all my investment is returned to me at the end of term',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 2
			),
			'access_not_required_and_comfortable_to_go_down' => array(
				'label' => 'I do not require quick and easy access to the funds that I am investing and am comfortable that my investment can go down',
				'section' => 'Risk appetite/dependency on funds invested',
				'points' => 3
			)
		),
	);
	if($structureOnly === true) {
		return $array;
	}
 	if($quest !== null && $option !== null) {
		return isset($array[$quest][$option]) ? $array[$quest][$option] : false;
 	}
	return false;
}

/*function getQuestionnairePoints($user_id,$sectionWise = false) {
	$umetas = get_user_meta($user_id);
	$qinfo = getQuestionnaireInfo(null,null,true);
	$sections = [];
	foreach($qinfo as $mkey => $val) {
		if(!isset($umetas[$mkey][0])) {
			$sec = current($val)['section'];
			$sections[$sec] += 0;
		}else{
			$sections[$val[$umetas[$mkey][0]]['section']] += $qinfo[$mkey][$umetas[$mkey][0]]['points'];
		}
	}
	unset($sections['']);
	$sections['total'] = array_sum($sections);

	if($sectionWise === true) {
		return $sections;
	}
	return $sections['total'];
}*/

function getQuestionnairePoints($user_id,$sectionWise = false, $siteid = '') {
	$umetas = get_user_meta($user_id);
	$qinfo = getQuestionnaireInfo(null,null,true);
	if($siteid != '') {
		$sections = [];
		foreach($qinfo as $mkey => $val) {
			$k = $siteid.'_'.$mkey;
			if(!isset($umetas[$k][0])) {
				$sec = current($val)['section'];
				$sections[$sec] += 0;
			}else{
				$sections[$val[$umetas[$k][0]]['section']] += $qinfo[$mkey][$umetas[$k][0]]['points'];
			}
		}
		unset($sections['']);
		$sections['total'] = array_sum($sections);
	}else{
		$sections = [];
		foreach($qinfo as $mkey => $val) {
			if(!isset($umetas[$mkey][0])) {
				$sec = current($val)['section'];
				$sections[$sec] += 0;
			}else{
				$sections[$val[$umetas[$mkey][0]]['section']] += $qinfo[$mkey][$umetas[$mkey][0]]['points'];
			}
		}
		unset($sections['']);
		$sections['total'] = array_sum($sections);
	}
	if($sectionWise === true) {
		return $sections;
	}
	return $sections['total'];

}

function getQuestionnairePointsFromArray($array,$sectionWise = false) {
	$umetas = $array;
	$qinfo = getQuestionnaireInfo(null,null,true);
	$sections = [];
	foreach($qinfo as $mkey => $val) {
		if(!isset($umetas[$mkey])) {
			$sec = current($val)['section'];
			$sections[$sec] += 0;
		}else{
			$sections[$val[$umetas[$mkey]]['section']] += $qinfo[$mkey][$umetas[$mkey]]['points'];
		}
	}
	unset($sections['']);
	$sections['total'] = array_sum($sections);

	if($sectionWise === true) {
		return $sections;
	}
	return $sections['total'];
}

add_action( 'wp_ajax_nopriv_get_user_questionnaire_points', 'get_user_questionnaire_points' );
add_action( 'wp_ajax_get_user_questionnaire_points', 'get_user_questionnaire_points' );
function get_user_questionnaire_points() {
	$qinfo = getQuestionnaireInfo(null,null,true);
	$umetas = $_POST['data'];
	$sections = [];
	foreach($qinfo as $mkey => $val) {
		if($mkey == 'q_invested_before') {
			if(!isset($umetas[$mkey]) || count($umetas[$mkey]) <= 0) {
				$sec = current($val)['section'];
				$sections[$sec] += 0;
			}else{
				$sec = current($val)['section'];
				$sections[$sec] += 0;
				foreach($umetas[$mkey] as $investedBeforeKey => $investedBeforeVal){
					$sections[$val[$umetas[$mkey]]['section']] += $qinfo[$mkey][$investedBeforeVal]['points'];
				}
			}
		}else{
			if(!isset($umetas[$mkey])) {
				$sec = current($val)['section'];
				$sections[$sec] += 0;
			}else{
				$sections[$val[$umetas[$mkey]]['section']] += $qinfo[$mkey][$umetas[$mkey]]['points'];
			}
		}
	}
	unset($sections['']);
	$sections['total'] = array_sum($sections);
	$html = '<h3>Your Appropriation Points</h3>';
	$html .= '<table class="user-points-signup" border="1" width="100%">';
	foreach($sections as $secKey => $secVal) {
		$html .= '<tr><td>'.$secKey.'</td><td>'.$secVal.'</td></tr>';
	}
	$html .= '</table>';
	$ret = array(
		'status' => 'true',
		'html' => $html
	);

	echo json_encode($ret);die();

}

function getProjectInvestors($projectID){
	global $switched;
	switch_to_blog(1);

	$args = array(
		'post_type' => 'investment',
		'post_status'    => array('publish','pending','received'),
		'meta_query' => array(
			array(
				'key'     => 'themeum_investment_project_id',
				'value'   => $projectID,
				'compare' => '='
			)
		),
		'posts_per_page'    => -1
	);

	$investments = get_posts( $args );
	$users = [];
	foreach($investments as $investor) {
		$investorID = get_post_meta($investor->ID,'themeum_investor_user_id',true);
		$users[] = $investorID;
	}
	$users = array_unique($users);
	restore_current_blog();
	return $users;
}

function getEmailHeader() {
	//return "<div style='height:300px;'><img src='https://therightx.com/wp-content/uploads/email-banner.jpg' alt='' /></div>";

	$themeum_options = get_option('themeum_options');

	if (isset($themeum_options['logo']))
    {

		if($themeum_options['logo-text-en']) {
			$logo = esc_html($themeum_options['logo-text']);
		}
		else
		{
			if(!empty($themeum_options['logo'])) {

				$logo = '<img class="enter-logo img-responsive" src="'.esc_url($themeum_options['logo']['url']).'" alt="" title="" width="27%">';
			}else{
				$logo = esc_html(get_bloginfo('name'));
			}
		}
    }
	else
    {
		$logo = esc_html(get_bloginfo('name'));
    }
	$header = '<!DOCTYPE html>
				<html lang="en">

				<head>
					<meta charset="utf-8">
					<meta name="viewport"
						  content="width=device-width, initial-scale=1, shrink-to-fit=no">
					<link rel="stylesheet"
						  href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
					<title>Email Template</title>
					<style>

					</style>
				</head>

				  <body>
					<div style="width: 600px; margin: 0 auto;">
					  <div style="width: 100%; text-align: center; padding: 15px 0;">
						<a href="'.get_site_url().'">'.$logo.'</a>
					  </div>
					  <div style="width: 100%;">
					  ';

	  return $header;

}
function testme() {
	global $themeum_options;
	echo "<pre>";
	print_r($themeum_options);
	echo "</pre>";
	exit;
}
//testme();
function getEmailFooter() {
	$themeum_options = get_option('themeum_options');
	if (isset($themeum_options['logo']))
    {

		if($themeum_options['logo-text-en']) {
			$logo = esc_html($themeum_options['logo-text']);
		}
		else
		{
			if(!empty($themeum_options['logo'])) {

				$logo = '<img class="enter-logo img-responsive" style="max-width : 100%;" src="'.esc_url($themeum_options['logo']['url']).'" alt="" title="">';
			}else{
				$logo = esc_html(get_bloginfo('name'));
			}
		}
    }
	else
    {
		$logo = esc_html(get_bloginfo('name'));
    }
	//$footerContent = get_theme_mod('email_footer_text');
	//$footerContent = str_replace("[logo]",$logo,$footerContent);
	$footer = '<div style="background-color: #f2f2f2; padding: 35px 0; width: 100%;  float: left; margin-bottom: 20px;">
          <div style="width: 35%; float: left; padding: 0 1%;"><a href="'.get_site_url().'">'.$logo.'</a></div>';
		  if(get_current_blog_id() == 15) {
          $footer .= '<div style="width: 60%; float: right; padding: 0 2%;">
            OFFICE  +44 (0)1843 423256 <br>
            <a href="mailto:info@apex-capital.co.uk">info@apex-capital.co.uk</a>  |  <a href="apex-capital.co.uk">apex-capital.co.uk</a> <br>
            Unit 14 | Invicta Way | Manston Park | Ramsgate | Kent | CT12 5FD
          </div>';
		  }

        $footer .= '</div>';
		if(get_current_blog_id() == 15) {
        $footer .= '<div style="width: 100%; font-size: 12px;">
          Apex Capital is a trading style of Opes Distribution Limited, a company registered in England & Wales, under company number 10673801 with registered address at 14, Invicta Way, Manston Park, Ramsgate, Kent. CT12 5FD<br><br>

          The value of any investments can go down as well as up and you might not get back what you put in. These products featured are not regulated by the Financial Conduct Authority or covered by the Financial Services Compensation Scheme, or the Financial Ombudsman Service.<br><br>

          “The information contained in this e-mail is confidential and may also be subject to legal privilege. It is intended only for the recipient(s) named above. If you are not named above as a recipient, you must not read, copy, disclose, forward or otherwise use the information contained in this email. If you have received this e-mail in error, please notify the sender (whose contact details are above) immediately by reply e-mail and delete the message and any attachments without retaining any copies.
        </div>';
		 }

      $footer .= '</div>
    </div>
  </body>

</html>';

return $footer;
}

function getEmailBody($subject,$content, $postid = null) {
		  $message = "<div style='width:600px;margin:auto;background-color:#ffffff;border-radius: 3px!important;
    background-color: #ffffff;
    border: 1px solid #dedede;'>"; // main wrapper
		  $message .= getEmailHeader();
		  $message .= "<div style='padding: 36px 48px; background: #52B7EA; color: #fff;'><h1>".$subject."</h1></div>";
		  $message .= "<div style='padding: 48px;'>"; // content
			$message .= $content;
	  	  $message .= "</div>"; // end of content
	  	  $message .= getEmailFooter();
  	  $message .= "</div>"; // end of main wrapper

  	  return $message;
}

function email_user_on_fundrais_complete($companyId,$args = array()) {

			  $companyName = get_the_title($companyId);
			  $company = get_post($companyId);
		  	  $subject = "Fundraising completed for your company : $companyName";
			  $message = "Congratulations your company has now completed its fundraising and you are now a shareholder within the company. Your shares are now held by The Right Crowd nominee limited and can be viewed in your account on (<a href='https://therightcrowd.com/'>The Right Crowd</a>).<br/><br/>";
	  		  $message .= "Best Wishes<br/>The Right Crowd";

		     //$body = getEmailBody($subject,$message);

		     $headers = array('Content-Type: text/html; charset=UTF-8');
		     	$holder = get_userdata($company->post_author);
		     	wp_mail($holder->user_email, $subject, $message,$headers);
}

function email_user_on_payment($userID,$companyId,$args = array()) {
			  global $switched;
			  switch_to_blog(1);
			  // send this email with client details to Apex-Capital if investment done on Apex-Capital
			  $siteID = get_post_meta($companyId,'thm_site_id',true);
			  $companyName = get_the_title($companyId);
			  $company = get_post($companyId);
			  restore_current_blog();
			  $amount = $args['amount'];
		  	  $subject = "Thank you for investing in : $companyName";
			  $message = "Dear User,<br/>Thank you for making an investment. The details are below:<br/>Company Name: $companyName<br/>Amount: £$amount";
	  		  $message .= "<br/><br/>Best Wishes<br/>The Right Crowd";

		     //$body = getEmailBody($subject,$message);

		     $headers = array('Content-Type: text/html; charset=UTF-8');
		     $holder = get_userdata($userID);
			 restore_current_blog();
			 switch_to_blog($siteID);
			 switch_to_blog($siteID);
		     wp_mail($holder->user_email, $subject, $message,$headers);
			 restore_current_blog();
			 switch_to_blog(1);
			if($siteID == 15) {
				global $switched;
				switch_to_blog(1);
				$companyName = get_the_title($companyId);
				$company = get_post($companyId);
				restore_current_blog();
				$holder = get_userdata($userID);
				$amount = $args['amount'];
				$subject = "Investment made in company : $companyName";
				$message = "Dear Admin,<br/>An Investment is made in the company on www.apex-capital.co.uk. The details are below:<br/>
					Company Name: $companyName<br/>
					User Name: ".$holder->data->first_name." ".$holder->data->last_name."<br/>
					User ID: ".$holder->ID."
					Amount: £$amount";
				$message .= "<br/><br/>Best Wishes<br/>The Right Crowd";

				$headers = array('Content-Type: text/html; charset=UTF-8');
				restore_current_blog();
			 switch_to_blog($siteID);
				wp_mail('info@apex-capital.co.uk', $subject, $message,$headers);
				restore_current_blog();
				switch_to_blog(1);
			}
}

function getBankDetails($projectID, $isa = false) {
	$details = '';
	if($projectID > 0) {
		$name = get_post_meta($projectID, 'bank_details_name', true);
		$bankName = get_post_meta($projectID, 'bank_details_bank_name', true);
		$sortcode = get_post_meta($projectID, 'bank_details_sort_code', true);
		$account = get_post_meta($projectID, 'bank_details_account', true);
		$iban = get_post_meta($projectID, 'bank_details_iban', true);
		$swift = get_post_meta($projectID, 'bank_details_swift', true);

		if($isa === true) {
			$name = get_post_meta($projectID, 'isa_bank_details_name', true);
			$bankName = get_post_meta($projectID, 'isa_bank_details_bank_name', true);
			$sortcode = get_post_meta($projectID, 'isa_bank_details_sort_code', true);
			$account = get_post_meta($projectID, 'isa_bank_details_account', true);
			$iban = get_post_meta($projectID, 'isa_bank_details_iban', true);
			$swift = get_post_meta($projectID, 'isa_bank_details_swift', true);
		}

		//if($name !== '' && $sortcode != '' && $account != '') {
			if(trim($name) != '') {
				$details = $name.'<br/>';
			}
			if(trim($bankName) != '') {
				$details .= 'Bank: '.$bankName.'<br/>';
			}
			if(trim($sortcode) != '') {
				$details .= 'Sort code: '.$sortcode.'<br/>';
			}
			if(trim($account) != '') {
				$details .= 'Account: '.$account.'<br/>';
			}
			if(trim($iban) != '') {
				$details .= 'IBAN: '.$iban.'<br/>';
			}
			if(trim($swift) != '') {
				$details .= 'Swift/Bic:  '.$swift.'<br/>';
			}
		//}
	}
	return $details;
}

/*function email_user_on_bank_transfer_payment($userID,$companyId,$args = array()) {
			  global $switched;
			  switch_to_blog(1);
			  $companyName = get_the_title($companyId);
			  $company = get_post($companyId);
			  restore_current_blog();
			  $amount = $args['amount'];
			  $ref = $args['ref'];
		  	  //Name: Right Crowd Ltd<br/>
			  $subject = "Thank you for investing in : $companyName";
			  $message = "Dear User,<br/>Thank you for making an investment in company: $companyName.<br/>You have used 'Bank Transfer Payment Method'. The Bank transfer is to be made to the bank details below:<br/><br/>
				Acc Number: 10074040<br/>
				Sort Code: 09-02-22<br/>
				Reference: $ref<br/>
				Amount: £$amount";
			  $message .= "<br/><br/>A confirmation email will be sent to you once we have confirmed that funds have been received.";
	  		  $message .= "<br/><br/>Best Wishes<br/>Apex Capital";

		     //$body = getEmailBody($subject,$message);

		     $headers = array('Content-Type: text/html; charset=UTF-8');
			$holder = get_userdata($userID);
			//wp_mail($holder->user_email, $subject, $message,$headers);
}*/
function email_user_on_bank_transfer_payment($userID,$companyId,$args = array()) {
			  global $switched;
			  switch_to_blog(1);
			  $companyName = get_the_title($companyId);
			  $company = get_post($companyId);
			  $siteID = get_post_meta($companyId,'thm_site_id',true);
			  $extraFiles = get_post_meta($companyId,'thm_extra_files',true);
			  $cur = get_post_meta($companyId,'goal_currency',true);
			  $cursign = "";
			  switch ($cur) {
				  case "USD":
				    $cursign = "$";
				    break;
				  case "GBP":
				    $cursign = "£";
				    break;
				  case "EUR":
				    $cursign = "€";
				    break;
				  default:

				}
			  $bankDetails = getBankDetails($companyId);
			  $bankDetailsISA = getBankDetails($companyId, true);

			  $toattachment = [];
			  $upload_dir = wp_upload_dir();
			  $certificate = $upload_dir['basedir'].'/statements/statement_'.$userID.'.pdf';
			  if(file_exists($certificate)) {
				$toattachment[] = $certificate;
			  }

			  if($siteID == '74') { // only pp0 site
				  $NDA = $upload_dir['basedir'].'/NDA/nda_'.$userID.'.pdf';
				  if(file_exists($NDA)) {
					$toattachment[] = $NDA;
				  }

				  // application
				  $application = $upload_dir['basedir'].'/pp09spv_applications/application_'.$userID.'_'.$args['investment_id'].'.pdf';
				  if(file_exists($application)) {
					$toattachment[] = $application;
				  }
			  }
			  // attach terms and conditions
			  //$toattachment[] = get_attached_file( 7724 );

			  if(isset($args['investment_id']) && $args['investment_id'] > 0){
				  $siteID = get_post_meta($args['investment_id'],'site_id',true);
			  }
			  restore_current_blog();
			  $currentSite = get_current_blog_id();
			  $amount = $args['amount'];
			  $ref = $args['ref'];
			  $holder = get_userdata($userID);
			  $holderFirstName = get_user_meta($holder->ID,'billing_first_name',true);
			  $holderLastName = get_user_meta($holder->ID,'billing_last_name',true);
			  $refcode = get_user_meta($holder->ID,'referralcode',true);
		  	  //Name: Right Crowd Ltd<br/> OLD bank : 10074040
			  $subject = "Thank you for investing in : $companyName";
			  	restore_current_blog();
			 switch_to_blog($siteID);

			 if(count($extraFiles) > 0) {
		 		foreach($extraFiles as $extraFile) {
		 			$emailExMetaKey = "extra_email_".$extraFile;
		 			restore_current_blog();
		 			switch_to_blog(1);
		 			$emailExChecked = get_post_meta($companyId,$emailExMetaKey,true);
		 			restore_current_blog();
		 			switch_to_blog($siteID);
		 			if($emailExChecked == 'yes') {
		 				$toattachment[] = get_attached_file( $extraFile );
		 			}
		 		}
		 	 }

			 $bcc = get_option( 'bcc_email');
			  $content = getEmailHeader();
			  //if($args['transfertype'] == 'isa_transfer') {
				 if($args['source'] == 'isa')  {
					$content .= '<h6 style="font-size: 16px; margin-bottom: 15px;">Hello '.$holderFirstName.' '.$holderLastName.',</h6>
							<div style="font-size: 16px; font-weight: 500; margin-bottom: 20px;">
							  Thank you for submitting your investment instruction of '.$amount.' into '.$companyName.'<br><br>



							  You will now be sent your transfer application forms in the post, please sign and return the forms in the stamped address envelope provided.<br/><br/>

							A confirmation email will be sent to you once we have confirmed that funds have been received and you will also be able to track, manage and Re-invest on your Dashboard here:<br/><br/>
							<a href="'.get_site_url().'/dashboard">Dashboard</a><br/><br/>

							Kind Regards
							</div>'; //Just one step to finalise your investment and start receiving your coupons!<br><br>
			  }else{
					$content .= '<h6 style="font-size: 16px; margin-bottom: 15px;">Hello '.$holderFirstName.' '.$holderLastName.',</h6>
							<div style="font-size: 16px; font-weight: 500; margin-bottom: 20px;">
							  Thank you for submitting your investment instruction into '.$companyName.'<br>

							';

							$content .=	'Simply use the below bank details to transfer your investment funds.<br><br>';

						$site_title = get_bloginfo('name');
						$siteOptions = get_option( 'swpsmtp_options' );
						$adminEmail = $siteOptions['from_email_field'];
						$content .=	'
							<br/>
							';
							$haveBankDetails = false;
							if($args['investing_into'] == 'isa' && $bankDetailsISA != '') {
								$content .= $bankDetailsISA;
								$content .= 'Reference: '.$ref.'<br/>
									Amount: '.$cursign.$amount.'<br/><br/>';
									$haveBankDetails = true;
							}else if($bankDetails != '') {
									$content .= $bankDetails;
									$content .= 'Reference: '.$ref.'<br/>
									Amount: '.$cursign.$amount.'<br/><br/>';
									$haveBankDetails = true;
							}else{ //Swift/Bic:  BUKBGB22<br/>
								$content .= 'Account Name<br/>
									Bank: BANK<br/>
									Sort code: 00-00-00<br/>
									Account: 00000000<br/>
									IBAN: ABCDEF000000000000000<br/>
									BIC: ABCDE00000<br/>
									Reference: '.$ref.'<br/>
									Amount: '.$cursign.$amount.'<br/><br/>
								';

							}
							if($siteID == 57){
								//The Right crowd Ltd on behalf of
								//$content .= 'A confirmation email will be sent to you once we have confirmed that funds have been received and you will also be able to track, manage and Re-invest on your Dashboard<br/><br/><br/><br/>';
							}else{
								$content .= 'A confirmation email will be sent to you once we have confirmed that funds have been received and you will also be able to track, manage and re-invest on your Dashboard here:<br/><br/>
							<a href="'.get_site_url().'/dashboard">Dashboard</a><br/><br/>';
							}

							if($siteID == 55) { //$bankDetailsISA == '' && $bankDetails == ''
								if($goal_currency == 'EUR' || $goal_currency == 'USD') {
								/*$content .= 'Payment Notes: <br/>
									<p>All of your payments are made to The Right Crowd on Behalf of Northern Provident Investments Limited as custodian - Northern Provident Investments Limited are Authorised and regulated by the Financial Conduct Authority and offer ISA and client services.</p>
									<p>FRN: 647948  </p>
									<p>Registered address: 3rd Floor, 207 Regent Street, London, W1B 3HH. Company number: 08807099</p>';*/
								}else{
								/*$content .= 'Payment Notes: <br/>
									<p>All of your payments are made to Northern Provident Investments Limited as custodian - Northern Provident Investments Limited are Authorised and regulated by the Financial Conduct Authority and offer ISA and client services.</p>
									<p>FRN: 647948  </p>
									<p>Registered address: 3rd Floor, 207 Regent Street, London, W1B 3HH. Company number: 08807099</p>';*/
								}
							}else if ($bankDetailsISA == '' && $bankDetails == ''){
								/*$content .= 'Payment Notes: <br/>
									<p>All of your payments are made to Northern Provident Investments Limited as custodian - Northern Provident Investments Limited are Authorised and regulated by the Financial Conduct Authority and offer ISA and client services.</p>
									<p>FRN: 647948  </p>
									<p>Registered address: 3rd Floor, 207 Regent Street, London, W1B 3HH. Company number: 08807099</p>';*/
							}
							if($siteID == '75') {
								$content = getEmailHeader();
								$content .= '<h6 style="font-size: 16px; margin-bottom: 15px;">Hello '.$holderFirstName.' '.$holderLastName.',</h6>
								<div style="font-size: 16px; font-weight: 500; margin-bottom: 20px;">
								  Thank you for submitting your investment instruction into '.$companyName.'<br/>

								';

								$content .=	'Documents containing bank details for you to review and sign will be sent over shortly.<br><br>';
							}
							$content .= '
							Your bank, when making payment, may require you to answer fraud questions, please be aware this is perfectly normal when making payments to new entities and it is merely the bank doing their job.</p>
<p>Should you have any questions or queries or require more information please do not hesitate to contact us.<br/><br/>

							Kind Regards<br/>
							'.$site_title.'<br/>
							'.$adminEmail.'
							</div>';

			  }
			$content .= getEmailFooter();

		     //$body = getEmailBody($subject,$message);

		     if(!empty($bcc))
		     {
		     	$headers = array('Content-Type: text/html; charset=UTF-8','bcc: '.$bcc.'');
		     }
		     else
		     {
		     	$headers = array('Content-Type: text/html; charset=UTF-8');
		     }

			$holder = get_userdata($userID);
			restore_current_blog();
			restore_current_blog();
			switch_to_blog($siteID);
			if(count($toattachment) > 0) {
				wp_mail($holder->data->user_email, $subject, $content,$headers, $toattachment);
			}else{
				wp_mail($holder->data->user_email, $subject, $content,$headers);
			}


			// send email to admin
			$subject = "New investment in : $companyName";
			$message = "A new investment done in company: $companyName <br/>";
			$message .=  "Details are: <br/>";
			$message .=  "User Name of Investee: ".$holderFirstName." ".$holderLastName." (".$holder->user_login.")<br/>";
			$message .= 'Name of Product: '.$companyName.'<br/>
							Reference: '.$ref.'<br/>
							Amount: '.$cursign.$amount.'<br/><br/>';
			//$message .= "Please take an action on the investment <a href='https://therightcrowd.com/wp-admin/edit.php?post_type=investment' >here</a><br/><br/>";
			$message .= "Thank you";
				restore_current_blog();
			 switch_to_blog($siteID);
			 $smtpOptions = get_option( 'swpsmtp_options' );
			//wp_mail('Admin@therightcrowd.com', $subject, $message,$headers);
			if($siteID != '8') {
				wp_mail($smtpOptions['from_email_field'], $subject, $message,$headers);
			}
			restore_current_blog();
			switch_to_blog(1);
}

function email_user_on_funding_received($userID,$investmentID,$args = array()) {
			  global $switched;
			  switch_to_blog(1);
			  $investment = get_post($investmentID);
			  $siteID = get_post_meta($investmentID,'site_id',true);

			  restore_current_blog();
			  $currentSite = get_current_blog_id();
			  $holder = get_userdata($userID);
			  $holderFirstName = get_user_meta($holder->ID,'billing_first_name',true);
			  $holderLastName = get_user_meta($holder->ID,'billing_last_name',true);
			  $refcode = get_user_meta($holder->ID,'referralcode',true);
			  $subject = "Your funds have been received for: ".$investment->post_title;
			  restore_current_blog();
			 switch_to_blog($siteID);
			 $bcc = get_option( 'bcc_email');
			  $content = getEmailHeader();
			  $dashboardURL = site_url('/dashboard/');
			  $content .= '<h6 style="font-size: 16px; margin-bottom: 15px; font-weight: bold;">Hello '.$holderFirstName.' '.$holderLastName.',</h6>
							<div style="font-size: 16px; font-weight: 500; margin-bottom: 20px;">

							Your funds have been received.<br/><br/>
							Please check your dashboard for further updates.<br/>
							<a href="'.$dashboardURL.'"></a>
							Thank you<br/>
							Kind Regards
							</div>'; //Just one step to finalise your investment and start receiving your coupons!<br><br>

			$content .= getEmailFooter();

		     //$body = getEmailBody($subject,$message);

		     if(!empty($bcc))
		     {
		     	$headers = array('Content-Type: text/html; charset=UTF-8','bcc: '.$bcc.'');
		     }
		     else
		     {
		     	$headers = array('Content-Type: text/html; charset=UTF-8');
		     }

			//$holder = get_userdata($userID);
			restore_current_blog();
			restore_current_blog();
			switch_to_blog($siteID);
			wp_mail($holder->user_email, $subject, $content,$headers);

			restore_current_blog();
			switch_to_blog(1);
}
/*
function send_admin_email_on_new_approval_queue($queue,$site_id,$postsTitles) {
	$current_blog_details = get_blog_details( $site_id );
	$siteName = $current_blog_details->blogname;
	// send email to admin
	$subject = "New products approval request to display on: $siteName";
	$message = "New products approval request to display on site: $siteName <br/>";
	$message .=  "Product names are: <br/>";

	/*global $switched;
	switch_to_blog(1);
	$posts = get_posts(array(
		'include'   => $newQueue
	));*/
	//restore_current_blog();
	/*if(count($postsTitles) > 0) {
		$message .= "<ul>";
		foreach($postsTitles as $postTitle){
			$message .= "<li>".$postTitle."</li>";
		}
		$message .= "</ul>";
	}
	$message .= "Please take an action <a href='' >here</a><br/><br/>";
	$message .= "Thank you";
	$headers = array('Content-Type: text/html; charset=UTF-8');
	wp_mail('Admin@therightcrowd.com', $subject, $message,$headers);
}
*/
function isManager($user_id = null) {
	if(current_user_can('seeadmin')) {
			return true;
	}
	return false;
}

function isCustodian() {
	return (bool) in_array('custodian',wp_get_current_user()->roles);
}

add_action( 'wp_ajax_nopriv_change_post_status', 'change_post_status' );
add_action( 'wp_ajax_change_post_status', 'change_post_status' );
function change_post_status() {
		restore_current_blog();
	$isManager = isManager();
	global $switched;
   switch_to_blog(1);
	$ret = array('status'=>'false');
	if($isManager) {
		if( (int) $_POST['pid'] > 0 && $_POST['post_status'] != '') {
			wp_update_post(array(
		     'ID'    =>  $_POST['pid'],
		     'post_status'   =>  $_POST['post_status']
		     ));
		}
		$ret['status'] = 'true';
		restore_current_blog();
		echo json_encode($ret);die();
	}
	restore_current_blog();
	echo json_encode($ret);die();
}

add_action( 'wp_ajax_nopriv_d_p', 'del_post' );
add_action( 'wp_ajax_d_p', 'del_post' );
function del_post() {
	restore_current_blog();
	$isManager = isManager();
	global $switched;
   switch_to_blog(1);
	$ret = array('status'=>'false');
	if($isManager) {
		if( (int) $_POST['pid'] > 0) {
			wp_delete_post($_POST['pid']);
		}
		$ret['status'] = 'true';
		restore_current_blog();
		echo json_encode($ret);die();
	}
	restore_current_blog();
	echo json_encode($ret);die();
}

add_action( 'wp_ajax_nopriv_display_business_child', 'display_business_child' );
add_action( 'wp_ajax_display_business_child', 'display_business_child' );
function display_business_child() {
	//restore_current_blog();
	$isManager = isManager();
	//global $switched;
	//switch_to_blog(1);
	$currentSite = get_current_blog_id();

	$ret = array('status'=>'false');
	if($isManager) {
		//if( isset($_POST['display_business'])) {
			/**/
			$currentSite = get_current_blog_id();
			$key = 'display_business_'.$currentSite;
			$approvalKey = 'display_business_approval_'.$currentSite;
			$approvalQueueKey = 'approval_queue_'.$currentSite;
			$params = array();
			parse_str($_POST['data'], $params);
			$pids = array_keys($params['display_business']);
			/*echo "<pre>";
			print_r($pids);
			exit;*/
			$siteType = get_option('site_type');
			$parent = (int) get_option('parent_site');

			//if($siteType == 'child' && $parent) {
				 //global $switched;
				 //switch_to_blog($parent);
			//}elseif($siteType == 'master') {
				global $switched;
				 switch_to_blog(1);
			//}
			$args = array(
				 'post_type' 		=> 'project',
				 'post_status'		=> array('publish','pending'),
				 'posts_per_page'    => -1
			);
			$posts = get_posts($args);
			$postsToApprove = [];
			$postsTitles = [];
			foreach($posts as $p) {
				$approved = get_post_meta($p->ID,$approvalKey,true);
				if(in_array($p->ID,$pids)) {
					if($approved == '' || $approved == 'no') {
						update_post_meta($p->ID,$key,'yes');
						update_post_meta($p->ID,$approvalKey,'no');
						$postsToApprove[$p->ID] = $p->ID;
						$postsTitles[] = $p->post_title;

					}else{
						update_post_meta($p->ID,$key,'yes');
					}
				}else{
					update_post_meta($p->ID,$key,'no');
				}
			}
			// set approval queue for admins
			//update_approval_queue();
			$oldApprovalQueue = get_option($approvalQueueKey);
			$oldApprovalQueue = array_unique($oldApprovalQueue);
			if($oldApprovalQueue != '') {
				$newQueue = array_merge($oldApprovalQueue,$postsToApprove);
			}else{
				$newQueue = $postsToApprove;
			}
			$newQueue = array_unique($newQueue);
			update_option($approvalQueueKey,$newQueue);
			//send email to admin here
			send_admin_email_on_new_approval_queue($newQueue,$currentSite,$postsTitles);
			restore_current_blog();
		//}
		$ret['status'] = 'true';
		echo json_encode($ret);die();
	}
	echo json_encode($ret);die();
}

add_action( 'wp_ajax_nopriv_display_file', 'display_file' );
add_action( 'wp_ajax_display_file', 'display_file' );
function display_file() {
	restore_current_blog();
	$isManager = isManager();
	global $switched;
   switch_to_blog(1);
	$ret = array('status'=>'false');
	if($isManager) {
		if( (int) $_POST['pid'] > 0) {
			//update_post_meta();
			$metaKey = $_POST['metaKey'];
			$value = $_POST['value'];
			update_post_meta($_POST['pid'],$metaKey,$value);
		}
		$ret['status'] = 'true';
		restore_current_blog();
		echo json_encode($ret);die();
	}
	restore_current_blog();
	echo json_encode($ret);die();
}

add_action( 'wp_ajax_nopriv_delete_uploaded_file', 'delete_uploaded_file' );
add_action( 'wp_ajax_delete_uploaded_file', 'delete_uploaded_file' );
function delete_uploaded_file() {
	restore_current_blog();
	$isManager = isManager();
	global $switched;
   switch_to_blog(1);
	$ret = array('status'=>'false');
	if($isManager) {
		if( (int) $_POST['pid'] > 0) {
			//update_post_meta();
			$metaKey = $_POST['metaKey'];
			$relation = $_POST['rel'];
			$fileID = $_POST['file_id'];
			switch($relation) {
				case 'bank':
					$banks = get_post_meta($_POST['pid'],'thm_bank_statements',true);
					$key = array_search($fileID, $banks);
					if (false !== $key) {
						unset($banks[$key]);
						$banks = array_values($banks);
						delete_post_meta($_POST['pid'],$metaKey);
					}
					update_post_meta($_POST['pid'],'thm_bank_statements',$banks);
					$ret['status'] = 'true';
				break;
				case 'financial':
					$banks = get_post_meta($_POST['pid'],'thm_financial_reports',true);
					$key = array_search($fileID, $banks);
					if (false !== $key) {
						unset($banks[$key]);
						$banks = array_values($banks);
						delete_post_meta($_POST['pid'],$metaKey);
					}
					update_post_meta($_POST['pid'],'thm_financial_reports',$banks);
					$ret['status'] = 'true';
				break;
				case 'extra':
					$banks = get_post_meta($_POST['pid'],'thm_extra_files',true);
					$key = array_search($fileID, $banks);
					$titleMetaKey = "extra_file_title_".$fileID;
					if (false !== $key) {
						unset($banks[$key]);
						$banks = array_values($banks);
						delete_post_meta($_POST['pid'],$metaKey);
						delete_post_meta($_POST['pid'],$titleMetaKey);
					}
					update_post_meta($_POST['pid'],'thm_extra_files',$banks);
					$ret['status'] = 'true';
				break;
			}

		}
		restore_current_blog();
		echo json_encode($ret);die();
	}
	restore_current_blog();
	echo json_encode($ret);die();
}

add_action( 'wp_ajax_nopriv_save_extra_title', 'save_extra_title' );
add_action( 'wp_ajax_save_extra_title', 'save_extra_title' );
function save_extra_title() {
	restore_current_blog();
	$isManager = isManager();
	global $switched;
   switch_to_blog(1);
	$ret = array('status'=>'false');
	if($isManager) {
		if( (int) $_POST['pid'] > 0) {
			//update_post_meta();
			$metaKey = $_POST['metaKey'];
			$value = $_POST['value'];
			update_post_meta($_POST['pid'],$metaKey,$value);
		}
		$ret['status'] = 'true';
		restore_current_blog();
		echo json_encode($ret);die();
	}
	restore_current_blog();
	echo json_encode($ret);die();
}

function getPostsFromOtherSite() {
	$siteType = get_option('site_type');
	$parent = (int) get_option('parent_site');
	$currentSite = get_current_blog_id();
	if($siteType == 'child' && $parent) {
	 global $switched;
    switch_to_blog($parent);
	}elseif($siteType == 'master') {
		global $switched;
		 switch_to_blog(1);
	}else{
		return array();
	}
	$args = array(
		 'post_type' 		=> 'project',
		 'post_status'		=> array('publish'),
		 'posts_per_page'    => -1
	);
	$query = new WP_Query($args);
	restore_current_blog();

   return $query;
}

add_action('init', function() {
  $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
  $parts = explode('/',$url_path);
  if ( $parts[0] == 'project' ) {
     // load the file if exists
     $load = locate_template('single-project.php', true);
     if ($load) {
        exit(); // just exit if template was found and loaded
     }
  }
});

function getProjectURL($id,$homeURL) {
	$orgURL = get_the_permalink($id);
//	echo $orgURL;
	//echo $orgURL;exit;
   $urlParsed = parse_url($orgURL);
   /*if(isset($urlParsed['query'])) {
   	return $homeURL."?".$urlParsed['query'];
   }*/
   $urlParts = explode("blog",$urlParsed['path']);
   if(strpos($orgURL,"blog")) {
   	$postURL = $homeURL.$urlParts[1];
   }else{
   	$postURL = $homeURL.$urlParts[0];
   }

   return $postURL;
}

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

function getProjectImageThumbSrc($id) {

    $siteID = get_post_meta($id,'thm_site_id',true);
	$attachmentId = get_post_meta($id,'_thumbnail_id',true);
	global $switched;
	switch_to_blog($siteID);
	$image = wp_get_attachment_image_src($attachmentId, 'full');
	restore_current_blog();
	global $switched;
		 switch_to_blog(1);

	return $image ? $image[0] : false;
}

function getProjectFiles($id) {
	restore_current_blog();
	global $switched;
		 switch_to_blog(1);

	$siteID = get_post_meta($id,'thm_site_id',true);
	$comp_cert = get_post_meta($id,'thm_certificate',true);
	$article = get_post_meta($id,'thm_article',true);
	$memorandum = get_post_meta($id,'thm_memorandum',true);
	$banks = get_post_meta($id,'thm_bank_statements',true);
	$financials = get_post_meta($id,'thm_financial_reports',true);
	$extraFiles = get_post_meta($id,'thm_extra_files',true);
	restore_current_blog();


	$array = [];
	global $switched;
	switch_to_blog($siteID);

	$array['certificate'] = array( 'path' => wp_get_attachment_url($comp_cert), 'id'=>$comp_cert);
	$array['article'] = array( 'path' => wp_get_attachment_url($article), 'id' => $article);
 	$array['memorandum'] = array( 'path' => wp_get_attachment_url($memorandum), 'id' => $memorandum);
 	$array['banks'] = [];
 	$array['financials'] = [];
 	$array['extraFiles'] = [];
 	if(count($banks) > 0) {
 		foreach($banks as $bank) {
 			$array['banks'][$bank] = wp_get_attachment_url($bank);
 		}
 	}
 	if(count($financials) > 0) {
 		foreach($financials as $financial) {
 			$array['financials'][$financial] = wp_get_attachment_url($financial);
 		}
 	}
 	if(count($extraFiles) > 0) {
 		foreach($extraFiles as $extraFile) {
 			$array['extraFiles'][$extraFile] = wp_get_attachment_url($extraFile);
 		}
 	}
	return $array;
}

add_action( 'wp_ajax_nopriv_save_terms_conditions', 'save_terms_conditions' );
add_action( 'wp_ajax_save_terms_conditions', 'save_terms_conditions' );
function save_terms_conditions() {

	$ret = array('status'=>'false');
	if(get_current_user_id()) {
			update_user_meta(get_current_user_id(),'tandc',$_POST['value']);
			$ret['status'] = 'true';
		echo json_encode($ret);die();
	}

	echo json_encode($ret);die();
}

add_action( 'wp_ajax_nopriv_bank_transfer', 'bank_transfer' );
add_action( 'wp_ajax_bank_transfer', 'bank_transfer' );
function bank_transfer() {
	restore_current_blog();
	$currentSiteID = get_current_blog_id();

	switch_to_blog($currentSiteID);
	$ret = array('status'=>'false');
	if(get_current_user_id()) {
		$projectID = $_POST['project_id'];
		$amount = sprintf('%0.2f', $_POST['amount'] / 100.0);
		$userID = get_current_user_id();
		$userData = get_userdata($userID);
		$uMetas = get_user_meta($userID);
		$canDoInvestment = get_user_meta($userID,'canDoInvestment',true);
		$reference = 'TRC2019'.$userID;
		$source = $_POST['source'];
		$transfertype = $_POST['transfertype'];
		$investing_into = $_POST['investing_into'];
		$name_isa_transfer = $_POST['name_isa_provider'];
		$address1_isa_transfer = $_POST['address1_isa_provider'];
		$address2_isa_transfer = $_POST['address2_isa_provider'];
		$city_isa_transfer = $_POST['city_isa_provider'];
		$state_isa_transfer = $_POST['state_isa_provider'];
		$account_isa_transfer = $_POST['account_isa_provider'];
		$zip_isa_transfer = $_POST['zip_isa_provider'];
		$full_part_isa_transfer = $_POST['full_part_isa_provider'];
		if($currentSiteID==86):
			$investment_term = $_POST['investment_term'];
			$interest_payment_intervals = $_POST['interest_payment_intervals'];
		endif;
		$ni_number = isset($_POST['ni_number']) ? $_POST['ni_number'] : '';
		restore_current_blog();
		global $switched;
		switch_to_blog(1);
		$investment_currency = get_post_meta($projectID,'goal_currency',true);
		if($investment_currency == '') {
			$investment_currency = 'GBP';
		}
		// insert investment as pending
		$postTitle = "Investment in company : ".get_the_title($projectID);
		$order_page = array(
				'post_title'    => $postTitle,
				'post_content'  => '',
				'post_status'   => 'pending',
				'post_author'   => esc_attr( $userID ), //1,
				'post_parent' => $projectID,
				'post_type'     => 'investment'
			);

			// assigning number of shares to investor user
			$sharePrice = get_post_meta($projectID,'thm_share_price',true);
			if($sharePrice == '') {
				$sharePrice = 1;
			}
			$totalShares = (int) (($amount*100)/$sharePrice);

			$post_id = wp_insert_post( $order_page );
			restore_current_blog();
			switch_to_blog(1);
			update_post_meta( $post_id , 'themeum_project_name', esc_attr( get_the_title($projectID) ));
			update_post_meta( $post_id , 'themeum_invest_id', 'via Bank');
			update_post_meta( $post_id , 'themeum_investor_user_id', esc_attr( $userID ));
			update_post_meta( $post_id , 'themeum_investor_user_first_name', $uMetas['billing_first_name'][0]);
			update_post_meta( $post_id , 'themeum_investor_user_last_name', $uMetas['billing_last_name'][0]);
			update_post_meta( $post_id , 'themeum_investor_user_email', $userData->data->email);
			update_post_meta( $post_id , 'themeum_investor_user_ref', $uMetas['referralcode'][0]);
			update_post_meta( $post_id , 'themeum_investment_project_id', $projectID);
			update_post_meta( $post_id , 'themeum_shares', $totalShares);
			update_post_meta( $post_id , 'themeum_investment_amount', esc_attr( $amount ));
			update_post_meta( $post_id , 'themeum_payment_id', 'via Bank');
			update_post_meta( $post_id , 'themeum_payment_method', 'bank' );
			update_post_meta( $post_id , 'themeum_investment_date', date("Y-m-d H:i:s") );
			update_post_meta( $post_id , 'themeum_status_all', 'pending' );
			update_post_meta( $post_id , 'themeum_share_price', $sharePrice );
			update_post_meta( $post_id , 'themeum_payment_ref', $reference );
			update_post_meta( $post_id , 'site_id', $currentSiteID );
			update_post_meta( $post_id , 'themeum_source_of_wealth', $source );
			update_post_meta( $post_id , 'themeum_investing_into', $investing_into );
			update_post_meta( $post_id , 'investment_currency', $investment_currency );

			if($currentSiteID==86):
				update_post_meta( $post_id , 'investment_term', $investment_term );
				update_post_meta( $post_id , 'interest_payment_intervals', $interest_payment_intervals );
			endif;

			if($transfertype != '') {
				update_post_meta( $post_id , 'themeum_transfertype', $transfertype );
			}
			if($name_isa_transfer != '') {
				update_post_meta( $post_id , 'themeum_name_isa_provider', $name_isa_transfer );
			}
			if($address1_isa_transfer != '') {
				update_post_meta( $post_id , 'themeum_address1_isa_provider', $address1_isa_transfer );
			}
			if($address2_isa_transfer != '') {
				update_post_meta( $post_id , 'themeum_address2_isa_provider', $address2_isa_transfer );
			}
			if($city_isa_transfer != '') {
				update_post_meta( $post_id , 'themeum_city_isa_provider', $city_isa_transfer );
			}
			if($state_isa_transfer != '') {
				update_post_meta( $post_id , 'themeum_state_isa_provider', $state_isa_transfer );
			}
			if($zip_isa_transfer != '') {
				update_post_meta( $post_id , 'themeum_zip_isa_provider', $zip_isa_transfer );
			}
			if($account_isa_transfer != '') {
				update_post_meta( $post_id , 'themeum_account_isa_provider', $account_isa_transfer );
			}
			if($full_part_isa_transfer != '') {
				update_post_meta( $post_id , 'themeum_full_part_isa_transfer', $full_part_isa_transfer );
			}
			if($ni_number != '') {
				update_post_meta( $post_id , 'themeum_ni_number', $ni_number );
				update_user_meta($userID,'ni_number',$ni_number);
			}
			if(isset($_SESSION['source_invest_site'])){
				update_post_meta($post_id,'source_invest_site',$_SESSION['source_invest_site']);
				unset($_SESSION['source_invest_site']);
			}

		if($canDoInvestment != 'yes') {
			update_post_meta($post_id,'pm_approval','no');
		}else{
			update_post_meta($post_id,'pm_approval','yes');
		}
		$companyName = get_the_title($projectID);
		// restore the blog
		restore_current_blog();
		switch_to_blog($currentSiteID);
		// send email to admin
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$emailOptions = get_option( 'swpsmtp_options' );
		$toWhiteLabel = $emailOptions[ 'from_email_field' ];
		$subject = "New investment in : $companyName";
		$message = "A new investment done in company: $companyName <br/>";
		$message .=  "Details are: <br/>";
		$message .= 'Name of Product: '.$companyName.'<br/>
						Reference: '.$reference.'<br/>
						Amount: £'.$amount.'<br/><br/>';
		//$message .= "Please take an action on the investment <a href='https://therightcrowd.com/wp-admin/edit.php?post_type=investment' >here</a><br/><br/>";
		$message .= "Thank you";
		wp_mail($toWhiteLabel, $subject, $message,$headers);

		// fire email to user for thank you
		//email_user_on_payment($userID,$projectID,array('amount' => $amount));

		// email bank transfer status and details
		if($canDoInvestment == 'yes') {
			//email_user_on_bank_transfer_payment($userID,$projectID,array('amount' => $amount,'ref'=>$reference,'transfertype' => $transfertype, 'source' => $source));
		}
		//generatePDFformAndSendEmail($userID,$projectID,array('amount' => $amount,'ref'=>$reference));

		// generate application form pdf only for site https://pp09spv.com/ - site id 74
		if($currentSiteID == '74') {
			$umetas = get_user_meta($userID);
			generateUserApplicationPDF($userID, array('first_name' => $umetas['billing_first_name'][0], 'last_name' => $umetas['billing_last_name'][0],'inv_id' => $post_id, 'inv_amount' => $amount));
		}

		$ret['status'] = 'true';
		$ret['redirect'] = esc_url(get_option("payment_success_page"))."?pid=".$projectID;
		echo json_encode($ret);die();
	}

	echo json_encode($ret);die();
}
/*add_action( 'wp_mail_failed', 'onMailError', 10, 1 );
function onMailError( $wp_error ) {
    echo "<pre>";
    print_r($wp_error);
    echo "</pre>";
	exit;
}  */
function getFundRaised($period = 'today') {
	// $period could be today, week, month, all/''
	$isManager = isManager();
	$currentUserID = get_current_user_id();
	global $wpdb, $switched;
	$site_id = get_current_blog_id();
	switch_to_blog(1);
	$date = date('Y-m-d'); //'2019-03-26';
        //$result = $wpdb->get_row( $wpdb->prepare("SELECT SUM(meta_value) as total FROM ".$wpdb->prefix."postmeta WHERE site_id = ANY(SELECT site_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = '%s'  AND meta_value = '%d') AND meta_key = '%s'", 'site_id', $site_id, 'themeum_investment_amount'));
        //return $result->total;
	$args = array(
		'post_type' => 'investment',
		'post_status'    => array('publish'), //'pending','pack_out','transfer_requested'
		'author' => $currentUserID,
		'meta_query' => array(
			array(
			    'key' => 'themeum_investment_date',
			    'value' => $date,
			    'compare' => '=',
					'type'    => 'DATE'
			)
		    )
	);
if($site_id != 1) {
	$args['meta_query'][] = array(
            'key' => 'site_id',
            'value' => $site_id,
            'compare' => '='
        );
}
if($period == 'week') {
	$dayOfWeek = date('w');
	if($dayOfWeek == 1) {
		$lastMonday = date('Y-m-d');
	}else{
		$lastMonday = date('Y-m-d',strtotime('last monday',time()));
	}
	$today = date('Y-m-d');
	$args['meta_query'] = array(
		/*array(
			'key'     => 'themeum_investment_date',
			'value'   => $today,
			'compare' => '<=',
			'type'    => 'DATE'
		),
		array(
			'key'     => 'themeum_investment_date',
			'value'   => $lastMonday,
			'compare' => '>=',
			'type'    => 'DATE'
		)*/
		array(
				array(
					'key'     => 'themeum_complete_date',
					'value'   => $today,
					'compare' => '<=',
					'type'    => 'DATE'
				),
				array(
					'key'     => 'themeum_complete_date',
					'value'   => $lastMonday,
					'compare' => '>=',
					'type'    => 'DATE'
				)
			)
    );
	if($site_id != 1) {
		$args['meta_query'][] = array(
				'key' => 'site_id',
				'value' => $site_id,
				'compare' => '='
			);
	}
}

if($period == 'month') {
	$firstDate = date('Y-m-01');
    $lastDate  = date('Y-m-t');

	$args['meta_query'] = array(
			//'relation' => 'OR',
			/*array(
				array(
					'key'     => 'themeum_investment_date',
					'value'   => $lastDate,
					'compare' => '<=',
					'type'    => 'DATE'
				),
				array(
					'key'     => 'themeum_investment_date',
					'value'   => $firstDate,
					'compare' => '>=',
					'type'    => 'DATE'
				)
			),*/
			array(
				array(
					'key'     => 'themeum_complete_date',
					'value'   => $lastDate,
					'compare' => '<=',
					'type'    => 'DATE'
				),
				array(
					'key'     => 'themeum_complete_date',
					'value'   => $firstDate,
					'compare' => '>=',
					'type'    => 'DATE'
				)
			)
    );
	if($site_id != 1) {
		$args['meta_query'][] = array(
				'key' => 'site_id',
				'value' => $site_id,
				'compare' => '='
			);
	}
}

// remove if current user is manager
if($isManager) {
	unset($args['author']);
}

if($period == 'today') {
	$args['post_status'] = array('publish', 'received', 'pending','pack_out','transfer_requested');
}

$args['posts_per_page'] = '-1';


$the_query  = new WP_Query( $args );
if($the_query->post_count > 0) {
	$total = 0;
	foreach($the_query->posts as $post) {
		$amount = get_post_meta($post->ID,'themeum_investment_amount',true);
		$amount = floatval($amount);
		$total += $amount;
	}
}
restore_current_blog();
	/*echo "<pre>";
	print_r($the_query);*/
	return floatval($total);
}

function getFundStats($period = 'today') {
	// $period could be today, week, month

	$isManager = isManager();
	$currentUserID = get_current_user_id();
	global $wpdb, $switched;
	$site_id = get_current_blog_id();

	switch_to_blog(1);

	if($period == 'today') {
		// investments
		$date = date('Y-m-d'); //'2019-03-26';
		$args = array(
			'post_type' => 'investment',
			'post_status'    => array('publish', 'received', 'pending','pack_out','transfer_requested'), //'pending','pack_out','transfer_requested'
			'author' => $currentUserID,
			'meta_query' => array(
				array(
					'key' => 'themeum_investment_date',
					'value' => $date,
					'compare' => '=',
					'type'    => 'DATE'
				)
			)
		);

		$completeMetaQuery = array(
			array(
				'key' => 'themeum_complete_date',
				'value' => $date,
				'compare' => '=',
				'type'    => 'DATE'
			)
		);

		// users
		$userArgs = [
			'blog_id' => $site_id,
			'date_query' => [
				[
					'year'  => current_time( 'Y' ),
					'month' => current_time( 'm' ),
					'day'   => current_time( 'd' ),
				]
			]
		];
	}

	if($period == 'week') {

		// investments
		$dayOfWeek = date('w');
		if($dayOfWeek == 1) {
			$lastMonday = date('Y-m-d');
		}else{
			$lastMonday = date('Y-m-d',strtotime('last monday',time()));
		}
		$today = date('Y-m-d');
		$args = array(
			'post_type' => 'investment',
			'post_status'    => array('publish', 'received', 'pending','pack_out','transfer_requested'),
			'author' => $currentUserID,
			'meta_query' => array(
					array(
						array(
							'key'     => 'themeum_investment_date',
							'value'   => $today,
							'compare' => '<=',
							'type'    => 'DATE'
						),
						array(
							'key'     => 'themeum_investment_date',
							'value'   => $lastMonday,
							'compare' => '>=',
							'type'    => 'DATE'
						)
					)
			)
		);

		$completeMetaQuery = array(
				array(
					array(
						'key'     => 'themeum_complete_date',
						'value'   => $today,
						'compare' => '<=',
						'type'    => 'DATE'
					),
					array(
						'key'     => 'themeum_complete_date',
						'value'   => $lastMonday,
						'compare' => '>=',
						'type'    => 'DATE'
					)
				)
		);

		// users
		$userArgs = [
			'blog_id' => $site_id,
			'date_query' => [
				[
					'after' => 'last week'
				]
			]
		];
	}

	if($period == 'month') {
		// investments
		$firstDate = date('Y-m-01');
		$lastDate  = date('Y-m-t');
		$args = array(
			'post_type' => 'investment',
			'post_status'    => array('publish', 'received', 'pending','pack_out','transfer_requested'),
			'author' => $currentUserID,
			'meta_query' => array(
				array(
						array(
							'key'     => 'themeum_investment_date',
							'value'   => $lastDate,
							'compare' => '<=',
							'type'    => 'DATE'
						),
						array(
							'key'     => 'themeum_investment_date',
							'value'   => $firstDate,
							'compare' => '>=',
							'type'    => 'DATE'
						)
					)
			)
		);

		$completeMetaQuery = array(
				array(
					array(
						'key'     => 'themeum_complete_date',
						'value'   => $lastDate,
						'compare' => '<=',
						'type'    => 'DATE'
					),
					array(
						'key'     => 'themeum_complete_date',
						'value'   => $firstDate,
						'compare' => '>=',
						'type'    => 'DATE'
					)
				)
		);
		// users
		$userArgs = [
			'blog_id' => $site_id,
			'date_query' => [
				[
					'after' => 'last month'
				]
			]
		];
	}

	if($period == 'year') {
		// investments
		$firstDate = date('Y-01-01');
		$lastDate  = date('Y-m-t');
		$args = array(
			'post_type' => 'investment',
			'post_status'    => array('publish', 'received', 'pending','pack_out','transfer_requested'),
			'author' => $currentUserID,
			'meta_query' => array(
				array(
						array(
							'key'     => 'themeum_investment_date',
							'value'   => $lastDate,
							'compare' => '<=',
							'type'    => 'DATE'
						),
						array(
							'key'     => 'themeum_investment_date',
							'value'   => $firstDate,
							'compare' => '>=',
							'type'    => 'DATE'
						)
					)
			)
		);

		$completeMetaQuery = array(
				array(
					array(
						'key'     => 'themeum_complete_date',
						'value'   => $lastDate,
						'compare' => '<=',
						'type'    => 'DATE'
					),
					array(
						'key'     => 'themeum_complete_date',
						'value'   => $firstDate,
						'compare' => '>=',
						'type'    => 'DATE'
					)
				)
		);
		// users
		$userArgs = [
			'blog_id' => $site_id,
			'date_query' => [
				[
					'after' => 'last month'
				]
			]
		];
	}

	if($site_id != '1') {
		$args['meta_query'][] = array(
				'key' => 'site_id',
				'value' => $site_id,
				'compare' => '='
			);
	}

	if($site_id == '1') {
		unset($userArgs['blog_id']);
	}

	$users = new WP_User_Query($userArgs);
	$totalUsers = $users->get_total();

	// remove if current user is manager
	if($isManager) {
		unset($args['author']);
	}
	// limit
	$args['posts_per_page'] = '-1';

	// PLEDGED
	$the_query  = new WP_Query( $args );
	if($the_query->post_count > 0) {
		$pledged = 0;
		foreach($the_query->posts as $post) {
			$amount = get_post_meta($post->ID,'themeum_investment_amount',true);
			$amount = floatval($amount);
			$pledged += $amount;
		}
	}
	wp_reset_query(); // destroying previous query object

	// change post status to received only
	$args['post_status'] = array('received');
	$the_query  = new WP_Query( $args );
	if($the_query->post_count > 0) {
		$received = 0;
		foreach($the_query->posts as $post) {
			$amount = get_post_meta($post->ID,'themeum_investment_amount',true);
			$amount = floatval($amount);
			$received += $amount;
		}
	}

	wp_reset_query(); // destroying previous query object

	// change post status to complete only and replace investment date to complete date meta query
	$args['post_status'] = array('publish');
	$firstDate = date('Y-m-01');
	$lastDate  = date('Y-m-t');
	$args['meta_query'] = $completeMetaQuery;
	if($site_id != '1') {
		$args['meta_query'][] = array(
				'key' => 'site_id',
				'value' => $site_id,
				'compare' => '='
			);
	}

	$the_query  = new WP_Query( $args );
	if($the_query->post_count > 0) {
		$complete = 0;
		foreach($the_query->posts as $post) {
			$amount = get_post_meta($post->ID,'themeum_investment_amount',true);
			$amount = floatval($amount);
			$complete += $amount;
		}
	}
	wp_reset_query(); // destroying previous query object

	restore_current_blog();

	$arr = [
		'users' => $totalUsers,
		'pledged' => floatval($pledged),
		'received' => floatval($received),
		'complete' => floatval($complete)
	];
	return $arr;
}

add_filter( 'views_edit-investment', 'meta_views_investment_pending', 10, 1 );

function meta_views_investment_pending( $views )
{
    //$views['separator'] = '|';
    $views['metakey'] = '<a href="edit.php?meta_data=themeum_payment_method&post_type=investment">All Bank Transfers</a>';
    return $views;
}

add_action( 'load-edit.php', 'load_bank_transfers_only' );

function load_bank_transfers_only()
{
    global $typenow;

    // Adjust the Post Type
    if( 'investment' != $typenow )
        return;

    add_filter( 'posts_where' , 'load_bank_transfers_only_posts_where' );
}

function load_bank_transfers_only_posts_where( $where )
{
    global $wpdb;
    if ( isset( $_GET[ 'meta_data' ] ) && !empty( $_GET[ 'meta_data' ] ) )
    {
        $meta = esc_sql( $_GET['meta_data'] );
        $where .= " AND ID IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key='$meta' AND meta_value='bank' )";
    }

    return $where;
}


// show all pending bank transfers
add_filter( 'views_edit-investment', 'meta_views_investment_pending_bank', 10, 1 );
function meta_views_investment_pending_bank( $views )
{
    //$views['separator'] = '|';
    $views['metakey_pending'] = '<a href="edit.php?meta_data_pending=themeum_status_all&post_type=investment">Pending Bank Transfers</a>';
    return $views;
}

add_action( 'load-edit.php', 'load_pending_bank_transfers_only' );

function load_pending_bank_transfers_only()
{
    global $typenow;

    // Adjust the Post Type
    if( 'investment' != $typenow )
        return;

    add_filter( 'posts_where' , 'load_pending_bank_transfers_only_posts_where' );
}

function load_pending_bank_transfers_only_posts_where( $where )
{
    global $wpdb;
    if ( isset( $_GET[ 'meta_data_pending' ] ) && !empty( $_GET[ 'meta_data_pending' ] ) )
    {
        $meta = esc_sql( $_GET['meta_data_pending'] );
        $where .= " AND ID IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key='$meta' AND meta_value='pending' ) AND ID IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key='themeum_payment_method' AND meta_value='bank' )";
    }

    return $where;
}


/*add_action( 'pending_to_publish', function( $post ){
	// your code
	if($post->post_type != 'investment') return;

	update_post_meta($post->ID,'themeum_status_all','complete');
} );

add_action( 'publish_to_pending', function( $post ){
	// your code
	if($post->post_type != 'investment') return;

	update_post_meta($post->ID,'themeum_status_all','pending');
} );*/

function getProjectInvestments($projectID, $params = array()){
	switch_to_blog(1);

	$args = array(
		'post_type' => 'investment',
		'post_status'    => array('publish'),
		'meta_query' => array(
			array(
				'key'     => 'themeum_investment_project_id',
				'value'   => $projectID,
				'compare' => '='
			)
		),
		'posts_per_page'    => -1
	);
	if(isset($params['limit']) && $params['limit'] > 0) {
		$args['posts_per_page'] = $params['limit'];
	}
	if(isset($params['is_sh_synced'])) {
		$args['meta_query'][] = array(
				'key'     => 'is_sh_synced',
				//'value'   => 'yes',
				'compare' => 'NOT EXISTS'
			);
	}
	$investments = get_posts( $args );
	restore_current_blog();
	if(count($investments) > 0) {
		return $investments;
	}
	return false;
}

add_action('transition_post_status','post_status_change_on_investment',9999,3);
 function post_status_change_on_investment($new_status,$old_status,$post){
	global $wpdb;
  if($post->post_type != 'investment') return;

  if(isset($_REQUEST['post_status'])) {
	  $new_status = $_REQUEST['post_status'];
  }else if (isset($_REQUEST['_status'])) {
	  $new_status = $_REQUEST['_status'];
  }
  //echo $new_status;exit;
  if($new_status == 'publish' || $new_status == 'complete') {
	$projectID = get_post_meta($post->ID,'themeum_investment_project_id',true);
	$investments = 	getProjectInvestments($projectID);

	update_post_meta($post->ID,'themeum_status_all','complete');
	update_post_meta($post->ID,'themeum_complete_date',date("Y-m-d H:i:s"));
	update_post_meta($post->ID, 'share_status', 'notforsale');
	update_post_meta($post->ID, 'share_offering', esc_attr('ordinary'));

	update_post_meta($projectID,'thm_trx_display','1'); // setting this project to list in portfolio on main site

	$site = get_post_meta($post->ID,'site_id',true);
	//send email to user regarding its confirmation
	$uid = get_post_meta($post->ID,'themeum_investor_user_id',true);
	global $switched;
	switch_to_blog($site);
	$user = get_userdata($uid);
	$umetas = get_user_meta($uid);
	$siteURL = home_url();

	emailUserOnPendingInvestmentConfirmation($post,$user,$siteURL);
	restore_current_blog();

	// create company to The ShareHub if its first investment
	switch_to_blog(1);
	// create shares
	// add shares now
		$com = get_post($projectID);
		$coupon = get_post_meta($projectID,'thm_coupon',true);
		$shareargs = array('post_type'=>'shares','post_parent'=>$projectID,'post_status'=>'publish','post_author'=>$uid);
		$shareargs['post_title'] = 'Shares of company '.$com->post_title;
		$share_id = wp_insert_post( $shareargs );
		$invesmentMetas = get_post_meta($post->ID);

		if((int)$share_id > 0) {
			update_post_meta($share_id, 'thm_businessid', $projectID);
			update_post_meta($share_id, 'thm_user_id', $uid);
			update_post_meta($share_id, 'thm_user_email', esc_attr($user->data->user_email));
			update_post_meta($share_id, 'thm_user_firstname', $umetas['first_name'][0]);
			update_post_meta($share_id, 'thm_user_surname', $umetas['last_name'][0]);
			update_post_meta($share_id, 'thm_noshares', esc_attr($invesmentMetas['themeum_shares'][0]));
			//update_post_meta($share_id, 'thm_purchasedate', esc_attr($datePurchased));
			update_post_meta($share_id, 'thm_status', 'notforsale');
			update_post_meta($share_id, 'thm_offering', esc_attr('ordinary'));
			update_post_meta($share_id, 'thm_share_type', 'share');
			update_post_meta($share_id, 'trc_investment_id',$post->ID);
			if($coupon != '') {
				$couponPaid = get_post_meta($projectID,'thm_coupon_paid',true);
				update_post_meta($share_id, 'thm_coupon', $coupon);
				update_post_meta($share_id, 'thm_coupon_paid', $couponPaid);
				update_post_meta($share_id, 'thm_is_coupon', 'yes');
			}

			// assign customer user role to user
			$user = new WP_User($uid);
			$user->add_role('customer');
		}
	restore_current_blog();
  }else if($new_status == 'received') {
	$q = "update ".$wpdb->prefix."posts set post_status='$new_status' where ID=".$post->ID;
	$wpdb->query($q);
	update_post_meta($post->ID,'themeum_status_all','received');
	update_post_meta($post->ID,'themeum_received_date',date("Y-m-d H:i:s"));

	//send email
	$uid = get_post_meta($post->ID,'themeum_investor_user_id',true);
	email_user_on_funding_received($uid, $post->ID);
  }else if($new_status == 'cancelled') {
	$q = "update ".$wpdb->prefix."posts set post_status='$new_status' where ID=".$post->ID;
	$wpdb->query($q);
	update_post_meta($post->ID,'themeum_status_all','cancelled');
	update_post_meta($post->ID,'themeum_cancelled_date',date("Y-m-d H:i:s"));

  }else if($new_status == 'investment_closed') {
	$q = "update ".$wpdb->prefix."posts set post_status='$new_status' where ID=".$post->ID;
	$wpdb->query($q);
	update_post_meta($post->ID,'themeum_status_all','investment_closed');
  }else if($new_status == 'pending'){
	$q = "update ".$wpdb->prefix."posts set post_status='$new_status' where ID=".$post->ID;
	$wpdb->query($q);
	update_post_meta($post->ID,'themeum_status_all','pending');
  }else if($new_status == 'transfer_requested'){
	$q = "update ".$wpdb->prefix."posts set post_status='$new_status' where ID=".$post->ID;
	$wpdb->query($q);
	update_post_meta($post->ID,'themeum_status_all','transfer_requested');
  }else if($new_status == 'pack_out'){
	$q = "update ".$wpdb->prefix."posts set post_status='$new_status' where ID=".$post->ID;
	$wpdb->query($q);
	update_post_meta($post->ID,'themeum_status_all','pack_out');
	update_post_meta($post->ID,'themeum_pack_out_date',date("Y-m-d H:i:s"));
  }else if($new_status == 'hidden'){
	$q = "update ".$wpdb->prefix."posts set post_status='$new_status' where ID=".$post->ID;
	$wpdb->query($q);
	update_post_meta($post->ID,'themeum_status_all','hidden');
  }

 }

function emailUserOnPendingInvestmentConfirmation($investment,$holder, $siteURL) {
	restore_current_blog();
	//$siteURL = get_site_url();
	global $switched;
	switch_to_blog(1);
	$companyID = get_post_meta($investment->ID,'themeum_investment_project_id',true);
	$siteID = get_post_meta($investment->ID,'site_id',true);

	$blog_details = get_blog_details( $siteID );
	$siteName = $blog_details->blogname;
	$uMetas = get_user_meta($holder->ID);
	$companyName = get_the_title($companyID);
	$subject = "Your investment is complete.";
	$message = "Hello ".$uMetas['billing_first_name'][0]." ".$uMetas['billing_last_name'][0].",<br/><br/> I hope this email finds you well. <br/><br/>Your funds have been received and your investment is now complete.";
	//if($siteID == 55) {
		//$message .= ".";
	//}else{
		//$message .= " and your certificate will be issued shortly";
	//}
	$message .= "<br/><br/>";

	if($siteID != 57){
		$message .= "Please login to your <a href='".$siteURL."/dashboard'>dashboard</a> to see your holdings.<br/><br/>";
	}
	$message .= "For any questions or queries please do not hesitate to contact us on the details below.<br/><br/>";
	restore_current_blog();
	switch_to_blog($siteID);
	$smtpOptions = get_option('swpsmtp_options');
	$message .= "Kind Regards<br/>".$companyName."<br/>".$smtpOptions['from_email_field'];
	$headers = array('Content-Type: text/html; charset=UTF-8');
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
	restore_current_blog();
	switch_to_blog($siteID);
	wp_mail($holder->data->user_email, $subject, $body,$headers);
	restore_current_blog();

}

add_action( 'wp_ajax_nopriv_project_section_settings', 'project_section_settings' );
add_action( 'wp_ajax_project_section_settings', 'project_section_settings' );
function project_section_settings() {
		restore_current_blog();
	$isManager = isManager();
	global $switched;
   switch_to_blog(1);
	$ret = array('status'=>'false');
	if($isManager) {
		if( (int) $_POST['pid'] > 0) {
			$value = esc_attr($_POST['value']);
			$old = get_post_meta($_POST['pid'],'sections_to_hide',true);
			if($old != '') {
				if(esc_attr($_POST['checked']) == 'yes') {
						$old[] = $value;
						$newArray = array_unique($old);
				}else{
					if (($key = array_search($value, $old)) !== false) {
						unset($old[$key]);
					}
					$newArray = $old;
				}
			}else{
				$newArray = array($value);
			}
			update_post_meta($_POST['pid'],'sections_to_hide',$newArray);

			$ret['status'] = 'true';
			restore_current_blog();
			echo json_encode($ret);die();
		}
	}
	restore_current_blog();
	echo json_encode($ret);die();
}

add_action( 'wp_ajax_nopriv_project_set_order', 'project_set_order' );
add_action( 'wp_ajax_project_set_order', 'project_set_order' );
function project_set_order() {
	restore_current_blog();
	$siteID = get_current_blog_id();
	$isManager = isManager();
	global $switched;
   switch_to_blog(1);
	$ret = array('status'=>'false');
	if($isManager) { // Are you a manager? no??? Naughty!! Go back and be a man-ager
		parse_str($_POST['items'], $itemsArray);
		if( count($itemsArray['pid']) > 0) {
			$order = 1;
			foreach($itemsArray['pid'] as $pid) {
				$keyName = 'project_order_'.$siteID;
				update_post_meta($pid,$keyName,$order);
				$order++;
			}
			$ret['status'] = 'true';
			restore_current_blog();
			echo json_encode($ret);die();
		}
	}
	restore_current_blog();
	echo json_encode($ret);die();
}

add_action('add_meta_boxes','tm_metaBoxes');
add_action('save_post','tm_save_Posts',10,3);
function tm_metaBoxes(){
add_meta_box('tm_one_meta_Box', 'Team Members', 'tm_teammembers','project','normal','high' );
add_meta_box('user_info_investment_meta_box', 'User Info', 'tm_userinfo_investment','investment','normal','high' );
}

function tm_teammembers() {
	global $wpdb;
    global $post;

	$pSiteId = get_post_meta($post->ID, 'thm_site_id', true);
	$teamMembers = get_post_meta($post->ID, 'thm_team_members', true);
    $teamMembers = unserialize($teamMembers);
	if($teamMembers && count($teamMembers) > 0) {
		//echo "<ul class='team_members'>";
		foreach($teamMembers as $key => $member){ ?>

				<?php
				$mem_image = '';
				if($member['image'] > 0) {
					switch_to_blog($pSiteId);
					$mem_image = wp_get_attachment_image_src($member['image'], 'project-thumb');
					restore_current_blog();
				}
				?>

			<div class='team-fields-wrapper' id='<?php echo $key ?>'>
			   <span class='remove-team-member' data-order='<?php echo $key ?>' style='cursor: pointer;' >Remove</span>
				<fieldset>
					<div class='team-field form-group'>
						<label>Title</label>
						<input type="text" name="team_member_title[<?php echo $key ?>]" id='team_member_title<?php echo $key ?>' value="<?php echo $member['title'] ?>" />
					</div>
					<div class='team-field form-group'>
						<label>Name</label>
						<input type="text" name="team_member_name[<?php echo $key ?>]" id='team_member_name<?php echo $key ?>' value="<?php echo $member['name'] ?>" />
					</div>
					<div class='team-field form-group'>
						<label>Image</label>
						<input type="file" name="team_member_file[<?php echo $key ?>]" id='team_member_file<?php echo $key ?>' />
						<input type="hidden" name="t_f_id_<?php echo $key ?>" value="<?php echo $member['image'] ?>" />
						<?php if($mem_image != '') { ?>
						<div id="img_previw_<?php echo $key ?>"><img src="<?php echo $mem_image[0] ?>" /></div>
						<?php } ?>
					</div>
					<div class='team-field form-group'>
						<label>Bio</label>
						<input type="text" name="team_member_bio[<?php echo $key ?>]" id='team_member_bio<?php echo $key ?>' value="<?php echo $member['bio'] ?>" />
					</div>
				</fieldset>
			</div>
		<?php } ?>

		<?php
	}else{
		echo "No Team Members found!";
	}
	?>
	<input type="hidden" id="team_order_key" name="team_order_key" value="<?php echo ($key >= 1) ? ++$key : 1 ?>" />
	<div class="form-group" id="team_section">
		<div id="team_fields_section"></div>
		<a href="javascript:void(0)" id="add_team_fields">Add Member</a>
	</div>
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
			<div class='team-field form-group'>
				<label>Image</label>
				<input type="file" name="team_member_file[{{order_number}}]" id='team_member_file{{order_number}}'/>
			</div>
			<div class='team-field form-group'>
				<label>Bio</label>
				<input type="text" name="team_member_bio[{{order_number}}]" id='team_member_bio{{order_number}}'/>
			</div>
		</fieldset>
	</div>
</script>
<script type="text/javascript">
team_order = jQuery("#team_order_key").val();
	jQuery(function($){
		$('#team_section').on('click','#add_team_fields',function(){
			var teamFieldsHtml = $('#team_fields').html();
			teamFieldsHtml = teamFieldsHtml.replace(/{{order_number}}/g, team_order);
			$('#team_fields_section').append(teamFieldsHtml);
			team_order++;
		});

		// remove team member
		$('#team_section, .team-fields-wrapper').on('click','.remove-team-member',function(){
			if(confirm('Are you sure?')) {
				var orderNo = $(this).attr('data-order');
				$('#'+orderNo).remove();
			}
		});

	});
</script>

<?php }

function tm_save_Posts($post_id,$post,$update){
	global $pagenow;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE && !is_admin()) {
        return $post_id;
    }
	$cSite = get_current_blog_id();
	if($post->post_type != 'project') {
		return $post_id;
	}

	if($pagenow != 'post.php') {
		return $post_id;
	}

	if(isset($_FILES['team_member_file']) && count($_FILES['team_member_file']['name']) > 0) {
		foreach($_FILES['team_member_file']['name'] as $k => $f) {
			$fK = 'team_member_file_'.$k;
			$_FILES[$fK]['name'] = $_FILES['team_member_file']['name'][$k];
			$_FILES[$fK]['type'] = $_FILES['team_member_file']['type'][$k];
			$_FILES[$fK]['tmp_name'] = $_FILES['team_member_file']['tmp_name'][$k];
			$_FILES[$fK]['error'] = $_FILES['team_member_file']['error'][$k];
			$_FILES[$fK]['size'] = $_FILES['team_member_file']['size'][$k];
		}
	}
	$teamOutput = [];
	/*echo "<pre>";
	echo $post_id;
	print_r($_FILES);
	echo "</pre>";exit;*/
	if(isset($_POST['team_member_name'])) {
		foreach($_POST['team_member_name'] as $orderKey => $value) {
			$f_id_key = 't_f_id_'.$orderKey;
			$file_id = isset($_POST[$f_id_key]) ? $_POST[$f_id_key] : 0; // setting default file id
			// upload file to wp directory and get the file id
			$fUploadName = 'team_member_file_'.$orderKey;
			switch_to_blog($cSite);
			if(isset($_FILES[$fUploadName]) && $_FILES[$fUploadName]['error'] === 0) {
				$file_id = media_handle_upload( $fUploadName, 0 );
			}
			restore_current_blog();
			$teamOutput[$orderKey] = array(
				'title' => isset($_POST['team_member_title'][$orderKey]) ? $_POST['team_member_title'][$orderKey] : '',
				'name' => isset($_POST['team_member_name'][$orderKey]) ? $_POST['team_member_name'][$orderKey] : '',
				'bio' => isset($_POST['team_member_bio'][$orderKey]) ? $_POST['team_member_bio'][$orderKey] : '',
				'image' => $file_id
			);
		}
	}
	$teamMembers = serialize($teamOutput);
	restore_current_blog();
	switch_to_blog(1);
	//delete_post_meta($post_id,'thm_team_members');
	update_post_meta((int)$post_id, 'thm_team_members', (string)$teamMembers);
	restore_current_blog();

 }

 function getSharePrice($companyID,$format = 'pence', $withSign = true){
	$sharePrice = get_post_meta($companyID,'thm_share_price',true);
	$shareCurrency = get_post_meta($companyID,'share_currency',true);
	if($shareCurrency == '') {
		$shareCurrency = 'GBP';
	}
	if($sharePrice != '') {
		/*if($companyID==9089):
				$sharePrice = floatval($sharePrice);
		else:
				$sharePrice = floatval(preg_replace('/[^d.]/', '', $sharePrice));
		endif;*/
		if($format != 'pence') {
			if($sharePrice >= 100) {
				$n = $sharePrice/100;
				$sharePrice = number_format($n, 2, '.', '');
				if($withSign === true) {
					$sharePrice = get_currencies($shareCurrency).''.$sharePrice;
				}
			}else{
				if($withSign === true) {
					$sharePrice = $sharePrice.' '.getLowerCurrencyCode($shareCurrency);
				}
			}
		}
	}
	return $sharePrice;
 }

 function convertStringToNumbers($number){
	 return floatval(preg_replace('/[^0-9]/', '', $number));
 }

 function emailSubscribersOnNews($companyId,$newsItem) {
	restore_current_blog();
	$homeURL = home_url();
	global $switched;
	switch_to_blog(1);
	$pURL = getProjectURL($companyId,$homeURL);
	$companyName = get_the_title($companyId);
	$subject = "Latest Update: ".$companyName;
	$message = "An update has been posted by $companyName<br/><br/>";
	$message .= $newsItem['title'] ."<br/><br/>";
	$message .= "To read full update : <a href='".$pURL."'>click here</a><br/><br/>";
	$message .= "Best Wishes<br/>The Right Crowd";

	$body = $message; //getEmailBody($subject,$message);

	/*
	WILL REOPEN
	*/
	/*$shareholders = getProjectInvestors($companyId);
	$headers = array('Content-Type: text/html; charset=UTF-8');
	if(count($shareholders)) {
		foreach($shareholders as $sh) {
			$holder = get_userdata($sh);
			//$subject = $subject." : ".$holder->user_email;
			wp_mail($holder->user_email, $subject, $body,$headers);
		}
	}*/
}

function wp_send_email_on_new_company_publish($args = array()) {

		  /*if(isset($args['to']) && $args['to'] != '' && filter_var($args['to'],FILTER_VALIDATE_EMAIL)) {
		  	       $subject = "Congratulations! Your company is now live on Sharehub";

			  $message  = "<p>Congratulations your company is now live with Sharehub, please remember to update your news section on the site on a regular basis so you keep your shareholders up-to-date.</p>";
			  $message .= "<p>We would like to remind you of our premium package at just £29 per month you can reach our large and expanding client base.</p>";
			  //$message .= "<p><a href='https://therightx.com?add-to-cart=15475' class='btn confirm-package-btn'>Confirm and Activate Subscription</a></p>";
	  		  $message .= "<p>If you have any questions, please email us at admin@therightx.com</p>";
	  		  $message .= "Best Wishes<br/>The Sharehub";

  	  		  $body = getEmailBody($subject,$message);

	  		  $headers = array('Content-Type: text/html; charset=UTF-8');
		     //wp_mail($args['to'], $subject, $body, $headers);
		  }	*/
}

add_action( 'transition_post_status', 'onCompanyPublished', 898999, 3 );
function onCompanyPublished( $new_status, $old_status, $post )
{
    if ( 'project' !== $post->post_type )
        return; // restrict the filter to a specific post type

	 if ( 'publish' !== $new_status or 'publish' === $old_status )
        return;


	restore_current_blog();
	//$siteID = get_current_blog_id();
	//switch_to_blog($siteID);
    //$userDetail = get_userdata($post->post_author);
	$mailSettings = get_option('woocommerce_wc_new_project_settings');

	$mailer = WC()->mailer();
	$mails = $mailer->get_emails();
	if ( ! empty( $mails ) ) {
		foreach ( $mails as $mail ) {
			if ( $mail->id == 'wc_new_project' ) {
				$mail->subject = $mailSettings['subject'];
				$mail->recipient = $mailSettings['recipient'];
			   $mail->trigger( $post->ID );
			   break;
			}
		 }
	}

	switch_to_blog(1);

	/*restore_current_blog();
	$siteid = get_post_meta($post->ID,'thm_site_id',true);
	restore_current_blog();
	switch_to_blog($siteid);
	$subs = get_users( array('role__in' => 'subscriber') );
	restore_current_blog();
	foreach($subs as $sub) {
		$mail->recipient = $sub->user_email;
		//$mail->subject = "Company '".$post->post_title."' is published.";
		$mail->trigger( $post->ID );
	}*/
    //wp_send_email_on_new_company_publish(array('to'=> $userDetail->user_email));
}

function wp_send_email_on_new_company($args = array()) {

		  if(isset($args['to']) && $args['to'] != '' && filter_var($args['to'],FILTER_VALIDATE_EMAIL)) {
		  	       $subject = "Thank you for registering your company.";
	  $message = "<div style='width:600px;margin:auto;background-color:#ffffff;border-radius: 3px!important;
    background-color: #ffffff;
    border: 1px solid #dedede;'>"; // main wrapper
		  $message .= getEmailHeader();
		  $message .= "<div style='padding: 36px 48px; background: #52B7EA; color: #fff;'><h1>".$subject."</h1></div>";
		  $message .= "<div style='padding: 48px;'>"; // content
			  $message  .= "<p>Thank you for registering your company with The Right Crowd; On completing and uploading all the relevant files we will automatically email your shareholders with their login details so please remember to update your news section on the site on a regular basis so you keep your shareholders up-to-date.</p>";
			  $message .= "<p>Your News will be visible to all users of TheRightCrowd for a 3 month period for free. If you wish to continue sharing your information and news with thousands of potential new investors join our premium subscribers for £39 per month (12 month contract), alternatively your news will only be shared with your current investors.</p>";
	  		  $message .= "Best Wishes<br/>TheRightCrowd Team";
	  	  $message .= "</div>"; // end of content
	  	  $message .= getEmailFooter();
  	  $message .= "</div>"; // end of main wrapper


	  		  $headers = array('Content-Type: text/html; charset=UTF-8');
		     wp_mail($args['to'], $subject, $message, $headers);
		  }
}

function wp_send_admin_email_on_new_company($args = array()) {

		  if(isset($args['subject']) && $args['subject'] != '') {
			  $message  = "Edit and approve it here: n";
			  $message .= admin_url('edit.php?post_type=project');
		     wp_mail('tom@therightcrowd.com', $args['subject'], $message);
		  }
}

add_action('wp_footer', 'custom_login_signup_modal_function');
function custom_login_signup_modal_function(){
	?>
	<!-- NINO National Insurance Number check js function -->
	<script id="nino" type="text/javascript">
		function checkNINO (toCheck) {
		  var exp1 = /^[A-CEGHJ-NOPR-TW-Z]{1}[A-CEGHJ-NPR-TW-Z]{1}[0-9]{6}[A-Ds]{1}/i;
		  var exp2 = /(^GB)|(^BG)|(^NK)|(^KN)|(^TN)|(^NT)|(^ZZ).+/i;

		  if (toCheck.match(exp1) && !toCheck.match(exp2))
			  return toCheck.toUpperCase()
			else
			  return false;
		}

		jQuery("#ni_number").blur(function(e){
			var ni = jQuery(this).val();
			if(ni != '') {
				var formattedNI = checkNINO(ni);
				if(formattedNI !== false){
					jQuery(this).val(formattedNI);
				}else{
					alert("National Insurance Number has invalid format");
					setTimeout(function() { jQuery("#ni_number").focus(); }, 100);
					e.preventDefault();
				}
			}
		});
		var lookedAddresses; // global variable to fill addresses after responses and use in on change event to select field
		// address lookup ajax to api
		jQuery("#find_address").click(function(){
			var pc = jQuery("#address_lookup").val();
			var api_url = 'https://api.getAddress.io/find/'+pc;
			jQuery.ajax({
						url: api_url,
						type: 'GET',
						data: {
							'api-key': 'pldwkTlAdkq04Es7cGwSnQ22195'
						}
			})
      .done(function(data) {
				if(data.addresses.length > 0) {
					lookedAddresses = data.addresses;
					jQuery('#lookedup_addresses').show();
					var selField = jQuery('#lookedup_addresses');
					if(jQuery("#add-not-found-error").length){
						jQuery("#add-not-found-error").remove();
					}
					selField.find('option').remove();
					selField.append(new Option(data.addresses.length + ' found'), '' );
					jQuery.each(data.addresses,function(index,item){
						var addRes = item.replace(", , ,",",");
						var replaceAdd = addRes.replace(", ,","");

						selField.append(new Option(replaceAdd.replace("Newbury  ,", 'Newbury,'), index));
					});

				}
			})
			.fail(function() {
				console.log("error");
				var selField = jQuery('#lookedup_addresses');
				selField.hide();
				if(jQuery("#add-not-found-error").length){
					jQuery("#add-not-found-error").remove();
				}
				selField.parent().append("<span class='red-error' id='add-not-found-error'>Addresses not found.</span>");
			});
		});

		// push the address to relevant fields
		jQuery("#lookedup_addresses").change(function(){
			var i = jQuery(this).val();
			if(i != '') {
				console.log(lookedAddresses[i]);
				var add = lookedAddresses[i];
				var addParts = add.split(',');
				var address1,address2, town, county;
				county = addParts[addParts.length-1];
				city = addParts[addParts.length-2];
				address1 = addParts[0]+', '+addParts[1];
				address2 = addParts[2]+', '+addParts[3]+', '+addParts[4];
				var postcode = jQuery("#address_lookup").val();
				jQuery("#billing_address_1").val(address1);
				//jQuery("#billing_address_2").val(address2);
				jQuery("#billing_city").val(city);
				jQuery("#billing_state").val(county);
				jQuery("#billing_postcode").val(postcode);
			}
		});

		if(jQuery("#fhTable a.download").length){
			jQuery("#fhTable a.download").each(function(index){
				var fhLinkHref = jQuery(this).attr("href");
				var fhNewHref = "https://beta.companieshouse.gov.uk"+fhLinkHref;
				jQuery(this).attr("href",fhNewHref);
				if(jQuery(this).parent().next('div').length) {
					var fhDownloadIXLink = jQuery(this).parent().next('div').find('a');
					var fhDownLinkHref = fhDownloadIXLink.attr('href');
					var fhDownNewHref = "https://beta.companieshouse.gov.uk"+fhDownLinkHref;
					fhDownloadIXLink.attr('href',fhDownNewHref);
				}
			});

		}

	</script>
	<style>
		.custom_field_html  .address_lookup_label {
			width: 360px;
		}
		.custom_field_html  .address_lookup_label button {
			float:right;
		}
		#add-not-found-error {
			clear: both;
			display: block;
			color: #fa0000;
		}
		.my-bank-details-dashboard textarea#bank_details {
			border: 0.1em solid;
			height: 125px;
		}
		.my-bank-details-dashboard #bank-details-btn {
			margin-top: 0;
		}

		#isa_field_set {
			padding: 15px;
			border: 1px solid #ccc;
		}
		#isa_field_set legend {
			font-size: 14px;
			margin-bottom: 0;
			border-bottom: 0;
			font-weight: bold;
			width: auto;
		}
		.user-points-signup tr td {
			border: 1px solid #ccc !important;
			padding: 10px !important;
		}
	</style>
	<?php
	if(!is_user_logged_in() && !isset($_GET['sid']) && !is_page( 'Home' )) {
	?>
	<!--<div id="custom-login-signup-modal" class="modal fade">
		<div class="modal-dialog modal-md">
			 <div class="modal-content">
				 <div class="modal-header">
					 <i class="fa fa-close close" data-dismiss="modal" id="form-submit-close"></i>
				 </div>
				 <div class="modal-body">
					 <h3><?php // _e('Already have an Account?','themeum') ?></h3>
					 <?php //echo do_shortcode('[wppb-login]'); ?>
					 <p><a href='/forgot-password/'>Forgot Password?</a></p>
					 <h3><?php // _e('Signup for free','themeum') ?></h3>
					 <?php //echo do_shortcode('[wppb-register]'); ?>
				 </div>
			 </div>
		</div>
	</div>-->
	<div id="custom-investortype-modal" class="modal fade">
		<div class="modal-dialog modal-md">
			 <div class="modal-content">
				 <div class="modal-header">
					 <!--<i class="fa fa-close close" data-dismiss="modal" id="form-submit-close"></i>-->
				 </div>
				 <div class="modal-body">
					 <p>
						<h4>Legal and regulatory information</h4>
						<p style="border:2px solid #000; padding: 10px;border-radius: 5px; -webkit-border-radius: 5px; font-weight: bold; color: #000;">The content of this promotion has not been approved by an authorised person within the meaning of the Financial Services and Markets Act 2000. Reliance on this promotion for the purpose of engaging in any investment activity may expose an individual to a significant risk of losing all of the property or other assets invested.</p>
						<p>The information in this website sets out legal information and regulatory restrictions about investments, which you should read carefully. By continuing to access any page of this website, you agree to be bound by these restrictions. If you do not agree to be bound by them, you must leave the website. We remain not responsible for any misrepresentations you may make in order to gain access to this website.</p>
					 </p>
					 <p>
						In order to access this site you must confirm that you fit into one of the 3 categories of investor as set out below.
					 </p>
					 <p>
						<h4>Certified Sophisticated Investor</h4>
						<p>You have invested in more than one unlisted company, been a director of a company with an annual turnover of at least £1 million, or worked in private equity in the last two years, or you have been a member of a business angels network for at least the last six months.</p>
					 </p>
					 <p>
						<h4>Self Certified Sophisticated Investor</h4>
						<p>A self-certified sophisticated investor is a member of a network or syndicate of business angels and have been so for at least the last six months prior; has made more than one investment in an unlisted company, in the last two years prior; is working, or have worked in the two years prior, in a professional capacity in the private equity sector, or in the provision of finance for small and medium enterprises; is currently, or have been in the two years prior, a director of a company with an annual turnover of at least £1 million.</p>
					 </p>
					 <p>
						<h4>High Net Worth Investor</h4>
						<p>You earn more than £100,000 per year or hold net assets of at least £250,000.</p>
					 </p>
					 <br/>
					 <p>If you do not fall into one of these categories, then please exit the site. You will upon entering the site be requested to complete a full registration prior to any investment information being accessed.</p>
					 <p>By proceeding to access any page of this website, you agree, so far as is permitted by law and regulation, to the exclusion of any liability whatsoever for any errors and/or omissions by it and/or any relevant third parties, in respect of its content. The site does not exclude any liability for, or remedy in respect of, fraudulent misrepresentation.</p>
					 <p>The information set out in this website may be amended without notice to you. If you continue to access this website following any such changes, you will be deemed to have accepted them. This website does not constitute an offer or invitation to invest in any securities.</p>
					 <p>
						<h4>Risk factors</h4>
						<p>
							Investors should be aware there are risks to investing in securities of any company, especially if they are private companies not listed on a Recognised Investment Exchange, and there may be little or no opportunity to sell the securities easily should you need to do so. The value of your Investments can go down as well as up and therefore you may not recover some, or in extreme cases, any of the value of your initial investment. <a href="/terms-conditions/risk-warning/">Risk warning</a>
						</p>
						<p>If you are still unsure, seek the advice of a regulated financial adviser. Investments in these securities are NOT covered by the Financial Services Compensation Scheme (FSCS) nor would you have access to the Financial Ombudsman Service (FOS).</p>
					 </p>
					 <p>
						<h4>Contents of this website</h4>
						<p>This website is published solely for the purpose of receiving information. Please see full details <a href="/terms-conditions/membership-terms-conditions/">here</a></p>
					 </p>
					 <p>
						<h4>Data protection</h4>
						<p>We may share your information with selected third parties including other members, professional service firms, identity and credit reference agencies, suppliers and sub-contractors for the performance of any contract we enter into with them or you. Please see full details <a href="/terms-conditions/privacy-policy/">here</a></p>
					 </p>
					 <p>
						<!--<button name="investor_everyday" id="investor_everyday" class="btn investor_type_button" value="Restricted Investor">Restricted</button>
						<button name="investor_sophisticated" id="investor_sophisticated" class="btn investor_type_button" value="Sophisticated Investor">Sophisticated</button>
						<button name="investor_highnet" id="investor_highnet" class="btn investor_type_button" value="High Net Worth Investor">High Net Worth</button>-->
						<?php
							$proceedURL = get_option('proceed_redirection','');
							if($proceedURL == '') {
								$proceedURL = '/sign-up';
							}
						?>
						<button name="investor_proceed" id="investor_proceed" class="btn investor_type_button" value="" data-url="<?php echo $proceedURL ?>">Proceed</button>
					 </p>
				 </div>
			 </div>
		</div>
	</div>
	<?php
	}else{ ?>
		<div id="bank-transfer-popup-modal" class="modal fade">
			<div class="modal-dialog modal-md">
				 <div class="modal-content">
					 <div class="modal-header">
						 <i class="fa fa-close close" data-dismiss="modal" id="form-submit-close"></i>
					 </div>
					 <div class="modal-body bank-transfer-body" id="bank-transfer-modal-body">
						 <h3><?php _e('Further Details','themeum') ?></h3>
						 <!--<p>
							Bank transfers to be made to:
							<ul>
							Acc Number: 00003992<br/>
							Sort Code: 04-05-41<br/>
								<!--<li><span>Name:</span> <span>Right Crowd Ltd</span></li>-->
								<!--<li><span>Acc Number:</span> <span>00003992</span></li>
								<li><span>Sort Code:</span> <span>04-05-41</span></li>
								<li><span>Ref:</span> <span id="bank_transfer_reference">TRC2019'.$user_id.'</span></li>
							</ul>
						 </p>-->
						 <p>
							<!--Banking details will be sent to you via email to complete your investment.-->
							Further Instructions will be sent to you via email to complete your investment.
						 </p>
					 </div>
				 </div>
			</div>
		</div>
		<?php if(is_page('thank-you')) { ?>
		<div id="custom-thankyou-message-modal" class="modal fade">
			<div class="modal-dialog modal-md">
				 <div class="modal-content">
					 <div class="modal-header">
						 <i class="fa fa-close close" data-dismiss="modal" id="close-form-submit-close"></i>
					 </div>
					 <div class="modal-body">
						<p>The Companies minimum effective target to implement their business plan has not been met, your investments will be held for a short period until the target has been reached at which point you will receive confirmation of your investment.</p>
					 </div>
				 </div>
			</div>
		</div>

		<?php } ?>
	<?php }
}

add_filter( 'admin_post_thumbnail_html', 'adminPostThumbnailHTML', 10, 3 );
function adminPostThumbnailHTML( $content, $post_id, $thumbnail_id )
{
    $_wp_additional_image_sizes = wp_get_additional_image_sizes();

	$post = get_post( $post_id );

	if($post->post_type != 'project') {
		return $content;
	}

	$post_type_object   = get_post_type_object( $post->post_type );
	$set_thumbnail_link = '<p class="hide-if-no-js"><a href="%s" id="set-post-thumbnail"%s class="thickbox">%s</a></p>';
	$upload_iframe_src  = get_upload_iframe_src( 'image', $post->ID );

	/*$content = sprintf( $set_thumbnail_link,
		esc_url( $upload_iframe_src ),
		'', // Empty when there's no featured image set, `aria-describedby` attribute otherwise.
		esc_html( $post_type_object->labels->set_featured_image )
	);*/
	$content = '<p class="hide-if-no-js">'.esc_html( $post_type_object->labels->set_featured_image ).'</p>';

	if ( $thumbnail_id) {
		$size = isset( $_wp_additional_image_sizes['post-thumbnail'] ) ? 'post-thumbnail' : array( 266, 266 );

		/**
		 * Filters the size used to display the post thumbnail image in the 'Featured Image' meta box.
		 *
		 * Note: When a theme adds 'post-thumbnail' support, a special 'post-thumbnail'
		 * image size is registered, which differs from the 'thumbnail' image size
		 * managed via the Settings > Media screen. See the `$size` parameter description
		 * for more information on default values.
		 *
		 * @since 4.4.0
		 *
		 * @param string|array $size         Post thumbnail image size to display in the meta box. Accepts any valid
		 *                                   image size, or an array of width and height values in pixels (in that order).
		 *                                   If the 'post-thumbnail' size is set, default is 'post-thumbnail'. Otherwise,
		 *                                   default is an array with 266 as both the height and width values.
		 * @param int          $thumbnail_id Post thumbnail attachment ID.
		 * @param WP_Post      $post         The post object associated with the thumbnail.
		 */
		$size = apply_filters( 'admin_post_thumbnail_size', $size, $thumbnail_id, $post );
		$siteID = get_post_meta($post_id,'thm_site_id',true);
		switch_to_blog($siteID);
		$thumbnail_html = wp_get_attachment_image( $thumbnail_id, $size );
		restore_current_blog();
		switch_to_blog(1);
		if ( ! empty( $thumbnail_html ) ) {
			/*$content = sprintf( $set_thumbnail_link,
				esc_url( $upload_iframe_src ),
				' aria-describedby="set-post-thumbnail-desc"',
				$thumbnail_html
			);*/
			$content = $thumbnail_html;
			//$content .= '<p class="hide-if-no-js howto" id="set-post-thumbnail-desc">' . __( 'Click the image to edit or update' ) . '</p>';
			$content .= '<p class="hide-if-no-js"><a href="#" id="remove-post-thumbnail">' . esc_html( $post_type_object->labels->remove_featured_image ) . '</a></p>';
		}
	}

	$content .= '<input type="hidden" id="_thumbnail_id" name="_thumbnail_id" value="' . esc_attr( $thumbnail_id ? $thumbnail_id : '-1' ) . '" />';

	return $content;
}

add_action('wp_footer', 'thankYouPageModal');
function thankYouPageModal(){
		if(is_page('thank-you') && isset($_GET['pid']) && $_GET['pid'] == '5040') {
		//wp_enqueue_script( 'modal', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js', array(), null, true );
		?>
					<script type="text/javascript">
						jQuery(document).ready(function(){ 'use strict';
							//console.log(jQuery("#custom-thankyou-message-modal"));
							jQuery("#custom-thankyou-message-modal").addClass('in');
							jQuery("#custom-thankyou-message-modal .modal-backdrop").addClass('in');
							jQuery("#custom-thankyou-message-modal").show();
							jQuery("#custom-thankyou-message-modal").modal();
						});
					</script>
			<?php }
}

add_action('wp_footer', 'investorTypeModal');
function investorTypeModal(){
	restore_current_blog();
	restore_current_blog();
	restore_current_blog();
	restore_current_blog();
	restore_current_blog();
	restore_current_blog();
	//unset ( $GLOBALS['_wp_switched_stack'] );
	$currentSiteID = get_current_blog_id();
	//$cookieName = 'investor_type';
	$newCookieName = 'is_proceed';
	if($currentSiteID !== 1){
		$proceedURL = get_option('proceed_redirection');

		if(!is_user_logged_in() && !is_page('sign-up') && !is_page('forgot-password') && !is_page('contact-us') && $currentSiteID !== 13
			&& !is_page('thank-you-for-register')
			) { //!isset($_COOKIE[$newCookieName])
			 if($proceedURL == ''){
				 ?>
				 <script type="text/javascript">
							jQuery(document).ready(function(){ 'use strict';
								jQuery("#custom-investortype-modal").addClass('in');
								jQuery("#custom-investortype-modal .modal-backdrop").addClass('in');
								jQuery("#custom-investortype-modal").show();
								jQuery("#custom-investortype-modal").modal({
									backdrop: 'static',
									keyboard: false
								});
							});
						</script>
				 <?php
			}else if(($proceedURL != '' && !isset($_COOKIE[$newCookieName]))){
		?>
			<script type="text/javascript">
							jQuery(document).ready(function(){ 'use strict';
								jQuery("#custom-investortype-modal").addClass('in');
								jQuery("#custom-investortype-modal .modal-backdrop").addClass('in');
								jQuery("#custom-investortype-modal").show();
								jQuery("#custom-investortype-modal").modal({
									backdrop: 'static',
									keyboard: false
								});
							});
						</script>
		<?php
			}
		}else if (is_page('opportunities') && $currentSiteID == 13) {
			?>
			<script type="text/javascript">
							jQuery(document).ready(function(){ 'use strict';
								jQuery("#custom-investortype-modal").addClass('in');
								jQuery("#custom-investortype-modal .modal-backdrop").addClass('in');
								jQuery("#custom-investortype-modal").show();
								jQuery("#custom-investortype-modal").modal({
									backdrop: 'static',
									keyboard: false
								});
							});
						</script>
			<?php
		}
	}
	?>
	<script>
		jQuery(function($){

			$('.investor_type_button').click(function(){
				var v = $(this).val();
				var proceedURL = $(this).data('url');
				var cookieName = 'is_proceed'; //'investor_type';
				var date = new Date();
				  date.setTime(date.getTime()+(30*24*60*60*1000));
				  document.cookie = cookieName + "=" + v + "; expires=" + date.toGMTString();
				  jQuery("#custom-investortype-modal").modal('hide');
				  window.location = proceedURL; //'/sign-up';
			});
		});
	</script>
	<?php
}

if(is_page('opportunities')) {
	function sort_by_custom_order_value( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}
		$currentSiteID = get_current_blog_id();
		$metaKeyOrder = 'project_order_'.$currentSiteID;

		$query->set( 'meta_key', $metaKeyOrder );
		$query->set( 'orderby', array( 'meta_value_num' => 'ASC' ) );

		add_filter( 'get_meta_sql', 'filter_get_meta_sql_for_custom_order' );
	}
	add_action( 'pre_get_posts', 'sort_by_custom_order_value' );

	function filter_get_meta_sql_for_custom_order( $clauses ) {
		remove_filter( 'get_meta_sql', 'filter_get_meta_sql_for_custom_order' );

		// Change the inner join to a left join,
		// and change the where so it is applied to the join, not the results of the query.
		$clauses['join']  = str_replace( 'INNER JOIN', 'LEFT JOIN', $clauses['join'] ) . $clauses['where'];
		$clauses['where'] = '';

		return $clauses;
	}
}

// Show share purchase history in user profiles
add_action( 'show_user_profile', 'add_customer_logs',3,1);
add_action( 'edit_user_profile', 'add_customer_logs',3,1);

function add_customer_logs($user){

	$html = '<div class="col-sm-12">';//myshares-row
		$html .= '<div class="yith-wcmbs-admin-profile-membership-table in-admin wpneo-clearfix logs-list">';
        $html .= '<h3>'.__( "Logs" , "wp-crowdfunding" ).'</h3>';
        include_once WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/email-logs.php';
    	$html .= '</div>';//wpneo-shadow
	$html .= '</div>';//myshares-row

	$html .= '<div class="col-sm-12">';//myshares-row
		$html .= '<div class="yith-wcmbs-admin-profile-membership-table in-admin wpneo-clearfix user-investments">';
        $html .= '<h3>'.__( "My Investments" , "wp-crowdfunding" ).'</h3>';
        include_once WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/user-investments.php';

    	$html .= '</div>';//wpneo-shadow
	$html .= '</div>';//myshares-row

	if(in_array('product_manager',$user->roles) && is_super_admin(wp_get_current_user()->ID)) {
		$ajURL = admin_url('admin-ajax.php');
		$assigned = get_user_meta($uid,'assigned_products',true);
		//$html .= "<pre>".print_r($assigned,1)."</pre>";
		$html .= '<div class="product_manager_products">
					<h3>Assign Products to Manage</h3>';
		$allProducts = get_posts(array('post_type'=>'project','post_status'=>array('pending','publish'),'posts_per_page' => '-1'));

			$html .= '<table id="manage_products_for_manager_table">';
				$html .= '<tr><th>Product Name</th><th>Can Manage?</th></tr>';
					foreach($allProducts as $product){
						$html .= '<tr><td>'.$product->post_title.'</td><td align="center" class="manage_products_checkbox" data-pid="'.$product->ID.'">
								<input type="checkbox" name="manage_products[]" value="yes" '.(in_array($product->ID,$assigned) ? "checked" : "").' /></td></tr>';
					}
			$html .= '</table>';
		$html .= "</div>";
		$html .= '
			<script>
				jQuery(function(){
					jQuery("#manage_products_for_manager_table").on("change",".manage_products_checkbox input[type=checkbox]",function(){
						var thisel = jQuery(this);
						var checkparent = thisel.parent();
						var pid = checkparent.data("pid");
						var valu = thisel.is(":checked") ? "yes" : "no";
						jQuery.post({
						  url: "'.$ajURL.'",
						  data: {
							action: "assign_manager_products",
							pid: pid,
							value: valu,
							uid: "'.$user->ID.'"
						  },
						  success: function(response){
							console.log(response);
								var res = JSON.parse(response);
								if(res.status === "true") {
									var message = jQuery("<span />", {
											 "class": "flash_message",
											 text: "Saved!"
										  }).fadeIn("fast");
										checkparent.append(message);
										message.delay(2000).fadeOut("normal", function() {
										 jQuery(this).remove();
									  });
								}
						  }
						});
					});
				});
			</script>
		';
	}

	echo $html;
}

add_action( 'wp_ajax_nopriv_assign_manager_products', 'assign_manager_products' );
add_action( 'wp_ajax_assign_manager_products', 'assign_manager_products' );
function assign_manager_products() {
	$ret = array('status'=>'false');
	$uid = (int) esc_attr($_POST['uid']);
		if($uid > 0) {
			$projectID = (int) esc_attr($_POST['pid']);
			$value = esc_attr($_POST['value']);
			$old = get_user_meta($uid,'assigned_products',true);
			if($old == '' || count($old) <= 0) {
				$data = array($projectID);
			}else{
				if($value == 'yes') {
					$old[] = $projectID;
				}else{
					if (($key = array_search($projectID, $old)) !== false) {
						unset($old[$key]);
					}
				}
				$data = $old;
			}
			update_user_meta($uid, 'assigned_products', $data);
			$ret['status'] = 'true';
		}

		echo json_encode($ret);die();
}

add_filter('wppb_send_credentials_checkbox_logic','custom_send_credentials_logic_signup',10,3);
function custom_send_credentials_logic_signup($content,$requestData,$form){
	//$field = '<li class="wppb-form-field wppb-send-credentials-checkbox"><label for="send_credentials_via_email"><input id="send_credentials_via_email" type="checkbox" name="send_credentials_via_email" value="sending"'.( ( isset( $request_data['send_credentials_via_email'] ) && ( $request_data['send_credentials_via_email'] == 'sending' ) ) ? ' checked' : '' ).'/>'.__( 'Send these credentials via email.', 'profile-builder').'</label></li>';
	$content = '<input type="hidden" name="send_credentials_via_email" value="sending" />';
	return $content;
}

add_action('pre_get_posts','users_own_attachments',999,1);
function users_own_attachments( $wp_query_obj ) {

    global $current_user, $pagenow;
	if(!is_admin()) {
		return;
	}
    $is_attachment_request = ($wp_query_obj->get('post_type')=='attachment');

    if( !$is_attachment_request )
        return;

    if( !in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) )
        return;


    if( current_user_can('delete_pages') ) {
	   unset($wp_query_obj->query_vars['author']);
	}


    return $wp_query_obj;
}

function gd_modify_user_table( $column ) {
	$column['referred_by'] = 'Referred By';
	if(isset($_GET['role']) && $_GET['role'] == 'read_only_manager') {
		$column['referred_by'] = 'Referral Code';
	}
	$column['proofs'] = 'Proof Documents';
	$column['ni_number'] = 'NI Number';
	$column['certificate'] = 'Certificate';
    return $column;
}
add_filter( 'manage_users_columns', 'gd_modify_user_table' );

function gd_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'referred_by' :
			$ref = get_user_meta( $user_id, 'referralcode', true );
			if(isset($_GET['role']) && $_GET['role'] == 'read_only_manager') {
				$ref = get_user_meta( $user_id, 'intro_referralcode', true );
			}
			if($ref != '') {
				return $ref;
			}else{
				return "-";
			}
		case 'proofs' :
            $IdProof = get_user_meta($user_id,'idproof',true);
			$AddressProof = get_user_meta($user_id,'addressproof',true);
			$proofs = '';
			if($IdProof > 0) {
				$IdProofPost = get_post($IdProof);
				$proofs .= 'ID: <a href="'.$IdProofPost->guid.'" target="_blank">'.$IdProofPost->post_title.'</a><br/>';
			}
			if($AddressProof > 0) {
				$AddressProofPost = get_post($AddressProof);
				$proofs .= 'Address: <a href="'.$AddressProofPost->guid.'" target="_blank">'.$AddressProofPost->post_title.'</a>';
			}
			return $proofs;
		case 'ni_number' :
            $niNumber = get_user_meta( $user_id, 'ni_number', true );
			if($niNumber != '') {
				return $niNumber;
			}else{
				return "-";
			}
		case 'certificate' :
            $certificate = site_url().'/wp-content/uploads/statements/statement_'.$user_id.'.pdf';
			$ch = curl_init($certificate);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			//$certificate_dir = WP_CONTENT_DIR.'/wp-content/uploads/statements/statement_'.$user_id.'.pdf';
			if($code == '200') {
				return '<a href="'.$certificate.'" target="_blank">statement_'.$user_id.'.pdf</a>';
			}else{
				return '-';
			}
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'gd_modify_user_table_row', 10, 3 );

add_action( 'wp_ajax_nopriv_upload_user_proofs', 'upload_user_proofs' );
add_action( 'wp_ajax_upload_user_proofs', 'upload_user_proofs' );
function upload_user_proofs() {
	$currentSite = get_current_blog_id();
	$currentUserID = get_current_user_id();
	$ret = array('idproof_status'=>'false','addressproof_status' => 'false');

		if( isset($_FILES['idproof']) && $_FILES['idproof']['error'] == 0) {
			$idproof = media_handle_upload( 'idproof', 0 );
			if( is_wp_error( $idproof ) ) {
				$ret['idproof_error'] = $idproof->get_error_message();
				$ret['idproof_status'] = 'false';
			}else{
				//if($idproof) {
				update_user_meta($currentUserID,'idproof',$idproof);
				$ret['idproof'] = $idproof;
				$ret['idproof_status'] = 'true';
			}
		}else{
			$ret['idproof_error'] = 'File size exceeded. Please compress and try to upload again.';
			$ret['idproof_status'] = 'false';
		}
		if( isset($_FILES['addressproof']) && $_FILES['addressproof']['error'] == 0) {
			$addressproof = media_handle_upload( 'addressproof', 0 );
			if( is_wp_error( $addressproof ) ) {
				$ret['addressproof_error'] = $addressproof->get_error_message();
				$ret['addressproof_status'] = 'false';
			}else{
				//if($addressproof) {
				update_user_meta($currentUserID,'addressproof',$addressproof);
				$ret['addressproof'] = $addressproof;
				$ret['addressproof_status'] = 'true';
			}
		}else{
			$ret['addressproof_error'] = 'File size exceeded. Please compress and try to upload again.';
			$ret['addressproof_status'] = 'false';
		}

		echo json_encode($ret);die();
}

add_action( 'wp_ajax_nopriv_save_user_bankdetails', 'save_user_bankdetails' );
add_action( 'wp_ajax_save_user_bankdetails', 'save_user_bankdetails' );
function save_user_bankdetails() {
	$currentSite = get_current_blog_id();
	$currentUserID = get_current_user_id();
	//echo $currentSite." => ".$currentUserID;exit;
	$ret = array('status'=>'false');
		if( isset($_POST['bankdetails']) && $_POST['bankdetails'] !== '') {
			$bankdetails = esc_attr($_POST['bankdetails']);
			//$coupon_interval = esc_attr($_POST['coupon_interval']);
			update_user_meta($currentUserID, 'bank_details', $bankdetails);
			//update_user_meta($currentUserID, 'user_coupon_interval', $coupon_interval);
			$ret['status'] = 'true';
		}

		echo json_encode($ret);die();
}

// adding custom statuses for investment
register_post_status( 'cancelled', array(
		/* WordPress built in arguments. */
		'label'                       => __( 'Cancelled', 'wp-statuses' ),
		'label_count'                 => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'wp-statuses' ),
		'public'                      => false,
		'show_in_admin_all_list'      => false,
		'show_in_admin_status_list'   => true,

		/* WP Statuses specific arguments. */
		'post_type'                   => array( 'investment' ), // Only for posts!
		'show_in_metabox_dropdown'    => true,
		'show_in_inline_dropdown'     => true,
		'show_in_press_this_dropdown' => true,
		'labels'                      => array(
			'metabox_dropdown' => __( 'Cancelled',        'wp-statuses' ),
			'inline_dropdown'  => __( 'Cancelled',        'wp-statuses' ),
		),
		'dashicon'                    => 'dashicons-cancelled',
	) );

	register_post_status( 'investment_closed', array(
		/* WordPress built in arguments. */
		'label'                       => __( 'Investment Closed', 'wp-statuses' ),
		'label_count'                 => _n_noop( 'Investment Closed <span class="count">(%s)</span>', 'Investment Closed <span class="count">(%s)</span>', 'wp-statuses' ),
		'public'                      => false,
		'show_in_admin_all_list'      => false,
		'show_in_admin_status_list'   => true,

		/* WP Statuses specific arguments. */
		'post_type'                   => array( 'investment' ), // Only for posts!
		'show_in_metabox_dropdown'    => true,
		'show_in_inline_dropdown'     => true,
		'show_in_press_this_dropdown' => true,
		'labels'                      => array(
			'metabox_dropdown' => __( 'Investment Closed',        'wp-statuses' ),
			'inline_dropdown'  => __( 'Investment Closed',        'wp-statuses' ),
		),
		'dashicon'                    => 'dashicons-closed',
	) );

register_post_status( 'received', array(
		/* WordPress built in arguments. */
		'label'                       => __( 'Funds Received', 'wp-statuses' ),
		'label_count'                 => _n_noop( 'Funds Received <span class="count">(%s)</span>', 'Funds Received <span class="count">(%s)</span>', 'wp-statuses' ),
		'public'                      => false,
		'show_in_admin_all_list'      => false,
		'show_in_admin_status_list'   => true,

		/* WP Statuses specific arguments. */
		'post_type'                   => array( 'investment' ), // Only for posts!
		'show_in_metabox_dropdown'    => true,
		'show_in_inline_dropdown'     => true,
		'show_in_press_this_dropdown' => true,
		'labels'                      => array(
			'metabox_dropdown' => __( 'Funds Received',        'wp-statuses' ),
			'inline_dropdown'  => __( 'Funds Received',        'wp-statuses' ),
		),
		'dashicon'                    => 'dashicons-received',
	) );

	register_post_status( 'transfer_requested', array(
		/* WordPress built in arguments. */
		'label'                       => __( 'Transfer Requested', 'wp-statuses' ),
		'label_count'                 => _n_noop( 'Transfer Requested <span class="count">(%s)</span>', 'Transfer Requested <span class="count">(%s)</span>', 'wp-statuses' ),
		'public'                      => false,
		'show_in_admin_all_list'      => false,
		'show_in_admin_status_list'   => true,

		/* WP Statuses specific arguments. */
		'post_type'                   => array( 'investment' ), // Only for posts!
		'show_in_metabox_dropdown'    => true,
		'show_in_inline_dropdown'     => true,
		'show_in_press_this_dropdown' => true,
		'labels'                      => array(
			'metabox_dropdown' => __( 'Transfer Requested',        'wp-statuses' ),
			'inline_dropdown'  => __( 'Transfer Requested',        'wp-statuses' ),
		),
		'dashicon'                    => 'dashicons-transfer-requested',
	) );

	register_post_status( 'pack_out', array(
		/* WordPress built in arguments. */
		'label'                       => __( 'Pack Out', 'wp-statuses' ),
		'label_count'                 => _n_noop( 'Pack Out <span class="count">(%s)</span>', 'Pack Out <span class="count">(%s)</span>', 'wp-statuses' ),
		'public'                      => false,
		'show_in_admin_all_list'      => false,
		'show_in_admin_status_list'   => true,

		/* WP Statuses specific arguments. */
		'post_type'                   => array( 'investment' ), // Only for posts!
		'show_in_metabox_dropdown'    => true,
		'show_in_inline_dropdown'     => true,
		'show_in_press_this_dropdown' => true,
		'labels'                      => array(
			'metabox_dropdown' => __( 'Pack Out',        'wp-statuses' ),
			'inline_dropdown'  => __( 'Pack Out',        'wp-statuses' ),
		),
		'dashicon'                    => 'dashicons-cancelled',
	) );

	register_post_status( 'hidden', array(
		/* WordPress built in arguments. */
		'label'                       => __( 'Hidden', 'wp-statuses' ),
		'label_count'                 => _n_noop( 'Hidden <span class="count">(%s)</span>', 'Hidden <span class="count">(%s)</span>', 'wp-statuses' ),
		'public'                      => false,
		'show_in_admin_all_list'      => false,
		'show_in_admin_status_list'   => true,

		/* WP Statuses specific arguments. */
		'post_type'                   => array( 'project' ), // Only for posts!
		'show_in_metabox_dropdown'    => true,
		'show_in_inline_dropdown'     => true,
		'show_in_press_this_dropdown' => true,
		'labels'                      => array(
			'metabox_dropdown' => __( 'Hidden',        'wp-statuses' ),
			'inline_dropdown'  => __( 'Hidden',        'wp-statuses' ),
		),
		'dashicon'                    => 'dashicons-hidden',
	) );

	register_post_status( 'partial_funds', array(
		/* WordPress built in arguments. */
		'label'                       => __( 'Partial Funds Received', 'wp-statuses' ),
		'label_count'                 => _n_noop( 'Partial Funds Received <span class="count">(%s)</span>', 'Partial Funds Received <span class="count">(%s)</span>', 'wp-statuses' ),
		'public'                      => false,
		'show_in_admin_all_list'      => false,
		'show_in_admin_status_list'   => true,

		/* WP Statuses specific arguments. */
		'post_type'                   => array( 'investment' ), // Only for posts!
		'show_in_metabox_dropdown'    => true,
		'show_in_inline_dropdown'     => true,
		'show_in_press_this_dropdown' => true,
		'labels'                      => array(
			'metabox_dropdown' => __( 'Partial Funds Received',        'wp-statuses' ),
			'inline_dropdown'  => __( 'Partial Funds Received',        'wp-statuses' ),
		),
		'dashicon'                    => 'dashicons-partial-funds',
	) );


function getUserInvestments($user_id = NULL,$amountOnly = false) {
	if($user_id === NULL) {
		$user_id = get_current_user_id();
	}
	$site_id = get_current_blog_id();
	switch_to_blog(1);
	$args = array(
    'post_type' => 'investment',
    //'author'    => $user_id,
    'post_status'    => array('publish','pending','received','cancelled','transfer_requested','pack_out','investment_closed'),
	'meta_key'          => 'themeum_investment_date',
	  'orderby'           => 'meta_value',
	  'order'             => 'DESC',
		'meta_query' => array(
			array(
				'key' => 'site_id',
				'value' => $site_id,
				'compare' => '='
			),
			array(
				'key' => 'themeum_investor_user_id',
				'value' => $user_id,
				'compare' => '='
			)
		),
		'posts_per_page'    => -1
	);
	$investments = get_posts( $args );
	if($amountOnly === true) {
		$totalAmount = 0;
		if(count($investments) > 0) {
			foreach($investments as $invest) {
				$info = getInvestmentInfo($invest->ID);
				$totalAmount += $info['investment_amount'];
			}
		}
		restore_current_blog();
		return $totalAmount;
	}
	if(count($investments) > 0) {
		restore_current_blog();
		return $investments;
	}
	restore_current_blog();
	return false;
}

add_action( 'wp_ajax_nopriv_cancel_investment', 'cancel_investment' );
add_action( 'wp_ajax_cancel_investment', 'cancel_investment' );
function cancel_investment() {
		restore_current_blog();
		$user_id = get_current_user_id();
	global $switched;
   switch_to_blog(1);
	$ret = array('status'=>'false');
	if($user_id > 0) {
		if( (int) $_POST['invest_id'] > 0) {
			wp_update_post(array(
		     'ID'    =>  $_POST['invest_id'],
		     'post_status'   =>  'cancelled'
		     ));
		}
		update_post_meta($_POST['invest_id'],'themeum_status_all','cancelled');
		$ret['status'] = 'true';
		restore_current_blog();
		echo json_encode($ret);die();
	}
	restore_current_blog();
	echo json_encode($ret);die();
}


// custom menu item in admin panel for approving products to display
function products_approval_admin_menu() {
	$cSite = get_current_blog_id();
		if($cSite == 1) {
			add_menu_page(
		__( 'Products Approval Queue', 'custom_gd_lang' ),
		__( 'Products Approval Queue', 'custom_gd_lang' ),
		'manage_options',
		'products_approval_queue',
		'admin_products_approval_queue',
		'dashicons-schedule',
		3
	);
		}

}

//add_action( 'admin_menu', 'products_approval_admin_menu' );

function admin_products_approval_queue() {
		$cSite = get_current_blog_id();
		if($cSite != 1) {
			return;
		}
		?>
		<div class="approval_queue_container">
			<h1>
				<?php esc_html_e( 'Approval/Disapproval of Products Display', 'my-plugin-textdomain' ); ?>
			</h1>
		<?php
		$allSites = get_sites();

		foreach($allSites as $site) {
			if($site->blog_id == 1) continue;

			$approvalQueueKey = "approval_queue_".$site->blog_id;
			$products = get_option($approvalQueueKey);
			$products = array_unique($products);
			$approvalKey = 'display_business_approval_'.$site->blog_id;
			?>
			<fieldset class="" style="border:1px solid #cecece; padding: 10px;">
				<legend><?php echo $site->domain ?></legend>
				<div>
					<?php if(count($products) > 0 && $products != '') { ?>
							<form id="site_id_<?php echo $site->blog_id ?>" class="approval_form_submission" >
							<ul>
							<?php foreach($products as $pID) {
									$prod = get_post($pID);
									if($prod){
										$approved = get_post_meta($pID,$approvalKey,true);
										echo "<li><span>".$pID." = ".$approvalKey." = ".$prod->post_title."</span><span><input ".($approved == 'yes' ? 'checked' : '')." type='checkbox' name='approval_queue[".$site->blog_id."][".$pID."]' value='yes' /></span></li>";
										unset($approved);
									}
								?>

							<?php } ?>

							</ul>
							<button type="button" onClick="submitApprovalForm('<?php echo $site->blog_id ?>');" >Submit</button>
							</form>
					<?php }else{ ?>
						<p style="color: #fa0000;">No products found for approval.</p>
					<?php } ?>
				</div>
			</fieldset>
		<?php } ?>
		</div>
		<script>
			function submitApprovalForm(blogid){
				var ajxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
				var formData = jQuery("#site_id_"+blogid).serialize();
				var data = {
					'action':'approve_site_products',
					'data' : formData,
					'site_id': blogid
				};

				jQuery.ajax({
					url: ajxUrl,
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(data) {
					console.log(data);
					//var res = JSON.parse(data);
					if (data.status == "true") {
						var message = jQuery("<span />", {
								 "class": "flash_message",
								 text: "Saved!"
							  }).fadeIn("fast");
							parentel.append(message);
							message.delay(2000).fadeOut("normal", function() {
							 jQuery(this).remove();
						  });
					}
				})
				.fail(function() {
					console.log("error");
				});
			}
		</script>
	<?php }

add_action( 'wp_ajax_nopriv_approve_site_products', 'approve_site_products' );
add_action( 'wp_ajax_approve_site_products', 'approve_site_products' );
function approve_site_products() {

	$ret = array('status'=>'false');
	if(is_admin()) {
			$params = array();
			parse_str($_POST['data'], $params);
			$currentSite = $_POST['site_id'];
			$pIds = array_keys($params['approval_queue'][$currentSite]);
			$approvalKey = 'display_business_approval_'.$currentSite;
			$approvalQueueKey = "approval_queue_".$currentSite;
			$products = get_option($approvalQueueKey);
			$products = array_unique($products);

			$approved = [];
			if(count($pIds) > 0) {
				foreach($products as $id) {
					if(in_array($id,$pIds)) {
						update_post_meta($id,$approvalKey,'yes');
						$approved[] = $id;
					}else{
						update_post_meta($id,$approvalKey,'no');
					}

				}
			}
			// set approval queue for admins
			//update_approval_queue();
			/*$oldApprovalQueue = get_option($approvalQueueKey);
			if($oldApprovalQueue != '') {
				$newQueue = array_merge($oldApprovalQueue,$postsToApprove);
			}else{
				$newQueue = $postsToApprove;
			}*/
			//$newQueue = array_unique($newQueue);
			//update_option($approvalQueueKey,$newQueue);
			//send email to admin here
		$ret['status'] = 'true';
		echo json_encode($ret);die();
	}
	echo json_encode($ret);die();
}

add_action( 'wp_ajax_nopriv_upload_new_files', 'upload_new_files' );
add_action( 'wp_ajax_upload_new_files', 'upload_new_files' );
function upload_new_files() {
	restore_current_blog();
	$siteID = get_current_blog_id();
	$isManager = isManager();
	global $switched;
   switch_to_blog(1);
	$ret = array('status'=>'false');
	if($isManager && $_POST['pid'] > 0) { // Are you a manager? no??? Naughty!! Go back and be a man-ager
		$files = $_POST;
		$post_id = $files['pid'];
		update_post_meta($post_id,'thm_certificate',$files['cert-inc-id']);
		update_post_meta($post_id,'thm_article',$files['article-inc-id']);
		update_post_meta($post_id,'thm_memorandum',$files['memorandum-id']);
		$banks = (array) get_post_meta($post_id,'thm_bank_statements',true);
		$financials = (array) get_post_meta($post_id,'thm_financial_reports',true);
		$extraFiles = (array) get_post_meta($post_id,'thm_extra_files',true);
		if(count($files['bank-file-id']) > 0) {
			$bankOutput = [];
			foreach($_POST['bank-file-id'] as $orderKey => $value) {
				if($value != '' && $value > 0) {
					$bankOutput[] = $value;
				}
			}
			$banks = array_merge($banks,$bankOutput);
			$banks = array_unique($banks);
		}
		if(count($files['financial-file-id']) > 0) {
			$financialOutput = [];
			foreach($_POST['financial-file-id'] as $orderKey => $value) {
				if($value != '' && $value > 0) {
					$financialOutput[] = $value;
				}
			}
			$financials = array_merge($financials,$financialOutput);
			$financials = array_unique($financials);
		}
		if(count($files['extra-file-id']) > 0) {
			$extraOutput = [];
			$extraTitleOutput = [];
			foreach($_POST['extra-file-id'] as $orderKey => $value) {
				if($value != '' && $value > 0) {
					$extraOutput[] = $value;
					if(isset($_POST['extra-file-title'][$orderKey]) && $_POST['extra-file-title'][$orderKey] != '') {
						$extraTitleOutput[$value] = $_POST['extra-file-title'][$orderKey];
					}
				}
			}
			$extraFiles = array_merge($extraFiles,$extraOutput);
			$extraFiles = array_unique($extraFiles);
			if(count($extraTitleOutput) > 0 ) {
				foreach($extraTitleOutput as $fileID => $titleVal) {
					$titleMetaKey = "extra_file_title_".$fileID;
					update_post_meta($post_id,$titleMetaKey,$titleVal);
				}
			}
		}
		// update files
		update_post_meta($post_id, 'thm_bank_statements', $banks);
		update_post_meta($post_id, 'thm_financial_reports', $financials);
		update_post_meta($post_id, 'thm_extra_files', $extraFiles);
		$ret = array('status'=>'true');
	}
	restore_current_blog();
	echo json_encode($ret);die();
}

function getInvestingIntoLabels($array = array()) {
	if(count($array) <= 0) {
		return '';
	}
	$labels = [];
	foreach($array as $val) {
		$labels[] = ucwords(str_replace("_"," ",$val));
	}
	return implode(", ",$labels);
}

// Show share purchase history in user profiles
add_action( 'show_user_profile', 'add_customer_notes',9,1);
add_action( 'edit_user_profile', 'add_customer_notes',9,1);

function add_customer_notes($user) {
	$trcNotes = get_user_meta($user->ID,'trc_notes',true);
?>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="trc_notes">TRC Notes</label></th>
				<td>
					<textarea class="custom_field_input" name="trc_notes" id="trc_notes"><?php echo $trcNotes ?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="trc_notes">Questionnaire Points</label></th>
				<td>
					<?php
					$points = getQuestionnairePoints($user->ID,true);
					//echo "<span class='points_hover' id='".$user->ID."'>".$points['total']."</span>";
					?>
					<div class='points_hover_div' id='points_hover_div_<?php echo $user->ID; ?>'>
						<?php foreach($points as $k => $v) { ?>
						<span style="font-weight: bold;"><?php echo $k ?></span>: <span><?php echo $v ?></span><br/>
						<?php } ?>
					<div>
				</td>
			</tr>
		</tbody>
	</table>
<?php }

add_action('edit_user_profile_update', 'update_customer_notes');
add_action('personal_options_update', 'update_customer_notes');
 function update_customer_notes($user_id) {
     if ( current_user_can('edit_user',$user_id) )
         update_user_meta($user_id, 'trc_notes', $_POST['trc_notes']);
 }

function gd_login_redirect( $redirect_to, $request, $user ){
	//if($user->ID > 0) {
		return home_url('/opportunities');
	//}
}
//add_filter( 'login_redirect', 'gd_login_redirect', 999, 3 );

function tm_userinfo_investment() {
	global $wpdb;
    global $post;

	$pSiteId = get_post_meta($post->ID, 'site_id', true);
	$investorID = get_post_meta($post->ID,'themeum_investor_user_id',true);
	if($investorID != '') {
		$userMetas = get_user_meta($investorID);
		?>
		<div class="investment_user_info">
			<ul>
				<?php if($userMetas['billing_first_name'][0] != '') { ?>
					<li>
						<span>First Name:</span>
						<span><?php echo $userMetas['billing_first_name'][0] ?></span>
					</li>
				<?php } ?>
				<?php if($userMetas['billing_last_name'][0] != '') { ?>
					<li>
						<span>Last Name:</span>
						<span><?php echo $userMetas['billing_last_name'][0] ?></span>
					</li>
				<?php } ?>
				<?php if($userMetas['billing_address_1'][0] != '') { ?>
					<li>
						<span>Address 1:</span>
						<span><?php echo $userMetas['billing_address_1'][0] ?></span>
					</li>
				<?php } ?>
				<?php if($userMetas['billing_address_2'][0] != '') { ?>
					<li>
						<span>Address 2:</span>
						<span><?php echo $userMetas['billing_address_2'][0] ?></span>
					</li>
				<?php } ?>
				<?php if($userMetas['billing_city'][0] != '') { ?>
					<li>
						<span>City:</span>
						<span><?php echo $userMetas['billing_city'][0] ?></span>
					</li>
				<?php } ?>
				<?php if($userMetas['billing_state'][0] != '') { ?>
					<li>
						<span>State:</span>
						<span><?php echo $userMetas['billing_state'][0] ?></span>
					</li>
				<?php } ?>
				<?php if($userMetas['billing_postcode'][0] != '') { ?>
					<li>
						<span>Postcode:</span>
						<span><?php echo $userMetas['billing_postcode'][0] ?></span>
					</li>
				<?php } ?>
				<?php if($userMetas['billing_country'][0] != '') { ?>
					<li>
						<span>Country:</span>
						<span><?php echo $userMetas['billing_country'][0] ?></span>
					</li>
				<?php } ?>
				<?php if($userMetas['billing_phone'][0] != '') { ?>
					<li>
						<span>Phone:</span>
						<span><?php echo $userMetas['billing_phone'][0] ?></span>
					</li>
				<?php }else{ ?>
					<li>
						<span>Phone:</span>
						<span><?php echo $userMetas['phone_number'][0] ?></span>
					</li>
				<?php } ?>
				<?php if($userMetas['billing_email'][0] != '') { ?>
					<li>
						<span>Email:</span>
						<span><?php echo $userMetas['billing_email'][0] ?></span>
					</li>
				<?php } ?>
			</ul>
		</div>
		<?php
	}else{
		echo "<p>No user info found.</p>";
	}
	?>
	<style>
	.investment_user_info ul li {
		clear:both;
	}
		.investment_user_info ul li > span {
			font-weight: bold;
			float:left;
			width: 200px;
		}
		.investment_user_info ul li > span + span {
			font-weight: normal;
			float:left;
		}
	</style>
	<?php
}

add_action( 'wp_ajax_nopriv_save_featured', 'save_featured' );
add_action( 'wp_ajax_save_featured', 'save_featured' );
function save_featured() {
	restore_current_blog();
	$isManager = isManager();
	$currentSite = get_current_blog_id();
	global $switched;
   switch_to_blog(1);
	$ret = array('status'=>'false');
	if($isManager) {
		if( (int) $_POST['pid'] > 0) {
			//update_post_meta();
			$value = $_POST['value'];
			$metaKey = "is_featured_".$currentSite;
			update_post_meta($_POST['pid'],$metaKey,$value);
		}
		$ret['status'] = 'true';
		restore_current_blog();
		echo json_encode($ret);die();
	}
	restore_current_blog();
	echo json_encode($ret);die();
}

/*Customizer Code HERE*/
add_action('customize_register', 'theme_email_header_footer_customizer');
function theme_email_header_footer_customizer($wp_customize){
 //adding section in wordpress customizer
$wp_customize->add_section('email_header_footer_settings_section', array(
  'title'          => 'Email Header/Footer Section'
 ));
//adding setting for footer text area
$wp_customize->add_setting('email_header_text', array(
 'default'        => 'Default Text For Email Header',
 ));
$wp_customize->add_setting('email_footer_text', array(
 'default'        => 'Default Text For Email Footer',
 ));

 $wp_customize->add_control('email_header_text', array(
 'label'   => 'Header Text Here',
  'section' => 'email_header_footer_settings_section',
 'type'    => 'textarea',
));

$wp_customize->add_control('email_footer_text', array(
 'label'   => 'Footer Text Here',
  'section' => 'email_header_footer_settings_section',
 'type'    => 'textarea',
));
}


if (!wp_next_scheduled('my_task_hook')) {
	wp_schedule_event( strtotime('09:00:00'), 'daily', 'my_task_hook' );
}
add_action ( 'my_task_hook', 'my_schedule_hook' );

function my_schedule_hook() {
switch_to_blog(1);
	$date = date('Y-m-d'); //'2019-03-26';
    $date7Days = date('Y-m-d',strtotime ( '-7 day' ));
	//echo $date7Days;
	$args = array(
		'post_type' => 'investment',
		'post_status'    => 'pending',
		'posts_per_page'    => '-1',
		'meta_query' => array(
			/*array(
				'key'     => 'themeum_investment_date',
				'value'   => $date,
				'compare' => '<=',
				'type'    => 'DATE'
			),*/
			array(
				'key'     => 'themeum_investment_date',
				'value'   => $date7Days,
				'compare' => '<=',
				'type'    => 'DATE'
			),
			array(
				'key'     => 'themeum_source_of_wealth',
				'value'   => 'cash',
				'compare' => '=',
			)
		)
	);

	$the_query  = new WP_Query( $args );
	/*echo "<pre>";
	print_r($the_query->posts);
	exit;*/
	if($the_query->post_count > 0) {
		$total = 0;
		foreach($the_query->posts as $post) {
			$isReminderSent = get_post_meta($post->ID,'reminder_sent',true);
			if($isReminderSent == 'yes') {
				continue;
			}
			$siteID = get_post_meta($post->ID,'site_id',true);
			$blogDetails = get_blog_details( array( 'blog_id' => $siteID ) );

			$siteName = $blogDetails->blogname;
			$investorID = get_post_meta($post->ID,'themeum_investor_user_id',true);
			$uData = get_userdata($investorID);
			$phoneNumber = get_user_meta($investorID,'phone_number',true);
			$phoneNumber = trim($phoneNumber);
			if($phoneNumber == 'n/a' || $phoneNumber == 'N/A' || $phoneNumber == '' ) {
				continue;
			}
			$countryCode = '+44'; // Replace with known country code of user.
			$number_to = $phoneNumber;
			if( $phoneNumber[0] == '0') {
			  $number_to = "{$countryCode}" . substr($phoneNumber,1);
			}else if($phoneNumber[0] != '0' && $phoneNumber[0] != '4'){
			  $number_to = "{$countryCode}" . $phoneNumber;
			}

			$msg = $siteName.": Don't forget to make your bank transfer to complete your investment.";
			//$number_to = "+447740281666";
			// send sms
			$args = array(
				'number_to' => $number_to,
				'message' => $msg,
			);
			twl_send_sms( $args );
			restore_current_blog();
			switch_to_blog($siteID);
			//$headers = array("Content-Type: text/html; charset=UTF-8 rn");
			$headers = "Content-Type: text/html; charset=UTF-8rn";
			//wp_mail($uData->data->user_email, "Reminder of cash transaction", $msg,$headers);
			//echo get_current_blog_id();
			$siteOptions = get_option('swpsmtp_options');
			$headers .= "From: ".$siteOptions['from_name_field']." <".$siteOptions['from_email_field'].">rn";
			$headers .= "Reply-To: '".$siteOptions['from_name_field']."' <".$siteOptions['from_email_field'].">rn";
			$headers .= "X-Mailer: PHP". phpversion() ."rn";
			mail($uData->data->user_email, "Reminder of cash transaction", $msg,$headers);
			//mail('', "Reminder of cash transaction sent to ".$number_to." and ".$uData->data->user_email, $msg,$headers);

			//wp_mail('gdang.gits@gmail.com', "Reminder of cash transaction sent to ".$number_to." and ".$uData->data->user_email, $msg,$headers);
			restore_current_blog();
			switch_to_blog(1);
			update_post_meta($post->ID,'reminder_sent','yes');

			// nullyfy the vars
			unset($siteOptions);
			unset($headers);
		}
	}
	restore_current_blog();
}

/*function my_schedule_hook(){ // this isbackup function
    switch_to_blog(1);
	$date = date('Y-m-d'); //'2019-03-26';
    $date7Days = date('Y-m-d',strtotime ( '-7 day' ));


	$args = array(
		'post_type' => 'investment',
		'post_status'    => 'pending',
		'posts_per_page'    => '-1',
		'meta_query' => array(
			array(
				'key'     => 'themeum_investment_date',
				'value'   => $date,
				'compare' => '<=',
				'type'    => 'DATE'
			),
			array(
				'key'     => 'themeum_investment_date',
				'value'   => $date7Days,
				'compare' => '>=',
				'type'    => 'DATE'
			),
			array(
				'key'     => 'themeum_source_of_wealth',
				'value'   => 'cash',
				'compare' => '=',
			)
		)
	);

	$the_query  = new WP_Query( $args );
	echo "<pre>";
	print_r($the_query->posts);
	echo "</pre>";

	if($the_query->post_count > 0) {
		$total = 0;
		foreach($the_query->posts as $post) {
			$isReminderSent = get_post_meta($post->ID,'reminder_sent',true);
			if($isReminderSent == 'yes') {
				continue;
			}
			$siteID = get_post_meta($post->ID,'site_id',true);
			$blogDetails = get_blog_details( array( 'blog_id' => $siteID ) );

			$siteName = $blogDetails->blogname;
			$investorID = get_post_meta($post->ID,'themeum_investor_user_id',true);
			$uData = get_userdata($investorID);
			$phoneNumber = get_user_meta($investorID,'phone_number',true);
			$phoneNumber = trim($phoneNumber);
			if($phoneNumber == 'n/a' || $phoneNumber == 'N/A' || $phoneNumber == '' ) {
				continue;
			}
			$countryCode = '+44'; // Replace with known country code of user.
			$number_to = $phoneNumber;
			if( $phoneNumber[0] == '0') {
			  $number_to = "{$countryCode}" . substr($phoneNumber,1);
			}else if($phoneNumber[0] != '0' && $phoneNumber[0] != '4'){
			  $number_to = "{$countryCode}" . $phoneNumber;
			}

			$msg = $siteName.": Don't forget to make your bank transfer to complete your investment.";
			//$number_to = "+447740281666";
			// send sms
			$args = array(
				'number_to' => $number_to,
				'message' => $msg,
			);
			//twl_send_sms( $args );
			restore_current_blog();
			switch_to_blog($siteID);
			$headers = array('Content-Type: text/html; charset=UTF-8');
			//wp_mail($uData->data->user_email, "Reminder of cash transaction", $msg,$headers);
			//echo get_current_blog_id();
			$siteOptions = get_option('swpsmtp_options');
			$headers[] = "From: ".$siteOptions['from_name_field']." <".$siteOptions['from_email_field'].">";
			wp_mail('gdang.gits@gmail.com', "Reminder of cash transaction sent to ".$number_to." and ".$uData->data->user_email, $msg,$headers);
			restore_current_blog();
			switch_to_blog(1);
			//update_post_meta($post->ID,'reminder_sent','yes');

			// nullyfy the vars
			unset($siteOptions);
			unset($headers);
		}
	}
	restore_current_blog();
}*/

/******************* DUMMY FUNCTION *******************************/
/*add_filter('wp_mail_from','reminder_set_wp_mail_from',999999);
function reminder_set_wp_mail_from($email) {
  return $email;
}*/
/*add_filter('wp_mail_from_name','reminder_set_wp_mail_from_name',999999);
function reminder_set_wp_mail_from_name($name) {
  return $name;
}*/

function dummyreminderhook(){
    switch_to_blog(1);
	$date = date('Y-m-d'); //'2019-03-26';
    $date7Days = date('Y-m-d',strtotime ( '-7 day' ));

	$args = array(
		'post_type' => 'investment',
		'post_status'    => 'pending',
		'posts_per_page'    => '-1',
		'meta_query' => array(
			array(
				'key'     => 'themeum_investment_date',
				'value'   => $date,
				'compare' => '<=',
				'type'    => 'DATE'
			),
			array(
				'key'     => 'themeum_investment_date',
				'value'   => $date7Days,
				'compare' => '>=',
				'type'    => 'DATE'
			),
			array(
				'key'     => 'themeum_source_of_wealth',
				'value'   => 'cash',
				'compare' => '=',
			)
		)
	);

	$the_query  = new WP_Query( $args );

	if($the_query->post_count > 0) {
		$total = 0;
		foreach($the_query->posts as $post) {
			$isReminderSent = get_post_meta($post->ID,'reminder_sent',true);
			if($isReminderSent == 'yes') {
				continue;
			}
			$siteID = get_post_meta($post->ID,'site_id',true);
			$blogDetails = get_blog_details( array( 'blog_id' => $siteID ) );

			$siteName = $blogDetails->blogname;
			$investorID = get_post_meta($post->ID,'themeum_investor_user_id',true);
			$uData = get_userdata($investorID);
			$phoneNumber = get_user_meta($investorID,'phone_number',true);
			$phoneNumber = trim($phoneNumber);
			if($phoneNumber == 'n/a' || $phoneNumber == 'N/A' || $phoneNumber == '' ) {
				continue;
			}
			$countryCode = '+44'; // Replace with known country code of user.
			$number_to = $phoneNumber;
			if( $phoneNumber[0] == '0') {
			  $number_to = "{$countryCode}" . substr($phoneNumber,1);
			}else if($phoneNumber[0] != '0' && $phoneNumber[0] != '4'){
			  $number_to = "{$countryCode}" . $phoneNumber;
			}

			$msg = $siteName.": Don't forget to make your bank transfer to complete your investment.";
			//$number_to = "+447740281666";
			// send sms
			$args = array(
				'number_to' => $number_to,
				'message' => $msg,
			);
			//twl_send_sms( $args );
			restore_current_blog();
			switch_to_blog($siteID);
			//$headers = array("Content-Type: text/html; charset=UTF-8 rn");
			$headers = "Content-Type: text/html; charset=UTF-8rn";
			//wp_mail($uData->data->user_email, "Reminder of cash transaction", $msg,$headers);
			//echo get_current_blog_id();
			$siteOptions = get_option('swpsmtp_options');
			$headers .= "From: ".$siteOptions['from_name_field']." <".$siteOptions['from_email_field'].">rn";
			$headers .= "Reply-To: '".$siteOptions['from_name_field']."' <".$siteOptions['from_email_field'].">rn";
			$headers .= "X-Mailer: PHP". phpversion() ."rn";
			//mail($uData->data->user_email, "Reminder of cash transaction", $msg,$headers);
			mail('gdang.gits@gmail.com', "Reminder of cash transaction sent to ".$number_to." and ".$uData->data->user_email, $msg,$headers);

			//wp_mail('gdang.gits@gmail.com', "Reminder of cash transaction sent to ".$number_to." and ".$uData->data->user_email, $msg,$headers);
			restore_current_blog();
			switch_to_blog(1);
			//update_post_meta($post->ID,'reminder_sent','yes');

			// nullyfy the vars
			unset($siteOptions);
			unset($headers);
		}
	}
	restore_current_blog();
}
//dummyreminderhook();
/*************************/

function getInvestments($type='', $params) {
	if($type == '') {
		$type = 'cash';
	}
	if(!isset($params['limit'])) {
		$limit = -1;
	}else{
		$limit = $params['limit'];
	}



	if($type == 'cancelled') {
		$args = array(
			'post_type' => 'investment',
			'post_status'    => array('cancelled','investment_closed'),
			'meta_key'          => 'themeum_investment_date',
			'orderby'           => 'meta_value',
			'order'             => 'DESC',
			'posts_per_page' => $limit
		);
	}else{
		if($type=='all') {
			$args = array(
				'post_type' => 'investment',
				'post_status'    => array('pending','received','cancelled','publish','transfer_requested','pack_out','investment_closed'),
				'meta_key'          => 'themeum_investment_date',
				'orderby'           => 'meta_value',
				'order'             => 'DESC',
				'posts_per_page' => $limit
			);
		}else{
			$args = array(
				'post_type' => 'investment',
				'post_status'    => array('pending','received','cancelled','publish','transfer_requested','pack_out','investment_closed'),
				'meta_key'          => 'themeum_investment_date',
				'orderby'           => 'meta_value',
				'order'             => 'DESC',
				'meta_query' => array(
					array(
						'key' => 'themeum_source_of_wealth',
						'value' => $type,
						'compare' => '='
					)
				),
				'posts_per_page' => $limit
			);
		}
	}
	if(isset($params['status']) && $params['status'] != '') {
		$args['post_status'] = $params['status'];
	}
	if(isset($params['product']) && $params['product'] != '') {
		$product = wp_unslash( $params['product'] );
		$product = sanitize_text_field( $product );
		$args['meta_query'][] = array(
					'key' => 'themeum_project_name',
					'value' => $product,
					'compare' => 'LIKE'
				);
	}

	if(isset($params['filter_name_email']) && $params['filter_name_email'] != '') {
		$nameEmail = wp_unslash( $params['filter_name_email'] );
		$nameEmail = sanitize_text_field( $nameEmail );
		$args['meta_query'][] = array(
			'relation' => 'OR',
			array(
				'key' => 'themeum_investor_user_first_name',
				'value' => $nameEmail,
				'compare' => 'LIKE'
			),
			array(
				'key' => 'themeum_investor_user_last_name',
				'value' => $nameEmail,
				'compare' => 'LIKE'
			),
			array(
				'key' => 'themeum_investor_user_email',
				'value' => $nameEmail,
				'compare' => 'LIKE'
			)
		);

	}

	if(isset($params['funding_source']) && $params['funding_source'] != '') {
		$args['meta_query'][] = array(
					'key' => 'themeum_source_of_wealth',
					'value' => $params['funding_source'],
					'compare' => '='
				);
	}

	if(isset($params['filter_ref']) && $params['filter_ref'] != '') {
		//unset($args['post_status']);
		$args['posts_per_page'] = -1;
		unset($args['paged']);
		if(is_array($params['filter_ref'])) {
			$args['meta_query'][] = array(
					'key' => 'themeum_investor_user_ref',
					'value' => $params['filter_ref'],
					'compare' => 'IN'
				);
		}else{
			$args['meta_query'][] = array(
					'key' => 'themeum_investor_user_ref',
					'value' => $params['filter_ref'],
					'compare' => 'LIKE'
				);
		}
	}

	if(isset($params['investment_type']) && $params['investment_type'] != '') {
		$args['meta_query'][] = array(
					'key' => 'themeum_investing_into',
					'value' => $params['investment_type'],
					'compare' => '='
				);
	}
	if($params['filter_site'] != 1 && isset($params['filter_site']) && $params['filter_site'] != '') {
		$args['meta_query'][] = array(
			'relation' => 'OR',
			array(
				'key' => 'site_id',
				'value' => $params['filter_site'],
				'compare' => '='
			),
			array(
				'key' => 'source_invest_site',
				'value' => $params['filter_site'],
				'compare' => '='
			)
		);
	}
	if(isset($params['user_id']) && $params['user_id'] > 0) {
		$args['meta_query'][] = array(
					'key' => 'themeum_investor_user_id',
					'value' => $params['user_id'],
					'compare' => '='
				);
	}

	if(isset($params['project_id']) && $params['project_id'] > 0) {
		$args['meta_query'][] = array(
					'key' => 'themeum_investment_project_id',
					'value' => $params['project_id'],
					'compare' => '='
				);
	}

	if(isset($params['ref_users']) && is_array($params['ref_users'])) {
		$args['meta_query'][] = array(
					'key' => 'themeum_investor_user_id',
					'value' => $params['ref_users'],
					'compare' => 'IN'
				);
	}

	if(isset($params['paged']) && $params['paged'] > 0) {
		$args['paged'] = $params['paged'];
	}


	$query = new WP_Query($args);

	if(isset($params['full_query']) && $params['full_query'] === true) {
		return $query;
	}
	if($query->post_count) {
		return $query->posts;
	}
	return false;
}

// custom menu item in admin panel for reports to display
function reports_admin_menu() {
	$cSite = get_current_blog_id();
		if($cSite == 1 && is_super_admin()) {
			add_menu_page(
		__( 'Reports', 'custom_gd_lang' ),
		__( 'Reports', 'custom_gd_lang' ),
		'manage_options',
		'products_reports',
		'admin_products_reports',
		'dashicons-schedule',
		3
	);
		}

}

add_action( 'admin_menu', 'reports_admin_menu' );

function admin_products_reports() {

		global $wpdb;
		$cSite = get_current_blog_id();
		if($cSite != 1 && !is_super_admin()) {
			return;
		}


		//wppb_update_options_all_sites();
		$params = array();
		if(isset($_GET['filter_uref']) && $_GET['filter_uref'] != '') {
			$refParts = explode("TRC2019",$_GET['filter_uref']);
			if(isset($refParts[1])) {
				$params['user_id'] = $refParts[1];
			}
		}
		if(isset($_GET['filter_status']) && $_GET['filter_status'] != '') {
			$params['status'] = $_GET['filter_status'];
		}
		if(isset($_GET['filter_product']) && $_GET['filter_product'] != '') {
			$params['product'] = $_GET['filter_product'];
		}
		if(isset($_GET['filter_funding_source']) && $_GET['filter_funding_source'] != '') {
			$params['funding_source'] = $_GET['filter_funding_source'];
		}
		if(isset($_GET['filter_investment_type']) && $_GET['filter_investment_type'] != '') {
			$params['investment_type'] = $_GET['filter_investment_type'];
		}
		if(isset($_GET['filter_site']) && $_GET['filter_site'] != '') {
			$params['filter_site'] = $_GET['filter_site'];
		}

		if(isset($_GET['filter_name_email']) && $_GET['filter_name_email'] != '') {
			$params['filter_name_email'] = $_GET['filter_name_email'];
		}

		if(isset($_GET['filter_ref']) && $_GET['filter_ref'] != '') {
			$params['filter_ref'] = $_GET['filter_ref'];
		}

		$limit = 100;


		if(!isset($_GET['tab']) || $_GET['tab'] == 'isa-transactions' || $_GET['tab'] == '') {

			$params['full_query'] = true;
			$cpage = $pn = isset($_GET['cpage']) ? (int)$_GET['cpage'] : 1;
			$params['limit'] = $limit;
			$params['offset'] = $offset;
			$params['paged'] = $cpage;

			$invQuery = getInvestments('all',$params);
			$investments = $invQuery->posts;
			$totalPosts = $invQuery->found_posts;
			$totalPages = $invQuery->max_num_pages;

			//echo $totalPosts. " = ". $totalPages;

			$k = (($cpage+4>$totalPages)?$totalPages-4:(($cpage-4<1)?5:$cpage));
			if($k < 1) {
				$k = 5;
			}
		}else if(isset($_GET['tab']) && $_GET['tab'] == 'cash-transactions') {
			$investments = getInvestments('cash', $params);
		}else if(isset($_GET['tab']) && $_GET['tab'] == 'cancelled-transactions') {
			$investments = getInvestments('cancelled', $params);
		}

		?>

		<div class="products_reports_container">
			<h1>
				<?php esc_html_e( 'Reports (This page is under construction)', 'my-plugin-textdomain' ); ?>
			</h1>
			<div class="tabs-container reports-tabs">
				<ul>
					<li class="<?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'isa-transactions' || $_GET['tab'] == '')  ? 'active' : '' ?>"><a href="<?php echo add_query_arg(array('page'=>'products_reports','tab'=>'isa-transactions'), admin_url('admin.php')) ?>" id="tab-isa-transactions">All Transactions</a></li>
					<!--<li class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'cash-transactions')  ? 'active' : '' ?>"><a href="<?php echo add_query_arg('tab','cash-transactions') ?>" id="tab-cash-transactions">Cash Transactions</a></li>
					<li class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'cancelled-transactions')  ? 'active' : '' ?>"><a href="<?php echo add_query_arg('tab','cancelled-transactions') ?>" id="tab-cancelled-transactions">Cancelled Transactions</a></li>-->
					<li class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'users')  ? 'active' : '' ?>"><a href="<?php echo add_query_arg('tab','users') ?>" id="tab-users">Users</a></li>
				</ul>
			</div>
			<?php if(!isset($_GET['tab']) || $_GET['tab'] == 'isa-transactions' || $_GET['tab'] == '') { ?>
			<section class="reports_content content-isa-transactions" id="content-isa-transactions">
				<h2>All Transactions</h2>
				<div class="filter_box">
					<form>
						<input type="hidden" name="page" value="products_reports" />
						<input type="hidden" name="tab" value="<?php echo (!isset($_GET['tab']) || $_GET['tab'] == '')  ? 'isa-transactions' : $_GET['tab'] ?>" />
						<span><input type="text" name="filter_uref" id="filter_uref" placeholder="TRC Reference Number" value="<?php echo isset($_GET['filter_uref']) ? $_GET['filter_uref'] : '' ?>" /></span>
						<span><input type="text" name="filter_name_email" id="filter_name_email" placeholder="Name/Email" /></span>
						<span><input type="text" name="filter_product" id="filter_product" placeholder="product" value="<?php echo isset($_GET['filter_product']) ? $_GET['filter_product'] : '' ?>" /></span>
						<span>
							<select name="filter_funding_source">
								<option value="">Funding Source</option>
								<option value="isa" <?php echo isset($_GET['filter_funding_source']) && $_GET['filter_funding_source'] == 'isa' ? 'selected' : '' ?>>ISA</option>
								<option value="cash" <?php echo isset($_GET['filter_funding_source']) && $_GET['filter_funding_source'] == 'cash' ? 'selected' : '' ?>>Cash</option>
								<option value="sass" <?php echo isset($_GET['filter_funding_source']) && $_GET['filter_funding_source'] == 'sass' ? 'selected' : '' ?>>SASS</option>
								<option value="sipp" <?php echo isset($_GET['filter_funding_source']) && $_GET['filter_funding_source'] == 'sipp' ? 'selected' : '' ?> >SIPP</option>
							</select>
						</span>
						<span>
							<select name="filter_investment_type">
								<option value="">Investment Type</option>
								<option value="isa" <?php echo isset($_GET['filter_investment_type']) && $_GET['filter_investment_type'] == 'isa' ? 'selected' : '' ?>>ISA</option>
								<option value="ifisa" <?php echo isset($_GET['filter_investment_type']) && $_GET['filter_investment_type'] == 'ifisa' ? 'selected' : '' ?>>IFISA</option>
								<option value="ssisa" <?php echo isset($_GET['filter_investment_type']) && $_GET['filter_investment_type'] == 'ssisa' ? 'selected' : '' ?>>SSISA</option>
								<option value="bond" <?php echo isset($_GET['filter_investment_type']) && $_GET['filter_investment_type'] == 'bond' ? 'selected' : '' ?>>Bond</option>
								<option value="loan_note" <?php echo isset($_GET['filter_investment_type']) && $_GET['filter_investment_type'] == 'loan_note' ? 'selected' : '' ?>>Loan Note</option>
								<option value="equity" <?php echo isset($_GET['filter_investment_type']) && $_GET['filter_investment_type'] == 'equity' ? 'selected' : '' ?> >Equity</option>
								<option value="fund" <?php echo isset($_GET['filter_investment_type']) && $_GET['filter_investment_type'] == 'fund' ? 'selected' : '' ?> >Fund</option>
							</select>
						</span>
						<span>
							<select name="filter_status">
								<option value="">Status</option>
								<option value="pending" <?php echo isset($_GET['filter_status']) && $_GET['filter_status'] == 'pending' ? 'selected' : '' ?>>Awaiting Funding</option>
								<option value="received" <?php echo isset($_GET['filter_status']) && $_GET['filter_status'] == 'received' ? 'selected' : '' ?>>Funds Received</option>
								<option value="publish" <?php echo isset($_GET['filter_status']) && $_GET['filter_status'] == 'publish' ? 'selected' : '' ?>>Completed</option>
								<option value="cancelled" <?php echo isset($_GET['filter_status']) && $_GET['filter_status'] == 'cancelled' ? 'selected' : '' ?> >Cancelled</option>
								<option value="investment_closed" <?php echo isset($_GET['filter_status']) && $_GET['filter_status'] == 'investment_closed' ? 'selected' : '' ?> >Investment Closed</option>
								<option value="pack_out" <?php echo isset($_GET['filter_status']) && $_GET['filter_status'] == 'pack_out' ? 'selected' : '' ?> >Pack Out</option>
								<option value="transfer_requested" <?php echo isset($_GET['filter_status']) && $_GET['filter_status'] == 'transfer_requested' ? 'selected' : '' ?> >Transfer Requested</option>
							</select>
						</span>
						<span>
							<?php
								$allSites = get_sites(array('site__not_in' => array(1)));

							?>
							<select name="filter_site">
								<option value="">White Label Site</option>
								<?php
									if(count($allSites) > 0):
									  foreach($allSites as $site) :
										$siteName = get_blog_details($site->blog_id)->blogname;
								?>
										<option value="<?php echo $site->blog_id ?>" <?php echo isset($_GET['filter_site']) && $_GET['filter_site'] == $site->blog_id ? 'selected' : '' ?> ><?php echo $siteName ?></option>
								<?php endforeach;
									endif;
								?>
							</select>
						</span>
						<span><input type="text" name="filter_ref" id="filter_ref" placeholder="Referred By" value="<?php echo isset($_GET['filter_ref']) ? $_GET['filter_ref'] : '' ?>" /></span>
						<span><button type="submit" name="filter_button" >Filter</button></span>
					</form>

					<form action="<?php echo admin_url("admin-post.php") ?>" method="post" class="export-reports-form">
						<input type="hidden" name="action" value="exportreports_special" />
						<input type="hidden" name="tab" value="all" />
						<input type="hidden" name="uref" value="<?php echo isset($_GET['filter_uref']) ? $_GET['filter_uref'] : '' ?>" />
						<input type="hidden" name="name_email" value="<?php echo isset($_GET['filter_name_email']) ? $_GET['filter_name_email'] : '' ?>" />
						<input type="hidden" name="status" value="<?php echo isset($_GET['filter_status']) && $_GET['filter_status'] ? $_GET['filter_status'] : '' ?>" />
						<input type="hidden" name="product" value="<?php echo isset($_GET['filter_product']) ? $_GET['filter_product'] : '' ?>" />
						<input type="hidden" name="funding_source" value="<?php echo isset($_GET['filter_funding_source']) ? $_GET['filter_funding_source'] : '' ?>" />
						<input type="hidden" name="investment_type" value="<?php echo isset($_GET['filter_investment_type']) ? $_GET['filter_investment_type'] : '' ?>" />
						<input type="hidden" name="filter_site" value="<?php echo isset($_GET['filter_site']) ? $_GET['filter_site'] : '' ?>" />
						<button type="submit" name="exportbtn">Export to CSV</button>
					</form><br/>
					<form action="<?php echo admin_url("admin-post.php") ?>" method="post" class="export-reports-form">
						<input type="hidden" name="action" value="exportreports_special" />
						<input type="hidden" name="status" value="publish" />
						<button type="submit" name="exportbtn">Export Completed Investments to CSV</button>
					</form><br/>

					<form action="<?php echo admin_url("admin-post.php") ?>" method="post" class="export-reports-form">
						<input type="hidden" name="action" value="exportreports_special" />
						<input type="hidden" name="status" value="pending" />
						<button type="submit" name="exportbtn">Export Pending Investments to CSV</button>
					</form>
				</div>
				<div class="user_pagination pagination">
					<ul>
				<!-- Pagination -->
				<?php $pagLink = "";
        if($pn>=2){
            echo "<li><a href='/wp-admin/admin.php?".(http_build_query($_GET))."&cpage=1'> << </a></li>";
            echo "<li><a href='/wp-admin/admin.php?".(http_build_query($_GET))."&cpage=".($pn-1)."'> < </a></li>";
        }

if($totalPages > 1) {
        for ($i=-4; $i<=4; $i++) {
          if($k+$i==$pn)
            $pagLink .= "<li class='active'><a href='/wp-admin/admin.php?".(http_build_query($_GET))."&cpage=".($k+$i)."'>".($k+$i)."</a></li>";
          else
            $pagLink .= "<li><a href='/wp-admin/admin.php?".(http_build_query($_GET))."&cpage=".($k+$i)."'>".($k+$i)."</a></li>";
        };
		echo $pagLink;
}

        if($pn<$totalPages){
            echo "<li><a href='/wp-admin/admin.php?".(http_build_query($_GET))."&cpage=".($pn+1)."'> > </a></li>";
            echo "<li><a href='/wp-admin/admin.php?".(http_build_query($_GET))."&cpage=".$totalPages."'> >> </a></li>";
        }     ?>
		</ul>
		</div>
				<table border='1' cellpadding="5" width="100%" cellspacing="0" id="table-isa-transactions" class="content-table">
					<thead>
						<tr><th>Transaction ID</th><th>TRC Reference Number</th><th>Title</th><th class="column_fname">First Name</th><th class="column_lname">Last Name</th><th class="column_email">Email</th><th>Investor Type</th><th>Product</th><th>Funding Source</th><th>Investment Type</th><th>Status</th><th>Funds Received</th><th>Investment Amount</th>
						<th>Application</th>
						<th>Pledge Date</th><th>White Label Site</th>
						<!--<th>AML Docs</th>-->
						<th>AML Check</th>
						<th>Referred By</th><th>Actions</th></tr>
					</thead>
					<tbody>
						<?php if($investments) {
							$transferTypeLabels = array(
								'isa_transfer' => 'ISA Transfer',
								'new_isa' => 'New ISA',
								'cash' => 'Cash',
								'stocks' => 'Stocks and Shares',
								'ifisa' => 'IFISA'
							);
						?>
						<?php foreach($investments as $inv) :
							$invInfo = getInvestmentInfo($inv->ID);
							$uMetas = get_user_meta($invInfo['investor_user_id']);
							$uData = get_userdata($invInfo['investor_user_id']);
							$invSiteID = get_post_meta($inv->ID,'site_id',true);
							$blog_details = get_blog_details( $invSiteID );
							$siteName = $blog_details->blogname;
							$sourceSiteName = ' - ';
							$sourceSiteID = get_post_meta($inv->ID,'source_invest_site',true);
							if($sourceSiteID != '') {
								$s_blog_details = get_blog_details( $sourceSiteID );
								$sourceSiteName = $s_blog_details->blogname;
							}
							$notes = get_post_meta($inv->ID,'themeum_notes',true);
							$inv_curr = get_post_meta($inv->ID,'investment_currency',true);
							if($inv_curr == '') {
								$inv_curr = 'GBP';
							}
							$title = ($invInfo['project_name'] != '')
										? $invInfo['project_name']
										: get_the_title( $invInfo['investment_project_id'] );

							if(trim($title) == '') {
								$investTitleParts = explode(":",$inv->post_title);
								if(count($investTitleParts) > 1) {
									$title = $investTitleParts[1];
								}
							}

							$updir = wp_get_upload_dir();
							$application = $updir['basedir'].'/pp09spv_applications/application_'.$invInfo['investor_user_id'].'_'.$inv->ID.'.pdf';
							if(file_exists($application)){
								$application = site_url().'/wp-content/uploads/pp09spv_applications/application_'.$invInfo['investor_user_id'].'_'.$inv->ID.'.pdf';
								$applicationCode = 	'<a href="'.$application.'" target="_blank">Application</a>';
							}else{
								$applicationCode = '-';
							}
						?>
							<tr class="investment-row" data-invid="<?php echo $inv->ID ?>">
								<td><?php echo $inv->ID ?></td>
								<td><?php echo 'TRC2019'.$uData->ID; ?></td>
								<td><?php echo $uMetas['user_title'][0] ?></td>
								<td><?php echo $uMetas['billing_first_name'][0] ?></td>
								<td><?php echo $uMetas['billing_last_name'][0] ?></td>
								<td><?php echo $uData->data->user_email ?></td>
								<td><?php echo $uMetas['investor_type'][0] ?></td>
								<td><?php echo '<a href="'.get_edit_post_link( $inv->ID ).'">'.$title.'</a>'; ?> </td>
								<td><?php echo $invInfo['source_of_wealth'] != '' ? $invInfo['source_of_wealth'] : '-' ?></td>
								<td><?php echo $invInfo['investing_into'] != '' ? $invInfo['investing_into'] : '-' ?></td>
								<td>
									<?php
										$status = esc_html(get_post_meta( $inv->ID , 'themeum_status_all' , true ));
										if($status == 'cancelled') {
											echo "Funding Cancelled";
										}else if ($status == 'investment_closed') {
											echo "Investment Closed";
										}else if ($status == 'received'){
											echo "Funding Received";
										}else if ($status == 'complete') {
											echo "Investment Complete";
										}else if ($status == 'pending'){
											echo "Awaiting Funding";
										}else if ($status == 'transfer_requested'){
											echo "Transfer Requested";
										}else if ($status == 'pack_out') {
											echo "Pack Out";
										}else{
											echo "Awaiting Funding";
										}
									?>
								</td>
								<td><?php
									if($invInfo['received_date'] != '') {
										//$frd = strtotime($invInfo['received_date']);
										$frdt = new DateTime($invInfo['received_date']);
										echo $frdt->format("d/m/Y h:i:s a");
									}else{
										echo '-';
									}
								?></td>
								<td>
									<?php echo get_currencies($inv_curr).(number_format($invInfo['investment_amount'],2)); ?>
								</td>
								<td><?php echo $applicationCode ?></td>
								<td>
									<?php

									$d = str_replace("pm","",$invInfo['investment_date']);
									$d = str_replace("am","",$d);
									$dt = new DateTime($d);

									echo $dt->format("d/m/Y h:i:s a");

									?></td>
								<td><?php echo $siteName ?></td>
								<!--<td><?php
									/* if($sourceSiteID != '') {
											switch_to_blog($sourceSiteID);
										}else{
											switch_to_blog($invSiteID);
										}
										$docs = get_user_meta($uData->ID,'aml_docs',true);
										if($docs != '' && count($docs) > 0) {
											foreach($docs as $docid) {
												$p = get_post($docid);
												echo '<span style="display: block;"><a href="'.$p->guid.'" target="_blank">'.$p->post_title.'</a></span>';
											}
										}else{
											echo 'Not uploaded';
										}
										restore_current_blog(); */
								?></td>-->
								<td style="text-align: center;">
									<input type="checkbox" id="aml_check_<?php echo $inv->ID ?>" data-uid="<?php echo $uData->ID ?>" class="aml_check aml_check_u_<?php echo $uData->ID ?>" <?php echo isset($uMetas['aml_doc_check']) && $uMetas['aml_doc_check'][0] == 'yes' ? 'checked' : '' ?>  data-inv="<?php echo $inv->ID ?>"/>
								</td>
								<td><?php echo $invInfo['investor_user_ref'] != '' ? $invInfo['investor_user_ref'] : $uMetas['referralcode'][0] ?></td>
								<td><a href="<?php echo get_edit_post_link($inv->ID); ?>">edit</a>
								 |
								<a id="more_investment_details_<?php echo $inv->ID ?>" data-fancybox data-type="ajax" data-src="<?php echo admin_url('admin-ajax.php')."?action=getInvDetails&inv_id=".$inv->ID ?>" href="javascript:;" class="btn btn-primary">More Details</a>
								</td>
							</tr>
							<tr style="display:none;" class="details-row" id="investment-details-<?php echo $inv->ID ?>">
								<td></td>
								<td>
									<span>DOB: </span><span><?php echo $uMetas['userdob'][0] ?></span>
								</td>
								<td></td>
								<td></td>
								<td><span>NI Number: </span><span><?php echo $uMetas['ni_number'][0] ?></span></td>
								<td>
									<span>Address: </span>
									<span>
										<?php echo $uMetas['billing_address_1'][0] ?>,<?php echo $uMetas['billing_address_2'][0] ?>,
										<?php echo $uMetas['billing_city'][0] ?>, <?php echo $uMetas['billing_state'][0] ?>,
										<?php echo $uMetas['billing_country'][0] ?> - <?php echo $uMetas['billing_postcode'][0] ?>
									</span>
								</td>
								<td>
									<?php
										if($sourceSiteID != '') {
											switch_to_blog($sourceSiteID);
										}else{
											switch_to_blog($invSiteID);
										}
										$IdProof = get_user_meta($uData->ID,'idproof',true);
										$AddressProof = get_user_meta($uData->ID,'addressproof',true);
										//echo $IdProof;
										$proofs = 'Proofs: <br/>';
										if($IdProof > 0) {
											$IdProofPost = get_post($IdProof);
											$proofs .= 'ID: <a href="'.$IdProofPost->guid.'" target="_blank">'.$IdProofPost->post_title.'</a><br/>';
										}
										if($AddressProof > 0) {
											$AddressProofPost = get_post($AddressProof);
											$proofs .= 'Address: <a href="'.$AddressProofPost->guid.'" target="_blank">'.$AddressProofPost->post_title.'</a>';
										}
										echo $proofs;
										restore_current_blog();
									?>
								</td>
								<td></td>
								<td></td>
								<?php if($invInfo['source_of_wealth'] != 'isa'): ?>
								<td></td>
								<td></td>
								<td></td>
								<?php endif; ?>
								<td><span>Contact Number: </span><span><?php echo $uMetas['phone_number'][0] ?></span></td>
								<td><span>Funding Source: </span><span><?php echo $invInfo['source_of_wealth'] ?></span></td>
								<td><span>ISA Type: </span><span><?php echo $invInfo['transfertype'] != '' ? $transferTypeLabels[$invInfo['transfertype']] : '-' ?></span></td>
								<?php if($invInfo['source_of_wealth'] == 'isa'): ?>
									<td><span>Name of ISA Provider: </span><span><?php echo $invInfo['name_isa_provider'] ?></span></td>
									<td><span>Address of ISA Provider: </span><span><?php echo $invInfo['address1_isa_provider'].", ".$invInfo['address1_isa_provider'].", ".$invInfo['city_isa_provider'].", ".$invInfo['state_isa_provider'].", ".$invInfo['zip_isa_provider'] ?></span></td>
									<td>
										<span>Account/Reference Number: </span><span><?php echo $invInfo['account_isa_provider'] ?></span>
										<?php if($invInfo['another_account'] == 'yes'): ?>
										<br/>
										<span>Another Account: </span><span><?php echo $invInfo['another_account_isa_provider'] ?></span>
										<?php endif; ?>
									</td>
									<td><span>Full/Part ISA Transfer: </span><span><?php echo ucwords(str_replace("_"," ",$infInfo['full_part_isa_transfer'])); ?></span></td>
								<?php endif; ?>
								<td><span>Notes: </span><br/>
									<ul class="investment-notes" id="notes_wrapper_<?php echo $inv->ID ?>">
										<?php if($notes != '' && count($notes) > 0): ?>
											<?php foreach($notes as $timestamp => $n): ?>
													<li style="line-height: 15px;">
														<?php echo "<span style='font-size:10px;'>".(date("d/m/Y h:i:s a",$timestamp))."</span>"; ?><br/>
														<?php echo trim($n) ?>
													</li>
											<?php endforeach; ?>
										<?php endif; ?>
									</ul>
									<div class="investment-notes-input"><input type="text" class="" id="notes_<?php echo $inv->ID ?>" placeholder="Add Note.."  /><button type="button" data-investmentid="<?php echo $inv->ID ?>" class="btn investment-notes-submit">Submit</button></div>
								</td>
							</tr>
						<?php endforeach; ?>
						<?php }else{ ?>
							<tr>
								<td colspan="13" align="center">No Investments Found!</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</section>
			<?php
			} //end section ISA

			if((isset($_GET['tab']) && $_GET['tab'] == 'users')) {
				$limit = 50;
				$result = $wpdb->get_results(
					"SELECT SQL_CALC_FOUND_ROWS * FROM ".$wpdb->base_prefix."users WHERE 1 LIMIT $limit;"
				);
				$total_count = $wpdb->get_var(
					"SELECT FOUND_ROWS();"
				);
				$totalPages = ceil($total_count/$limit);
				$cpage = $pn = isset($_GET['cpage']) ? (int)$_GET['cpage'] : 1;
				$offset = ($cpage-1) * $limit;
				$k = (($cpage+4>$totalPages)?$totalPages-4:(($cpage-4<1)?5:$cpage));
				$sq = "SELECT * FROM ".$wpdb->base_prefix."users ";
				$sq .= "where 1 ";
				if(isset($_GET['filter_name']) && $_GET['filter_name'] != '') {
					$sq .= "AND display_name like '%".$_GET['filter_name']."%' ";
				}
				if(isset($_GET['filter_username']) && $_GET['filter_username'] != '') {
					$sq .= "AND user_login like '%".$_GET['filter_username']."%' ";
				}
				if(isset($_GET['filter_email']) && $_GET['filter_email'] != '') {
					$sq .= "AND user_email like '%".$_GET['filter_email']."%' ";
				}
				$sq .= "ORDER BY user_registered DESC limit $offset,$limit";
				$allUsers = $wpdb->get_results($sq);
			?>
			<section class="reports_content content-users" id="content-users">
				<h2>Users</h2>
				<div class="filter_box">
					<form>
						<input type="hidden" name="page" value="products_reports" />
						<input type="hidden" name="tab" value="<?php echo (!isset($_GET['tab']) || $_GET['tab'] == '')  ? 'isa-transactions' : $_GET['tab'] ?>" />
						<span><input type="text" name="filter_name" id="filter_name" placeholder="Name" value="<?php echo isset($_GET['filter_name']) ? $_GET['filter_name'] : ''  ?>" /></span>
						<span><input type="text" name="filter_username" id="filter_username" placeholder="Username" value="<?php echo isset($_GET['filter_username']) ? $_GET['filter_username'] : ''  ?>" /></span>
						<span><input type="text" name="filter_email" id="filter_email" placeholder="email" value="<?php echo isset($_GET['filter_email']) ? $_GET['filter_email'] : ''  ?>" /></span>
						<span><button type="submit" name="filter_button" >Filter</button></span>
					</form>
				</div>
				<form action="<?php echo admin_url("admin-post.php") ?>" method="post" class="export-reports-form">
					<input type="hidden" name="action" value="exportreports" />
					<input type="hidden" name="tab" value="users" />
					<input type="hidden" name="filter_name" value="<?php echo isset($_GET['filter_name']) && $_GET['filter_name']!= '' ? $_GET['filter_name'] : '' ?>" />
					<input type="hidden" name="filter_username" value="<?php echo isset($_GET['filter_username']) && $_GET['filter_username']!= '' ? $_GET['filter_username'] : '' ?>" />
					<input type="hidden" name="filter_email" value="<?php echo isset($_GET['filter_email']) && $_GET['filter_email']!= '' ? $_GET['filter_email'] : '' ?>" />
					<button type="submit" name="exportbtn">Export to CSV</button>
				</form><br/>
				<?php if(!isset($_GET['filter_button'])) { ?>
				<div class="user_pagination pagination">
					<ul>

						<?php $pagLink = "";
        if($pn>=2){
            echo "<li><a href='/wp-admin/admin.php?page=products_reports&tab=users&cpage=1'> << </a></li>";
            echo "<li><a href='/wp-admin/admin.php?page=products_reports&tab=users&cpage=".($pn-1)."'> < </a></li>";
        }
        for ($i=-4; $i<=4; $i++) {
          if($k+$i==$pn)
            $pagLink .= "<li class='active'><a href='/wp-admin/admin.php?page=products_reports&tab=users&cpage=".($k+$i)."'>".($k+$i)."</a></li>";
          else
            $pagLink .= "<li><a href='/wp-admin/admin.php?page=products_reports&tab=users&cpage=".($k+$i)."'>".($k+$i)."</a></li>";
        };
        echo $pagLink;
        if($pn<$totalPages){
            echo "<li><a href='/wp-admin/admin.php?page=products_reports&tab=users&cpage=".($pn+1)."'> > </a></li>";
            echo "<li><a href='/wp-admin/admin.php?page=products_reports&tab=users&cpage=".$totalPages."'> >> </a></li>";
        }     ?>
					</ul>
				</div>
				<?php } ?>
				<table border='1' cellpadding="5" width="100%" cellspacing="0" id="table-users" class="content-table">
					<thead>
						<tr><th>Username</th><th>First Name</th><th>Last Name</th><th>Investor Type</th><th>Email</th><th>Date Registered</th><th>Source Site</th><th>Referred By</th><th>Certificate</th><th>NDA</th><th>Questionnaire Points</th><th>Actions</th></tr>
					</thead>
					<tbody>
						<?php foreach($allUsers as $user) :
							$uMetas = get_user_meta($user->ID);
							$uData = get_userdata($user->ID);
							$uSiteID = $uMetas['primary_blog'][0];
							$blog_details = get_blog_details( $uSiteID );
							$siteurl = $blog_details->siteurl;
							$siteName = $blog_details->blogname;
							$notes = getNotes($user->ID);
						?>
						<tr class="user-row" data-uid="<?php echo $user->ID ?>">
							<td><?php echo $uData->data->user_login ?></td>
							<td><?php echo $uMetas['billing_first_name'][0] ?></td>
							<td><?php echo $uMetas['billing_last_name'][0] ?></td>
							<td><?php echo $uMetas['investor_type'][0] ?></td>
							<td><?php echo $uData->data->user_email ?></td>
							<td><?php echo date("d/m/Y",strtotime($uData->data->user_registered)) ?></td>
							<td><?php echo $siteName ?></td>
							<td><?php echo $uMetas['referralcode'][0] ?></td>
							<td><?php
								$certificate = $siteurl.'/wp-content/uploads/statements/statement_'.$user->ID.'.pdf';
								$ch = curl_init($certificate);
								curl_setopt($ch, CURLOPT_NOBODY, true);
								curl_exec($ch);
								$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
								//$certificate_dir = WP_CONTENT_DIR.'/wp-content/uploads/statements/statement_'.$user_id.'.pdf';
								if($code == '200') {
									echo  '<a href="'.$certificate.'" target="_blank">statement_'.$user->ID.'.pdf</a>';
								}else{
									echo '-';
								}
							?></td>
							<td><?php
								$NDA = $siteurl.'/wp-content/uploads/NDA/nda_'.$user->ID.'.pdf';
								$ch = curl_init($NDA);
								curl_setopt($ch, CURLOPT_NOBODY, true);
								curl_exec($ch);
								$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
								//$certificate_dir = WP_CONTENT_DIR.'/wp-content/uploads/statements/statement_'.$user_id.'.pdf';
								if($code == '200') {
									echo  '<a href="'.$NDA.'" target="_blank">NDA Doc</a>';
								}else{
									echo '-';
								}
							?></td>
							<td style="position: relative;"><?php
								$points = getQuestionnairePoints($uData->ID,true);
								echo "<span class='points_hover' id='".$uData->ID."'>".$points['total']."</span>";
								?>
								<div class='points_hover_div' style="display:none" id='points_hover_div_<?php echo $uData->ID; ?>'>
									<?php foreach($points as $k => $v) { ?>
									<span><?php echo $k ?></span>: <span><?php echo $v ?></span><br/>
									<?php } ?>
								<div>
							</td>
							<td><a target="_blank" href="<?php echo '/wp-admin/network/user-edit.php?user_id='.$user->ID.'&wp_http_referer=%2Fwp-admin%2Fnetwork%2Fusers.php'
							/*restore_current_blog();
							switch_to_blog($uSiteID);
							echo get_edit_user_link($user->ID);
							restore_current_blog();
							switch_to_blog(1);*/
							?>">edit</a>
							 | <a class="updateUserWhiteLabel" data-uid="<?php echo $uData->ID ?>" href="javascript:void(0);">Change WhiteLabel</a>
							</td>
						</tr>
						<tr style="display:none;" class="details-row" id="user-details-<?php echo $user->ID ?>">
							<td colspan="9">
							<table width="100%">
								<tr>
									<td>
										<span>DOB: </span><span><?php echo $uMetas['userdob'][0] ?></span>
									</td>
									<td><span>NI Number: </span><span><?php echo $uMetas['ni_number'][0] ?></span></td>
									<td colspan="2">
										<span>Address: </span>
										<span>
											<?php echo $uMetas['billing_address_1'][0] ?>,<?php echo $uMetas['billing_address_2'][0] ?>,
											<?php echo $uMetas['billing_city'][0] ?>, <?php echo $uMetas['billing_state'][0] ?>,
											<?php echo $uMetas['billing_country'][0] ?> - <?php echo $uMetas['billing_postcode'][0] ?>
										</span>
									</td>
									<td><span>Contact Number: </span><span><?php echo $uMetas['phone_number'][0] ?></span></td>
									<td colspan="2"><span>Notes: </span><br/>
										<ul class="user-notes" id="user_notes_wrapper_<?php echo $user->ID ?>">
											<?php if(count($notes) > 0): ?>
												<?php foreach($notes as $n): ?>
														<li><?php echo $n ?></li>
												<?php endforeach; ?>
											<?php endif; ?>
										</ul>
										<div class="investment-notes-input"><input type="text" class="" id="user_notes_<?php echo $user->ID ?>" placeholder="Add Note.."  />
										<select id="user_notes_privacy_<?php echo $user->ID ?>" style="width: 20%;">
											<option value="admin">Admin Only</option>
											<option value="admin_whitelabel">Admin & White Label Managers</option>
											<option value="admin_whitelabel_referrer">Admin, White Label Managers & Referrer</option>
										</select>
										<button type="button" data-userid="<?php echo $user->ID ?>" class="btn user-notes-submit">Submit</button></div>
									</td>
								</tr>
								<tr>
									<td colspan="4"><span>Bank Details: </span><br/><span><?php echo nl2br($uMetas['bank_details'][0]) ?></span></td>
									<td colspan="4"><span>Coupons Paid Interval: </span><br/><span><?php echo $uMetas['user_coupon_interval'][0] ?></span></td>
								</tr>
								<tr>
									<td colspan="8">
										<?php
											$userInvestments = getInvestments('all',array('user_id' => $user->ID));
											echo "<table class='uinvestments' width='100%'>";
											echo "<tr>
													<th>Project Name</th>
													<th>Amount</th>
													<th>Status</th>
													<th>Source</th>
													<th>Date</th>
													<th>Site</th>
											</tr>";
											if(count($userInvestments) > 0) {
												foreach($userInvestments as $uInv):
												$uInvInfo = getInvestmentInfo($uInv->ID);
												$invSiteID = get_post_meta($uInv->ID,'site_id',true);
												$blog_details = get_blog_details( $invSiteID );
												$siteName = $blog_details->blogname;
												$source = get_post_meta($uInv->ID,'themeum_source_of_wealth',true);
											?>
												<tr>
													<td><?php echo '<a href="'.get_edit_post_link( $uInv->ID ).'">'.get_the_title(esc_html($uInvInfo['investment_project_id'])).'</a>';  ?></td>
													<td>£<?php echo $uInvInfo['investment_amount']; ?></td>
													<td>
														<?php
															$status = esc_html(get_post_meta( $uInv->ID , 'themeum_status_all' , true ));
															if($status == 'cancelled') {
																echo "Funding Cancelled";
															}else if($status == 'investment_closed') {
																echo "Investment Closed";
															}else if ($status == 'received'){
																echo "Funding Received";
															}else if ($status == 'complete') {
																echo "Investment Complete";
															}else if ($status == 'pending'){
																echo "Awaiting Funding";
															}else if ($status == 'transfer_requested'){
																echo "Transfer Requested";
															}else if ($status == 'pack_out') {
																echo "Pack Out";
															}else{
																echo "Awaiting Funding";
															}
														?>
													</td>
													<td>
														<?php echo $source; ?>
													</td>
													<td>
														<?php echo date('d/m/Y',strtotime($uInvInfo['investment_date'])); ?>
													</td>
													<td><?php echo $siteName ?></td>
												</tr>
											<?php
												endforeach;
											}else{
												echo "<tr><td colspan='4'>No investments found.</td></tr>";
											}
											echo "</table>";
										?>
									</td>
								</tr>
							</table>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</section>
			<?php } ?>
		</div>
		<style>
			.points_hover {
					display: block;
					padding: 10px;
					cursor: pointer;
					color: #00AED1;
				}
				.points_hover_div {
					position: absolute;
					border: 1px solid #ccc;
					background: #fff;
					padding: 10px;
					width: 180px;
					right: 0;
					color: #000 !important;
					z-index: 9;
				}
			.reports_content table.content-table > tbody > tr {
				cursor: pointer;
			}
			.reports_content table.content-table > tbody > tr:hover {
				background-color: #ccc;
				color: #fff;
			}
			.reports_content table.content-table > tbody tr.details-row {
				cursor: text !important;
				border: none;
				height: 100px;
			}
			.reports_content table.content-table > tbody tr.details-row td {
				border: none;
				word-wrap: break-word;
				vertical-align: top;
			}
			.reports_content table.content-table > tbody tr.details-row td span {
				font-weight: bold;
			}
			.reports_content table.content-table > tbody tr.details-row td span + span {
				font-weight: normal;
			}
			.reports_content table.content-table > tbody tr.details-row:hover {
				background: none;
				color: #000;
			}
			.reports_content table.content-table > tbody tr.details-row td .investment-notes {
				height: 75px;
				overflow-y: auto;
			}
			.reports-tabs {
				margin-top: 30px;
			}
			.reports-tabs li {
				display: inline-block;
			}
			.reports-tabs ul li a {
				background: #C7CED6;
				padding: 10px;
				color: #fff;
				text-decoration: none;
				font-size: 12px;
			}
			.reports-tabs ul li.active a {
				background: #01D5EB;
			}
			.filter_box {
				margin-bottom: 20px;
			}
			.filter_box select {
				vertical-align: top;
			}
			.filter_box button {
				height: 26px;
    vertical-align: top;
    margin-top: 1px;
			}
			#table-isa-transactions_filter, #table-cash-transactions_filter {
				display: none !important;
			}
			.user_pagination ul li {
				display: inline-block;
				margin-right: 8px;
			}
			.user_pagination ul li a {
				padding: 5px;
				border: 1px solid #ccc;
				color: #000;
				text-decoration: none;
			}
			.user_pagination ul li.active a {
				background: #01D5EB;
				color: #fff;
				border: 1px solid #01D5EB;
			}
		</style>
		<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo get_template_directory_uri() ?>/fancybox/dist/jquery.fancybox.min.js"></script>
		<link href="<?php echo get_template_directory_uri() ?>/fancybox/dist/jquery.fancybox.min.css" rel="stylesheet"></script>
		<script>
			jQuery(function(){
				jQuery('.points_hover').hover(function(){
					var id = jQuery(this).attr('id');
					jQuery("#points_hover_div_"+id).show();
				},function(){
					var id = jQuery(this).attr('id');
					jQuery("#points_hover_div_"+id).hide();
				});

				jQuery(".investment-row").click(function(event){
					var invID = jQuery(this).data('invid');
					jQuery.fancybox.open({
						src  : '<?php echo admin_url("admin-ajax.php")."?action=getInvDetails&inv_id=" ?>'+invID,
						type : 'ajax',
						opts : {
							touch: false
						}
					});
					//jQuery(this).toggleClass('active-row');
					//jQuery("#investment-details-"+invID).toggle();
					//jQuery("#more_investment_details_"+invID).click();
					//event.preventDefault();
				});

				jQuery(".updateUserWhiteLabel").click(function(event){
					event.stopPropagation();

					var uid = jQuery(this).data('uid');
					jQuery.fancybox.open({
						src  : '<?php echo admin_url("admin-ajax.php")."?action=changeUserWhiteLabelHTML&uid=" ?>'+uid,
						type : 'ajax',
						opts : {
							touch: false
						}
					});
				});

				// change user white label
				jQuery('body').on('click','#change_whitelabel_sbmt_btn',function(e){
					//e.preventDefault();
					var form = jQuery('#new_whitelabel_form')[0];
					var postdata = new FormData(form);
					var thisEl = jQuery(this);
					var parentel = thisEl.parent();
					thisEl.hide();
					jQuery.ajax({
							async: false,
							url : '<?php echo admin_url("admin-ajax.php") ?>',
							type: "POST",
							data : postdata,
							processData: false,
							contentType: false,
							enctype: 'multipart/form-data',
							success:function(response) {

								var response = JSON.parse(response);
								if(response.status === "true") {
									thisEl.show();
									var message = jQuery("<span />", {
										 "class": "flash_message flash_error",
										 text: "Whitelabel updated successfully."
									  }).fadeIn("fast");
									parentel.append(message);
									message.delay(2000).fadeOut("normal", function() {
									 jQuery(this).remove();
									 location.reload();
								  });
								}else{
									thisEl.show();
								}
							},
							error: function(jqXHR, textStatus, errorThrown){
								console.log(errorThrown);
								thisEl.show();
								var message = jQuery("<span />", {
										 "class": "flash_message flash_error",
										 text: "Something went wrong!"
									  }).fadeIn("fast");
									parentel.append(message);
									message.delay(2000).fadeOut("normal", function() {
									 jQuery(this).remove();
								  });
							}
						});

					});

				jQuery(".user-row").click(function(){
					var uID = jQuery(this).data('uid');
					jQuery(this).toggleClass('active-row');
					jQuery("#user-details-"+uID).toggle();
				});
				jQuery(".investment-notes-submit").click(function(){
					var invID = jQuery(this).data('investmentid');
					var n = jQuery("#notes_"+invID).val();
					var privacy = jQuery("#investment_notes_privacy_"+invID).val();
					jQuery.post({
					  url: "<?php echo admin_url('admin-ajax.php'); ?>",
					  data: {
						action: "add_investment_note",
						invest_id: invID,
						privacy: privacy,
						note: n
					  },
					  success: function(response){
						console.log(response);
						var res = JSON.parse(response);
						if(res.status === "true") {
							jQuery("#notes_wrapper_"+invID).append(res.html);
							jQuery("#notes_"+invID).val('');
						}
					  }
					});
				});

				jQuery(".user-notes-submit").click(function(){
					var userID = jQuery(this).data('userid');
					var n = jQuery("#user_notes_"+userID).val();
					var permission = jQuery("#user_notes_privacy_"+userID).val();
					jQuery.post({
					  url: "<?php echo admin_url('admin-ajax.php'); ?>",
					  data: {
						action: "add_user_note",
						user_id: userID,
						privacy: permission,
						note: n
					  },
					  success: function(response){
						console.log(response);
						var res = JSON.parse(response);
						if(res.status === "true") {
							jQuery("#user_notes_wrapper_"+userID).append(res.html);
							jQuery("#user_notes_"+userID).val('');
						}
					  }
					});
				});

				//datatable search
				<?php if(!isset($_GET['tab']) || $_GET['tab'] == 'isa-transactions' || $_GET['tab'] == '') { ?>
				/*var isaTable = jQuery("#table-isa-transactions").DataTable({
					"paging":   false,
					"ordering": false,
					"info":     false,

				});
				function createFilter(table, columns, elementID) {
				  var input = jQuery(elementID).on("keyup", function() {
					table.draw();
				  });

				  jQuery.fn.dataTable.ext.search.push(function(
					settings,
					searchData,
					index,
					rowData,
					counter
				  ) {
					var val = input.val().toLowerCase();

					for (var i = 0, ien = columns.length; i < ien; i++) {
					  if (searchData[columns[i]].toLowerCase().indexOf(val) !== -1) {
						return true;
					  }
					}

					return false;
				  });

				  return input;
				}


				var filter2 = createFilter(isaTable, [0],'#filter_uref');
				var filter1 = createFilter(isaTable, [2, 3, 4],'#filter_name_email');
				var filter2 = createFilter(isaTable, [6],'#filter_product');*/
				<?php } ?>


				jQuery(".aml_check").on("click",function(e){
					//e.preventDefault();
					e.stopPropagation();
				});

				jQuery(".aml_check").on("change",function(e){
					var checkparent = jQuery(this).parent();
					var thisel = jQuery(this);
					var uid = thisel.data("uid");
					var inv = thisel.data("inv");
					var v = (thisel.is(":checked") ? "yes" : "no");
					if(!confirm("Are you sure to AML check?")){
						if(v=="yes"){
							thisel.attr("checked",false);
						}else{
							thisel.attr("checked",true);
						}
						return false;
					}

					jQuery.post({
						  url: "<?php echo admin_url('admin-ajax.php'); ?>",
						  data: {
							action: "user_aml_check",
							uid: uid,
							value: v,
							invid: inv
						  },
						  success: function(response){
							console.log(response);
							var res = JSON.parse(response);
							if(res.status === "true") {
								if(v == "yes") {
									jQuery(".aml_check_u_"+uid).prop("checked",true);
								}else{
									jQuery(".aml_check_u_"+uid).prop("checked",false);
								}
								var message = jQuery("<span />", {
										 "class": "flash_message",
										 text: "changes saved!"
									  }).fadeIn("fast");
									checkparent.append(message);
									message.delay(2000).fadeOut("normal", function() {
									 jQuery(this).remove();
								  });
							}
						  }
						});
				});

				// fancybox popup

			});
		</script>
	<?php }

add_action( 'wp_ajax_nopriv_remove_investment_note', 'remove_investment_note' );
add_action( 'wp_ajax_remove_investment_note', 'remove_investment_note' );
function remove_investment_note() {
	switch_to_blog(1);

	$oldNotes = get_post_meta($_POST['invest_id'],'themeum_notes',true);

	if(!empty($oldNotes)):
		unset($oldNotes[$_POST['note_id']]);
		update_post_meta($_POST['invest_id'],'themeum_notes',$oldNotes);
	endif;
	restore_current_blog();
	exit;
}

add_action( 'wp_ajax_nopriv_add_investment_note', 'add_investment_note' );
add_action( 'wp_ajax_add_investment_note', 'add_investment_note' );
function add_investment_note() {
	$ret = array('status'=>'false','html' => '');
	if((int) $_POST['invest_id'] > 0) {
		switch_to_blog(1);
		$oldNotes = get_post_meta($_POST['invest_id'],'themeum_notes',true);
		if($oldNotes == '') {
			$oldNotes = [];
			$now = time();
			$n['notes'] = esc_attr($_POST['note']);
			$n['privacy'] = esc_attr($_POST['privacy']);
			//array_push($oldNotes,$n);
			$oldNotes[$now] = $n; //esc_attr($_POST['note']);
		}else{
			$now = time();
			$n['notes'] = esc_attr($_POST['note']);
			$n['privacy'] = esc_attr($_POST['privacy']);
			$oldNotes[$now] = $n;
		}
		update_post_meta($_POST['invest_id'],'themeum_notes',$oldNotes);
		$ret['status'] = 'true';
		$ret['html'] = '<li>'.esc_attr($_POST['note']).'</li>';
		restore_current_blog();
		echo json_encode($ret);die();
	}
	echo json_encode($ret);die();
}

function getNotes($id, $type = 'user',$blogid=0) { // $type can also be investment
	$filteredNotes = [];
	if($type=='user') {
		$notes = get_user_meta($id,'user_notes',true);
	}else{
		switch_to_blog(1);
		$notes = get_post_meta($id,'themeum_notes',true);
		restore_current_blog();
	}

		if($blogid!=0):
			switch_to_blog($blogid);
		endif;
	if(count($notes) > 0) {
		$currentUser = wp_get_current_user();
		$role = 'all';
		if(in_array('sitemanager',$currentUser->roles)) {
			$role = 'admin_whitelabel';
		}else if(in_array('read_only_manager',$currentUser->roles)) {
			$role = 'admin_whitelabel_referrer';
		}

		//$key = array_search($role, array_column($notes, 'label'));
		if(is_super_admin()) {
			return $notes;
			exit;
		}
		$filteredNotes = [];
		foreach($notes as $index => $note) {
			if($note['privacy'] == $role) {
				$filteredNotes[$index] = $note;
			}
		}

	}
	return $filteredNotes;
}

add_action( 'wp_ajax_nopriv_add_user_note', 'add_user_note' );
add_action( 'wp_ajax_add_user_note', 'add_user_note' );
function add_user_note() {
	$ret = array('status'=>'false','html' => '');
	if((int) $_POST['user_id'] > 0) {
		$oldNotes = get_user_meta($_POST['user_id'],'user_notes',true);
		if($oldNotes == '') {
			$oldNotes = array();
		}
		$notes['note'] = esc_attr($_POST['note']);
		$notes['privacy'] = $_POST['privacy'];

		$oldNotes[] = $notes;
		update_user_meta($_POST['user_id'],'user_notes',$oldNotes);
		$ret['status'] = 'true';
		$ret['html'] = '<li>'.esc_attr($_POST['note']).'</li>';
		echo json_encode($ret);die();
	}
	echo json_encode($ret);die();
}

function array2csv($fields, array &$array, $type = 'reports')
{
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');

   fputcsv($df, $fields);
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}

function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}
function myaExportCSV($data) {
	$fields = array('Username', 'Name', 'Address', 'Phone', 'Mobile', 'Email', 'Bank Details', 'Referred By', 'Type of Investor', 'Date of Birth', 'Date Registered', 'Last Login', 'NI Number', 'Total Investments (£)');
	download_send_headers("users.csv");
	echo array2csv($fields,$data, 'users');
	die();
}
function trcExportTransactionsCSV($data) {
	$fields = array('First Name', 'Last Name', 'Email', 'Investor Type', 'Product', 'Investment Date', 'Funding Source', 'Investment Type', 'Status', 'Investment Amount', 'White Label Site', 'Referred By', 'Bank Details' ); //'Name of ISA Provider', 'Address of ISA Provider', 'Account/Reference Number of ISA Provider', 'Another Account/Reference Number of ISA Provider', 'Full/Part Transfer'
	download_send_headers("reports.csv");
	echo array2csv($fields,$data);
	die();
}
function trcExportTransactionsCSV_special($data) {
	$fields = array('Transaction Reference Number', 'Investing Product', 'Status', 'Funds Received' , 'First Name(s)', 'Surname', 'Address line 1', 'Address line 2', 'Address line 3', 'Address line 4', 'Postcode', 'DOB', 'NINO', 'Investment Type', 'Investment Amount', 'Pledge Date', 'AML Check', 'Income Type', 'Classification', 'Email Address', 'Contact Number', 'Complete Date', 'Bank Details' );
	$currU = wp_get_current_user();
	if(in_array('product_manager',$currU->roles) || in_array('sitemanager',$currU->roles) || in_array('administrator',$currU->roles) || count($currU->roles) === 0) {
		$fields[] = 'Referred By';
	}
	download_send_headers("fullreports.csv");
	echo array2csv($fields,$data);
	die();
}
function trcExportUsersCSV($data) {
	$fields = array('Username', 'First Name', 'Last Name', 'Type of Investor', 'Email', 'Bank Details', 'Date Registered', 'Source Site', 'Referred By', 'Phone Number','Investment Experience Points','Education and Employment Points','Financial Status Points','Risk appetite/dependency on funds invested Points','Total Points');
	download_send_headers("reports.csv");
	echo array2csv($fields,$data, 'users');
	die();
}

add_action( 'admin_post_nopriv_trc_export_users', 'trc_export_users' );
add_action( 'admin_post_trc_export_users', 'trc_export_users' );

function trc_export_users() {
	if(isManager() && !in_array('read_only_manager',wp_get_current_user()->roles)) {
		$userArgs = array(
		//'blog_id' => get_current_blog_id(),
		'role__not_in' => array('administrator'),
		'number'=>-1,
		'orderby'      => 'registered',
		'order'        => 'DESC',
	);

	$users = get_users($userArgs);
	$data = [];
	if(count($users) > 0) {
		foreach($users as $user):
			$umetas = get_user_meta($user->ID);
			if($umetas['billing_address_1'][0] != '') {
				$add = $umetas['billing_address_1'][0].', '.$umetas['billing_address_2'][0].', '.$umetas['billing_city'][0].', '.$umetas['billing_state'][0].', '.$umetas['billing_country'][0].', '.$umetas['billing_postcode'][0];
			}else{
				$add = '-';
			}
			if($umetas['wc_last_active'][0] != '') {
				$lastActive = date('d/m/y H:i',$umetas['wc_last_active'][0]);
			}else{
				$lastActive = 'Never';
			}
			$totalInvestments = getUserInvestments($user->ID,true);
			$totInvestments = $totalInvestments;

			$data[] = array(
				$user->data->user_login,$user->data->display_name,$add,$umetas['phone_number'][0],$umetas['phone_number'][0],$user->data->user_email,
				preg_replace("/rn|nr|n|r/", " ", $umetas['bank_details'][0]),
				$umetas['referralcode'][0],$umetas['investor_type'][0],$umetas['userdob'][0],date('d/m/y H:i',strtotime($user->data->user_registered)),
				$lastActive,($umetas['ni_number'][0] != '' ? $umetas['ni_number'][0] : '-'),$totInvestments
			);
		endforeach;
	}
	myaExportCSV($data);
	}

}

add_action( 'admin_post_nopriv_exportshares', 'exportshares' );
add_action( 'admin_post_exportshares', 'exportshares' );

function exportshares() {

$args = array(
    'post_type'     => 'shares',
    'post_status'    => 'publish',
    'meta_key'     => 'thm_user_id',
    'meta_value'     => (isset($_GET['user_id']) && $_GET['user_id'] != '') ? $_GET['user_id'] : get_current_user_id(),
    'posts_per_page'    => 10
);
$args['posts_per_page'] = -1;
if(get_current_blog_id() == '1') {
		$managerSiteID = get_user_meta(get_current_user_id(),'site_id',true);
		if($managerSiteID == '') {
			$managerSiteID = 0;
		}
	}else{
		$managerSiteID = get_current_blog_id();
	}

if(in_array('product_manager',wp_get_current_user()->roles)) {
	unset($args['meta_key']);
	unset($args['meta_value']);
	$hasCompany = hasCompany();
	if($hasCompany) {
		$args['meta_query'][] = array(
			'key' => 'thm_businessid',
			'value' => $hasCompany->ID,
			'compare' => '='
		);
	}else{
		$args['meta_query'][] = array(
			'key' => 'thm_businessid',
			'value' => 0,
			'compare' => '='
		);
	}
}

if(in_array('sitemanager',wp_get_current_user()->roles)) {
	unset($args['meta_key']);
	unset($args['meta_value']);
	if(get_current_blog_id() == '1') {
		$managerSiteID = get_user_meta(get_current_user_id(),'site_id',true);
		if($managerSiteID == '') {
			$managerSiteID = 0;
		}
	}else{
		$managerSiteID = get_current_blog_id();
	}
	$args['meta_query'][] = array(
    'posts_per_page' => -1,
		'relation' => 'OR',
		array(
			'key' => 'site_id',
			'value' => $managerSiteID,
			'compare' => '='
		),
		array(
			'key' => 'source_invest_site',
			'value' => $managerSiteID,
			'compare' => '='
		)
	);
}


$investments = get_posts( $args );

$data = array();
            if(!empty($investments)):
              foreach($investments as $investment):
                $info = get_post_meta($investment->ID);
                $data[] = array(get_the_title(get_post_meta($investment->ID,'thm_businessid',true)),$info['thm_user_firstname'][0]." ".$info['thm_user_surname'][0],$info['thm_user_email'][0],$info['investment_amount'][0] != '' ? '£'.$info['investment_amount'][0] : 0,$info['thm_share_type'][0],$info['thm_noshares'][0],$info['thm_purchaseprice'][0] != '' ? $info['thm_purchaseprice'][0].'p' : 0);
              endforeach;
          endif;

$fields = array("Product","Name","Email","Ownership Total","Holdings Type","Unit Held","Unit Price");
	$currU = wp_get_current_user();

	download_send_headers("sharereports.csv");
	echo array2csv($fields,$data);
	die();

}
add_action( 'admin_post_nopriv_exportreports', 'exportreports' );
add_action( 'admin_post_exportreports', 'exportreports' );

function exportreports() {
	global $wpdb;
	$cSite = get_current_blog_id();
	if($cSite != 1) {
		return;
	}
	$params = array();
	if(isset($_POST['status']) && $_POST['status'] != '') {
		$params['status'] = $_POST['status'];
	}
	if(isset($_POST['product']) && $_POST['product'] != '') {
		$params['product'] = $_POST['product'];
	}
	if(isset($_POST['funding_source']) && $_POST['funding_source'] != '') {
		$params['funding_source'] = $_POST['funding_source'];
	}
	if(isset($_POST['investment_type']) && $_POST['investment_type'] != '') {
		$params['investment_type'] = $_POST['investment_type'];
	}
	if(isset($_POST['filter_site']) && $_POST['filter_site'] != '') {
		$params['filter_site'] = $_POST['filter_site'];
	}

	if(isset($_POST['intro_referralcode']) && $_POST['intro_referralcode'] != '') {
		$refcode = $_POST['intro_referralcode'];
		$ref_users = getReferralUserInvestments($refcode);
		$params['ref_users'] = $ref_users;
	}

	if($_POST['tab'] == 'all') {
		$investments = getInvestments('all',$params);
	}else if(!isset($_POST['tab']) || $_POST['tab'] == 'isa-transactions' || $_POST['tab'] == '') {
		$investments = getInvestments('isa',$params);
	}else if(isset($_POST['tab']) && $_POST['tab'] == 'cash-transactions') {
		$investments = getInvestments('cash', $params);
	}else if(isset($_POST['tab']) && $_POST['tab'] == 'cancelled-transactions') {
		$investments = getInvestments('cancelled', $params);
	}

	$data = [];
	if($investments) {
		$transferTypeLabels = array(
			'isa_transfer' => 'ISA Transfer',
			'new_isa' => 'New ISA',
			'cash' => 'Cash',
			'stocks' => 'Stocks and Shares',
			'ifisa' => 'IFISA'
		);
		$currU = wp_get_current_user();
		foreach($investments as $inv) :
			$invInfo = getInvestmentInfo($inv->ID);
			$uMetas = get_user_meta($invInfo['investor_user_id']);
			$uData = get_userdata($invInfo['investor_user_id']);
			$invSiteID = get_post_meta($inv->ID,'site_id',true);
			$blog_details = get_blog_details( $invSiteID );
			$siteName = $blog_details->blogname;
			$notes = get_post_meta($inv->ID,'themeum_notes',true);

			$status = esc_html(get_post_meta( $inv->ID , 'themeum_status_all' , true ));
			if($status == 'cancelled') {
				$st = "Funding Cancelled";
			}else if ($status == 'investment_closed'){
				$st = "Investment Closed";
			}else if ($status == 'received'){
				$st = "Funding Received";
			}else if ($status == 'complete') {
				$st = "Investment Complete";
			}else if ($status == 'pending'){
				$st = "Awaiting Funding";
			}else if ($status == 'transfer_requested'){
				$st = "Transfer Requested";
			}else if ($status == 'pack_out') {
				$st = "Pack Out";
			}else{
				$st = "Awaiting Funding";
			}

			$nameIsaProvider = $addressIsaProvider = $accountIsaProvider = $fullpartIsaProvider = '-';
			if($invInfo['source_of_wealth'] == 'isa') {
				$nameIsaProvider = $invInfo['name_isa_provider'];
				$addressIsaProvider = $invInfo['address1_isa_provider'].", ".$invInfo['address1_isa_provider'].", ".$invInfo['city_isa_provider'].", ".$invInfo['state_isa_provider'].", ".$invInfo['zip_isa_provider'];
				$accountIsaProvider = $invInfo['account_isa_provider'];
				$fullpartIsaProvider = $invInfo['full_part_isa_transfer'];
				if($invInfo['another_account'] == 'yes') {
					$anotherAccountIsaProvider = $invInfo['another_account_isa_provider'];
				}else{
					$anotherAccountIsaProvider = ' - ';
				}
			}

			if($invInfo['received_date'] != '') {
				$frdt = new DateTime($invInfo['received_date']);
				$frdate = $frdt->format("d/m/Y h:i:s a");
			}else{
				$frdate = '-';
			}

			$compDate = get_post_meta($inv->ID,'themeum_complete_date',true);
			$arr = array(
				'TRC2019'.$uData->ID,
				get_the_title(esc_html($invInfo['investment_project_id'])),
				$st,
				$frdate,
				$uMetas['billing_first_name'][0],
				$uMetas['billing_last_name'][0],
				$uMetas['billing_address_1'][0],
				$uMetas['billing_address_2'][0],
				$uMetas['billing_city'][0].' '.$uMetas['billing_state'][0],
				$uMetas['billing_country'][0],
				$uMetas['billing_postcode'][0],
				$uMetas['userdob'][0],
				$uMetas['ni_number'][0],
				$invInfo['investing_into'],
				$invInfo['investment_amount'],
				date("d/m/Y",strtotime($invInfo['investment_date'])),
				(isset($uMetas['aml_doc_check']) && $uMetas['aml_doc_check'][0] == 'yes' ? 'pass' : (isset($uMetas['aml_doc_check']) && $uMetas['aml_doc_check'][0] == 'no' ? 'fail' : 'not checked')),
				$invInfo['source_of_wealth'],
				$uMetas['investor_type'][0],
				$uData->data->user_email,
				$uMetas['phone_number'][0],
				($compDate != '' ? date("d/m/Y",strtotime($compDate)) : ''),
				preg_replace("/rn|nr|n|r/", " ", $uMetas['bank_details'][0]),
				$uMetas['referralcode'][0]
			);
			if(!in_array('product_manager',$currU->roles) && !in_array('sitemanager',$currU->roles) && !in_array('administrator',$currU->roles) && count($currU->roles) != 0) {
				array_pop($arr);
			}
			$data[] = $arr;
		endforeach;
		trcExportTransactionsCSV_special($data);
		/*
		$nameIsaProvider,
				$addressIsaProvider,
				$accountIsaProvider,
				$anotherAccountIsaProvider,
				$fullpartIsaProvider
		*/
	}

	if($_POST['tab'] == 'users') {
		$sq = "SELECT * FROM ".$wpdb->base_prefix."users ";
		$sq .= "where 1 ";
		if(isset($_POST['filter_name']) && $_POST['filter_name'] != '') {
			$sq .= "AND display_name like '%".$_POST['filter_name']."%' ";
		}
		if(isset($_POST['filter_username']) && $_POST['filter_username'] != '') {
			$sq .= "AND user_login like '%".$_POST['filter_username']."%' ";
		}
		if(isset($_POST['filter_email']) && $_POST['filter_email'] != '') {
			$sq .= "AND user_email like '%".$_POST['filter_email']."%' ";
		}
		/*<td><?php

								echo "<span class='points_hover' id='".$uData->ID."'>".$points['total']."</span>";
								?>
								<div class='points_hover_div' style="display:none" id='points_hover_div_<?php echo $uData->ID; ?>'>
									<?php foreach($points as $k => $v) { ?>
									<span><?php echo $k ?></span>: <span><?php echo $v ?></span><br/>
									<?php } ?>
								<div>
							</td>*/
		$sq .= "ORDER BY user_registered DESC";
		$allUsers = $wpdb->get_results($sq);
		if(count($allUsers) > 0) {
			foreach($allUsers as $user):
				$uData = get_userdata($user->ID);
				$uMetas = get_user_meta($user->ID);
				$uSiteID = $uMetas['primary_blog'][0];
				$blog_details = get_blog_details( $uSiteID );
				$siteName = $blog_details->blogname;
				$points = getQuestionnairePoints($uData->ID,true);
				$data[] = array(
					$uData->data->user_login,$uMetas['billing_first_name'][0],$uMetas['billing_last_name'][0],$uMetas['investor_type'][0],$uData->data->user_email,
					preg_replace("/rn|nr|n|r/", " ", $uMetas['bank_details'][0]),
					date('d/m/y H:i',strtotime($uData->data->user_registered)),$siteName,$uMetas['referralcode'][0],($uMetas['phone_number'][0] != '' ? $uMetas['phone_number'][0] : '-' ),
					$points['Investment Experience'],$points['Education and Employment'],$points['Financial Status'],$points['Risk appetite/dependency on funds invested'],$points['total']
				);
			endforeach;
		}
		trcExportUsersCSV($data);
	}

}

add_action( 'admin_post_nopriv_exportreports_special', 'exportreports_special' );
add_action( 'admin_post_exportreports_special', 'exportreports_special' );

function exportreports_special() {
	global $wpdb;
	$cSite = get_current_blog_id();
	/*if($cSite != 1) {
		return;
	}*/

	$params = array();
	if(isset($_POST['status']) && $_POST['status'] != '') {
		$params['status'] = $_POST['status'];
	}
	if(isset($_POST['product']) && $_POST['product'] != '') {
		$params['product'] = $_POST['product'];
	}
	if(isset($_POST['funding_source']) && $_POST['funding_source'] != '') {
		$params['funding_source'] = $_POST['funding_source'];
	}
	if(isset($_POST['investment_type']) && $_POST['investment_type'] != '') {
		$params['investment_type'] = $_POST['investment_type'];
	}
	if(isset($_POST['filter_site']) && $_POST['filter_site'] != '') {
		$params['filter_site'] = $_POST['filter_site'];
	}

	if(isset($_POST['name_email']) && $_POST['name_email'] != '') {
		$params['filter_name_email'] = $_POST['name_email'];
	}

	if(isset($_POST['uref']) && $_POST['uref'] != '') {
		$refParts = explode("TRC2019",$_POST['uref']);
		if(isset($refParts[1])) {
			$params['user_id'] = $refParts[1];
		}
	}

	$investments = getInvestments('all',$params);
	$data = [];
	if($investments) {
		$transferTypeLabels = array(
			'isa_transfer' => 'ISA Transfer',
			'new_isa' => 'New ISA',
			'cash' => 'Cash',
			'stocks' => 'Stocks and Shares',
			'ifisa' => 'IFISA'
		);
		$currU = wp_get_current_user();

		foreach($investments as $inv) :
			$invInfo = getInvestmentInfo($inv->ID);
			$uMetas = get_user_meta($invInfo['investor_user_id']);
			$uData = get_userdata($invInfo['investor_user_id']);
			$invSiteID = get_post_meta($inv->ID,'site_id',true);
			$blog_details = get_blog_details( $invSiteID );
			$siteName = $blog_details->blogname;
			$notes = get_post_meta($inv->ID,'themeum_notes',true);

			$status = esc_html(get_post_meta( $inv->ID , 'themeum_status_all' , true ));
			if($status == 'cancelled') {
				$st = "Funding Cancelled";
			}else if ($status == 'investment_closed'){
				$st = "Investment Closed";
			}else if ($status == 'received'){
				$st = "Funding Received";
			}else if ($status == 'complete') {
				$st = "Investment Complete";
			}else if ($status == 'pending'){
				$st = "Awaiting Funding";
			}else if ($status == 'transfer_requested'){
				$st = "Transfer Requested";
			}else if ($status == 'pack_out') {
				$st = "Pack Out";
			}else{
				$st = "Awaiting Funding";
			}

			$nameIsaProvider = $addressIsaProvider = $accountIsaProvider = $fullpartIsaProvider = '-';
			if($invInfo['source_of_wealth'] == 'isa') {
				$nameIsaProvider = $invInfo['name_isa_provider'];
				$addressIsaProvider = $invInfo['address1_isa_provider'].", ".$invInfo['address1_isa_provider'].", ".$invInfo['city_isa_provider'].", ".$invInfo['state_isa_provider'].", ".$invInfo['zip_isa_provider'];
				$accountIsaProvider = $invInfo['account_isa_provider'];
				$fullpartIsaProvider = $invInfo['full_part_isa_transfer'];
				if($invInfo['another_account'] == 'yes') {
					$anotherAccountIsaProvider = $invInfo['another_account_isa_provider'];
				}else{
					$anotherAccountIsaProvider = ' - ';
				}
			}

			$compDate = get_post_meta($inv->ID,'themeum_complete_date',true);

			$title = ($invInfo['project_name'] != '')
						? $invInfo['project_name']
						: get_the_title( $invInfo['investment_project_id'] );

			if(trim($title) == '') {
				$investTitleParts = explode(":",$inv->post_title);
				if(count($investTitleParts) > 1) {
					$title = $investTitleParts[1];
				}
			}

			if($invInfo['received_date'] != '') {
				$frdt = new DateTime($invInfo['received_date']);
				$frdate = $frdt->format("d/m/Y h:i:s a");
			}else{
				$frdate = '-';
			}

			$arr = array(
				'TRC2019'.$uData->ID,
				str_replace("–"," ",$title), //get_the_title($invInfo['investment_project_id'])
				$st,
				$frdate,
				$uMetas['billing_first_name'][0],
				$uMetas['billing_last_name'][0],
				$uMetas['billing_address_1'][0],
				$uMetas['billing_address_2'][0],
				$uMetas['billing_city'][0].' '.$uMetas['billing_state'][0],
				$uMetas['billing_country'][0],
				$uMetas['billing_postcode'][0],
				$uMetas['userdob'][0],
				$uMetas['ni_number'][0],
				$invInfo['investing_into'],
				$invInfo['investment_amount'],
				date("d/m/Y",strtotime($invInfo['investment_date'])),
				(isset($uMetas['aml_doc_check']) && $uMetas['aml_doc_check'][0] == 'yes' ? 'pass' : (isset($uMetas['aml_doc_check']) && $uMetas['aml_doc_check'][0] == 'no' ? 'fail' : 'not checked')),
				$invInfo['source_of_wealth'],
				$uMetas['investor_type'][0],
				$uData->data->user_email,
				$uMetas['phone_number'][0],
				($compDate != '' ? date("d/m/Y",strtotime($compDate)) : ''),
				preg_replace("/rn|nr|n|r/", " ", $uMetas['bank_details'][0]),
				$uMetas['referralcode'][0]
			);
			if(!in_array('product_manager',$currU->roles) && !in_array('sitemanager',$currU->roles) && !in_array('administrator',$currU->roles) && count($currU->roles) != 0) {
				array_pop($arr);
			}
			$data[] = $arr;
		endforeach;
		trcExportTransactionsCSV_special($data);
		/*
		$nameIsaProvider,
				$addressIsaProvider,
				$accountIsaProvider,
				$anotherAccountIsaProvider,
				$fullpartIsaProvider
		*/
	}

}

if(isset($_GET['page_type']) && $_GET['page_type'] == 'reports') {
	add_action('wp_footer','add_reports_page_js_css');
	function add_reports_page_js_css() {
		?>
		<style>
.front-reports-page .filter_box select {
		display: inline;
		width: auto;
}
.front-reports-page table.content-table > tbody > tr {
	cursor: pointer;
}
.front-reports-page table.content-table > thead > tr th, .front-reports-page table.content-table > tbody > tr td {
	padding: 8px;
}
.front-reports-page table.content-table > tbody > tr:hover {
	background-color: #ccc;
	color: #fff;
}
.front-reports-page table.content-table > tbody tr.details-row {
	cursor: text !important;
	border: none;
	height: 100px;
}
.front-reports-page table.content-table > tbody tr.details-row td {
	border: none;
	word-wrap: break-word;
	vertical-align: top;
}
.front-reports-page table.content-table > tbody tr.details-row td span {
	font-weight: bold;
}
.front-reports-page table.content-table > tbody tr.details-row td span + span {
	font-weight: normal;
}
.front-reports-page table.content-table > tbody tr.details-row:hover {
	background: none;
	color: #000;
}
.front-reports-page table.content-table > tbody tr.details-row td .investment-notes {
	height: 75px;
	overflow-y: auto;
}
.reports-tabs {
	margin-top: 30px;
}
.reports-tabs li {
	display: inline-block;
}
.reports-tabs ul li a {
	background: #C7CED6;
	padding: 10px;
	color: #fff;
	text-decoration: none;
	font-size: 12px;
}
.reports-tabs ul li.active a {
	background: #01D5EB;
}
.filter_box {
	margin-bottom: 20px;
}
.filter_box select {
	vertical-align: top;
}
.filter_box button {
	height: 26px;
vertical-align: top;
margin-top: 1px;
}
#table-isa-transactions_filter, #table-cash-transactions_filter {
	display: none !important;
}
.user_pagination ul li {
	display: inline-block;
	margin-right: 8px;
}
.user_pagination ul li a {
	padding: 5px;
	border: 1px solid #ccc;
	color: #000;
	text-decoration: none;
}
.user_pagination ul li.active a {
	background: #01D5EB;
	color: #fff;
	border: 1px solid #01D5EB;
}
</style>

<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/fancybox/dist/jquery.fancybox.min.js"></script>
		<link href="<?php echo get_template_directory_uri() ?>/fancybox/dist/jquery.fancybox.min.css" rel="stylesheet"></script>
		<script>
		jQuery(function(){
			jQuery("#sbmt-export-users").click(function(){
				jQuery("#trc_export_users_form").submit();
			});

		});
			jQuery(function(){
				jQuery(".investment-row").click(function(){
					var invID = jQuery(this).data('invid');
					//jQuery(this).toggleClass('active-row');
					//jQuery("#investment-details-"+invID).toggle();
					jQuery.fancybox.open({
						src  : '<?php echo admin_url("admin-ajax.php")."?action=getInvDetails&inv_id=" ?>'+invID,
						type : 'ajax',
						opts : {
							touch: false
						}
					});
				});
				jQuery(".user-row").click(function(){
					var uID = jQuery(this).data('uid');
					jQuery(this).toggleClass('active-row');
					jQuery("#user-details-"+uID).toggle();
				});
				jQuery(".investment-notes-submit").click(function(){
					var invID = jQuery(this).data('investmentid');
					var n = jQuery("#notes_"+invID).val();
					var privacy = jQuery("#investment_notes_privacy_"+invID).val();
					jQuery.post({
					  url: "<?php echo admin_url('admin-ajax.php'); ?>",
					  data: {
						action: "add_investment_note",
						invest_id: invID,
						privacy: privacy,
						note: n
					  },
					  success: function(response){
						console.log(response);
						var res = JSON.parse(response);
						if(res.status === "true") {
							jQuery("#notes_wrapper_"+invID).append(res.html);
							jQuery("#notes_"+invID).val('');
						}
					  }
					});
				});

				jQuery(".user-notes-submit").click(function(){
					var userID = jQuery(this).data('userid');
					var n = jQuery("#user_notes_"+userID).val();
					jQuery.post({
					  url: "<?php echo admin_url('admin-ajax.php'); ?>",
					  data: {
						action: "add_user_note",
						user_id: userID,
						note: n
					  },
					  success: function(response){
						console.log(response);
						var res = JSON.parse(response);
						if(res.status === "true") {
							jQuery("#user_notes_wrapper_"+userID).append(res.html);
							jQuery("#user_notes_"+userID).val('');
						}
					  }
					});
				});

				jQuery(".edit_ref_link").click(function(event){
					event.stopPropagation();
					var invID = jQuery(this).data('invid');
					jQuery.fancybox.open({
						src  : '<?php echo admin_url("admin-ajax.php")."?action=updateRefCode&inv_id=" ?>'+invID,
						type : 'ajax',
						opts : {
							touch: false
						}
					});
				});

				// edit amount
				jQuery(".edit_inv_amount").click(function(event){
					event.stopPropagation();
					var invID = jQuery(this).data('invid');
					jQuery.fancybox.open({
						src  : '<?php echo admin_url("admin-ajax.php")."?action=updateInvAmount&inv_id=" ?>'+invID,
						type : 'ajax',
						opts : {
							touch: false
						}
					});
				});

				// edit amount
				jQuery(".edit_inv_type").click(function(event){
					event.stopPropagation();
					var invID = jQuery(this).data('invid');
					jQuery.fancybox.open({
						src  : '<?php echo admin_url("admin-ajax.php")."?action=updateInvType&inv_id=" ?>'+invID,
						type : 'ajax',
						opts : {
							touch: false
						}
					});
				});

				// change user white label
				jQuery('body').on('click','#change_whitelabel_sbmt_btn',function(e){
					//e.preventDefault();
					var form = jQuery('#new_whitelabel_form')[0];
					var postdata = new FormData(form);
					var thisEl = jQuery(this);
					var parentel = thisEl.parent();
					thisEl.hide();
					jQuery.ajax({
							async: false,
							url : paymentAjax.ajaxurl,
							type: "POST",
							data : postdata,
							processData: false,
							contentType: false,
							enctype: 'multipart/form-data',
							success:function(response) {

								var response = JSON.parse(response);
								if(response.status === "true") {
									thisEl.show();
									var message = jQuery("<span />", {
										 "class": "flash_message flash_error",
										 text: "Whitelabel updated successfully."
									  }).fadeIn("fast");
									parentel.append(message);
									message.delay(2000).fadeOut("normal", function() {
									 jQuery(this).remove();
								  });
								}else{
									thisEl.show();
								}
							},
							error: function(jqXHR, textStatus, errorThrown){
								console.log(errorThrown);
								thisEl.show();
								var message = jQuery("<span />", {
										 "class": "flash_message flash_error",
										 text: "Something went wrong!"
									  }).fadeIn("fast");
									parentel.append(message);
									message.delay(2000).fadeOut("normal", function() {
									 jQuery(this).remove();
								  });
							}
						});

					});
				});
			//});
		</script>
		<?php
	}
}

function getWpNeoDashboardNav() {
	$get_id = '';
    if( isset($_GET['page_type']) ){ $get_id = $_GET['page_type']; }
        $pagelink = '/dashboard'; //get_permalink( get_the_ID() );

        $dashboard_menus = apply_filters('wpneo_crowdfunding_frontend_dashboard_menus', array(
            'dashboard' =>
                array(
                    'tab'             => 'dashboard',
                    'tab_name'        => __('Dashboard','wp-crowdfunding'),
                    'load_form_file'  => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/dashboard.php'
                ),
				'profile' =>
                array(
                    'tab'             => 'account',
                    'tab_name'        => __('My Profile','wp-crowdfunding'),
                    'load_form_file'  => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/profile.php'
                ),
            'password' =>
                array(
                    'tab'            => 'account',
                    'tab_name'       => __('Security','wp-crowdfunding'),
                    'load_form_file' => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/password.php'
                ),
			'pledges' =>
                array(
                    'tab'             => 'pledges',
                    'tab_name'        => isManager() ? __('All Investments','wp-crowdfunding') : __('My Investments','wp-crowdfunding'),
                    'load_form_file'  => WPNEO_CROWDFUNDING_DIR_PATH.'includes/woocommerce/dashboard/pledges.php'
                )
        ));

				$html .= '<div class="wpneo-head wpneo-shadow">';
            $html .= '<div class="wpneo-links clearfix">';

                $dashboard = $account = $campaign = $extra = '';
                foreach ($dashboard_menus as $menu_name => $menu_value){

                    if ( empty($get_id) && $menu_name == 'dashboard'){ $active = 'active';
                    } else { $active = ($get_id == $menu_name) ? 'active' : ''; }

                    $pagelink = add_query_arg( 'page_type', $menu_name , $pagelink );

                    if( $menu_value['tab'] == 'dashboard' ){
                        $dashboard .= '<div class="wpneo-links-list '.$active.'"><a href="'.$pagelink.'">'.$menu_value['tab_name'].'</a></div>';
                    }elseif( $menu_value['tab'] == 'account' ){
                        $account .= '<div class="wpneo-links-lists '.$active.'"><a href="'.$pagelink.'">'.$menu_value['tab_name'].'</a></div>';
                    }elseif( $menu_value['tab'] == 'campaign' ){
                        $campaign .= '<div class="wpneo-links-lists '.$active.'"><a href="'.$pagelink.'">'.$menu_value['tab_name'].'</a></div>';
                    }elseif( $menu_value['tab'] == 'pledges' ){
                        $campaign .= '<div class="wpneo-links-lists '.$active.'"><a href="'.$pagelink.'">'.$menu_value['tab_name'].'</a></div>';
                    }else{
                        $extra .= '<div class="wpneo-links-list '.$active.'"><a href="'.$pagelink.'">'.$menu_value['tab_name'].'</a></div>';
                    }
                }

                $html .= $dashboard;
                /*$html .= '<div class="wpneo-links-list wp-crowd-parent"><a href="#">'.__("My Account","wp-crowdfunding").'<span class="wpcrowd-arrow-down"></span></a>';
                    $html .= '<div class="wp-crowd-submenu wpneo-shadow">';
                        $html .= $account;
                        //$html .= '<div class="wpneo-links-lists"><a href="'.wp_logout_url( home_url() ).'">'.__('Logout','wp-crowdfunding').'</a></div>';
                    $html .= '</div>';
                $html .= '</div>';
				if(!in_array('product_manager',wp_get_current_user()->roles)){
                $html .= '<div class="wpneo-links-list wp-crowd-parent"><a href="#">'.__("Investments","wp-crowdfunding").'<span class="wpcrowd-arrow-down"></span></a>';
                    $html .= '<div class="wp-crowd-submenu wpneo-shadow">';
                        $html .= $campaign;
                    $html .= '</div>';
                $html .= '</div>';
				}
                $html .= $extra;
				$html .= '<div class="wpneo-links-list"><a href="/pm-dashboard-investments/">'.__('Product Investments ','wp-crowdfunding').'</a></div>';
                //$html .= '<div class="wpneo-links-list"><a href="/pm-dashboard/">'.__('Users Approval','wp-crowdfunding').'</a></div>';
				$html .= '<div class="wpneo-links-list"><a href="'.wp_logout_url( home_url() ).'">'.__('Logout','wp-crowdfunding').'</a></div>';*/

				$html .= '<div class="wpneo-links-list wp-crowd-parent"><a href="#">'.__("My Account","wp-crowdfunding").'<span class="wpcrowd-arrow-down"></span></a>';
                    $html .= '<div class="wp-crowd-submenu wpneo-shadow">';
                        $html .= $account;
                    $html .= '</div>';
                $html .= '</div>';
				if(!in_array('product_manager',wp_get_current_user()->roles) && !in_array('custodian',wp_get_current_user()->roles) && !in_array('read_only_manager',wp_get_current_user()->roles) ){
                $html .= '<div class="wpneo-links-list wp-crowd-parent"><a href="#">'.__("Investments","wp-crowdfunding").'<span class="wpcrowd-arrow-down"></span></a>';
                    $html .= '<div class="wp-crowd-submenu wpneo-shadow">';
                        $html .= $campaign;
                    $html .= '</div>';
                $html .= '</div>';
				}
                $html .= $extra;
				//get_current_blog_id() == 1 &&
				if(isManager() && (is_super_admin(wp_get_current_user()->ID))){ //in_array('product_manager',wp_get_current_user()->roles) ||
					$html .= '<div class="wpneo-links-list"><a href="/pm-dashboard-investments/">'.__('Product Investments ','wp-crowdfunding').'</a></div>';
					//$html .= '<div class="wpneo-links-list"><a href="/pm-dashboard/">'.__('Users Approval','wp-crowdfunding').'</a></div>';
				}
                                $html .= '<div class="wpneo-links-list"><a href="'.wp_logout_url( home_url() ).'">'.__('Logout','wp-crowdfunding').'</a></div>';
                if(((in_array('add_products',wp_get_current_user()->roles) || in_array('product_manager',wp_get_current_user()->roles)) && !hasCompany()) || is_super_admin()) {
                $html .= '<div class="wp-crowd-new-campaign"><a class="wp-crowd-btn wp-crowd-btn-primary" href="/add-your-business/">'.__("Add Company","wp-crowdfunding").'</a></div>';
					}else{
						if(in_array('product_manager',wp_get_current_user()->roles)){
							$cm = hasCompany();
							?>
							 <div class="wp-crowd-new-campaign">
							 <a class="wp-crowd-btn wp-crowd-btn-primary" href="/add-your-business/?action=edit&postid=<?php echo $cm->ID ?>">Edit Company</a>
							 </div>
							<?php
						}
					}

					if(in_array('product_manager',wp_get_current_user()->roles)) {
						$html .= '<div class="wp-crowd-new-campaign"><a class="wp-crowd-btn wp-crowd-btn-primary" href="#upload-new-shares">'.__("Upload Shareholders","wp-crowdfunding").'</a></div>';
						$html .= '<div class="wp-crowd-new-campaign company-manager-dashboard-button-white-label-enquiry"><a class="wp-crowd-btn wp-crowd-btn-primary" href="/white-label-enquiry-form/">'.__("i'm looking to raise funds","wp-crowdfunding").'</a></div>';
					}
					if(in_array('customer',wp_get_current_user()->roles)) {
						$html .= '<div class="wp-crowd-new-campaign" style="margin-right: 10px; margin-left: 10px;"><a class="wp-crowd-btn wp-crowd-btn-primary" href="/upload-your-units">'.__("Upload Units","wp-crowdfunding").'</a></div>';
					}

            $html .= '</div>';
        $html .= '</div>';

		return $html;
}

if(isManager()) {
	add_action( 'wp_ajax_nopriv_approve_to_invest', 'approve_to_invest' );
	add_action( 'wp_ajax_approve_to_invest', 'approve_to_invest' );
	function approve_to_invest() {
		$ret = array('status'=>'false');
		$uid = $_POST['uid'];
		if( (int) $_POST['uid'] > 0) {
			$user = get_userdata($uid);
			$uMetas = get_user_meta($user->ID);
			$uSiteID = $uMetas['primary_blog'][0];
			update_user_meta($uid,'canDoInvestment','yes');
			$ret['status'] = 'true';
			echo json_encode($ret);die();
		}
		echo json_encode($ret);die();
	}

	add_action( 'wp_ajax_nopriv_reject_to_invest', 'reject_to_invest' );
	add_action( 'wp_ajax_reject_to_invest', 'reject_to_invest' );
	function reject_to_invest() {
		$ret = array('status'=>'false');
		$uid = $_POST['uid'];
		if( (int) $_POST['uid'] > 0) {
			$user = get_userdata($uid);
			$uMetas = get_user_meta($user->ID);
			$uSiteID = $uMetas['primary_blog'][0];
			update_user_meta($uid,'canDoInvestment','no');
			$ret['status'] = 'true';
			echo json_encode($ret);die();
		}
		echo json_encode($ret);die();
	}
	// new shortcode for product manager only
	function pm_all_users() {
				global $wpdb;
				$limit = 50;
				$result = $wpdb->get_results(
					"SELECT SQL_CALC_FOUND_ROWS * FROM ".$wpdb->base_prefix."users WHERE 1 LIMIT $limit;"
				);
				$total_count = $wpdb->get_var(
					"SELECT FOUND_ROWS();"
				);
				$totalPages = ceil($total_count/$limit);
				$cpage = $pn = isset($_GET['cpage']) ? (int)$_GET['cpage'] : 1;
				$offset = ($cpage-1) * $limit;
				$k = (($cpage+4>$totalPages)?$totalPages-4:(($cpage-4<1)?5:$cpage));
				$sq = "SELECT * FROM ".$wpdb->base_prefix."users ";
				$sq .= "where 1 ";
				if(isset($_GET['filter_name']) && $_GET['filter_name'] != '') {
					$sq .= "AND display_name like '%".$_GET['filter_name']."%' ";
				}
				if(isset($_GET['filter_username']) && $_GET['filter_username'] != '') {
					$sq .= "AND user_login like '%".$_GET['filter_username']."%' ";
				}
				if(isset($_GET['filter_email']) && $_GET['filter_email'] != '') {
					$sq .= "AND user_email like '%".$_GET['filter_email']."%' ";
				}
				$sq .= "ORDER BY user_registered DESC limit $offset,$limit";
				$allUsers = $wpdb->get_results($sq);
				ob_start();

				echo getWpNeoDashboardNav();
			?>
			<section class="reports_content content-users" id="content-users">
				<h2>Users</h2>
				<div class="filter_box">
					<form>
						<input type="hidden" name="page" value="products_reports" />
						<input type="hidden" name="tab" value="<?php echo (!isset($_GET['tab']) || $_GET['tab'] == '')  ? 'isa-transactions' : $_GET['tab'] ?>" />
						<span><input type="text" name="filter_name" id="filter_name" placeholder="Name" value="<?php echo isset($_GET['filter_name']) ? $_GET['filter_name'] : ''  ?>" /></span>
						<span><input type="text" name="filter_username" id="filter_username" placeholder="Username" value="<?php echo isset($_GET['filter_username']) ? $_GET['filter_username'] : ''  ?>" /></span>
						<span><input type="text" name="filter_email" id="filter_email" placeholder="email" value="<?php echo isset($_GET['filter_email']) ? $_GET['filter_email'] : ''  ?>" /></span>
						<span><button type="submit" name="filter_button" >Filter</button></span>
					</form>
				</div>
				<?php if(!isset($_GET['filter_button'])) { ?>
				<div class="user_pagination pagination">
					<ul>
						<?php $pagLink = "";
						if($pn>=2){
							echo "<li><a href='/pm-dashboard/?cpage=1'> << </a></li>";
							echo "<li><a href='/pm-dashboard/?cpage=".($pn-1)."'> < </a></li>";
						}
						for ($i=-4; $i<=4; $i++) {
						  if($k+$i==$pn)
							$pagLink .= "<li class='active'><a href='/pm-dashboard/?cpage=".($k+$i)."'>".($k+$i)."</a></li>";
						  else
							$pagLink .= "<li><a href='/pm-dashboard/?cpage=".($k+$i)."'>".($k+$i)."</a></li>";
						};
						echo $pagLink;
						if($pn<$totalPages){
							echo "<li><a href='/pm-dashboard/?cpage=".($pn+1)."'> > </a></li>";
							echo "<li><a href='/pm-dashboard/?cpage=".$totalPages."'> >> </a></li>";
						}     ?>
					</ul>
				</div>
				<?php } ?>
				<table border='1' cellpadding="5" width="100%" cellspacing="0" id="table-users" class="content-table">
					<thead>
						<tr><th>Username</th><th>Date Registered</th><th>Source Site</th><th>Questionnaire Points</th><th>Actions</th></tr>
					</thead>
					<tbody>
						<?php foreach($allUsers as $user) :
							$uMetas = get_user_meta($user->ID);
							$uData = get_userdata($user->ID);
							$uSiteID = $uMetas['primary_blog'][0];
							$blog_details = get_blog_details( $uSiteID );
							$siteName = $blog_details->blogname;
							$notes = get_user_meta($user->ID,'user_notes',true);
						?>
						<tr class="user-row" data-uid="<?php echo $user->ID ?>">
							<td><?php echo $uData->data->user_login ?></td>
							<td><?php echo date("d/m/Y",strtotime($uData->data->user_registered)) ?></td>
							<td><?php echo $siteName ?></td>
							<td><?php
								$points = getQuestionnairePoints($uData->ID,true);
								echo "<span class='points_hover' id='".$uData->ID."'>".$points['total']."</span>";
								?>
								<div class='points_hover_div' style="display:none" id='points_hover_div_<?php echo $uData->ID; ?>'>
									<?php foreach($points as $k => $v) { ?>
									<span><?php echo $k ?></span>: <span><?php echo $v ?></span><br/>
									<?php } ?>
								<div>
							</td>
							<td>
							<?php if($uMetas['canDoInvestment'][0] == 'yes') {
									echo "<span style='color: green;'>Approved</span>";
								}else if($uMetas['canDoInvestment'][0] == 'no') {
									echo "<span style='color: red;'>Rejected</span>";
								}else{
									echo "action needed";
								}
								echo "<br/>";
							?>
							<a href="javascript:void(0);" class="approve_to_invest" data-uid="<?php echo $uData->ID ?>">approve</a> | <a href="javascript:void(0);" class="reject_to_invest" data-uid="<?php echo $uData->ID ?>">reject</a></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</section>
			<style>
				#table-users th, #table-users td {
					padding: 15px;
				}
				.points_hover {
					display: block;
					padding: 10px;
					cursor: pointer;
					color: #00AED1;
				}
				.points_hover_div {
					position: absolute;
					border: 1px solid #ccc;
					background: #fff;
					padding: 10px;
				}
				.user_pagination ul {
					list-style: none;
				}
				.user_pagination ul li {
					display: inline-block;
					margin-right: 10px;
				}
				.user_pagination ul li a {
					padding: 8px;
				}
				.user_pagination ul li.active a {
					background: #ccc;
				}
			</style>
			<script>
			jQuery(function(){
				jQuery('.points_hover').hover(function(){
					var id = jQuery(this).attr('id');
					jQuery("#points_hover_div_"+id).show();
				},function(){
					var id = jQuery(this).attr('id');
					jQuery("#points_hover_div_"+id).hide();
				});

				// approve
				jQuery(".approve_to_invest").click(function(){
					var uid = jQuery(this).data('uid');
					var thisel = jQuery(this);
					jQuery.post({
					  url: '<?php echo admin_url('admin-ajax.php'); ?>',
					  data: {
						action: "approve_to_invest",
						uid: uid
					  },
					  success: function(response){
						console.log(response);
						var res = JSON.parse(response);
						if(res.status === "true") {
							thisel.parent().html('Approved');
						}else{
							var message = $("<span />", {
							 "class": "flash_message",
								 text: "Something went wrong!"
							  }).fadeIn("fast");
							thisel.parent().append(message);
							message.delay(2000).fadeOut("normal", function() {
							 jQuery(this).remove();
						  });
						}
					  }
					});
				});

				// reject
				jQuery(".reject_to_invest").click(function(){
					var uid = jQuery(this).data('uid');
					var thisel = jQuery(this);
					jQuery.post({
					  url: '<?php echo admin_url('admin-ajax.php'); ?>',
					  data: {
						action: "reject_to_invest",
						uid: uid
					  },
					  success: function(response){
						console.log(response);
						var res = JSON.parse(response);
						if(res.status === "true") {
							thisel.parent().html('Rejected');
						}else{
							var message = $("<span />", {
							 "class": "flash_message",
								 text: "Something went wrong!"
							  }).fadeIn("fast");
							thisel.parent().append(message);
							message.delay(2000).fadeOut("normal", function() {
							 jQuery(this).remove();
						  });
						}
					  }
					});
				});
			});
			</script>
<?php
	return ob_get_clean();
	}

	add_action( 'wp_ajax_nopriv_approve_investment', 'approve_investment' );
	add_action( 'wp_ajax_approve_investment', 'approve_investment' );
	function approve_investment() {
		$ret = array('status'=>'false');
		$invid = $_POST['invid'];
		if( (int) $_POST['invid'] > 0) {
			switch_to_blog(1);
			update_post_meta($_POST['invid'],'pm_approval','yes');
			update_post_meta($_POST['invid'],'pm_approval_action','approved');
			$info = getInvestmentInfo($_POST['invid']);
			restore_current_blog();
			$userID = $info['investor_user_id'];
			$projectID = $info['investment_project_id'];
			$amount = $info['investment_amount'];
			$reference = 'TRC2019'.$userID;
			$source = $info['source_of_wealth'];
			$transfertype = $info['transfertype'];
			$investing_into = $info['investing_into'];
			email_user_on_bank_transfer_payment($userID,$projectID,array('amount' => $amount,'ref'=>$reference,'transfertype' => $transfertype, 'source' => $source,'investment_id' => $invid, 'investing_into' => $investing_into));
			$ret['status'] = 'true';
			echo json_encode($ret);die();
		}
		echo json_encode($ret);die();
	}

	add_action( 'wp_ajax_nopriv_reject_investment', 'reject_investment' );
	add_action( 'wp_ajax_reject_investment', 'reject_investment' );
	function reject_investment() {
		$ret = array('status'=>'false');
		$invid = $_POST['invid'];
		if( (int) $_POST['invid'] > 0) {
			switch_to_blog(1);
			update_post_meta($_POST['invid'],'pm_approval','no');
			update_post_meta($_POST['invid'],'pm_approval_action','rejected');
			wp_update_post(array(
				'ID'    =>  $_POST['invid'],
				'post_status'   =>  'cancelled'
			));
			restore_current_blog();
			$ret['status'] = 'true';
			echo json_encode($ret);die();
		}
		echo json_encode($ret);die();
	}

	// investments approval shortcode
	function pm_investments() {
				global $wpdb;
				$assigned = false;
				$currentUser = wp_get_current_user();
				if(in_array('product_manager',$currentUser->roles)){
					$assigned = get_user_meta($currentUser->ID,'assigned_products',true);
					if($assigned == '') {
						$assigned = array(0);
					}
				}

				$args = array(
					'post_type' => 'investment',
					'post_status'    => array('pending','received','publish'),
					'meta_key'          => 'themeum_investment_date',
				  'orderby'           => 'meta_value',
				  'order'             => 'DESC',
					'meta_query' => array(
						array(
							'key' => 'pm_approval',
							'value' => 'no',
							'compare' => '='
						)
					),
					'posts_per_page'    => -1
				);

				if($assigned !== false) {
					$args['meta_query'][] = array(
								'key' => 'themeum_investment_project_id',
								'value' => $assigned,
								'compare' => 'IN'
							);
				}

				$investments = get_posts( $args );
				ob_start();
				echo getWpNeoDashboardNav();

			?>

			<section class="reports_content content-investments" id="content-investments">
				<h2>Under approval investments</h2>

				<table border='1' cellpadding="5" width="100%" cellspacing="0" id="table-approval-transactions" class="content-table">
					<thead>
						<tr><th>Product</th><th>Appropriation Points</th>
							<th>Client Name</th>
						<th>Investment Type</th><th>Status</th><th>Investment Amount</th><th>White Label Site</th><th>Actions</th></tr>
					</thead>
					<tbody>
						<?php if($investments) {
							$transferTypeLabels = array(
								'isa_transfer' => 'ISA Transfer',
								'new_isa' => 'New ISA',
								'cash' => 'Cash',
								'stocks' => 'Stocks and Shares',
								'ifisa' => 'IFISA'
							);
						?>
						<?php foreach($investments as $inv) :
							$invInfo = getInvestmentInfo($inv->ID);
							$uMetas = get_user_meta($invInfo['investor_user_id']);

							$uData = get_userdata($invInfo['investor_user_id']);
							$invSiteID = get_post_meta($inv->ID,'site_id',true);
							$blog_details = get_blog_details( $invSiteID );
							$siteName = $blog_details->blogname;
							$investMentApproval = get_post_meta($inv->ID,'pm_approval',true);

						?>
							<tr class="investment-row" data-invid="<?php echo $inv->ID ?>">
								<td><?php echo get_the_title(esc_html($invInfo['investment_project_id']));  ?></td>
								<td><?php
									$points = getQuestionnairePoints($uData->ID,true);
									echo "<span class='points_hover' id='".$uData->ID."'>".$points['total']."</span>";
									?>
									<div class='points_hover_div' style="display:none" id='points_hover_div_<?php echo $uData->ID; ?>'>
										<?php foreach($points as $k => $v) { ?>
										<span><?php echo $k ?></span>: <span><?php echo $v ?></span><br/>
										<?php } ?>
									<div>
								</td>
								<td><?php echo $uMetas['billing_first_name'][0].' '.$uMetas['billing_last_name'][0] ?></td>
								<td><?php echo $invInfo['investing_into'] != '' ? $invInfo['investing_into'] : '-' ?></td>
								<td>
									<?php
										$status = esc_html(get_post_meta( $inv->ID , 'themeum_status_all' , true ));
										if($status == 'cancelled') {
											echo "Funding Cancelled";
										}else if ($status == 'investment_closed'){
											echo "Investment Closed";
										}else if ($status == 'received'){
											echo "Funding Received";
										}else if ($status == 'complete') {
											echo "Investment Complete";
										}else if ($status == 'pending'){
											echo "Awaiting Funding";
										}else if ($status == 'transfer_requested'){
											echo "Transfer Requested";
										}else if ($status == 'pack_out') {
											echo "Pack Out";
										}else{
											echo "Awaiting Funding";
										}
									?>
								</td>
								<td>
									£<?php echo $invInfo['investment_amount']; ?>
								</td>
								<td><?php echo $siteName ?></td>
								<td>
									<?php //echo get_post_meta($inv->ID,'pm_approval_action',true); ?>
									<a href="javascript:void(0);" class="approve_investment" data-invid="<?php echo $inv->ID ?>">approve</a> | <a href="javascript:void(0);" class="reject_investment" data-invid="<?php echo $inv->ID ?>">reject</a>
								</td>
							</tr>
						<?php endforeach; ?>
						<?php }else{ ?>
							<tr>
								<td colspan="8" align="center">No Investments Found!</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</section>
			<style>
				#table-approval-transactions th, #table-approval-transactions td {
					padding: 15px;
				}
				.points_hover {
					display: block;
					padding: 10px;
					cursor: pointer;
					color: #00AED1;
				}
				.points_hover_div {
					position: absolute;
					border: 1px solid #ccc;
					background: #fff;
					padding: 10px;
				}
			</style>
			<script>
			jQuery(function(){
				jQuery('.points_hover').hover(function(){
					var id = jQuery(this).attr('id');
					jQuery("#points_hover_div_"+id).show();
				},function(){
					var id = jQuery(this).attr('id');
					jQuery("#points_hover_div_"+id).hide();
				});

				// approve
				jQuery(".approve_investment").click(function(){
					var invid = jQuery(this).data('invid');
					var thisel = jQuery(this);
					jQuery.post({
					  url: '<?php echo admin_url('admin-ajax.php'); ?>',
					  data: {
						action: "approve_investment",
						invid: invid
					  },
					  success: function(response){
						console.log(response);
						var res = JSON.parse(response);
						if(res.status === "true") {
							thisel.parent().html('Approved');
						}else{
							var message = $("<span />", {
							 "class": "flash_message",
								 text: "Something went wrong!"
							  }).fadeIn("fast");
							thisel.parent().append(message);
							message.delay(2000).fadeOut("normal", function() {
							 jQuery(this).remove();
						  });
						}
					  }
					});
				});

				// reject
				jQuery(".reject_investment").click(function(){
					var invid = jQuery(this).data('invid');
					var thisel = jQuery(this);
					jQuery.post({
					  url: '<?php echo admin_url('admin-ajax.php'); ?>',
					  data: {
						action: "reject_investment",
						invid: invid
					  },
					  success: function(response){
						console.log(response);
						var res = JSON.parse(response);
						if(res.status === "true") {
							thisel.parent().html('Rejected');
						}else{
							var message = $("<span />", {
							 "class": "flash_message",
								 text: "Something went wrong!"
							  }).fadeIn("fast");
							thisel.parent().append(message);
							message.delay(2000).fadeOut("normal", function() {
							 jQuery(this).remove();
						  });
						}
					  }
					});
				});
			});
			</script>
<?php
	return ob_get_clean();
	}

function pm_no_access_message() {
	echo "You don't have access to this page.";
}
	$currentUser = wp_get_current_user();
	if(is_super_admin($currentUser->ID)){  //in_array('product_manager',$currentUser->roles) ||
		add_shortcode('pm_all_users', 'pm_all_users');
		add_shortcode('pm_investments', 'pm_investments');
	}else{
		add_shortcode('pm_all_users','pm_no_access_message');
		add_shortcode('pm_investments','pm_no_access_message');
	}

}// end of check site id 1

if(is_admin()) {
	add_filter('woocommerce_customer_meta_fields','remove_billing_address_admin',999999);
	function remove_billing_address_admin($fields) {
		unset($fields['billing']);
		unset($fields['shipping']);
		return $fields;
	}
}

if(trim( $_SERVER["REQUEST_URI"] , '/' ) == 'opportunities') {
	function less_points_modal() {
		if(is_user_logged_in()){
		?>
		<div id="less-points-message-modal" class="modal fade">
			<div class="modal-dialog modal-md">
				 <div class="modal-content">
					 <div class="modal-header">
						 <i class="fa fa-close close" data-dismiss="modal" id="close-form-submit-close"></i>
					 </div>
					 <div class="modal-body">
						<p>
						Please note, your score is below a score of 15, this means you may not be approved for this investment and it is down to the discretionary of the company to accept you as an investor. The Company may wish to contact you for further information.
						</p>
					 </div>
				 </div>
			</div>
		</div>
		<?php
			$userPoints = getQuestionnairePoints(get_current_user_id());

			if($userPoints <= 15) {
		?>
			<script type="text/javascript">
				jQuery(document).ready(function(){ 'use strict';
					//console.log(jQuery("#custom-thankyou-message-modal"));
					jQuery("#less-points-message-modal").addClass('in');
					jQuery("#less-points-message-modal .modal-backdrop").addClass('in');
					jQuery("#less-points-message-modal").show();
					jQuery("#less-points-message-modal").modal();
				});
			</script>
		<?php
			}
		}
	}
	add_action('wp_footer','less_points_modal');
}

function wppb_update_options_all_sites() {
	global $wpdb;
	//$sites = get_sites();
}


add_filter("gettext", "translate_publish_post_status", 9999, 2);

function translate_publish_post_status($translation, $text) {
	if(isset($_GET['action']) && $_GET['action'] == 'edit') {
		$p = $_GET['post'];
		$po = get_post($p);
		if($po->post_type == 'investment') {
			switch($text) {
				case "Publish":                     return "Complete";
				case "Published on: <b>%1$s</b>":  return "Completed on: <b>%1$s</b>";
				case "Publish <b>immediately</b>":  return "Complete <b>immediately</b>";
				case "Publish on: <b>%1$s</b>":    return "Complete on: <b>%1$s</b>";
				case "Published":                   return "Investment Completed";
				case "Save & Publish":              return "Complete"; //"Double-save"? :)
				default:                            return $translation;
			}
		}
	}
	return $translation;
}

//add_filter('validate_username','custom_username_validation',99999,1);
function custom_username_validation($username) {
	$allowed = array(".", "_"); // you can add here more value, you want to allow.
    if(ctype_alnum(str_replace($allowed, '', $username ))) {

        return true;
    } else {
        return false;
    }
}

add_action('user_register','custom_user_registration_hook');

function custom_user_registration_hook($user_id){
  //do your stuff
  restore_current_blog();
  $currSite = get_current_blog_id();
  update_user_meta($user_id,'primary_blog',$currSite);
  update_user_meta($user_id,'site_id',$currSite);

	// post to sharehub
	$data = [];
	$data['user_metas'] = $_POST;
	$data['trc_userid'] = $user_id;
	$data['site_id'] = $currSite;

	$url = "https://thesharehub.co.uk/wp-admin/admin-ajax.php?action=sync_trc_user";
	$args =  array(
		'method'    => 'POST',
		'timeout'   => 30,
		'blocking'  => 	true,
		'sslverify' => false,
		'body'      => $data
	);
	$response = wp_remote_post( $url, $args );
	$path = ABSPATH;
	if( is_wp_error( $response ) ) {
	   file_put_contents($path.'sh_user_response.txt','Error '.$projectID.' : <pre>'.print_r($response,1).'</pre>');
	} else {
		file_put_contents($path.'sh_user_response.txt','Success '.$projectID.' : <pre>'.print_r($response,1).'</pre>');
		$body = wp_remote_retrieve_body( $response );

		$resBody = json_decode($body,true);
		if($resBody['status'] == 'true') {
			update_user_meta($user_id,'sharehub_uid',$resBody['sharehub_uid']);
		}

	}
}

add_action('user_register','createStatementPDFonUserSignup');
function createStatementPDFonUserSignup($user_id){
  //do your stuff

  //$umetas = get_user_meta($user_id);
  $first_name = $_POST['first_name']; //$umetas['first_name'][0];
  $last_name = $_POST['last_name']; // $umetas['last_name'][0];

  update_user_meta($user_id,'billing_first_name',$first_name);
  update_user_meta($user_id,'billing_last_name',$last_name);

  /*if($_POST['certified_investor_type'] == 'self_certified_sophisticated') {
	update_user_meta($user_id,'investor_type','Self Certified Sophisticated Investor');
	generateUserPDF($user_id,'sophisticated',array('first_name' => $first_name, 'last_name' => $last_name));
  }else if($_POST['certified_investor_type'] == 'self_certified_highnet'){
	update_user_meta($user_id,'investor_type','Self Certified High Net Worth Investor');
	generateUserPDF($user_id,'highnet',array('first_name' => $first_name, 'last_name' => $last_name));
  }else{
	update_user_meta($user_id,'investor_type','Certified Sophisticated Investor');
  }*/

	$headers = array('Content-Type: text/html; charset=UTF-8');
	$emailOptions = get_option( 'swpsmtp_options' );
	$toWhiteLabel = $emailOptions[ 'from_email_field' ];
	$subject = "New User signed up";
	$message = "A new user has registered on your site<br/>";
	$message .=  "Details are: <br/>";
	$message .= 'Name: '.$first_name.' '.$last_name.'<br/>
					Email: '.$_POST['email'].'<br/>
					Username: '.$_POST['username'].'<br/><br/>';
	//$message .= "Please take an action on the investment <a href='https://therightcrowd.com/wp-admin/edit.php?post_type=investment' >here</a><br/><br/>";
	$message .= "Thank you";
	wp_mail($toWhiteLabel, $subject, $message,$headers);

	// send email if appropriation points are less than 15
	  $arr = array(
		'q_invested_before' => $_POST['q_invested_before'],
		'q_financial_experience' => $_POST['q_financial_experience'],
		'q_individual_investments' => $_POST['q_individual_investments'],
		'q_education_level' => $_POST['q_education_level'],
		'q_employment' => $_POST['q_employment'],
		'q_income' => $_POST['q_income'],
		'q_assets' => $_POST['q_assets'],
		'q_expenses_per' => $_POST['q_expenses_per'],
		'q_objective' => $_POST['q_objective'],
		'q_intend_investment' => $_POST['q_intend_investment'],
		'q_position' => $_POST['q_position']
	  );
	  $points = getQuestionnairePointsFromArray($arr);
	  if((int) $points < 15) {
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$subject = "Client Appropriation points less than 15";
		$message = "$first_name $last_name has tried sign up but has scored below a 15, please contact ".$_POST['email']."<br/><br/>";

		$message .= "Thank you";
		wp_mail($toWhiteLabel, $subject, $message,$headers);
	  }

  if(isset($_POST['investor_type'])) {
	  generateUserPDF($user_id,'both',array('first_name' => $first_name, 'last_name' => $last_name));
  }
  if(isset($_POST['user_nda']) && isset($_POST['user_nda'][0]) && $_POST['user_nda'][0] == 'yes') {
		generateUserNdaPDF($user_id,array('first_name' => $first_name, 'last_name' => $last_name));
  }
  /*if($_POST['certified_investor_type'] == 'agree') {
	  generateUserPDF($user_id,'both',array('first_name' => $first_name, 'last_name' => $last_name));
  }*/

}

add_action( 'wp_ajax_nopriv_test_statement', 'testStatement' );
add_action( 'wp_ajax_test_statement', 'testStatement' );

function testStatement() {
	//generateUserPDF(5,'both',array('first_name' => 'Test', 'last_name' => 'Dorset'));
}

add_action( 'admin_post_nopriv_update_suitability_test', 'update_suitability_test' );
add_action( 'admin_post_update_suitability_test', 'update_suitability_test' );
/*function update_suitability_test() {
	$uid = get_current_user_id();

	if($uid <= 0) {
		return;
	}

	update_user_meta($uid,'q_invested_before',$_POST['q_invested_before']);
	update_user_meta($uid,'q_financial_experience',$_POST['q_financial_experience']);
	update_user_meta($uid,'q_individual_investments',$_POST['q_individual_investments']);
	update_user_meta($uid,'q_education_level',$_POST['q_education_level']);
	update_user_meta($uid,'q_employment',$_POST['q_employment']);
	update_user_meta($uid,'q_income',$_POST['q_income']);
	update_user_meta($uid,'q_assets',$_POST['q_assets']);
	update_user_meta($uid,'q_expenses_per',$_POST['q_expenses_per']);
	update_user_meta($uid,'q_objective',$_POST['q_objective']);
	update_user_meta($uid,'q_intend_investment',$_POST['q_intend_investment']);
	update_user_meta($uid,'q_position',$_POST['q_position']);

	wp_redirect( "/opportunities" );
	exit;
}*/

function update_suitability_test() {
	$uid = get_current_user_id();

	if($uid <= 0) {
		return;
	}

	// get current site id and user's site id to check if user is on different whitelabel or its original one
	$currentSite = get_current_blog_id();
	$uSiteId = get_user_meta($uid,'site_id',true);
	$fname = get_user_meta($uid,'billing_first_name',true);
	$lname = get_user_meta($uid,'billing_last_name',true);

	$prefix = '';
	if($currentSite != $uSiteId) {
		$prefix = $currentSite."_";
	}

	update_user_meta($uid,$prefix.'q_invested_before',$_POST['q_invested_before']);
	update_user_meta($uid,$prefix.'q_financial_experience',$_POST['q_financial_experience']);
	update_user_meta($uid,$prefix.'q_individual_investments',$_POST['q_individual_investments']);
	update_user_meta($uid,$prefix.'q_education_level',$_POST['q_education_level']);
	update_user_meta($uid,$prefix.'q_employment',$_POST['q_employment']);
	update_user_meta($uid,$prefix.'q_income',$_POST['q_income']);
	update_user_meta($uid,$prefix.'q_assets',$_POST['q_assets']);
	update_user_meta($uid,$prefix.'q_expenses_per',$_POST['q_expenses_per']);
	update_user_meta($uid,$prefix.'q_objective',$_POST['q_objective']);
	update_user_meta($uid,$prefix.'q_intend_investment',$_POST['q_intend_investment']);
	update_user_meta($uid,$prefix.'q_position',$_POST['q_position']);

	$newSites = array($currentSite);
	$oldSites = get_user_meta($uid, 'suitability_sites',true);
	if($oldSites != '') {
		$newSites = array_merge($newSites,$oldSites);
	}
	update_user_meta($uid,'suitability_sites',$newSites);

	// update NDA if site id is 74 - pp09spv
	if($currentSite == '74' && isset($_POST['user_nda']) && $_POST['user_nda'] == 'yes') {
		generateUserNdaPDF($uid,array('first_name' => $fname, 'last_name' => $lname));
	}

	if(isset($_SESSION['redirect_path']) && $_SESSION['redirect_path'] != '') {
		wp_redirect( $_SESSION['redirect_path'] );
	}else{
		wp_redirect( "/opportunities" );
	}
	exit;
}


add_action('save_post','safe_thumbnail_on_update',999999999,3);
function safe_thumbnail_on_update($post_id,$post,$update){
	if(isset($_POST['_thumbnail_id'])) {
		switch_to_blog(1);
		update_post_meta($post_id,'_thumbnail_id',$_POST['_thumbnail_id']);
		$id = get_post_meta($post_id,'_thumbnail_id',true);
	}
}

function wp_statuses_register_password_protected() {

}

function getReferralUserInvestments($refcode = null) {
	if($refcode === null || $refcode == '') {
		return [0];
	}
	$refcode = trim(strtolower($refcode));
	$rc = explode(",",$refcode);
	$rc = implode("','",$rc);

	global $wpdb;
	$sq = "SELECT DISTINCT u.ID FROM ".$wpdb->base_prefix."users u
	INNER JOIN $wpdb->usermeta m ON m.user_id = u.ID
WHERE m.meta_key = 'referralcode' AND LOWER(m.meta_value) IN ('".$rc."')";

	$allUsers = $wpdb->get_results($sq,ARRAY_A);
	$ids = [0];
	if(count($allUsers) > 0) {
		foreach($allUsers as $u) {
			$ids[] = $u['ID'];
		}
	}
	return $ids;
}

// fetch users who are not checked with aml yet
function getUsersAML($site_id = 0) {
	global $wpdb;
	/*$sq = "SELECT DISTINCT u.ID FROM ".$wpdb->base_prefix."users u
	INNER JOIN $wpdb->usermeta m ON m.user_id = u.ID
WHERE (m.meta_key = 'aml_doc_check' AND LOWER(m.meta_value) == 'no') OR NOT EXISTS m.meta_key";*/
/*$sq = "SELECT DISTINCT u.ID FROM ".$wpdb->base_prefix."users u
WHERE NOT EXISTS (
              SELECT * FROM ".$wpdb->base_prefix."usermeta as m
               WHERE m.meta_key = 'aml_doc_check'
                AND m.`user_id` = u.ID
            )";*/
	$args = array(
		'blog_id' => $site_id,
		'role__not_in' => array('administrator'),
		'number'=> -1,
		'fields' => 'ID',
		'meta_query' => array(
		   'relation' => 'OR',
			array(
			 'key' => 'aml_doc_check',
			 'compare' => 'NOT EXISTS' // doesn't work
			),
			array(
			 'key' => 'aml_doc_check',
			 'value' => 'no'
			)
		)
	);
	$ids[0] = 0;
	$allUsers = get_users($args);
	return array_merge($ids,$allUsers);
}

add_action( 'wp_ajax_nopriv_cancel_my_investment', 'cancel_my_investment' );
add_action( 'wp_ajax_cancel_my_investment', 'cancel_my_investment' );
function cancel_my_investment() {
	restore_current_blog();
	$isManager = isManager();
	global $switched;
	switch_to_blog(1);

	$ret = array('status'=>'false');
		if( (int) $_POST['invid'] > 0) {
			$author = get_post_meta($_POST['invid'],'themeum_investor_user_id',true);
			$uid = get_current_user_id();
			if($author == $uid || $isManager ) {
				$inv = array(
					'ID'            => $_POST['invid'],
					'post_status'   => 'cancelled'
				);
				wp_update_post( $inv );
				$ret['status'] = 'true';
				restore_current_blog();
				echo json_encode($ret);die();
			}
		}
	restore_current_blog();
	echo json_encode($ret);die();
}

add_action( 'wp_ajax_nopriv_change_investment_status', 'change_investment_status' );
add_action( 'wp_ajax_change_investment_status', 'change_investment_status' );
function change_investment_status() {
	$ret = array('status'=>'false');
		if(isCustodian() || is_super_admin()) {
			if( (int) $_POST['invid'] > 0) {
					switch_to_blog(1);
					$inv = array(
						'ID'            => $_POST['invid'],
						'post_status'   => $_POST['st']
					);
					wp_update_post( $inv );
					$ret['status'] = 'true';
					restore_current_blog();
					echo json_encode($ret);die();
			}
		}
	echo json_encode($ret);die();
}

add_action( 'wp_ajax_nopriv_upload_aml_docs', 'upload_aml_docs' );
add_action( 'wp_ajax_upload_aml_docs', 'upload_aml_docs' );
function upload_aml_docs() {
	$currentSite = get_current_blog_id();
	$currentUserID = get_current_user_id();
	if(isset($_POST['uid'])) {
		$currentUserID = $_POST['uid'];
	}
	$oldFiles = get_user_meta($currentUserID,'aml_docs',true);
	$ret = array('status'=>'false');
	$uploadedFiles = $_FILES;

	$_FILES = array();
	$amlDocs = [];
	if(isset($uploadedFiles['aml-file']) && count($uploadedFiles['aml-file']['name'] > 0)) {
		foreach ($uploadedFiles['aml-file']['tmp_name'] as $key => $value) {
				$_FILES["aml-file-{$key}"] = array(
					'tmp_name' => $uploadedFiles['aml-file']['tmp_name'][$key],
					'name' => $uploadedFiles['aml-file']['name'][$key],
					'size' => $uploadedFiles['aml-file']['size'][$key],
					'type' => $uploadedFiles['aml-file']['type'][$key],
					'error' => $uploadedFiles['aml-file']['error'][$key]
				);
			   $amlDocID = media_handle_upload( "aml-file-{$key}", 0 );
			   if( !is_wp_error( $amlDocID ) ) {
					$amlDocs[] = $amlDocID;
			   }
		}
		// update user meta
		if($oldFiles != '' && count($oldFiles) > 0) {
			$amlDocs = array_merge($oldFiles,$amlDocs);
		}
		update_user_meta($currentUserID,'aml_docs',$amlDocs);
		$ret = array('status'=>'true');
	}

		echo json_encode($ret);die();
}

add_action( 'wp_ajax_nopriv_user_aml_check', 'user_aml_check' );
add_action( 'wp_ajax_user_aml_check', 'user_aml_check' );
function user_aml_check() {
	$ret = array('status'=>'false');
	if(isCustodian() || isManager()) {
		$uid = (int) $_POST['uid'];
		$value = $_POST['value'];

		if($_POST['invid'] > 0 && $value=="yes"){
			update_post_meta( $_POST['invid'], 'aml_check_date', time() );
		}

		if($_POST['invid'] > 0 && $value=="no"){
			update_post_meta( $_POST['invid'], 'aml_uncheck_date', time() );
		}
		if($uid > 0) {
			update_user_meta($uid,'aml_doc_check',$value);
			$ret = array('status'=>'true');
		}
	}
	echo json_encode($ret);die();
}


add_action( 'wp_ajax_nopriv_changeUserWhiteLabelHTML', 'changeUserWhiteLabelHTML' );
add_action( 'wp_ajax_changeUserWhiteLabelHTML', 'changeUserWhiteLabelHTML' );

function changeUserWhiteLabelHTML() {
	if(is_super_admin()) {
		$uid = $_GET['uid'];
		switch_to_blog(1);
		$fname = get_user_meta($uid,'billing_first_name',true);
		$lname = get_user_meta($uid,'billing_last_name',true);
		$siteid = get_user_meta($uid,'site_id',false);
		$selectField = false;
		if(count($siteid) > 1) {
			$selectField = true;
		}
		?>
		<form method="post" id="new_whitelabel_form">
			<?php if($selectField){ ?>
				<p>This user has multiple whitelabels, please choose one for action.</p>
				<select name="site_id" id="site_id">
					<option value="">Select Whitelabel</option>
					<?php foreach($siteid as $sid){ ?>
						<option value="<?php echo $sid ?>"><?php echo $sid ?></option>
					<?php } ?>
				</select>
			<?php }else{ ?>
				<input type="hidden" name="site_id" id="site_id" value="<?php echo $siteid[0] ?>" />
				<?php
					$blog_details = get_blog_details( $siteid[0] );
					$siteName = $blog_details->blogname;
				?>
				<h3>Current White Label: <?php echo $siteName ?></h3>
			<?php } ?>
			<h3>User: <?php echo $fname." ".$lname; ?></h3>
			<?php $allSites = get_sites(array('site__not_in' => array(1))); ?>
			<p>
				<label>
					New White Label
					<select name="new_site_id">
						<option value="">Select New Whitelabel</option>
						<?php foreach($allSites as $wl) {
							$wlName = get_blog_details($wl->blog_id)->blogname;
						?>
							<option value="<?php echo $wl->blog_id ?>"><?php echo $wlName ?></option>
						<?php } ?>
					</select>
					<input type="hidden" name="uid" id="uid" value="<?php echo $uid ?>" />
					<input type="hidden" name="multi_sites" value="<?php echo $siteid > 1 ? 'yes' : 'no' ?>" />
					<input type="hidden" name="action" id="action" value="action_change_user_whitelabel" /><br/>
				</label>
			</p>
			<p>
				<button name="change_whitelabel_sbmt_btn" type="button" id="change_whitelabel_sbmt_btn">Change</button>
			</p>
		</form>
		<?php
		restore_current_blog();
	}
}

add_action( 'wp_ajax_nopriv_action_change_user_whitelabel', 'action_change_user_whitelabel' );
add_action( 'wp_ajax_action_change_user_whitelabel', 'action_change_user_whitelabel' );
function action_change_user_whitelabel() {
	$ret = array('status'=>'false');
	if(is_super_admin()) {
		$uid = esc_attr($_POST['uid']);
		$udata = get_userdata($uid);
		$siteid = esc_attr($_POST['new_site_id']);

		$isMulti = $_POST['multi_sites']=='yes' ? true : false;
		if($isMulti) {
			update_user_meta($uid,'site_id',$siteid);
			update_user_meta($uid,'primary_blog',$siteid);
		}else{
			update_user_meta($uid,'site_id',$siteid);
			update_user_meta($uid,'primary_blog',$siteid);
		}
		add_user_to_blog($siteid,$uid,$udata->roles[0]);
		$ret = array('status'=>'true');
	}
	echo json_encode($ret);die();
}


add_action( 'wp_ajax_nopriv_getInvDetails', 'getInvDetails' );
add_action( 'wp_ajax_getInvDetails', 'getInvDetails' );
function getInvDetails() {
	if(is_admin()) {
		$invID = $_GET['inv_id'];
		$ajaxURL = admin_url('admin-ajax.php');
		$siteblogId = get_current_blog_id();
		if(get_current_blog_id() != '1') {
			switch_to_blog(1);
		}
		$inv = get_post($invID);
		$invInfo = getInvestmentInfo($invID);
		$uMetas = get_user_meta($invInfo['investor_user_id']);
		$uData = get_userdata($invInfo['investor_user_id']);
		$invSiteID = get_post_meta($inv->ID,'site_id',true);
		if($siteblogId==86):
			$investment_term = get_post_meta($inv->ID,'investment_term',true);
			$interest_payment_intervals = get_post_meta($inv->ID,'interest_payment_intervals',true);
		endif;
		$blog_details = get_blog_details( $invSiteID );
		$siteName = $blog_details->blogname;
		$sourceSiteName = ' - ';
		$sourceSiteID = get_post_meta($inv->ID,'source_invest_site',true);
		if($sourceSiteID != '') {
			$s_blog_details = get_blog_details( $sourceSiteID );
			$sourceSiteName = $s_blog_details->blogname;
		}
		$notes = getNotes($inv->ID,'investment',$siteblogId);
		$inv_curr = get_post_meta($invID,'investment_currency',true);
		if($inv_curr == '') {
			$inv_curr = 'GBP';
		}
		$title = ($invInfo['project_name'] != '') ? $invInfo['project_name'] : get_the_title( $invInfo['investment_project_id'] );
		if(trim($title) == '') {
			$investTitleParts = explode(":",$inv->post_title);
			if(count($investTitleParts) > 1) {
				$title = $investTitleParts[1];
			}
		}

		ob_start();
		?>
		<table>
			<tr><th colspan="2" align="center">Investment Product: <?php echo $title ?></th></tr>
			<tr><td>Title</td><td><?php echo $uMetas['user_title'][0] ?></td></tr>
			<tr><td>First Name</td><td><?php echo $uMetas['billing_first_name'][0] ?></td></tr>
			<tr><td>Middle Name</td><td><?php echo $uMetas['middle_name'][0] ?></td></tr>
			<tr><td>Last Name</td><td><?php echo $uMetas['billing_last_name'][0] ?></td></tr>
			<tr><td>Email</td><td><?php echo $uData->data->user_email ?></td></tr>
			<tr><td>DOB </td><td><?php echo $uMetas['userdob'][0] ?></td></tr>
			<tr><td>NI Number</td><td><?php echo $uMetas['ni_number'][0] ?></td></tr>
			<tr><td>Address</td><td><?php echo $uMetas['billing_address_1'][0] ?>,<?php echo $uMetas['billing_address_2'][0] ?>,
										<?php echo $uMetas['billing_city'][0] ?>, <?php echo $uMetas['billing_state'][0] ?>,
										<?php echo $uMetas['billing_country'][0] ?> - <?php echo $uMetas['billing_postcode'][0] ?></td></tr>
			<tr><td>Proofs</td><td><?php
										if($sourceSiteID != '') {
											switch_to_blog($sourceSiteID);
										}else{
											switch_to_blog($invSiteID);
										}
										$IdProof = get_user_meta($uData->ID,'idproof',true);
										$AddressProof = get_user_meta($uData->ID,'addressproof',true);
										//echo $IdProof;
										$proofs = '<br/>';
										if($IdProof > 0) {
											$IdProofPost = get_post($IdProof);
											$proofs .= 'ID: <a href="'.$IdProofPost->guid.'" target="_blank">'.$IdProofPost->post_title.'</a><br/>';
										}
										if($AddressProof > 0) {
											$AddressProofPost = get_post($AddressProof);
											$proofs .= 'Address: <a href="'.$AddressProofPost->guid.'" target="_blank">'.$AddressProofPost->post_title.'</a>';
										}
										echo $proofs;
										restore_current_blog();
									?></td></tr>
			<tr><td>Contact Number</td><td><?php echo $uMetas['phone_number'][0] ?></td></tr>
			<tr><td>Funding Source</td><td><?php echo $invInfo['source_of_wealth'] ?></td></tr>
			<tr><td>ISA Type</td><td><?php echo $invInfo['transfertype'] != '' ? $transferTypeLabels[$invInfo['transfertype']] : '-' ?></td></tr>
			<?php if($invInfo['source_of_wealth'] == 'isa'): ?>
					<tr><td>Name of ISA Provider</td><td><?php echo $invInfo['name_isa_provider'] ?></td></tr>
					<tr><td>Address of ISA Provider</td><td><?php echo $invInfo['address1_isa_provider'].", ".$invInfo['address1_isa_provider'].", ".$invInfo['city_isa_provider'].", ".$invInfo['state_isa_provider'].", ".$invInfo['zip_isa_provider'] ?></td></tr>
					<tr><td>Account/Reference Number</td><td><?php echo $invInfo['account_isa_provider'] ?></td></tr>
					<?php if($invInfo['another_account'] == 'yes'): ?>
							<tr><td>Another Account</td><td><?php echo $invInfo['another_account_isa_provider'] ?></td></tr>
					<?php endif; ?>
					<tr><td>Full/Part ISA Transfer</td><td><?php echo ucwords(str_replace("_"," ",$infInfo['full_part_isa_transfer'])); ?></td></tr>
			<?php endif; ?>

			<?php
			 if($siteblogId==86):
			 	?>
			 	<tr>
			 		<td>Investment Term</td>
			 		<td><?=$investment_term?></td>
			 	</tr>
			 	<tr>
			 		<td>Interest Payment Intervals</td>
			 		<td><?=$interest_payment_intervals?></td>
			 	</tr>
			 	<?php
			 endif;
			?>
			<tr><td colspan="2" class="popup_notes">Notes<br/>
				<?php
					$currentUser = wp_get_current_user();
				?>
				<ul class="investment-notes" id="notes_wrapper_<?php echo $inv->ID ?>">
					<?php if(count($notes) > 0): ?>
						<?php foreach($notes as $timestamp => $n): ?>
								<li style="line-height: 15px;">
									<?php echo "<span style='font-size:10px;'>".(date("d/m/Y h:i:s a",$timestamp))."</span>"; ?><br/>
									<?php echo trim($n['notes']) ?>
									<?php
									if($currentUser->roles[0]=="sitemanager" ||  is_super_admin()):
										if(array_key_exists("notes_by",$n) && $n['notes_by']!="superadmin" ||  is_super_admin()):
											?>
											 - <b><a href="javascript:void(0);" style="color:#FF0000;" class="removeNotes" data-timestmp="<?=$timestamp?>" data-id="<?=$inv->ID?>">X</a></b>
											<?php
										endif;
									endif;
									?>
								</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
				<div class="investment-notes-input"><input type="text" class="" id="notes_<?php echo $inv->ID ?>" placeholder="Add Note.."  />
				<select id="investment_notes_privacy_<?php echo $inv->ID ?>" style="width: 50%;">
					<option value="admin">Admin Only</option>
					<option value="admin_whitelabel">Admin & White Label Managers</option>
					<option value="admin_whitelabel_referrer">Admin, White Label Managers & Referrer</option>
				</select>
				<button type="button" data-investmentid="<?php echo $inv->ID ?>" class="btn investment-notes-submit">Submit</button></div>
			</td></tr>
		</table>
		<script>
			jQuery(document).on("click",".removeNotes",function(){
				jQuery(this).parent().parent().remove();
				jQuery.post({
				  url: "<?php echo $ajaxURL; ?>",
				  data: {
					action: "remove_investment_note",
					invest_id: jQuery(this).data('id'),
					note_id: jQuery(this).data('timestmp'),
				  },
				  success: function(response){

				  }
				});
			});
		jQuery(".investment-notes-submit").click(function(){
					var invID = jQuery(this).data('investmentid');
					//alert(invID);
					var thisel = jQuery(this);
					var n = jQuery(this).parent().find("input#notes_"+invID).val();
					var privacy = jQuery(this).parent().find("select#investment_notes_privacy_"+invID).val();
					//alert(n);
					jQuery.post({
					  url: "<?php echo $ajaxURL; ?>",
					  data: {
						action: "add_investment_note",
						invest_id: invID,
						privacy: privacy,
						note: n
					  },
					  success: function(response){
						console.log(response);
						var res = JSON.parse(response);
						if(res.status === "true") {
							jQuery(".popup_notes #notes_wrapper_"+invID).append(res.html);
							thisel.parent().find("input#notes_"+invID).val('');
						}
					  }
					});
				});
		</script>
		<?php
		if(get_current_blog_id() != '1') {
			restore_current_blog();
		}
		echo ob_get_clean();
		exit;
	}
}

function post_user_to_sharehub($user_id) {
	// post to sharehub
	$user = get_userdata($user_id);
	$umetas = get_user_meta($user_id);
	$userMetas = [];
	$userMetas['username'] = $user->data->user_login;
	$userMetas['email'] = $user->data->user_email;
	$userMetas['passw1'] = $user->data->user_pass;
	$userMetas['syncing'] = 'yes';
	foreach($umetas as $key => $um) {
		$userMetas[$key] = isset($um[0]) ? $um[0] : '';
	}

	$data = [];
	$data['user_metas'] = $userMetas;
	$data['trc_userid'] = $user_id;
	//$data['site_id'] = $currSite;

	$url = "https://thesharehub.co.uk/wp-admin/admin-ajax.php?action=sync_trc_user";
	$args =  array(
		'method'    => 'POST',
		'timeout'   => 30,
		'blocking'  => 	true,
		'sslverify' => false,
		'body'      => $data
	);
	$response = wp_remote_post( $url, $args );
	$path = ABSPATH;
	if( is_wp_error( $response ) ) {
	   file_put_contents($path.'sh_user_cron_response.txt','Error '.$projectID.' : <pre>'.print_r($response,1).'</pre>');
	} else {
		file_put_contents($path.'sh_user_cron_response.txt','Success '.$projectID.' : <pre>'.print_r($response,1).'</pre>');
		$body = wp_remote_retrieve_body( $response );

		$resBody = json_decode($body,true);
		if($resBody['status'] == 'true') {
			update_user_meta($user_id,'sharehub_uid',$resBody['sharehub_uid']);
			update_user_meta($user_id,'is_synced','yes');
		}

	}
}

add_action( 'wp_ajax_nopriv_sync_users_to_sharehub', 'sync_users_to_sharehub' );
add_action( 'wp_ajax_sync_users_to_sharehub', 'sync_users_to_sharehub' );

function sync_users_to_sharehub() {
	$args = array(
		'blog_id' => 0,
		'role' => 'subscriber',
		'number' => 25,
		'meta_query'=> array(
            array(
                'key' => 'is_synced',
                'compare' => "NOT EXISTS"
            )
       )
	);
	$users = get_users($args);

	if(count($users) > 0) {
		foreach($users as $u) {
			post_user_to_sharehub($u->ID);
		}
	}
	http_response_code(200);
}

add_action( 'wp_ajax_nopriv_sync_shares_to_sharehub', 'sync_shares_to_sharehub' );
add_action( 'wp_ajax_sync_shares_to_sharehub', 'sync_shares_to_sharehub' );
function sync_shares_to_sharehub() {
		$args = array(
			'post_type' => 'project',
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => 'trx_company_id',
					'compare' => "EXISTS"
				)
			),
			'posts_per_page' => -1
		);
		$result = new WP_Query($args);

		if(count($result->posts) > 0 ) {
			foreach($result->posts as $p) {
				$trxCompanyID = get_post_meta($p->ID,'trx_company_id',true);
				if($trxCompanyID == '0') continue;

				$investments = 	getProjectInvestments($p->ID,array('limit' => 2,'is_sh_synced' => 'yes')); //

				$projectID = $p->ID;
				if($investments){
					foreach($investments as $post) {
						// hola!! create company on ShareHub
						/*$isSynced = get_post_meta($post->ID,'is_synced',true);
						if($isSynced != 'yes') {*/
							$data = [];
							$data['investment'] = get_post($post->ID,ARRAY_A);
							$data['investment_metas'] = get_post_meta($post->ID);
							$data['trx_company_id'] = $trxCompanyID;
							$data['sharehub_uid'] = get_user_meta($data['investment_metas']['themeum_investor_user_id'][0],'sharehub_uid',true);

							$url = "https://thesharehub.co.uk/wp-admin/admin-ajax.php?action=sync_trc_post_new";
							$args =  array(
								'method'    => 'POST',
								'timeout'   => 30,
								'blocking'  => 	true,
								'sslverify' => false,
								'body'      => $data
							);
							$response = wp_remote_post( $url, $args );
							$path = get_home_path();
							if( is_wp_error( $response ) ) {
							   file_put_contents($path.'sh_response.txt','Error on Complete'.$projectID.' : <pre>'.print_r($response,1).'</pre>');
							} else {
								file_put_contents($path.'sh_response.txt','Success on Complete'.$projectID.' : <pre>'.print_r($response,1).'</pre>');
								$body = wp_remote_retrieve_body( $response );
								$resBody = json_decode($body,true);
								if($resBody['status'] == 'true') {
									update_post_meta($post->ID,'trx_share_id',$resBody['share_id']);
									update_post_meta($post->ID,'is_synced','yes');
									update_post_meta($post->ID,'is_sh_synced','yes');
								}

							}
						//}
					}
				}
			}
		}


	http_response_code(200);
}

// assign user to trx shares from trc investment
add_action( 'wp_ajax_nopriv_assign_trc_user_to_trx_shares', 'assign_trc_user_to_trx_shares' );
add_action( 'wp_ajax_assign_trc_user_to_trx_shares', 'assign_trc_user_to_trx_shares' );

function assign_trc_user_to_trx_shares() {
	global $wpdb;
	$postData = $_POST; //['data'];
	$ret = array(
		'success' => 'false',
		'status' => 'false'
	);

	if($postData['investment_id'] != '' && $postData['investment_id'] > 0) {
		$uid = get_post_meta($postData['investment_id'],'themeum_investor_user_id',true);
		if($uid > 0) {
			$user = get_userdata($uid);
			$ret = array(
				'status' => 'true',
				'username' => $user->data->user_login
			);
			echo json_encode($ret);
			die();
		}
	}
	echo json_encode($ret);
	die();
}

add_action( 'wp_ajax_nopriv_update_sh_share_status', 'update_sh_share_status' );
add_action( 'wp_ajax_update_sh_share_status', 'update_sh_share_status' );

function update_sh_share_status() {
	global $wpdb;
	$args = array(
		'post_status' => 'publish',
		'post_type' => 'investment',
		'meta_query' => array(
			array(
				'key' => 'themeum_status_all',
				'value' => 'complete',
				'compare' => '='
			),
			array(
				'key' => 'isShStatusUpdated',
				'compare' => 'NOT EXISTS',
				'value' => ''
			)
		),
		'numberposts' => 50
	);
	$posts = get_posts($args);

	if(count($posts) > 0) {
		foreach($posts as $p) {
			$data['trc_investment_id'] = $p->ID;
			$url = "https://thesharehub.co.uk/wp-admin/admin-ajax.php?action=update_sh_share_status";
			$args =  array(
				'method'    => 'POST',
				'timeout'   => 30,
				'blocking'  => 	true,
				'sslverify' => false,
				'body'      => $data
			);
			$response = wp_remote_post( $url, $args );
			if( !is_wp_error( $response ) ) {
				$body = wp_remote_retrieve_body( $response );
				$resBody = json_decode($body,true);
				$path = get_home_path();
				file_put_contents($path.'sh_response.txt','Debug : <pre>'.print_r($response,1).'</pre>');
				if($resBody['status'] == 'true') {
					update_post_meta($p->ID,'isShStatusUpdated','yes');
				}else{
					if(isset($resBody['investment_id'])){
						update_post_meta($resBody['investment_id'],'isShStatusUpdated','yes');
					}
				}

			}
		}
	}
	die();
}

//add_action( 'wp_ajax_nopriv_old_users_statements', 'generateOldUsersStatements' );
//add_action( 'wp_ajax_old_users_statements', 'generateOldUsersStatements' );
function generateOldUsersStatements() {
	global $wpdb;
	$sq = "SELECT * FROM ".$wpdb->base_prefix."users ";
	$sq .= "where 1 ";
	$allUsers = $wpdb->get_results($sq);

	// base directory
	$upload_dir = wp_upload_dir();
	$basedir = $upload_dir['basedir'];

	if(count($allUsers) > 0) {
		foreach($allUsers as $user) {
			$isStatementAdded = get_user_meta($user->ID,'statement_added',true);
			if($isStatementAdded == 'yes') {
				continue;
			}
			$fname = get_user_meta($user->ID,'billing_first_name',true);
			$lname = get_user_meta($user->ID,'billing_last_name',true);
			$file = $basedir.'/statements/statement_'.$user->ID.'.pdf';
			if(!file_exists($file)) {
				generateUserPDF($user->ID,'both',array('first_name' => $fname, 'last_name' => $lname));
			}
			update_user_meta($user->ID,'statement_added','yes');
			echo " === ".$user->ID. " === DONE";
			echo "<br/>";
		}
	}
}

add_action( 'wp_ajax_nopriv_updateRefCode', 'updateRefCode' );
add_action( 'wp_ajax_updateRefCode', 'updateRefCode' );
function updateRefCode() {
	if(isManager()) {
		$invID = $_GET['inv_id'];
		switch_to_blog(1);
		$invInfo = getInvestmentInfo($invID);
		$fname = get_user_meta($invInfo['investor_user_id'],'billing_first_name',true);
		$lname = get_user_meta($invInfo['investor_user_id'],'billing_last_name',true);
		?>
		<form id="new_ref_code_form">
			<h3>Change referral code of Investment #<?php echo $invID; ?></h3>
			<h3>User: <?php echo $fname." ".$lname; ?></h3>
			<p>
				<label>
					New Referral Code
					<input type="text" name="new_ref_code" id="new_ref_code" />
					<input type="hidden" name="inv_id" id="inv_id" value="<?php echo $invID ?>" />
					<input type="hidden" name="action" id="action" value="action_update_ref_code" /><br/>
					<input type="checkbox" name="update_user_ref" value="yes" /> Update Customer's Referral code also?
				</label>
			</p>
			<p>
				<button name="ref_code_btn" type="submit" id="ref_code_btn">Update</button>
			</p>
		</form>
		<?php
		restore_current_blog();
	}
}

add_action( 'wp_ajax_nopriv_action_update_ref_code', 'action_update_ref_code' );
add_action( 'wp_ajax_action_update_ref_code', 'action_update_ref_code' );
function action_update_ref_code() {
	$ret = array('status'=>'false');
	if(isManager()) {
		switch_to_blog(1);
		$invID = esc_attr($_POST['inv_id']);
		$refcode = esc_attr($_POST['new_ref_code']);
		update_post_meta($invID,'themeum_investor_user_ref',$refcode);
		if(isset($_POST['update_user_ref']) && $_POST['update_user_ref'] == 'yes') {
			$invInfo = getInvestmentInfo($invID);
			update_user_meta($invInfo['investor_user_id'],'referralcode',$refcode);
		}
		$ret = array('status'=>'true','refcode'=>$refcode,'invID'=>$invID);
		restore_current_blog();
	}
	echo json_encode($ret);die();
}


// Update AMOUNT of an Investment
add_action( 'wp_ajax_nopriv_updateInvAmount', 'updateInvAmount' );
add_action( 'wp_ajax_updateInvAmount', 'updateInvAmount' );
function updateInvAmount() {
	if(isManager()) {
		$invID = $_GET['inv_id'];
		switch_to_blog(1);
		$invInfo = getInvestmentInfo($invID);
		$inv_curr = get_post_meta($invID,'investment_currency',true);
		if($inv_curr == '') {
			$inv_curr = 'GBP';
		}
		?>
		<form id="new_inv_amount_form">
			<h3>Update amount of Investment #<?php echo $invID; ?></h3>
			<h3>Current Amount: <?php echo $invInfo['investment_amount']; ?></h3>
			<p>
				<label>
					New Amount
					<input type="text" name="new_inv_amount" id="new_inv_amount" /><br/>
					<em>Please enter amount without currency code. Amount will be in <?php echo get_currencies($inv_curr); ?></em>
					<input type="hidden" name="inv_id" id="inv_id" value="<?php echo $invID ?>" />
					<input type="hidden" name="action" id="action" value="action_update_inv_amount" />
				</label>
			</p>
			<p>
				<button name="update_inv_amount_btn" type="submit" id="update_inv_amount_btn">Update</button>
			</p>
		</form>
		<?php
		restore_current_blog();
	}
}

add_action( 'wp_ajax_nopriv_action_update_inv_amount', 'action_update_inv_amount' );
add_action( 'wp_ajax_action_update_inv_amount', 'action_update_inv_amount' );
function action_update_inv_amount() {
	$ret = array('status'=>'false');
	if(isManager()) {
		switch_to_blog(1);
		$invID = esc_attr($_POST['inv_id']);
		$invInfo = getInvestmentInfo($invID);
		$amount = esc_attr($_POST['new_inv_amount']);
		// assigning number of shares to investor user
			$sharePrice = get_post_meta($invInfo['investment_project_id'],'thm_share_price',true);
			if($sharePrice == '') {
				$sharePrice = 1;
			}
			$totalShares = (int) (($amount*100)/$sharePrice);
			update_post_meta( $invID , 'themeum_shares', $totalShares);
			update_post_meta( $invID , 'themeum_investment_amount', esc_attr( $amount ));

		$ret = array('status'=>'true','amount'=>$amount,'invID'=>$invID);
		restore_current_blog();
	}
	echo json_encode($ret);die();
}


// Update investment type of an Investment
add_action( 'wp_ajax_nopriv_updateInvType', 'updateInvType' );
add_action( 'wp_ajax_updateInvType', 'updateInvType' );
function updateInvType() {
	if(isManager()) {
		$invID = $_GET['inv_id'];
		switch_to_blog(1);
		$invInfo = getInvestmentInfo($invID);
		$investingInto = get_post_meta($invInfo['investment_project_id'], 'thm_investing_into',false);
		?>
		<?php if(count($investingInto) > 0) { ?>
		<form id="new_inv_type_form">
			<h3>Update type of Investment #<?php echo $invID; ?></h3>
			<h3>Current Type: <?php echo $invInfo['investing_into']; ?></h3>
			<p>
				<label>
					Select Investment Type
					<select name="new_investing_into">
						<?php foreach($investingInto as $inv) {
							echo '<option value="'.$inv.'">'.$inv.'</option>';
						} ?>
					</select>
					<input type="hidden" name="inv_id" id="inv_id" value="<?php echo $invID ?>" />
					<input type="hidden" name="action" id="action" value="action_update_inv_type" />
				</label>
			</p>
			<p>
				<button name="update_inv_type_btn" type="submit" id="update_inv_type_btn">Update</button>
			</p>
		</form>
		<?php }else{ ?>
			Product has no other investment types than equity.
		<?php } ?>
		<?php
		restore_current_blog();
	}
}

add_action( 'wp_ajax_nopriv_action_update_inv_type', 'action_update_inv_type' );
add_action( 'wp_ajax_action_update_inv_type', 'action_update_inv_type' );
function action_update_inv_type() {
	$ret = array('status'=>'false');
	if(isManager()) {
		switch_to_blog(1);
		$invID = esc_attr($_POST['inv_id']);
		$invInfo = getInvestmentInfo($invID);
		$type = esc_attr($_POST['new_investing_into']);
		update_post_meta( $invID , 'themeum_investing_into', esc_attr( $type ));

		$ret = array('status'=>'true','type'=>$type,'invID'=>$invID);
		restore_current_blog();
	}
	echo json_encode($ret);die();
}

/******************* NEWS POST TYPE REGISTER - NEWS SECTION by gd *******************/
function trx_post_type_news()
{
	$labels = array(
			'name'                	=> _x( 'News', 'Business', 'themeum-startup-idea' ),
			'singular_name'       	=> _x( 'News', 'Business', 'themeum-startup-idea' ),
			'menu_name'           	=> __( 'News', 'themeum-startup-idea' ),
			'parent_item_colon'   	=> __( 'Project:', 'themeum-startup-idea' ),
			'all_items'           	=> __( 'All News', 'themeum-startup-idea' ),
			'view_item'           	=> __( 'View News', 'themeum-startup-idea' ),
			'add_new_item'        	=> __( 'Add News', 'themeum-startup-idea' ),
			'add_new'             	=> __( 'Add News', 'themeum-startup-idea' ),
			'edit_item'           	=> __( 'Edit News', 'themeum-startup-idea' ),
			'update_item'         	=> __( 'Update News', 'themeum-startup-idea' ),
			'search_items'        	=> __( 'Search News', 'themeum-startup-idea' ),
			'not_found'           	=> __( 'No records found', 'themeum-startup-idea' ),
			'not_found_in_trash'  	=> __( 'No records found in Trash', 'themeum-startup-idea' )
		);

	$args = array(
			'labels'             	=> $labels,
			'public'             	=> true,
			'publicly_queryable' 	=> true,
			'show_in_menu'       	=> true,
			'show_in_admin_bar'   	=> true,
			'rewrite' => array('slug' => 'news','with_front' => false),
			'can_export'          	=> true,
			'has_archive'        	=> false,
			'hierarchical'       	=> false,
			'menu_position'      	=> null,
			'supports'           	=> array( 'title','editor','thumbnail','comments')
		);

	register_post_type('news',$args);

}

add_action('init','trx_post_type_news');

function getProjectNews($postid = null) {
	switch_to_blog(1);
	if((int)$postid > 0){
		$args = array('post_type' => 'news','post_parent' => $postid,'orderby' => 'date','numberposts' => -1, 'post_status'=>'publish');
		$news = get_posts($args);
		if(count($news) > 0) {
			restore_current_blog();
			return $news;
		}
	}else{
		$args = array('post_type' => 'news','orderby' => 'date','numberposts' => -1, 'post_status'=>'publish');
		$news = get_posts($args);
		if(count($news) > 0) {
			restore_current_blog();
			return $news;
		}
	}
	restore_current_blog();
	return false;
}
/******************** NEWS SECTION END **********************/

function emailShareholdersOnNews($companyId,$newsItem,$all = false) {
			  $companyName = get_the_title($companyId);
		  	  $subject = "Latest Update: ".$companyName;
			  $message = "An update has been posted by $companyName<br/><br/>";
			  $message .= $newsItem['title'] ."<br/><br/>";
			  $message .= "To read full update : <a href='".get_the_permalink( $companyId )."'>click here</a><br/><br/>";
	  		  $message .= "Best Wishes<br/>The Rightcrowd Team";

		     $body = getEmailBody($subject,$message, $companyId);
		     if($all === true) {
				 // $shareholders = get_users();
				  $headers = array('Content-Type: text/html; charset=UTF-8');


                   $users = get_users( array( 'fields' => array( 'ID' ) ) );
				   $shareholders = [];
					foreach($users as $u) {
						if(hasPublishedShare($u->ID)){
							$shareholders[] = $u;
						}
					}
                    $batchSize = 60;

					$batchesCount = ceil( count($shareholders) / $batchSize);

                    $chunks = array_chunk($shareholders ,$batchSize);

					foreach($chunks as $chunk ) {
	 				   $c = serialize($chunk);

	 				global $wpdb;
					$tableName = $wpdb->prefix."wp_cron_mail";
	 				$wpdb->insert($tableName, array(
					    'subject' => $subject,
					    'body' => $body,
					    'batch' => $c,
					));

	 				}


				  /*foreach($shareholders as $holder) {
				  	wp_mail($holder->user_email, $subject, $body,$headers);
				  }  */





		     }else{
		     	  $shareholders = getShareHolders($companyId,false);
				  $headers = array('Content-Type: text/html; charset=UTF-8');
				  foreach($shareholders as $sh) {
				  	$holder = get_userdata($sh);
				  	wp_mail($holder->user_email, $subject, $body,$headers);
				  }
		     }
}

function emailShareholdersOnNewOffer($companyId,$args = array()) {

			  $companyName = get_the_title($companyId);
		  	  $subject = "New Share Price Interest : ".$companyName;
			  $message .= "Someone has offered a price on shares to buy for the company: $companyName<br/><br/>";
			  $message .= $args['body'] ."<br/><br/>";
			  $message .= "If you are interested in this price, sell your shares now : <a href='".get_the_permalink($companyId)."'>$companyName</a><br/><br/>";
	  		  $message .= "Best Wishes<br/>The Right Crowd Team";

		     $body = getEmailBody($subject,$message);

		     $shareholders = getShareHolders($companyId,false);
		     $headers = array('Content-Type: text/html; charset=UTF-8');
		     foreach($shareholders as $sh) {
		     	$holder = get_userdata($sh);
		     	wp_mail($holder->user_email, $subject, $body,$headers);
		     }
}

function emailToUserOnOfferMatch($uid,$companyId,$sp,$args = array()) {

			  $companyName = get_the_title($companyId);
		  	  $subject = "Your offer price matched to buy shares : ".$companyName;
			  $message .= "Offer matched to buy share of the company: $companyName<br/><br/>";
			  $message .= "Offer Price you made (in pence) : $sp<br/><br/>";
			  $message .= "You can buy shares now on the company page (follow this link) : <a href='".get_the_permalink($companyId)."'>$companyName</a><br/><br/>";
	  		  $message .= "Best Wishes<br/>The Right Crowd Team";

		     $body = getEmailBody($subject,$message);

		     $headers = array('Content-Type: text/html; charset=UTF-8');
		     	$holder = get_userdata($uid);
		     	wp_mail($holder->user_email, $subject, $body,$headers);
}

function wp_new_shareholder_notification( $user_id, $plaintext_pass = '',$companyTitle = '' ) {
     $user = new WP_User($user_id);

     $user_login = stripslashes($user->user_login);
     $user_email = stripslashes($user->user_email);

     if ( empty($plaintext_pass) )
         return;


     $subject = $companyTitle;    //The Right Crowd Appointed by
	  $message = "<div style='width:600px;margin:auto;background-color:#ffffff;border-radius: 3px!important;
    background-color: #ffffff;
    border: 1px solid #dedede;'>"; // main wrapper
		  $message .= getEmailHeader();
		  $message .= "<div style='padding: 36px 48px; background: #52B7EA; color: #fff;'><h1>".$subject."</h1></div>";
		  $message .= "<div style='padding: 48px;' >"; // content
			  $message .= "Great news $companyTitle has now signed up with The Right Crowd making it much easier for their clients to see the company's news in one place.<br/><br/>";
			  $message .= "<ul>";
			  $message .= "<li>Read the latest company news and access Companies House filings</li>";
			  $message .= "<li>Discuss news through a discussion board</li>";
			  $message .= "<li>Have the possibility to trade your positions in the near future</li>";
			  $message .= "</ul>";
			  $message .= "Complete your registration now to access your account and dashboard.<br/>";
			  $message .= sprintf(__('Username: %s'), $user_email) . "<br/>";
			  $message .= sprintf(__('Password: %s'), $plaintext_pass) . "<br/><br/>";
			  $message .= "Complete my registration: ".site_url() . "<br/>";
			  $message .= sprintf(__('If you have any problems, please email us at %s.'), 'admin@therightcrowd.com') . "<br/><br/>";
		  	  $message .= "Best Wishes<br/>The Right Crowd Team";
	  	  $message .= "</div>"; // end of content
	  	  $message .= getEmailFooter();
  	  $message .= "</div>"; // end of main wrapper

	  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail($user_email, $subject, $message, $headers);

 }

 function getAllCompanies($assoc = true) {
	$cargs = array(
		'post_type' 		=> 'project',
		'post_status'    => 'publish',
		'posts_per_page'    => -1
	);
	$companies = get_posts( $cargs );
	if($assoc === true) {
		$data = array();
		if(count($companies) > 0) {
			foreach($companies as $company) {
				$data[$company->ID] = $company->post_title;
			}
		}
		return $data;
	}
	return $companies;
 }

 function hasCompany($cmID = null) { // company manager id
	if($cmID === null) {
		$cmID = get_current_user_id();
	}
	$args = array(
		'post_type' 		=> 'project',
		'post_status'		=> array('publish', 'draft', 'pending'),
		'author'    		=> $cmID,
		'posts_per_page'    => 1,
	);
	$posts = get_posts($args);
	if(count($posts) > 0) {
		return $posts[0];
	}

	return false;
 }

 add_filter('upload_mimes','restict_mime');
function restict_mime($mimes) {
	if(in_array('product_manager',wp_get_current_user()->roles)){
		$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'png' => 'image/png',
		);
	}
	return $mimes;
}

add_action( 'admin_post_nopriv_trc_upload_shareholders', 'trc_upload_shareholders' );
add_action( 'admin_post_trc_upload_shareholders', 'trc_upload_shareholders' );

function trc_upload_shareholders() {
	// add shares to post
   // Shares output to save in meta
   $hasCompany = hasCompany();
   if(isset($_POST['butimport']) && $hasCompany){
	    $companyMetas = get_post_meta($hasCompany->ID);
		$post_id = $hasCompany->ID;
		$offering = $companyMetas['thm_offering'][0];
		$title = $hasCompany->post_title;
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
						//if($filesop[3] != 'Number of Shares') {
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
						$colC++;
						continue;
					}
					if($colC > 0) {
					$fname = esc_attr(trim($filesop[0]));
					$lname = esc_attr(trim($filesop[1]));
					$email = $filesop[2];
					$numberShares = $filesop[3];
					$buyPrice =	$filesop[4]; // it's unit price
					$holdingsType =	$filesop[5] != '' ? $filesop[5] : 'share';
					$investmentAmount = $filesop[6];
					//$datePurchased = $filesop[6];


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
											'last_name' => $lname,
											'billing_first_name' => $fname,
											'billing_last_name' => $lname,
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
						update_post_meta($share_id, 'thm_purchaseprice', round(esc_attr($buyPrice),2));
						update_post_meta($share_id, 'investment_amount', $investmentAmount);
						//update_post_meta($share_id, 'thm_purchasedate', esc_attr($datePurchased));
						update_post_meta($share_id, 'thm_status', 'notforsale');
						update_post_meta($share_id, 'thm_offering', esc_attr($offering));
						update_post_meta($share_id, 'thm_share_type', $holdingsType);
					}
					unset($share_id);
					} } // end of if checking number of shares
					 } // end of file

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

				}
			}
		}
		header("location: /dashboard");
		// end of bonds saving and upload
}

add_action( 'admin_post_nopriv_cust_upload_shares', 'cust_upload_shares' );
add_action( 'admin_post_cust_upload_shares', 'cust_upload_shares' );

function cust_upload_shares() {
	$companyID = $post_id = $_POST['company_id'];
	if($companyID == '') {
		header("Location: /upload-your-units/?sharemsg=error");
		exit;
	}
	$holdingType = $_POST['holding_type'];
	if($holdingType == '') {
		header("Location: /upload-your-units/?sharemsg=error");
		exit;
	}
	if($companyID == 'other') {
		$company = esc_attr($_POST['other_company_name']);
		$post_id = 0;
		$companyName = $company;
	}else{
		$company = get_post($companyID);
		$companyName = $company->post_title;
	}

	$user_id = get_current_user_id();

	$udata = get_userdata($user_id);
	$email = $udata->data->user_email;
	$fname = get_user_meta($user_id,'billing_first_name',true);
	$lname = get_user_meta($user_id,'billing_last_name',true);
	$numberShares = $_POST['shares_numbers'];
	$buyPrice = esc_attr($_POST['shares_purchase_price']);
	$investmentAmount = esc_attr($_POST['shares_investment_amount']);
	//$datePurchased = $_POST['shares_purchase_date'];
	$offering = get_post_meta($companyID,'thm_offering',true);

	$shareargs = array('post_type'=>'shares','post_parent'=>$post_id,'post_status'=>'publish','post_author'=>$user_id);
	$shareargs['post_title'] = 'Shares of company '.$companyName;
	$share_id = wp_insert_post( $shareargs );
	if($share_id > 0) {
		update_post_meta($share_id, 'thm_businessid', $post_id);
		update_post_meta($share_id, 'thm_business_name', $companyName);
		if($companyID == 'other') {
			update_post_meta($share_id, 'thm_business_other', 'yes');
		}
		update_post_meta($share_id, 'thm_user_id', $user_id);
		update_post_meta($share_id, 'thm_user_email', esc_attr($email));
		update_post_meta($share_id, 'thm_user_firstname', esc_attr($fname));
		update_post_meta($share_id, 'thm_user_surname', esc_attr($lname));
		update_post_meta($share_id, 'thm_noshares', esc_attr($numberShares));
		update_post_meta($share_id, 'thm_sellingprice', '0');
		update_post_meta($share_id, 'thm_purchaseprice', round(esc_attr($buyPrice),2));
		update_post_meta($share_id, 'investment_amount', $investmentAmount);
		//update_post_meta($share_id, 'thm_purchasedate', esc_attr($datePurchased));
		update_post_meta($share_id, 'thm_status', 'notforsale');
		update_post_meta($share_id, 'thm_offering', esc_attr($offering));
		update_post_meta($share_id, 'thm_share_type', $holdingType);
	}
	//$companyName = $company->post_title;
	$subject = "Customer uploaded share units into the company : $companyName";
	$message = "$fname $lname ($email) has uploaded some share units to the company ($companyName).<br/><br/>";
	if($companyID == 'other') {
		$message .= "Company is not yet registered on The Right Crowd Platform. <br/><br/>";
	}
	$message .= "Best Wishes<br/>The Right Crowd";
	$headers = array('Content-Type: text/html; charset=UTF-8');
	wp_mail('enquiries@therightcrowd.com', $subject, $message,$headers);
	header("Location: /upload-your-units/?sharemsg=success");
	exit;
}

add_filter( 'pre_get_document_title', 'cyb_change_page_title' );
function cyb_change_page_title () {
	if(isset($_GET['page_type']) && $_GET['page_type'] == 'users') {
		return "Dashboard - Users";
	}else if(isset($_GET['page_type']) && $_GET['page_type'] == 'profile') {
		return "My Profile";
	}else if(isset($_GET['page_type']) && $_GET['page_type'] == 'password') {
		return "Change Password";
	}else if(isset($_GET['page_type']) && $_GET['page_type'] == 'pledges') {
		return "My Investments";
	}else if(isset($_GET['page_type']) && $_GET['page_type'] == 'portfolio') {
		return "Dashboard - Portolio";
	}else if(isset($_GET['page_type']) && $_GET['page_type'] == 'reports') {
		return "Reports";
	}else if(isset($_GET['page_type']) && $_GET['page_type'] == 'under-approval-investments') {
		return "Under Approval Investments";
	}else if(isset($_GET['page_type']) && $_GET['page_type'] == 'myshares') {
		return "My Share Holdings";
	}else if(isset($_GET['page_type']) && $_GET['page_type'] == 'mycoupons') {
		return "My Coupons";
	}
}

add_action( 'wp_ajax_nopriv_testing_area', 'testing_area' );
add_action( 'wp_ajax_testing_area', 'testing_area' );
function testing_area() {
	//generateUserNdaPDF(5, array('first_name' => 'Testing', 'last_name' => 'User'));
	generateUserApplicationPDF(5, array('first_name' => 'Testing', 'last_name' => 'User','inv_id' => 234, 'inv_amount' => 2567));
}

//add_action( 'wp_ajax_nopriv_update_investment_user_metas', 'update_investment_user_metas' );
//add_action( 'wp_ajax_update_investment_user_metas', 'update_investment_user_metas' );
function update_investment_user_metas() {

	$args = array(
		'post_type' => 'investment',
		'post_status'=> array('pending','received','cancelled','publish','transfer_requested','pack_out','investment_closed'),
		'posts_per_page' => -1,
		'fields' => 'ids'
	);

	$query = new WP_Query($args);
	foreach($query->posts as $id) {
		$uid = get_post_meta($id,'themeum_investor_user_id',true);
		$fname = get_user_meta($uid,'billing_first_name',true);
		if($fname == '') {
			$fname = get_user_meta($uid,'first_name',true);
		}
		$lname = get_user_meta($uid,'billing_last_name',true);
		if($lname == '') {
			$lname = get_user_meta($uid,'last_name',true);
		}
		$udata = get_userdata($uid);
		$email = $udata->user_email;

		update_post_meta($id,'themeum_investor_user_first_name',$fname);
		update_post_meta($id,'themeum_investor_user_last_name',$lname);
		update_post_meta($id,'themeum_investor_user_email',$email);

		echo "investment ID - ".$id."<br/>";
	}

	exit;
}

//add_action( 'wp_ajax_nopriv_update_complete_investment_shares', 'update_complete_investment_shares' );
//add_action( 'wp_ajax_update_complete_investment_shares', 'update_complete_investment_shares' );
function update_complete_investment_shares() {

	$args = array(
		'post_type' => 'investment',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'fields' => 'ids',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key' => 'is_trcmain_migrated',
				'value' => 'yes',
				'compare' => '!='
			),
			array(
				'key' => 'is_trcmain_migrated',
				'compare' => 'NOT EXISTS',
				'value' => ''
			)
		)
	);

	$query = new WP_Query($args);
	echo "<pre>";
	print_r($query->posts);
	echo "</pre>";exit;
	if(count($query->posts) > 0) {
	foreach($query->posts as $id) {
		$uid = get_post_meta($id,'themeum_investor_user_id',true);
		$projectID = get_post_meta($id,'themeum_investment_project_id',true);

		$fname = get_user_meta($uid,'billing_first_name',true);
		if($fname == '') {
			$fname = get_user_meta($uid,'first_name',true);
		}
		$lname = get_user_meta($uid,'billing_last_name',true);
		if($lname == '') {
			$lname = get_user_meta($uid,'last_name',true);
		}
		$udata = get_userdata($uid);
		$email = $udata->user_email;

		switch_to_blog(1);

		$com = get_post($projectID);
		$coupon = get_post_meta($projectID,'thm_coupon',true);
		$shareargs = array('post_type'=>'shares','post_parent'=>$projectID,'post_status'=>'publish','post_author'=>$uid);
		$shareargs['post_title'] = 'Shares of company '.$com->post_title;
		$share_id = wp_insert_post( $shareargs );
		$invesmentMetas = get_post_meta($id);

		if((int)$share_id > 0) {
			update_post_meta($share_id, 'thm_businessid', $projectID);
			update_post_meta($share_id, 'thm_user_id', $uid);
			update_post_meta($share_id, 'thm_user_email', $email);
			update_post_meta($share_id, 'thm_user_firstname', $fname);
			update_post_meta($share_id, 'thm_user_surname', $lname);
			update_post_meta($share_id, 'thm_noshares', esc_attr($invesmentMetas['themeum_shares'][0]));
			//update_post_meta($share_id, 'thm_purchasedate', esc_attr($datePurchased));
			update_post_meta($share_id, 'thm_status', 'notforsale');
			update_post_meta($share_id, 'thm_offering', esc_attr('ordinary'));
			update_post_meta($share_id, 'thm_share_type', 'share');
			update_post_meta($share_id,'thm_purchaseprice',$invesmentMetas['themeum_share_price'][0]);
			update_post_meta($share_id, 'trc_investment_id',$id);
			update_post_meta($share_id, 'investment_amount',$invesmentMetas['themeum_investment_amount'][0]);
			update_post_meta($share_id, 'org_share_price',$invesmentMetas['themeum_share_price'][0]);
			if($coupon != '') {
				$couponPaid = get_post_meta($projectID,'thm_coupon_paid',true);
				update_post_meta($share_id, 'thm_coupon', $coupon);
				update_post_meta($share_id, 'thm_coupon_paid', $couponPaid);
				update_post_meta($share_id, 'thm_is_coupon', 'yes');
				update_post_meta($share_id, 'thm_share_type', 'coupon');
			}

			// assign customer user role to user
			$user = new WP_User($uid);
			$user->add_role('customer');
		}
		update_post_meta($id,'is_trcmain_migrated','yes');
		restore_current_blog();

		echo "investment ID - ".$id."<br/>";
	}
	}
	exit;
}

//add_action( 'wp_ajax_nopriv_reset_news', 'reset_news' );
//add_action( 'wp_ajax_reset_news', 'reset_news' );
function reset_news() {
	//$all_blog = wp_get_sites();
    //foreach ($blog_ids as $key=>$current_blog) {
        // switch to each blog to get the posts
        //switch_to_blog(1); //$current_blog['blog_id']

		$args = array(
			'post_type' => 'news',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids'
		);

		$query = new WP_Query($args);
		if(count($query->posts) > 0) {
			foreach($query->posts as $id) {
				$details = get_post_meta($id,'details',true);
				wp_update_post(array(
					'ID' => $id,
					'post_content' => $details
				));
				echo "News ID - ".$id."<br/>";
			}
		}

        //restore_current_blog();

	exit;
}

add_action( 'wp_ajax_nopriv_migrate_refcodes', 'migrate_refcodes' );
add_action( 'wp_ajax_migrate_refcodes', 'migrate_refcodes' );
function migrate_refcodes() {

	$args = array(
		'post_type' => 'investment',
		'post_status'    => array('pending','received','cancelled','publish','transfer_requested','pack_out','investment_closed'),
		'posts_per_page' => -1,
		'fields' => 'ids'
	);

	$query = new WP_Query($args);
	foreach($query->posts as $id) {
		$uid = get_post_meta($id,'themeum_investor_user_id',true);
		//$invInfo['investor_user_ref'] != '' ? $invInfo['investor_user_ref'] : $uMetas['referralcode'][0]
		$rcode = get_user_meta($uid,'referralcode',true);
		update_post_meta($id,'themeum_investor_user_ref',$rcode);

		echo "investment ID - ".$id."<br/>";
	}

	exit;
}
