<!-- start footer -->
<?php $currentsite = get_current_blog_id(); 

?>
<div class=" vc_row-fluid vc_custom_1560261718885 riskwarning"><div class="container"><div class="wpb_row vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner"><div class="wpb_wrapper"><div class="section-header  text-center wow fadeInLeft animated" style="visibility: visible; animation-name: fadeInLeft;"><h2 style="color:#819e8c;font-size:36px;margin:0 0px 30px 0px;padding:0px 0px 0px 0px;">Risk Warnings</h2></div><div class="vc_row wpb_row vc_inner vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner"><div class="wpb_wrapper">
	<div class="wpb_text_column wpb_content_element ">
     <?php 
     $content = get_option('riskwarning');
     if($content!=""): 
        echo nl2br(html_entity_decode($content));
     else: ?>

    <?php if($currentsite == 65 || $currentsite == 67) { ?>
        <div class="wpb_wrapper footer-bottom-risk-warning">
            <p style="text-align: left;" class="make-it-bold">
                The content of this promotion has not been approved by an authorised person within the meaning of the Financial Services and Markets Act 2000. Reliance on this promotion for the purpose of engaging in any investment activity may expose an individual to a significant risk of losing all of the property or other assets invested.
            </p>
            
            <p style="text-align: left;" class="make-it-bold">Investments are not covered by the <a href="https://protected.fscs.org.uk/About-FSCS/" target="_blank">Financial Services Compensation Scheme (FSCS)</a>, your capital is at risk and returns are not guaranteed. Repayment of capital and interest or payment of dividends will be dependent on the success of the organisation’s business model and past performance isn't a reliable indicator of future performance. You should always read the offer document in full before deciding whether or not to invest as it will cover risks specific to an individual investment. You can read more about general risks associated with making these types of investments <a href="/terms-conditions/risk-warning/">here</a>. If you are unsure if any of these investments are right for you, you should contact an Independent Financial Advisor.</p>
        </div>
    <?php }elseif ($currentsite == 75){ ?>
        <div class="wpb_wrapper footer-bottom-risk-warning">
            <!--<p style="text-align: left;">Investors should be aware there are risks to investing in securities and corporate bonds of any company, especially if they are private companies not listed on a Recognised Investment Exchange, and there may be little or no opportunity to sell the securities easily should you need to do so. The value of your Investments can go down as well as up and therefore you may not recover some, or in extreme cases, any of the value of your initial investment.</p>-->
            <p style="text-align: left;" class="make-it-bold">The content of this promotion has not been approved by an authorised person within the meaning of the Financial Services and Markets Act 2000. Reliance on this promotion for the purpose of engaging in any investment activity may expose an individual to a significant risk of losing all of the property or other assets invested.</p>
            <!--<p style="text-align: left;" class="make-it-bold">Please read our full <strong><a href="/terms-conditions/risk-warning/">risk warning</a></strong> before deciding to invest and, if you are still unsure, seek the advice of a regulated financial adviser.
Investments in these securities are NOT covered by the <strong><a href="https://protected.fscs.org.uk/About-FSCS/" target="_blank">Financial Services Compensation Scheme (FSCS) nor would you have access to the Financial Ombudsman Service (FOS).</a></strong></p>-->
            <!--<p style="text-align: left;" class="make-it-bold">Investments offered on this platform are not readily realisable, which means that they may be difficult to sell and you may not get back the full amount invested. Investments are not covered by the <a href="https://protected.fscs.org.uk/About-FSCS/" target="_blank">Financial Services Compensation Scheme (FSCS)</a> and your capital is at risk and returns are not guaranteed. Repayment of capital and interest or payment of dividends will be dependent on the success of the organisation’s business model and past performance isn’t a reliable indicator of future performance. You should always read the offer document in full before deciding whether or not to invest as it will cover risks specific to an individual investment. You can read more about the general risks associated with making these types of investments here. If you are unsure if any of these investments are right for you, you should contact an Independent Financial Adviser.</p>-->
            <p style="text-align: left;" class="make-it-bold">Investments offered on this platform are classed as speculative illiquid securities, which means that they may be difficult to sell and you may not get back the full amount invested. Investments are not covered by the <a href="https://protected.fscs.org.uk/About-FSCS/" target="_blank">Financial Services Compensation Scheme (FSCS)</a>, your capital is at risk and returns are not guaranteed. Repayment of capital and interest or payment of dividends will be dependent on the success of the organisation’s business model and past performance isn't a reliable indicator of future performance. You should always read the offer document in full before deciding whether or not to invest as it will  cover risks specific to an individual investment. You can read more about general risks associated with making these types of investments here. If you are unsure if any of these investments are right for you, you should contact an Independent Financial Advisor.</p>
        </div>
    <?php }else if ($currentsite == 55 || $currentsite == 73 || $currentsite == 86){ ?>
        <div class="wpb_wrapper footer-bottom-risk-warning">
            <p style="text-align: left;" class="make-it-bold">
                You could lose ALL of your money investing in this product. This product is a High Risk Investment and is much riskier than a savings account. ISA eligibility does not guarantee returns or protect you from losses. Investors should be aware there are risks to investing in securities and corporate bonds of any company, especially if they are private companies not listed on a Recognised Investment Exchange, and there may be little or no opportunity to sell the securities easily should you need to do so. The value of your Investments can go down as well as up and therefore you may not recover some, or in extreme cases, any of the value of your initial investment.
            </p>
            <!--<p style="text-align: left;">Investors should be aware there are risks to investing in securities and corporate bonds of any company, especially if they are private companies not listed on a Recognised Investment Exchange, and there may be little or no opportunity to sell the securities easily should you need to do so. The value of your Investments can go down as well as up and therefore you may not recover some, or in extreme cases, any of the value of your initial investment.</p>-->
            <p style="text-align: left;" class="make-it-bold">The content of this promotion has not been approved by an authorised person within the meaning of the Financial Services and Markets Act 2000. Reliance on this promotion for the purpose of engaging in any investment activity may expose an individual to a significant risk of losing all of the property or other assets invested.</p>
            <!--<p style="text-align: left;" class="make-it-bold">Please read our full <strong><a href="/terms-conditions/risk-warning/">risk warning</a></strong> before deciding to invest and, if you are still unsure, seek the advice of a regulated financial adviser.
Investments in these securities are NOT covered by the <strong><a href="https://protected.fscs.org.uk/About-FSCS/" target="_blank">Financial Services Compensation Scheme (FSCS) nor would you have access to the Financial Ombudsman Service (FOS).</a></strong></p>-->
            <!--<p style="text-align: left;" class="make-it-bold">Investments offered on this platform are not readily realisable, which means that they may be difficult to sell and you may not get back the full amount invested. Investments are not covered by the <a href="https://protected.fscs.org.uk/About-FSCS/" target="_blank">Financial Services Compensation Scheme (FSCS)</a> and your capital is at risk and returns are not guaranteed. Repayment of capital and interest or payment of dividends will be dependent on the success of the organisation’s business model and past performance isn’t a reliable indicator of future performance. You should always read the offer document in full before deciding whether or not to invest as it will cover risks specific to an individual investment. You can read more about the general risks associated with making these types of investments here. If you are unsure if any of these investments are right for you, you should contact an Independent Financial Adviser.</p>-->
            <p style="text-align: left;" class="make-it-bold">Investments offered on this platform are classed as speculative illiquid securities, which means that they may be difficult to sell and you may not get back the full amount invested. Investments are not covered by the <a href="https://protected.fscs.org.uk/About-FSCS/" target="_blank">Financial Services Compensation Scheme (FSCS)</a>, your capital is at risk and returns are not guaranteed. Repayment of capital and interest or payment of dividends will be dependent on the success of the organisation’s business model and past performance isn't a reliable indicator of future performance. You should always read the offer document in full before deciding whether or not to invest as it will  cover risks specific to an individual investment. You can read more about general risks associated with making these types of investments here. If you are unsure if any of these investments are right for you, you should contact an Independent Financial Advisor.</p>
        </div>
    <?php }else{ ?>
        <div class="wpb_wrapper footer-bottom-risk-warning">
            <p style="text-align: left;" class="make-it-bold">
                You could lose ALL of your money investing in this product. This product is a High Risk Investment and is much riskier than a savings account. Investors should be aware there are risks to investing in securities and corporate bonds of any company, especially if they are private companies not listed on a Recognised Investment Exchange, and there may be little or no opportunity to sell the securities easily should you need to do so. The value of your Investments can go down as well as up and therefore you may not recover some, or in extreme cases, any of the value of your initial investment.
            </p>
            <!--<p style="text-align: left;">Investors should be aware there are risks to investing in securities and corporate bonds of any company, especially if they are private companies not listed on a Recognised Investment Exchange, and there may be little or no opportunity to sell the securities easily should you need to do so. The value of your Investments can go down as well as up and therefore you may not recover some, or in extreme cases, any of the value of your initial investment.</p>-->
            <p style="text-align: left;" class="make-it-bold">The content of this promotion has not been approved by an authorised person within the meaning of the Financial Services and Markets Act 2000. Reliance on this promotion for the purpose of engaging in any investment activity may expose an individual to a significant risk of losing all of the property or other assets invested.</p>
            <!--<p style="text-align: left;" class="make-it-bold">Please read our full <strong><a href="/terms-conditions/risk-warning/">risk warning</a></strong> before deciding to invest and, if you are still unsure, seek the advice of a regulated financial adviser.
Investments in these securities are NOT covered by the <strong><a href="https://protected.fscs.org.uk/About-FSCS/" target="_blank">Financial Services Compensation Scheme (FSCS) nor would you have access to the Financial Ombudsman Service (FOS).</a></strong></p>-->
            <!--<p style="text-align: left;" class="make-it-bold">Investments offered on this platform are not readily realisable, which means that they may be difficult to sell and you may not get back the full amount invested. Investments are not covered by the <a href="https://protected.fscs.org.uk/About-FSCS/" target="_blank">Financial Services Compensation Scheme (FSCS)</a> and your capital is at risk and returns are not guaranteed. Repayment of capital and interest or payment of dividends will be dependent on the success of the organisation’s business model and past performance isn’t a reliable indicator of future performance. You should always read the offer document in full before deciding whether or not to invest as it will cover risks specific to an individual investment. You can read more about the general risks associated with making these types of investments here. If you are unsure if any of these investments are right for you, you should contact an Independent Financial Adviser.</p>-->
            <p style="text-align: left;" class="make-it-bold">Investments offered on this platform are classed as speculative illiquid securities, which means that they may be difficult to sell and you may not get back the full amount invested. Investments are not covered by the <a href="https://protected.fscs.org.uk/About-FSCS/" target="_blank">Financial Services Compensation Scheme (FSCS)</a>, your capital is at risk and returns are not guaranteed. Repayment of capital and interest or payment of dividends will be dependent on the success of the organisation’s business model and past performance isn't a reliable indicator of future performance. You should always read the offer document in full before deciding whether or not to invest as it will  cover risks specific to an individual investment. You can read more about general risks associated with making these types of investments here. If you are unsure if any of these investments are right for you, you should contact an Independent Financial Advisor.</p>
        </div>
    <?php } ?>
    
     <?php endif;?>   
    </div>
</div></div></div></div></div></div></div></div></div></div>
<div class="social-container">
            <div class="social-left social-background">
            </div>
            <div class="social-right social-background">
            </div>
            <div class="social-overlay">
                <div class="social-icon-container">
                    <!--
                    <div class="social-icon">
                        <a href="https://www.linkedin.com/company/therightcrowd-com" target="_blank">
                            <i class="fa fa-linkedin-square" aria-hidden="true"></i>
                        </a>
                    </div>
                    -->
                    <div class="social-icon">
                        <a href="https://www.facebook.com/The-Right-Crowd-224645671437326/?epa=SEARCH_BOX" target="_blank">
                            <i class="fa fa-facebook-square" aria-hidden="true"></i>
                        </a>        
                    </div>
                    
                    <div class="social-icon">
                        <a href="https://twitter.com/CrowdRight" target="_blank">
                            <i class="fa fa-twitter-square" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <?php global $themeum_options; ?>
    <footer id="footer">
        <div class="container">
            <div class="row">
                <?php dynamic_sidebar('bottom'); ?>
            </div> <!-- end row -->
            <?php if (isset($themeum_options['copyright-en']) && $themeum_options['copyright-en']){?>
                <div class="row copyright text-center">
                    
                        <?php if(isset($themeum_options['copyright-text'])) echo balanceTags($themeum_options['copyright-text']); ?><!--p>All rights reserved <strong>Startup Idea </strong>2014</p-->
                    
                </div>
            <?php } ?>
            </div> <!-- end container -->
        <script type = "text/javascript">
        if (window.location.pathname === '/project/advanced-blast-ballistic-systems-limited/'){
            document.getElementById('page').classList.add('abbs');
            console.log('abbs-if-triggered');
        }
        </script>
    </footer>
    <!-- Custom Page Colors & Background Removal -->

</div> <!-- #page -->
<?php wp_footer(); ?>
</body>
</html>