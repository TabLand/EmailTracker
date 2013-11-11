<?php
	setcookie("notrack","No - Creator's Computer here",time()+(3600*24*365));
	$url = "http://culsusocieties.co.uk/Computing/tracker/parse.php?text=";
	if(isset($_GET["name"])) $name = "&name=" . str_rot13($_GET["name"]);
	else $name = "";
	if(isset($_GET["text"])) echo "&lt;img src=\"$url" . str_rot13($_GET["text"]) . $name . "\"/&gt;";
?>
