<?php
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

define('clientID', 'cda6ee6664f9482e8b14c0695678c53a'); 
define('clientSecret', '31ae98225a2040308165d350229869c8'); 
define('redirectURI', 'http://localhost/instagram/index.php'); 
define('ImageDirectory', 'pics/');

function connectToInstagram($url){
	$ch = curl_init();

	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => $url,
		));
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function getUserID($userName){
	$url = 'https://api.instagram.com/v1/users/search?q='.$userName. '&client_id='.clientID;
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);

	return $results['data'][0]['id'];
}

function printImages($userID){
	$url = 'https://api.instagram.com/v1/users/'. $userID . '/media/recent?client_id='.clientID.'&count=10';
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);
	// require_once(__DIR__ . "../view/header.php");
	foreach ($results['data'] as $items){
		$image_url = $items['images']['low_resolution']['url'];
		echo '<img id="pics" src=" '.$image_url.' "/><br/>';
		savePictures($image_url);
	}	
// require_once(__DIR__ . "../view/footer.php");
}
function savePictures($image_url){
	$filename = basename($image_url); 
		echo "<form action='$image_url'> <input type='submit' value='Fullscreen'></form>". '<br>';
	$destination = ImageDirectory . $filename;
		file_put_contents($destination, file_get_contents($image_url));
}
if (isset($_GET['code'])){
	$code = $_GET['code'];
	$url = 'https://api.instagram.com/oauth/access_token';
	$access_token_settings = array('client_id' => clientID,
									'client_secret' => clientSecret,
									'grant_type' => 'authorization_code',
									'redirect_uri' => redirectURI,
									'code' => $code
									);

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_settings);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($curl);

curl_close($curl);

$results = json_decode($result, true);

$userName = $results['user']['username'];

$userID = getUserID($userName);

printImages($userID);
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
		<title>Instagram</title>
		<link rel="stylesheet" type="text/css" href="css/style.css"> 
		<link rel="author" href="humans.txt">
	</head>
	<body>

	<a href="https:api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">Login</a>
	<script type="text/javascript" src="js/main.js"></script>
	</body>
</html>
<?php
}
?>