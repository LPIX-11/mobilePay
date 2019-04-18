<?php
	
	/**
	 * @author Mohamed Johnson
	 * Security Issues Fixed
	 * 04/2019
	 */

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');

	$merchant_uid = 'TQHVXfJT8fYBDy4kf5s8O1KNwKh2';            // Replace with your merchant_uid
	$merchant_public_key = 'pk_test_bupCDtHGt0WcsqE7'; // Replace with your merchant_public_key !!!
	$merchant_secret = 'sk_test_V0fmtvV7C8yvQ4SHlMaISfAGDxOAkuYH3zqSQxNzbbBe';                       // Replace with your merchant_private_key !!!
	
	$transaction_uid = '';// create an empty transaction_uid
	$transaction_token  = '';// create an empty transaction_token
	$transaction_provider_name  = ''; // create an empty transaction_provider_name
	$transaction_confirmation_code  = ''; // create an empty confirmation code
	
	if(isset(filter_input(INPUT_GET, 'transaction_uid'))) {
		$transaction_uid = filter_input(INPUT_GET, 'transaction_uid'); // Get the transaction_uid posted by the payment box
	}
	
	if(isset(filter_input(INPUT_GET, 'transaction_token'))) {
		$transaction_token  = filter_input(INPUT_GET, 'transaction_token'); // Get the transaction_token posted by the payment box
	}
	
	if(isset(filter_input(INPUT_GET, 'transaction_provider_name'))) {
		$transaction_provider_name  = filter_input(INPUT_GET, 'transaction_provider_name'); // Get the transaction_provider_name posted by the payment box
	}
	
	if(isset(filter_input(INPUT_GET, 'transaction_confirmation_code'))) {
		$transaction_confirmation_code  = filter_input(INPUT_GET, 'transaction_confirmation_code'); // Get the transaction_confirmation_code posted by the payment box
	}
	$url = 'https://www.wecashup.com/api/v2.0/merchants/'. $merchant_uid . '/transactions/' . $transaction_uid . '?merchant_public_key=' . $merchant_public_key;

	// If you want to print out the url
	// echo $url;

	//Steps 7 : You must complete this script at this to save the current transaction in your database.
	/* Provide a table with at least 5 columns in your database capturing the following
	/  transaction_uid | transaction_confirmation_code| transaction_token| transaction_provider_name | transaction_status */


	//Step 8 : Sending data to the WeCashUp Server

	$fields = array(
		'merchant_secret' => urlencode($merchant_secret),
		'transaction_token' => urlencode($transaction_token),
		'transaction_uid' => urlencode($transaction_uid),
		'transaction_confirmation_code' => urlencode($transaction_confirmation_code),
		'transaction_provider_name' => urlencode($transaction_provider_name),
		'_method' => urlencode('PATCH')
	);

	foreach($fields as $key=>$value) { 
		$fields_string .= $key.'='.$value.'&'; 

	}
	
	rtrim($fields_string, '&');
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	//Step 9  : Retrieving the WeCashUp Response

	$server_output = curl_exec ($ch);

	// echo $server_output;

	curl_close ($ch);

	$data = json_decode($server_output, true);

	if($data['response_status'] == "success") {
		
		//Do wathever you want to tell the user that it's transaction succeed or redirect him/her to a success page
		// echo "Success";
		print_r($data);
		// $location = 'https://www.wecashup.cloud/cdn/tests/websites/PHP/responses_pages/success.html';
		
		///
	} else {
		
		//Do wathever you want to tell the user that it's transaction failed or redirect him/her to a failure page
		
		// $location = 'https://www.wecashup.cloud/cdn/tests/websites/PHP/responses_pages/failure.html';
	}

	//redirect to your feedback page
	// echo '<script>top.window.location = "'.$location.'"</script>';
?>

