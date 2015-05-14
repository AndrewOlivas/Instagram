<?php
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

define('clientID', '286f5d5cab964acea2333b38249165d2'); 
define('clientSecret', '60bc0c4f4b3a4c3a97583145f696ede2'); 
define('redirectURI', 'https://localhost:8888/appacademyapi/index.php'); 
define('ImageDirectory', 'pics/');

if (isset($_GET['code'])){
	$code = ($_GET['code']);
	$url = 'https://api.instagram.com/oauth/access_token';
	$access_token_settings = array('client_id' => clientID,
									'cliend_secret' => clientSecret,
									'grant_type' => 'authorization_code',
									'redirect_uri' => redirectURI,
									'code' => $code
									);

	$curl = curl_init($url);
	curl_init($curl, CURLOPT_POST, true);
	curl_init($curl, CURLOPT_POSTFIELDS, $access_token_settings);
	curl_init($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_init($curl, CURLOPT_SSL_VERIFYPEER, false );

$result = curl_exec($curl);
curl_close($curl);

$result = json_decode($result, true);
echo $results['user']['username'];
}
else {
?>

<!-- CLIENT INFO
CLIENT ID	cda6ee6664f9482e8b14c0695678c53a
CLIENT SECRET	31ae98225a2040308165d350229869c8
WEBSITE URL	http://localhost;8888/instagram/index.php
REDIRECT URI	http://localhost;8888/instagram/index.php -->

<html>
	<head>
		<meta charset="utf-8">
		<meta name="decription" context="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Untitled</title>
		<link rel="stylesheet" type="text/css" href="css/style.css"> 
		<link rel="author" href="humans.txt">
	</head>
	<body>

	<a href="https:api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">Login</a>
	<script type="text/javascript" src="js/main.js"></script>
	</body>
</html>