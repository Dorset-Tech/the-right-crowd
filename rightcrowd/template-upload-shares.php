<?php
/*
 * Template Name: Upload Shares Customer Page
 */
get_header(); ?>

<script id="cust_shares_fields" type="text/template">
	<div class='sharerow shares-fields-wrapper' id='{{order_number}}'>
	   <div>
		<span class="remove-shares" data-order="{{order_number}}" style="cursor: pointer;">
			Remove
		</span>
	   </div>
	   
		<div class='shr3 shares-field'>
			Number of Units
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
		<div class='shr4 shares-field form-group'>
			Investment Amount
			<input type="date" name="shares_investment_amount[{{order_number}}]" id='shares_investment_amount{{order_number}}'/>
		</div>
	</div>
</script>

<section id="main" class="clearfix">
   <?php get_template_part('lib/sub-header')?>
    <div id="content" class="site-content" role="main">
    	<div class="container">
        <?php /* The loop */ ?>
        <?php while ( have_posts() ): the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
				<?php 
					$comps = getAllCompanies();
				?>
				<div id="cust_shares_section" class="upload-shares-wrapper shareselbox">
					<form action="<?php echo admin_url('admin-post.php?action=cust_upload_shares') ?>" method="post" id="cust-upload-share-form">
						<div class="fieldsWrapper form-group">
							<div class="field">
								<label>
									Select Company:
								</label>
								<select name="company_id" id="company_id">
									<option value="">Select</option>
									<?php if(count($comps) > 0) { 
											foreach($comps as $cID => $cTitle) {
										?>
										<option value="<?php echo $cID ?>"><?php echo $cTitle ?></option>
									<?php } 
									}
									?>
									<option value="other">Other</option>
								</select>
								<?php if(isset($_GET['sharemsg']) && $_GET['sharemsg'] == 'error'): ?>
									<p style="color: red; font-weight: bold;">Some fields are required.</p>
								<?php endif; ?>
							</div><br/><br/>
							<div class="field" id="other_company_name_wrapper">
								<label>
									Enter company name: 
								</label>
								<input type="text" name="other_company_name"  />
							</div>
							<div id="cust_shares_fields_section" class="shareselbox form-group" style="margin-top: 20px;">
								<div class='shr3 shares-field'>
									Number of Units
									<input type="text" name="shares_numbers" id='shares_numbers' />
								</div><br/>
								<div class='shr3 shares-field form-group'>
									Unit Price
									<input type="text" name="shares_purchase_price" id='shares_purchase_price'/>
									<em>in Pence (p)</em>
								</div><br/>
								<div class='shr4 shares-field form-group'>
									Holdings Type <em>*</em>
									<select name="holding_type" id="holding_type">
										<option value="shares">Shares</option>
										<option value="bond">Bond</option>
										<option value="equity">Equity</option>
										<option value="coins">Coins</option>
									</select>
								</div>
								<div class='shr4 shares-field form-group'>
									Ownership Total
									<input type="text" name="shares_investment_amount" id='shares_investment_amount'/>
									<em>in GBP</em>
								</div>
							</div>
							<!-- <a href="javascript:void(0);" id="cust_add_shares_fields">Add More</a> -->
						</div>
						<button type="submit">Submit</button>
					</form>
					<?php if(isset($_GET['sharemsg']) && $_GET['sharemsg'] == 'success'): ?>
						<p style="color: green; font-weight: bold;">Your share units are uploaded successfully.</p>
					<?php endif; ?>
				</div>
            </div>

            <?php // comment_template(); ?>

        <?php endwhile; ?>
        </div> <!--/.container-->
    </div> <!--/#content-->
</section> <!--/#main-->
<style>
#other_company_name_wrapper {
	display: none;
}
</style>
<script>
shares_order = 1;
jQuery(function($){
	
	$('#company_id').on('change',function(){
		var v = $(this).val();
		if(v === 'other') {
			$('#other_company_name_wrapper').show();
		}else{
			$('#other_company_name_wrapper').hide();
		}
	});
	
	$('#cust_shares_section').on('click','#cust_add_shares_fields',function(){
		var sharesFieldsHtml = $('#cust_shares_fields').html();
		sharesFieldsHtml = sharesFieldsHtml.replace(/{{order_number}}/g, shares_order);
		$('#cust_shares_fields_section').append(sharesFieldsHtml); 
		shares_order++;
	});
	
	// remove
	$('#cust_shares_section').on('click','.remove-shares',function(){
		var orderNo = $(this).attr('data-order');
		$('#'+orderNo).remove(); 
	});
	
	
});

</script>
<?php get_footer();
