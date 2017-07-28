<?php
	//ob_start();
	include "core/init.php";
	
	$path = ltrim($_SERVER['REQUEST_URI'], '/');
	$page = explode('/', $path);
	$includepocetna = "includes/pages/pocetna/pocetna.php";
	
	include "$pathtoinc/content.php";
?>