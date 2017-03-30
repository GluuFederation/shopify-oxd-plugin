<?php

	/* SHOPIFY CONFIG */
	define('SHOPIFY_API_KEY', 'f2bee041667a2edd64f21054c4190987');
	define('SHOPIFY_SECRET', '84cf04bde78151a0f40aea74e75f8afb');
	define('SHOPIFY_SCOPE', 'read_themes,write_themes,read_script_tags,write_script_tags,read_customers,write_customers');
    $servername = "localhost";
	$username = "root";
	$password = "odz@123";
	$dbname = "oxd-shopify";

	// Create connection
	$conn = new mysqli($servername, $username, $password,$dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	/* simple debug function */
	function debug(&$var) {
		echo "<pre>";
		echo '11';
		print_r($var);
		echo "</pre>";
	}

	/*************************/
	function pr($var){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

	function ebrk($var){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
		die("---||---");
	}
 

