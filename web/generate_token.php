<?php

// Get our helper functions
require_once("inc/functions.php");
require_once("inc/conn.php");

// Set variables for our request
$api_key = "d57f15768824d413b02e4e7f7e614be4";
$shared_secret = "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";
$params = $_GET; // Retrieve all request parameters
$hmac = $_GET['hmac']; // Retrieve HMAC request parameter

$params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
ksort($params); // Sort params lexographically

//echo $GLOBALS['store_name'] = $params['shop'];
$computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);

// Use hmac data to check that the response is from Shopify or not
if (hash_equals($hmac, $computed_hmac)) {

	// Set variables for our request
	$query = array(
		"client_id" => $api_key, // Your API key
		"client_secret" => $shared_secret, // Your app credentials (secret key)
		"code" => $params['code'] // Grab the access key from the URL
	);

	// Generate access token URL
	$access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";

	// Configure curl client and execute request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $access_token_url);
	curl_setopt($ch, CURLOPT_POST, count($query));
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
	$result = curl_exec($ch);
	curl_close($ch);

	// Store the access token
	$result = json_decode($result, true);
	$access_token = $result['access_token'];
	// Show the access token (don't do this in production!)
	$GLOBALS['token'] = $access_token;

	$sql_shop = "INSERT INTO shop (access_token,shop, status)
    VALUES ('".$access_token."','".$params['shop']."', '0')";
   	$result_shop = mysqli_query($conn,$sql_shop);

    $sql_announcement_bar = "INSERT INTO announcement_bar (shop)
    VALUES ('".$params['shop']."')";
   	$result_announcement_bar = mysqli_query($conn,$sql_announcement_bar);
   	
   /*if(! $result_announcement_bar ) {
      die('Could not enter data -bar: ' . mysqli_error());
   }*/
   //echo "Entered data successfully\n";

} else {
	// Someone is trying to be shady!
	die('This request is NOT from Shopify!');
}
