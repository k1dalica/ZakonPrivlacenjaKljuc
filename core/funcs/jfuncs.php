<?php
	include '../database/connect.php';
	include 'funcs.php';
	session_start();
	$a = $_POST['action'];
	$uid = loggedin();
	$dateandtime = date("H:i d.m.Y");
	$dnt = date('Y-m-d H:i:s');
	$url = "http://zakonprivlacenjakljuc.com";
	
	ini_set("SMTP","mail.reikonkarate.com");
	ini_set('smtp_port', 587);
	ini_set('sendmail_from', "office@zakonprivlacenjakljuc.com");
	$email = "office@zakonprivlacenjakljuc.com";
	$headers = "From: Zakon Privlačenja Ključ <$email> \r\n" .
		"Reply-To: $email" . "\r\n" .
		"Content-Type: text/html; charset=UTF-8\r\n";
	
	if($a=="loadingpercentage") {
		$done = $_POST['done'];
		$tasks = $_POST['tasks'];
		
		$percentpertask = 100 / $tasks;
		$percent = $done * $percentpertask;
		echo round($percent);
	}
	if($a=="markmsgsas") {
		$seen = $_POST['seen'];
		$mid = $_POST['mid'];
		mysql_query("UPDATE `poruke` SET `seen`='$seen' WHERE `id`='$mid'");
	}
	if($a=="editvideo") {
		$id = $_POST['id'];
		$vid = $_POST['vid'];
		$title = $_POST['title'];
		$text = $_POST['text'];
		
		if(!empty($title)&&yt_exists($vid)) {
			mysql_query("UPDATE `video` SET `url`='$vid', `title`='$title', `desc`='$text' WHERE `id`='$id'");
		}
	}
	if($a=="videoattachment") {
		$vid = $_POST['vid'];
		if(yt_exists($vid)) {
			echo $vid;
		} else {
			echo "error";
		}
	}
	if($a=="dodajvideo") {
		$vid = $_POST['vid'];
		$title = $_POST['title'];
		$text = $_POST['text'];
		if(yt_exists($vid)) {
			mysql_query("INSERT INTO `video` VALUES('','$title','$desc','$dateandtime','$vid')");
			$oid = mysql_insert_id();
			send_obavestenje("3", $oid, $title, $id);
			echo "succ";
		} else {
			echo "error";
		}
	}
	if($a=="obrisiobavestenje") {
		$oid = $_POST['oid'];
		mysql_query("DELETE FROM `obavestenja` WHERE `id`='$oid'");
	}
	if($a=="deletedogadjaj") {
		$did = $_POST['did'];
		mysql_query("DELETE FROM `dogadjaji` WHERE `id`='$did'");
		mysql_query("DELETE FROM `obavestenja` WHERE `oid`='$did'");
	}
	if($a=="obrisivideo") {
		$vid = $_POST['vid'];
		mysql_query("DELETE FROM `video` WHERE `id`='$vid'");
		mysql_query("DELETE FROM `obavestenja` WHERE `oid`='$vid'");
	}
	if($a=="obrisiporuku") {
		$mid = $_POST['mid'];
		mysql_query("UPDATE `poruke` SET `deleted`='1' WHERE `id`='$mid'");
		//mysql_query("DELETE FROM `reply` WHERE `pid`='$did'");
	}
	if($a=="todaydate") {
		$d = time();
		$m = date('m',$d);
		$y = date('Y',$d);
		$did = $_POST['did'];
		display_calendar($m, $y, $did);
	}
	if($a=="nextdate") {
		$month = $_POST['month'];
		$year = $_POST['year'];
		$did = $_POST['did'];
		if($month==12) {
			$m = 1;
			$y = $year + 1;
		} else {
			$m = $month + 1;
			$y = $year;
		}
		if($m<10) {
			$m = "0".$m;
		}
		display_calendar($m, $y, $did);
	}
	
	if($a=="prevdate") {
		$month = $_POST['month'];
		$year = $_POST['year'];
		$did = $_POST['did'];
		if($month==1) {
			$m = 12;
			$y = $year - 1;
		} else {
			$m = $month - 1;
			$y = $year;
		}
		if($m<10) {
			$m = "0".$m;
		}
		display_calendar($m, $y, $did);
	}
	
	if($a=="izmeniobjavu") {
		$oid = $_POST['oid'];
		$title = $_POST['title'];
		$text = $_POST['text'];
		mysql_query("UPDATE `objave` SET `title`='$title',`text`='$text' WHERE `id`='$oid'");
		
		$q = mysql_query("SELECT * FROM `links` WHERE `oid`='$oid'");
		$url = mysql_result($q,0,'url');
		echo "/objava/$url/";
	}
	if($a=="obrisiobjavu") {
		$oid = $_POST['oid'];
		$uadmin = get_users_data($uid,'admin');
		if(loggedin()&&$uadmin>=1) {
			$q = mysql_query("SELECT * FROM `objave` WHERE `id`='$oid'");
			$img = mysql_result($q,0,'cover');
			unlink("../../$img");
			mysql_query("DELETE FROM `objave` WHERE `id`='$oid'");
			mysql_query("DELETE FROM `links` WHERE `oid`='$oid'");
			mysql_query("DELETE FROM `obavestenja` WHERE `oid`='$oid'");
		}
	}
	if($a=="switchbtn") {
		$val = $_POST['val'];
		$bool  = $_POST['bool'];
		if($val=="objemail") {
			mysql_query("UPDATE `users` SET `subscribed`='$bool' WHERE `id`='$uid'");
		} else if($val=="confirmreg") {
			mysql_query("UPDATE `settings` SET `value2`='$bool' WHERE `value1`='confirm_reg'");
		} else if($val=="objsajt") {
			mysql_query("UPDATE `users` SET `obavestenja`='$bool' WHERE `id`='$uid'");
		}
	}
	if($a=="obrisidndsliku") {
		$path = $_POST['value'];
		unlink("../../$path");
	}
	if($a=="newpost") {
		$page = $_POST['page'];
		$image = $_POST['image'];
		$text = nl2br($_POST['text']);
		$title = $_POST['title'];
		$img = "";
		$newdate = date('Y-m-d H:i:s');
		if(!empty($image)&&is_image($image)) {
			$img = $image;
		}
		
		mysql_query("INSERT INTO `objave` VALUES('','$title','$text','$dateandtime','$newdate','$img','$uid','$page','0')");
		$oid = mysql_insert_id();
		$title2 = titletourlconverter($title);
		mysql_query("INSERT INTO `links` VALUES('','$oid','$title2','1')");
		send_obavestenje($page, $oid, $title, $title2);
		echo "/objava/$title2/";
	}
	if($a=="posaljiaktivacionikod") {
		$email = $_POST['email'];
		$q = mysql_query("SELECT * FROM `users` WHERE `email`='$email'");
		if(mysql_num_rows($q)!=0) {
			$ver = mysql_result($q, 0,'verified');
			if($ver==0) {
				$name = mysql_result($q,0,'name');
				$username = mysql_result($q,0,'username');
				$code = mysql_result($q,0,'code');
				$subject = "Zakon Privlačenja Ključ - Aktivacioni kod";
				$txt = "<center>
					<div style='width: 600px; padding: 10px; background-color: #fff; border: 1px solid #e3e3e3; box-sizing: border-box; color: #5b5b5b;'>
						<div style='font-size: 35px; font-family: Arial Black; font-weight: bold; border-bottom: 1px solid #e3e3e3; padding-bottom: 10px;'>
							<img src='http://zakonprivlacenjakljuc.com/includes/images/email.jpg' width='100%'>
						</div><div style='text-align: center; padding-top: 20px; font-family: Arial;'>
							<span style='font-size: 18px; display: block; margin-bottom: 10px;'>Pozdrav <b>$name</b></span>
							
							<center>
								<span style='display: block; margin-top: 80px;'>Ova poruka je poslata na osnovu zahteva ponovnog slanja aktivacionog koda.<br>Da bi aktivirali vaš nalog potrebno je kliknuti na dugme ispod.</span>
								<a target='_blank' href='$url/registracionikod/$code/' style='color: #fff; text-decoration: none;'><div style='margin-top: 20px; text-align: center; color: #fff; text-decoration: none; width: 170px; height: 40px; line-height: 40px; color: #fff; background-color: #0fa8ef; cursor: pointer;'>Aktiviraj nalog</div></a>
							</center>
							
							<span style='margin-top: 80px; display: block;'><h2 style='margin: 0px;'>Zakon Privlačenja Ključ</h2></span>
							<span><a target='_blank' href='$url' style='color: #0fa8ef; text-decoration: none;'>www.zakonprivlacenjakljuc.com</a></span>
							
							<div style='margin-top: 10px; color: #5b5b5b;'>
								<i>U slučaju da ne želite više da primate obaveštenja sa sajta kliknite <a target='_blank' href='$url/unsubscribe/$code/' style='color: #0fa8ef !important; text-decoration: none; cursor: pointer; font-weight: bold;'>ovde</a></i>
							</div>
						</div>
					</div>
				</center>";		
				mail($email,$subject,$txt,$headers);
				echo "succ";
			} else {
				echo "error2";
			}
		} else {
			echo "error1";
		}
	}
	if($a=="posaljizabloz") {
		$email = $_POST['email'];
		$q = mysql_query("SELECT * FROM `users` WHERE `email`='$email'");
		if(mysql_num_rows($q)!=0) {
			$name = mysql_result($q,0,'name');
			$username = mysql_result($q,0,'username');
			$pw = mysql_result($q,0,'password');
			$code = mysql_result($q,0,'code');
			$subject = "Zakon Privlačenja Ključ - Zaboravljena lozinka";
			$txt = "<center>
					<div style='width: 600px; padding: 10px; background-color: #fff; border: 1px solid #e3e3e3; box-sizing: border-box; color: #5b5b5b;'>
						<div style='font-size: 35px; font-family: Arial Black; font-weight: bold; border-bottom: 1px solid #e3e3e3; padding-bottom: 10px;'>
							<img src='http://zakonprivlacenjakljuc.com/includes/images/email.jpg' width='100%'>
						</div><div style='text-align: center; padding-top: 20px; font-family: Arial;'>
							<span style='font-size: 18px; display: block; margin-bottom: 10px;'>Pozdrav <b>$name</b></span>
							
							<center>
								<span style='display: block; margin-top: 80px;'>Ova poruka je poslata na osnovu zahteva zaboravljene lozinke.<br /><br />
								<font>Vaše korisničko ime: <b>$username</b></font><br />
								<font>Vaša lozinka: <b>$pw</b></font>
							</span>
								
							</center>
							
							<span style='margin-top: 80px; display: block;'><h2 style='margin: 0px;'>Zakon Privlačenja Ključ</h2></span>
							<span><a target='_blank' href='$url' style='color: #0fa8ef; text-decoration: none;'>www.zakonprivlacenjakljuc.com</a></span>
							
							<div style='margin-top: 10px; color: #5b5b5b;'>
								<i>U slučaju da ne želite više da primate obaveštenja sa sajta kliknite <a target='_blank' href='$url/unsubscribe/$code/' style='color: #0fa8ef !important; text-decoration: none; cursor: pointer; font-weight: bold;'>ovde</a></i>
							</div>
						</div>
					</div>
				</center>";			
			mail($email,$subject,$txt,$headers);
			echo "succ";
		} else {
			echo "error";
		}
	}
	if($a=="register") {
		$reguname = $_POST['reguname'];
		$regname = $_POST['regname'];
		$regemail = $_POST['regemail'];
		$regpw = $_POST['regpw'];
		$q1 = mysql_query("SELECT * FROM `users` WHERE `username`='$reguname'");
		$q2 = mysql_query("SELECT * FROM `users` WHERE `email`='$regemail'");
		$q3 = mysql_query("SELECT * FROM `settings` WHERE `value1`='confirm_reg'");
		$confirm_reg = mysql_result($q3,0,'value2');
		if(empty($reguname)||empty($regname)||empty($regemail)||empty($regpw)) {
			echo "error1";
		} else if(mysql_num_rows($q1)!=0) {
			echo "error3";
		} else if(!filter_var($regemail, FILTER_VALIDATE_EMAIL)) {
			echo "error2";
		} else if(mysql_num_rows($q2)!=0) {
			echo "error4";
		} else if(strlen($regpw)<6) {
			echo "error5";
		} else {
			$date = date("d.m.Y");
			$uip = GetIP();
			$charset = "asdfghjklzxcvbnmqwertyuiopASDFGHJKLZXCVBNMQWERTYUIOP0123456789";
			$code = substr(str_shuffle($charset), 0, 10);
			if($confirm_reg=="0") {
				$ver = 1;
				$txt = "<center>
					<div style='width: 600px; padding: 10px; background-color: #fff; border: 1px solid #e3e3e3; box-sizing: border-box; color: #5b5b5b;'>
						<div style='font-size: 35px; font-family: Arial Black; font-weight: bold; border-bottom: 1px solid #e3e3e3; padding-bottom: 10px;'>
							<img src='http://zakonprivlacenjakljuc.com/includes/images/email.jpg' width='100%'>
						</div><div style='text-align: center; padding-top: 20px; font-family: Arial;'>
							<span style='font-size: 18px; display: block; margin-bottom: 10px;'>Pozdrav <b>$regname</b></span>
							
							<center>
								<span style='display: block; margin-top: 80px;'>Uspešno ste se registrovali na sajtu Zakon Privlačenja Ključ.<br /><br />
								Vaše korisničko ime: <b>$reguname</b><br />
								Vaša lozinka: <b>$regpw</b></span>
								</center>
							
							<span style='margin-top: 80px; display: block;'><h2 style='margin: 0px;'>Zakon Privlačenja Ključ</h2></span>
							<span><a target='_blank' href='$url' style='color: #0fa8ef; text-decoration: none;'>www.zakonprivlacenjakljuc.com</a></span>
							
							<div style='margin-top: 10px; color: #5b5b5b;'>
								<i>U slučaju da ne želite više da primate obaveštenja sa sajta kliknite <a target='_blank' href='$url/unsubscribe/$code/' style='color: #0fa8ef !important; text-decoration: none; cursor: pointer; font-weight: bold;'>ovde</a></i>
							</div>
						</div>
					</div>
				</center>";
			} else {
				$ver = 0;
				$txt = "<center>
					<div style='width: 600px; padding: 10px; background-color: #fff; border: 1px solid #e3e3e3; box-sizing: border-box; color: #5b5b5b;'>
						<div style='font-size: 35px; font-family: Arial Black; font-weight: bold; border-bottom: 1px solid #e3e3e3; padding-bottom: 10px;'>
							<img src='http://zakonprivlacenjakljuc.com/includes/images/email.jpg' width='100%'>
						</div><div style='text-align: center; padding-top: 20px; font-family: Arial;'>
							<span style='font-size: 18px; display: block; margin-bottom: 10px;'>Pozdrav <b>$regname</b></span>
							
							<center>
								<span style='display: block; margin-top: 80px;'>Uspešno ste se registrovali na sajtu Zakon Privlačenja Ključ.<br />
								Ostao vam je još jedan korak kako bi u potpunsti završili registraciju. Sve šta treba da uradite jeste da kliknete da dugme ispod i samim tim će te potvrditi registraciju.</span>
								
								<a target='_blank' href='$url/registracionikod/$code/' style='color: #fff; text-decoration: none;'><div style='margin-top: 20px; margin-bottom: 20px; text-align: center; color: #fff; text-decoration: none; width: 170px; height: 40px; line-height: 40px; color: #fff; background-color: #0fa8ef; cursor: pointer;'>Aktiviraj nalog</div></a>
								
								Vaše korisničko ime: <b>$reguname</b><br />
								Vaša lozinka: <b>$regpw</b>
							</center>
							
							<span style='margin-top: 80px; display: block;'><h2 style='margin: 0px;'>Zakon Privlačenja Ključ</h2></span>
							<span><a target='_blank' href='$url' style='color: #0fa8ef; text-decoration: none;'>www.zakonprivlacenjakljuc.com</a></span>
							
							<div style='margin-top: 10px; color: #5b5b5b;'>
								<i>U slučaju da ne želite više da primate obaveštenja sa sajta kliknite <a target='_blank' href='$url/unsubscribe/$code/' style='color: #0fa8ef !important; text-decoration: none; cursor: pointer; font-weight: bold;'>ovde</a></i>
							</div>
						</div>
					</div>
				</center>";
			}
			mysql_query("INSERT INTO `users` VALUES('','$reguname','$regemail','$regpw','$regname','avatars/noavatar.png','$date','/','$uip','0','0','1','1','$code','$ver')");
			$uid = mysql_insert_id();
			
			$aq = mysql_query("SELECT * FROM `users` WHERE `admin`='1' AND `obavestenja`='1'");
			while($row = mysql_fetch_assoc($aq)) {
				$admid = $row['id'];
				$textzanc = "Novi član se registrovao na sajtu!<br><br>
				Ime i prezime: <b>$regname</b><br />
				Korisničko ime: <b>$reguname</b><br />
				Email adresa: <b>$regemail</b><br />";
				mysql_query("INSERT INTO `obavestenja` VALUES('','$admid','','Registrovan je novi član','$textzanc','$date','/profil/$uid/','0')");
			}
			
			$subject = "Zakon Privlačenja Ključ - Registracija";
			mail($regemail,$subject,$txt,$headers);
			
			if($confirm_reg=="0") {
				$_SESSION['zpuser'] = $uid;
				echo "succ1";
			} else {
				echo "succ2";
			}
		}
	}
	
	if($a=="loginuser") {
		$uid = $_POST['uid'];
		$_SESSION['zpuser'] = $uid;
	}
	
	if($a=="logincheck") {
		$uname = $_POST['uname'];
		$upass = $_POST['upass'];
		$q = mysql_query("SELECT * FROM `users` WHERE `username`='$uname' OR `email`='$uname'");
		$id = mysql_result($q, 0, 'id');
		$pw = mysql_result($q, 0, 'password');
		$verified = mysql_result($q, 0, 'verified');
		if(empty($uname)||empty($upass)) {
			echo "error1";
		} else if (mysql_num_rows($q)==0) {
			echo "error2";
		} else if($verified==0) {
			echo "error3";
		} else if($pw != $upass) {
			echo "error4";
		} else {
			echo $id;
		}
	}
?>