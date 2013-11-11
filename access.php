<?php
	//don't track users who use create and view trackers
	setcookie("notrack","No - Creator's Computer here",time()+ (3600*24*365));
	//do logging stuff here
	$file = 'access.html';
	// Open the file to get existing content
	$current = file_get_contents($file);
	echo $current;
?>
