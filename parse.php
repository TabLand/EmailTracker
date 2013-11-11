<?php

date_default_timezone_set('GMT');

//get logging time
$logtime = date('D j M Y h:i:s a', time()) . " GMT";

//get visitors IP address
$clientIP = $_SERVER['REMOTE_ADDR'];

if(isset($_GET["text"])){
	$topic = str_rot13($_GET["text"]);
	$text = "CompSocks Email Tracker - $topic . Consider this emails' acknowledgement of receipt logged. Your IP address $clientIP has also been logged. ";
}
else {
	$text = "Unauthorised use of CompSocks email acknowledgement tracker. Your IP " . $clientIP . " has been logged. ";
	$topic = "unspecified topic";	
}
$name = "";
if(isset($_GET["name"])) {
	$text .= "We have also created a cookie on your computer for future identification. ";
	$actname = str_rot13($_GET["name"]);
	if(!isset($_COOKIE["name"])) {
		setcookie("name", str_rot13($_GET["name"]), time()+(3600*24*365));
		$name = "Cookie[\"name\"] was not set. Just sent a cookie. Get[\"name\"] was set to " . str_rot13($_GET["name"]);

	}
	else {
		$name = "Cookie[\"name\"] was set to "  . $_COOKIE["name"] . " and Get[\"name\"] was set to " . str_rot13($_GET["name"]);
	}
}
else {
	if(isset($_COOKIE["name"])) {
		$name = "Cookie[\"name\"] was set to "  . $_COOKIE["name"] . " and Get[\"name\"] was not set. ";
		$actname = $_COOKIE["name"];
	}
	else $name = "Cookie[\"name\"] was not set. Get[\"name\"] was not set. ";
}

$text .= " Logging time is " . $logtime . " GMT. If you believe that your name is not " . $actname . " then please email computingsoc@city.ac.uk at once so we may flag up an invalid email receipt log record. ";

//do logging stuff here
$file = 'access.html';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$placeholder = "<!-- New row goes in here! -->";
$useragent = $_SERVER['HTTP_USER_AGENT'];

if(!isset($_COOKIE["notrack"])){
	$notrack = "Yes!";
}
else $notrack = $_COOKIE["notrack"];
//IP API for easier location based log viewing..
$newValues = "\n\t\t\t<tr>\n\t\t\t\t<td>$logtime</td>\n\t\t\t\t<td>$topic</td>\n\t\t\t\t<td>".str_rot13($_GET["name"])."</td>\n\t\t\t\t<td>".$_COOKIE["name"]."</td>\n\t\t\t\t<td><a href=\"http://ip-api.com/$clientIP\">$clientIP</a></td>\n\t\t\t\t<td>$useragent</td>\n\t\t\t\t<td>$notrack</td>\n\t\t\t</tr>";
$current = str_replace($placeholder, $placeholder . $newValues, $current);
// Write the contents back to the file
file_put_contents($file, $current);

//sendmail($actname, $logtime, $clientIP,$topic);

$textlength = strlen($text);
//only writing lines of 74 characters or less
$text = wordwrap($text, 74, "\r\n");
$texts = explode("\r\n", $text);

// Create a blank image and add some text
$im = imagecreatetruecolor(450, count($texts)*12);
$text_color = imagecolorallocate($im, 0, 255, 0);
$count = 0;
//for each line of text
foreach($texts as $t){
	//we will print a new row in the image
	imagestring($im, 2, 6, $count*11,  $t, $text_color);
	$count++;
}

// Set the content type header - in this case image/jpeg
header('Content-Type: image/jpeg');

// Output the image
imagejpeg($im);

// Free up memory
imagedestroy($im);
?>
