<?php

// Set variables for our request
$shop = $_GET['shop'];
$api_key = "d57f15768824d413b02e4e7f7e614be4";
//read_orders,write_products,
$scopes = "read_themes,write_themes,write_script_tags";
$redirect_uri = "https://3ba74bc9.ngrok.io/shopify/custom_apps/core/generate_token.php";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop . ".myshopify.com/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

// Redirect
header("Location: " . $install_url);
die();