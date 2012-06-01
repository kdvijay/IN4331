<?php
// PHP Proxy for Web services. 
// Responds to both HTTP GET and POST requests

define ('HOSTNAME', 'http://localhost:8899/');

// Get the REST call path from the AJAX application
// Is it a POST or a GET?
$path = ($_POST['ws_path']) ? $_POST['ws_path'] : $_GET['ws_path'];
$url = HOSTNAME.$path;

// Open the Curl session
$session = curl_init();
curl_setopt($session, CURLOPT_URL, $url);

// If it's a POST, put the POST data in the body
if ($_POST['ws_path']) {
	$postvars = '';
	while ($element = current($_POST)) {
		$postvars .= urlencode(key($_POST)).'='.urlencode($element).'&';
		next($_POST);
	}
	curl_setopt ($session, CURLOPT_POST, true);
	curl_setopt ($session, CURLOPT_POSTFIELDS, $postvars);
}

// Don't return HTTP headers. Do return the contents of the call
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// Make the call
$xml = curl_exec($session);

// The web service returns XML. Set the Content-Type appropriately
header("Content-Type: text/xml");

echo $xml;
curl_close($session);

?>