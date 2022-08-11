<?php
/*
 * Template Name: AML Verification
 */

if(get_current_user_id()==0):
	header("Location ".site_url());
endif;
get_header();

$postArray = [
					'veriphyServiceTO' => [
						'returnPDF' => true,
						'serviceCode' => 'ROUTE2'
					],
					'authenticationTO' => [
						'username' => 'zee@therightcrowd.com',
						'password' => 'Dexter1!'
					],
					'applicationTO' => [
						'applicants' => [
							[
								'addresses' => [
									[
										"address1"=> "68 Jesmond Road West", //"68 Jesmond Road West",
										"address2"=> "", //"Jesmond",
										"address3"=> "",
										"address4"=> "",
										"postTown"=> "adsdas",
										"county"  => "",
										"postCode"=> "93637",
										"country" => "GB"
									]
								],
								'dateOfBirth' => '1976-04-11',
								'gender' => 'M',
								'names' => [
									[
										"title"=> "Mr",
										"forename"=> "Hello",
										"otherNames"=> "",
										"surname"=> "GUys"
									]
								],
								'driversLicenceTO' => [
									"licenceNumber1"=> 0,
									"licenceNumber2"=> 123456789,
									"licenceNumber3"=> 123456789,
									"licenceNumber4"=> 123456789,
								],
								'bankTO'=>[

								]
							]
						]
					]
				];
				
				$post = json_encode($postArray);
				
				$ch = curl_init('https://test.veriphy.co.uk/api/IDAML');
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

				// execute!
				$response = curl_exec($ch);
				echo $response;
				// close the connection, release resources used
				curl_close($ch);

				// do anything you want with your response
				//echo "<pre>";
				//print_r(json_decode($response));
?>
<section id="main" class="clearfix donate-project">
    <div id="content" class="site-content" role="main">
    	<div class="container">
    		<form id="wpneo-dashboard-form" action="" method="" class="wpneo-form">
				<div class="wpneo-name"><p>Bank:</p></div>
				<div class="wpneo-fields float-right"><input type="text" name="bank" value="" style="border: 1px solid rgb(223, 225, 229);"></div>
				<div class="wpneo-name"><p>Contact:</p></div>
				<div class="wpneo-fields float-right"><input type="text" name="contact" value="" style="border: 1px solid rgb(223, 225, 229);"></div>
				<div class="wpneo-name"><p>ID card:</p></div>
				<div class="wpneo-fields float-right"><input type="text" name="id_card" value="" style="border: 1px solid rgb(223, 225, 229);"></div>
				<div class="wpneo-name"><p>Passport:</p></div>
				<div class="wpneo-fields float-right"><input type="text" name="passport" value="" style="border: 1px solid rgb(223, 225, 229);"></div>
				<div class="wpneo-name"><p>Travel Visa:</p></div>
				<div class="wpneo-fields float-right"><input type="text" name="travel_visa" value="" style="border: 1px solid rgb(223, 225, 229);"></div>
				<input type="submit" name="wp-submit" id="wppb-submit" class="headerbut" value="Submit" style="border:none;">
			</form>
    	</div>
    </div> <!--/#content-->
</section> <!--/#main-->
<?php get_footer();
