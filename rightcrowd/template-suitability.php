<?php
/*
 * Template Name: Suitability
 */
 
 get_header(); 
$currentSiteID = get_current_blog_id(); 
$actionUrl = admin_url('admin-ajax.php');
$siteUrl = esc_url(site_url());
$termsCondition = 'agreed'; //get_user_meta(get_current_user_id(),'tandc',true);
$isManager = isManager();
$isReadOnlyManager = in_array('read_only_manager',wp_get_current_user()->roles);

?>
<section id="main" class="clearfix">
   <?php get_template_part('lib/sub-header')?>
   <div id="content" class="site-content container" role="main">
      <form id="after_login_suitability_test" class="after_login_suitability_test" action="<?php echo admin_url('admin-post.php'); ?>" method="post">
         <input type="hidden" name="action" value="update_suitability_test" />
         <fieldset>
            <ul style="margin: 0;">
               <li class="wppb-form-field wppb-html" id="wppb-form-element-47">
                  <span class="custom_field_html ">
                     <p>
                        Please complete this client appropriateness assessment form, which is required in order to assess the level of your knowledge and experience of investments and to determine whether these investments are appropriate for you. If you do not complete this questionnaire, it will not be possible to assess your knowledge of investing in and experience of this sector. This questionnaire is not an offer to sell securities.
                     </p>
                     <p>
                        Your answers will be kept as confidential as possible. You agree, however, that this Questionnaire may be shown to such persons as the Company deems appropriate to determine your eligibility and your general suitability for investing in these products.
                     </p>
                  </span>
               </li>
               <li class="wppb-form-field wppb-heading" id="wppb-form-element-31">
                  <h4 class="extra_field_heading">Prior Investment Experience</h4>
                  <span class="wppb-description-delimiter"></span>
               </li>
               <li class="wppb-form-field wppb-checkbox" id="wppb-form-element-32">
                  <label for="q_invested_before">1. Which of the following have you used/invested in before? (select all that apply)<span class="wppb-required" title="This field is required">*</span></label>
                  <ul class="wppb-checkboxes">
                     <li class="wppb-hidden"><input type="hidden" value="" name="q_invested_before"></li>
                     <li><input value="none" class="custom_field_checkbox" name="q_invested_before[]" id="none_32" type="checkbox"><label for="none_32" class="wppb-rc-value">None</label></li>
                     <li><input value="savings_deposits" class="custom_field_checkbox" name="q_invested_before[]" id="savings_deposits_32" type="checkbox"><label for="savings_deposits_32" class="wppb-rc-value">Savings and Deposits</label></li>
                     <li><input value="bonds" class="custom_field_checkbox" name="q_invested_before[]" id="bonds_32" type="checkbox"><label for="bonds_32" class="wppb-rc-value">Bonds</label></li>
                     <li><input value="peer_lending" class="custom_field_checkbox" name="q_invested_before[]" id="peer_lending_32" type="checkbox"><label for="peer_lending_32" class="wppb-rc-value">Peer to Peer lending or unit trusts</label></li>
                     <li><input value="stocks_shares" class="custom_field_checkbox" name="q_invested_before[]" id="stocks_shares_32" type="checkbox"><label for="stocks_shares_32" class="wppb-rc-value">Stocks and Shares</label></li>
                     <li><input value="derivatives" class="custom_field_checkbox" name="q_invested_before[]" id="derivatives_32" type="checkbox"><label for="derivatives_32" class="wppb-rc-value">Derivatives</label></li>
                  </ul>
               </li>
               <li class="wppb-form-field wppb-radio" id="wppb-form-element-33">
                  <label for="q_financial_experience">2. How many years of financial investment experience do you have? <span class="wppb-required" title="This field is required">*</span></label>
                  <ul class="wppb-radios">
                     <li><input value="less_1" class="custom_field_radio " id="less_1_33" name="q_financial_experience" type="radio" required="required"><label for="less_1_33" class="wppb-rc-value">&lt;1 Year</label></li>
                     <li><input value="1_3" class="custom_field_radio " id="1_3_33" name="q_financial_experience" type="radio" required="required"><label for="1_3_33" class="wppb-rc-value">1 to 3 years</label></li>
                     <li><input value="3_5" class="custom_field_radio " id="3_5_33" name="q_financial_experience" type="radio" required="required"><label for="3_5_33" class="wppb-rc-value">3 to 5 years</label></li>
                     <li><input value="more_5" class="custom_field_radio " id="more_5_33" name="q_financial_experience" type="radio" required="required"><label for="more_5_33" class="wppb-rc-value">more than 5 years</label></li>
                  </ul>
               </li>
               <li class="wppb-form-field wppb-radio" id="wppb-form-element-34">
                  <label for="q_individual_investments">3. How many different individual financial investments have you made during the last ten years?<span class="wppb-required" title="This field is required">*</span></label>
                  <ul class="wppb-radios">
                     <li><input value="less_2" class="custom_field_radio " id="less_2_34" name="q_individual_investments" type="radio" required="required"><label for="less_2_34" class="wppb-rc-value">&lt;2</label></li>
                     <li><input value="3" class="custom_field_radio " id="3_34" name="q_individual_investments" type="radio" required="required"><label for="3_34" class="wppb-rc-value">3</label></li>
                     <li><input value="4_6" class="custom_field_radio " id="4_6_34" name="q_individual_investments" type="radio" required="required"><label for="4_6_34" class="wppb-rc-value">4 to 6</label></li>
                     <li><input value="more_6" class="custom_field_radio " id="more_6_34" name="q_individual_investments" type="radio" required="required"><label for="more_6_34" class="wppb-rc-value">more than 6</label></li>
                  </ul>
               </li>
               <li class="wppb-form-field wppb-heading" id="wppb-form-element-35">
                  <h4 class="extra_field_heading">Education and Employment</h4>
                  <span class="wppb-description-delimiter"></span>
               </li>
               <li class="wppb-form-field wppb-radio" id="wppb-form-element-36">
                  <label for="q_education_level">4. What level of education have you achieved? (select only one)<span class="wppb-required" title="This field is required">*</span></label>
                  <ul class="wppb-radios">
                     <li><input value="none" class="custom_field_radio " id="none_36" name="q_education_level" type="radio" required="required"><label for="none_36" class="wppb-rc-value">None</label></li>
                     <li><input value="primary_secondary" class="custom_field_radio " id="primary_secondary_36" name="q_education_level" type="radio" required="required"><label for="primary_secondary_36" class="wppb-rc-value">Primary/Secondary School or equivalent</label></li>
                     <li><input value="a_level" class="custom_field_radio " id="a_level_36" name="q_education_level" type="radio" required="required"><label for="a_level_36" class="wppb-rc-value">A Level or equivalent</label></li>
                     <li><input value="degree" class="custom_field_radio " id="degree_36" name="q_education_level" type="radio" required="required"><label for="degree_36" class="wppb-rc-value">Degree or equivalent</label></li>
                     <li><input value="postgraduate" class="custom_field_radio " id="postgraduate_36" name="q_education_level" type="radio" required="required"><label for="postgraduate_36" class="wppb-rc-value">Postgraduate</label></li>
                  </ul>
               </li>
               <li class="wppb-form-field wppb-radio" id="wppb-form-element-37">
                  <label for="q_employment">5. Which of the following apply in respect of either your current employment or any employment held by you within the last 5 years?<span class="wppb-required" title="This field is required">*</span></label>
                  <ul class="wppb-radios">
                     <li><input value="unemployed" class="custom_field_radio " id="unemployed_37" name="q_employment" type="radio" required="required"><label for="unemployed_37" class="wppb-rc-value">I am either unemployed or retired and have been for the last 5 years</label></li>
                     <li><input value="no_dealing" class="custom_field_radio " id="no_dealing_37" name="q_employment" type="radio" required="required"><label for="no_dealing_37" class="wppb-rc-value">I have no dealing with financial services/investments</label></li>
                     <li><input value="some_dealing" class="custom_field_radio " id="some_dealing_37" name="q_employment" type="radio" required="required"><label for="some_dealing_37" class="wppb-rc-value">I have some dealing with financial services/investments</label></li>
                     <li><input value="working" class="custom_field_radio " id="working_37" name="q_employment" type="radio" required="required"><label for="working_37" class="wppb-rc-value">I work in a financial services/investments business</label></li>
                  </ul>
               </li>
               <li class="wppb-form-field wppb-heading" id="wppb-form-element-38">
                  <h4 class="extra_field_heading">Financial status</h4>
                  <span class="wppb-description-delimiter"></span>
               </li>
               <li class="wppb-form-field wppb-radio" id="wppb-form-element-39">
                  <label for="q_income">6. What is your current, annual income?<span class="wppb-required" title="This field is required">*</span></label>
                  <ul class="wppb-radios">
                     <li><input value="less_10000" class="custom_field_radio " id="less_10000_39" name="q_income" type="radio" required="required"><label for="less_10000_39" class="wppb-rc-value">&lt; £10000 or equivalent</label></li>
                     <li><input value="10000_20000" class="custom_field_radio " id="10000_20000_39" name="q_income" type="radio" required="required"><label for="10000_20000_39" class="wppb-rc-value">£10000 to &lt;£20000 or equivalent</label></li>
                     <li><input value="20000_30000" class="custom_field_radio " id="20000_30000_39" name="q_income" type="radio" required="required"><label for="20000_30000_39" class="wppb-rc-value">£20000 to &lt; £30000 or equivalent</label></li>
                     <li><input value="30000_50000" class="custom_field_radio " id="30000_50000_39" name="q_income" type="radio" required="required"><label for="30000_50000_39" class="wppb-rc-value">£30000 to £50000 or equivalent</label></li>
                     <li><input value="more_50000" class="custom_field_radio " id="more_50000_39" name="q_income" type="radio" required="required"><label for="more_50000_39" class="wppb-rc-value">&gt; £50000 or equivalent</label></li>
                  </ul>
               </li>
               <li class="wppb-form-field wppb-radio" id="wppb-form-element-40">
                  <label for="q_assets">7. What is the net value of assets which are currently owned by you? (Where part owned, only include the value of your part)<span class="wppb-required" title="This field is required">*</span></label>
                  <ul class="wppb-radios">
                     <li><input value="no_assets" class="custom_field_radio " id="no_assets_40" name="q_assets" type="radio" required="required"><label for="no_assets_40" class="wppb-rc-value">No net assets</label></li>
                     <li><input value="less_50000" class="custom_field_radio " id="less_50000_40" name="q_assets" type="radio" required="required"><label for="less_50000_40" class="wppb-rc-value">&lt; £50000 or equivalent</label></li>
                     <li><input value="50000_150000" class="custom_field_radio " id="50000_150000_40" name="q_assets" type="radio" required="required"><label for="50000_150000_40" class="wppb-rc-value">£50000 to &lt; £150000 or equivalent</label></li>
                     <li><input value="150000_250000" class="custom_field_radio " id="150000_250000_40" name="q_assets" type="radio" required="required"><label for="150000_250000_40" class="wppb-rc-value">£150000 to £250000 or equivalent</label></li>
                     <li><input value="more_250000" class="custom_field_radio " id="more_250000_40" name="q_assets" type="radio" required="required"><label for="more_250000_40" class="wppb-rc-value">&gt; £250000 or equivalent</label></li>
                  </ul>
               </li>
               <li class="wppb-form-field wppb-radio" id="wppb-form-element-41">
                  <label for="q_expenses_per">8. What is the current percentage of your monthly expenses compared to you monthly income?<span class="wppb-required" title="This field is required">*</span></label>
                  <ul class="wppb-radios">
                     <li><input value="more_95" class="custom_field_radio " id="more_95_41" name="q_expenses_per" type="radio" required="required"><label for="more_95_41" class="wppb-rc-value">&gt;95%</label></li>
                     <li><input value="80_95" class="custom_field_radio " id="80_95_41" name="q_expenses_per" type="radio" required="required"><label for="80_95_41" class="wppb-rc-value">80% to 95%</label></li>
                     <li><input value="50_80" class="custom_field_radio " id="50_80_41" name="q_expenses_per" type="radio" required="required"><label for="50_80_41" class="wppb-rc-value">50% to 80%</label></li>
                     <li><input value="20_50" class="custom_field_radio " id="20_50_41" name="q_expenses_per" type="radio" required="required"><label for="20_50_41" class="wppb-rc-value">20% to 50%</label></li>
                     <li><input value="less_20" class="custom_field_radio " id="less_20_41" name="q_expenses_per" type="radio" required="required"><label for="less_20_41" class="wppb-rc-value">&lt; 20%</label></li>
                  </ul>
               </li>
               <li class="wppb-form-field wppb-heading" id="wppb-form-element-42">
                  <h4 class="extra_field_heading">Risk appetite/dependency on funds invested</h4>
                  <span class="wppb-description-delimiter"></span>
               </li>
               <li class="wppb-form-field wppb-radio" id="wppb-form-element-43">
                  <label for="q_objective">9. What is your objective for this investment? (select the most important answer)<span class="wppb-required" title="This field is required">*</span></label>
                  <ul class="wppb-radios">
                     <li><input value="capital_preservation" class="custom_field_radio " id="capital_preservation_43" name="q_objective" type="radio" required="required"><label for="capital_preservation_43" class="wppb-rc-value">Capital preservation</label></li>
                     <li><input value="income" class="custom_field_radio " id="income_43" name="q_objective" type="radio" required="required"><label for="income_43" class="wppb-rc-value">Income</label></li>
                     <li><input value="income_growth" class="custom_field_radio " id="income_growth_43" name="q_objective" type="radio" required="required"><label for="income_growth_43" class="wppb-rc-value">Income and growth</label></li>
                     <li><input value="growth" class="custom_field_radio " id="growth_43" name="q_objective" type="radio" required="required"><label for="growth_43" class="wppb-rc-value">Growth</label></li>
                  </ul>
               </li>
               <li class="wppb-form-field wppb-radio" id="wppb-form-element-44">
                  <label for="q_intend_investment">10. As a percentage of your current assets, how much do you intend to investment with us?<span class="wppb-required" title="This field is required">*</span></label>
                  <ul class="wppb-radios">
                     <li><input value="more_80" class="custom_field_radio " id="more_80_44" name="q_intend_investment" type="radio" required="required"><label for="more_80_44" class="wppb-rc-value">&gt;80%</label></li>
                     <li><input value="20_80" class="custom_field_radio " id="20_80_44" name="q_intend_investment" type="radio" required="required"><label for="20_80_44" class="wppb-rc-value">20% to 80%</label></li>
                     <li><input value="10_less_20" class="custom_field_radio " id="10_less_20_44" name="q_intend_investment" type="radio" required="required"><label for="10_less_20_44" class="wppb-rc-value">10% to &lt; 20%</label></li>
                     <li><input value="5_less_10" class="custom_field_radio " id="5_less_10_44" name="q_intend_investment" type="radio" required="required"><label for="5_less_10_44" class="wppb-rc-value">5% to &lt; 10%</label></li>
                     <li><input value="less_5" class="custom_field_radio " id="less_5_44" name="q_intend_investment" type="radio" required="required"><label for="less_5_44" class="wppb-rc-value">&lt; 5%</label></li>
                  </ul>
               </li>
               <li class="wppb-form-field wppb-radio" id="wppb-form-element-45">
                  <label for="q_position">11. Which of the following best describes your position regarding your intended investment with us?<span class="wppb-required" title="This field is required">*</span></label>
                  <ul class="wppb-radios">
                     <li><input value="access_required_full_return_with_interest" class="custom_field_radio " id="access_required_full_return_with_interest_45" name="q_position" type="radio" required="required"><label for="access_required_full_return_with_interest_45" class="wppb-rc-value">It is critical that all my investment is returned to me together with the full offered interest and I require quick and easy access to the funds that I am investing.</label></li>
                     <li><input value="access_not_required_but_full_return_with_interest" class="custom_field_radio " id="access_not_required_but_full_return_with_interest_45" name="q_position" type="radio" required="required"><label for="access_not_required_but_full_return_with_interest_45" class="wppb-rc-value">2.	I do not require quick and easy access to the funds that I am investing but it is very important that all my investment is returned to me together with the full offered interest</label></li>
                     <li><input value="access_not_required_return_at_end_term" class="custom_field_radio " id="access_not_required_return_at_end_term_45" name="q_position" type="radio" required="required"><label for="access_not_required_return_at_end_term_45" class="wppb-rc-value">3.	I do not require quick and easy access to the funds that I am investing but it is very important that all my investment is returned to me at the end of term</label></li>
                     <li><input value="access_not_required_and_comfortable_to_go_down" class="custom_field_radio " id="access_not_required_and_comfortable_to_go_down_45" name="q_position" type="radio" required="required"><label for="access_not_required_and_comfortable_to_go_down_45" class="wppb-rc-value">4.	I do not require quick and easy access to the funds that I am investing and am comfortable that my investment can go down</label></li>
                  </ul>
               </li>
               <li class="wppb-form-field wppb-radio" id="wppb-form-element-51">
                    <label for="investor_type">What type of Investor are you?
                        <span class="wppb-required" title="This field is required">*</span>
                    </label>
                    <ul class="wppb-radios">
                        <li>
                            <input value="Sophisticated Investor" class="custom_field_radio " id="sophisticated-investor_51" name="investor_type" type="radio" required="required">
                            <label for="sophisticated-investor_51" class="wppb-rc-value">I confirm that I am a Sophisticated Investor. I acknowledge the risks and receipt of this document.</label>
                        </li>
                        <li>
                            <input value="High Net Worth Investor" class="custom_field_radio " id="high-net-worth-investor_51" name="investor_type" type="radio" required="required">
                            <label for="high-net-worth-investor_51" class="wppb-rc-value">I confirm that I am a High Net Worth Investor. I acknowledge the risks and receipt of this document.</label>
                        </li>
                    </ul>
                </li>
			   <?php if($currentSiteID == '74') { ?>
			   <li class="wppb-form-field wppb-checkbox" id="wppb-form-element-52">
				   <label for="user_nda"><span class="wppb-required" title="This field is required">*</span></label>
				   <ul class="wppb-checkboxes">
					  <li><input value="yes" class="custom_field_checkbox" name="user_nda" id="nda_checkbox" type="checkbox">
					  <label for="nda_checkbox" class="wppb-rc-value">Please tick this box to accept the <a href="https://pp09spv.com/NDA.pdf" target="_blank">NDA</a></label></li>
				   </ul>
				</li>
			   <?php } ?>
            </ul>
         </fieldset>
         <fieldset>
            <button type="submit" class="btn" id="sbmt-suitability-btn">Submit</button>
         </fieldset>
      </form>
   </div>
</section>
<?php get_footer(); ?>
<?php if($currentSiteID == '74') { ?>
<script>
	jQuery(function() {
		$("#sbmt-suitability-btn").on('click',function(){
			if(!$("#nda_checkbox").is(":checked")){
				alert("Please tick the NDA box. It is mandatory.");
				return false;
			}
		});
	});
</script>
<?php } ?>
<style>
.after_login_suitability_test ul {
	list-style: none;
}
.after_login_suitability_test ul li {
	clear: both;
}
.after_login_suitability_test fieldset > ul > li {
	margin: 10px 0px;
}
.after_login_suitability_test .wppb-heading {
	margin: 15px 0;
}
.after_login_suitability_test .custom_field_html  {
		margin-left: 0;
}
</style>