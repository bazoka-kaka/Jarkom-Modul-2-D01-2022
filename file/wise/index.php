<?php
	if($_SERVER['REQUEST_URI'] == '/index.php/home' || $_SERVER['REQUEST_URI'] == '/home' || $_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == '/') readfile('home.html');
	else http_response_code(404);
?>