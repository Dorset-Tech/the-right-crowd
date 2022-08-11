<?php
/*
 * Template Name: Upload Document Page
 */
get_header(); ?>
<section id="main" class="clearfix">
	
	This is demo
<?php
//if(isset($_POST['id_number'])) {
			// 	// set post fields
			// 	$postArray = [
			// 		'veriphyServiceTO' => [
			// 			'returnPDF' => true,
			// 			'serviceCode' => 'IDAMLLITE'
			// 		],
			// 		'authenticationTO' => [
			// 			'username' => 'zee@therightcrowd.com',
			// 			'password' => 'Dexter1!'
			// 		],
			// 		'applicationTO' => [
			// 			'applicants' => [
			// 				[
			// 					'addresses' => [
			// 						[
			// 							"address1"=> $billing_address_1, //"68 Jesmond Road West",
			// 							"address2"=> $billing_address_2, //"Jesmond",
			// 							"address3"=> "",
			// 							"address4"=> "",
			// 							"postTown"=> $billing_city,
			// 							"county"=> "",
			// 							"postCode"=> $billing_postcode,
			// 							"country"=> "GB"
			// 						]
			// 					],
			// 					'dateOfBirth' => '1976-04-11',
			// 					'gender' => 'M',
			// 					'names' => [
			// 						[
			// 							"title"=> "Mr",
			// 							"forename"=> $first_name,
			// 							"otherNames"=> "",
			// 							"surname"=> $last_name
			// 						]
			// 					],
			// 					'driversLicenceTO' => [
			// 						"licenceNumber1"=> $_POST['id_number'],
			// 						"licenceNumber2"=> $_POST['id_number'],
			// 						"licenceNumber3"=> $_POST['id_number'],
			// 						"licenceNumber4"=> $_POST['id_number'],
			// 					]
			// 				]
			// 			]
			// 		]
			// 	];
				
			// 	$post = json_encode($postArray);
				
			// 	$ch = curl_init('https://test.veriphy.co.uk/api/IDAML');
			// 	curl_setopt($ch, CURLOPT_POST, 1);
			// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

			// 	// execute!
			// 	$response = curl_exec($ch);
			// 	echo $response;
			// 	// close the connection, release resources used
			// 	curl_close($ch);

			// 	// do anything you want with your response
			// 	echo "<pre>";
			// 	print_r($response);exit;
			// }
?>
</section> <!--/#main-->
<?php get_footer();
