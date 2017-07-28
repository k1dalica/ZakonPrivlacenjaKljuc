<?php
	session_start();
	include 'database/connect.php';
	include 'funcs/funcs.php';
	
	$website_url = "http://www.zakonprivlacenjakljuc.com";
	$website_name = "Zakon Privlačenja Ključ";
	
	$pathtoinc = "includes/";
	$pathtoimgs = "includes/images/";
	
	$dateandtime = date("H:i d.m.Y");
	
	if(loggedin()) {
		$u_id = loggedin();
		$u_username = get_users_data($u_id,'username');
		$u_name = get_users_data($u_id,'name');
		$u_email = get_users_data($u_id,'email');
		$u_avatar = get_users_data($u_id,'avatar');
		$u_admin = get_users_data($u_id,'admin');
		$u_obavestenja = get_users_data($u_id,'obavestenja');
		$u_sub = get_users_data($u_id,'subscribed');
		
		update_user($u_id);
	}
	count_visit($u_id);
	not_online_anymore();
?>