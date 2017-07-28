<?php
	include '../database/connect.php';
	include 'funcs.php';
	session_start();
	$a = $_POST['action'];
	
	if($a=="deleteapmessagesask") {
		$msg = $_POST['msg'];
		echo "<div class='confirm centerit'>
			<div class='title'>Brisanje poruka</div>
			<div class='text'>$msg</div>
			<div class='buttons'>
				<input type='button' class='inputbtngrey mr-10' value='Otkaži' onClick='hidepopup();'>
				<input type='button' class='inputbtn' value='Obriši' onClick='acceptbrisanjeporuka();'>
			</div>
		</div>";
	}
	if($a=="izmenivideo") {
		$vid = $_POST['vid'];
		$q = mysql_query("SELECT * FROM `video` WHERE `id`='$vid'");
		$title = mysql_result($q,0,'title');
		$url = mysql_result($q,0,'url');
		$text = mysql_result($q,0,'desc');
		$text = str_replace("<br />","",$text);
		echo "<div class='izmeniobjavu centerit'>
			<input type='hidden' value='$vid' id='editvid'>
			<input type='text' value='$title' class='inputtext' id='editvtitle'>
			<div class='bbc video'>
				<div class='bbcbtn' style='background-image: url(/includes/images/bbc/bold.png);' onclick=\"formatText(editvtext,'b');\">
				</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/italic.png);' onclick=\"formatText(editvtext,'i');\">
				</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/underline.png);' onclick=\"formatText(editvtext,'u')\">
				</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/striked.png);' onclick=\"formatText(editvtext,'s');\">
				</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/h1.png);' onclick=\"formatText(editvtext,'h3');\"></div>
				
				<input type='text' class='vid' id='evid' value='$url' placeholder='YouTube Video ID'>
			</div>
			<textarea rows='15' id='editvtext'>$text</textarea>
			<div class='buttons'>
				<!--<form id='imageform'  action='inputupload.php' method='post' enctype='multipart/form-data'>
					<input type='hidden' id='dndimg'>
					<input type='file' name='file' class='hidden' id='openfile'>
					<input type='button' class='inputimgbtn floatl' onclick=\"$('#openfile').click();\" value='Izmeni naslovnu sliku'>
				</form>
				<div class='floatr'>
					<input type='button' class='inputbtngrey mr-10' value='Otkaži' onclick='hidepopup();'>
					<input type='button' class='inputbtn' value='Izmeni' onclick=\"\">
				</div>-->
				<input type='button' class='inputbtngrey mr-10' value='Otkaži' onclick='hidepopup();'>
				<input type='button' class='inputbtn' value='Izmeni' onclick=\"editvideo();\">
				
			</div>
		</div>";
	}
	if($a=="deletedogadjaj") {
		$did = $_POST['did'];
		$q = mysql_query("SELECT * FROM `dogadjaji` WHERE `id`='$did'");
		$title = mysql_result($q,0,'name');
		echo "<div class='confirm centerit'>
			<div class='title'>Brisanje poruke</div>
			<div class='text'>Da li ste sigurni da želite da obrišete ovaj dogadjaj <br><span>$title</span></div>
			<div class='buttons'>
				<input type='button' class='inputbtngrey mr-10' value='Otkaži' onClick='hidepopup();'>
				<input type='button' class='inputbtn' value='Obriši' onClick=\"acceptobrisidogadjaj('$did');\">
			</div>
		</div>";
	}
	if($a=="obrisivideo") {
		$vid = $_POST['vid'];
		$q = mysql_query("SELECT * FROM `video` WHERE `id`='$vid'");
		$title = mysql_result($q,0,'title');
		echo "<div class='confirm centerit'>
			<div class='title'>Brisanje poruke</div>
			<div class='text'>Da li ste sigurni da želite da obrišete ovaj video <br><span>$title</span></div>
			<div class='buttons'>
				<input type='button' class='inputbtngrey mr-10' value='Otkaži' onClick='hidepopup();'>
				<input type='button' class='inputbtn' value='Obriši' onClick=\"acceptobrisivideo('$vid');\">
			</div>
		</div>";
	}
	if($a=="obrisiporuku") {
		$oid = $_POST['did'];
		$q = mysql_query("SELECT * FROM `poruke` WHERE `id`='$oid'");
		$sender = mysql_result($q,0,'sender');
		$member = mysql_result($q,0,'member');
		if($member==1) {
			$query = mysql_query("SELECT * FROM `users` WHERE `id`='$sender'");
			$sender = mysql_result($query,0,'email');
		}
		echo "<div class='confirm centerit'>
			<div class='title'>Brisanje poruke</div>
			<div class='text'>Da li ste sigurni da želite da obrišete poruku od<br><span>$sender</span></div>
			<div class='buttons'>
				<input type='button' class='inputbtngrey mr-10' value='Otkaži' onClick='hidepopup();'>
				<input type='button' class='inputbtn' value='Obriši' onClick=\"acceptdeleteporuku('$oid');\">
			</div>
		</div>";
	}
	
	if($a=="showdogadjaj") {
		$did = $_POST['did'];
		$q = mysql_query("SELECT * FROM `dogadjaji` WHERE `id`='$did'");
		$name = mysql_result($q,0,'name');
		$mesto = mysql_result($q,0,'mesto');
		$time = mysql_result($q,0,'time');
		$desc = mysql_result($q,0,'desc');
		$datep = explode("-",mysql_result($q,0,'date'));
		$y = $datep[0]; $m = $datep[1]; $d = $datep[2];
		$day = mktime(0,0,0,$m,$d,$y);
		$day = day_to_dan(date('D', $day));
		$mesec = brmesec_to_mesec($datep[1]);
		echo "<div class='dogadjajpreview centerit'>";
			echo "<div class='title'>$name</div>";
			echo "<div class='subtitle'>$day, ".$datep[2].". $mesec u $time</div>";
			echo "<div class='subtitle'>$mesto</div>";
			echo "<div class='desc'>$desc</div>";
		echo "</div>";
	}
	if($a=="choosedate") {
		echo "<div id='kalendar' class='choosedate centerit'>";
            $date = time();
			$month = date('m',$date);
			$year = date('Y',$date);
			$did = $_POST['did'];
			display_calendar($month, $year, $did);
        echo "</div>";
	}
	
	if($a=="izmeniobjavu") {
		$oid = $_POST['oid'];
		$q = mysql_query("SELECT * FROM `objave` WHERE `id`='$oid'");
		$title = mysql_result($q,0,'title');
		$text = mysql_result($q,0,'text');
		$text = str_replace("<br />","",$text);
		echo "<div class='izmeniobjavu centerit'>
			<input type='hidden' value='$oid' id='editoid'>
			<input type='text' value='$title' class='inputtext' id='edittitle'>
			<div class='bbc'>
				<div class='bbcbtn' style='background-image: url(/includes/images/bbc/bold.png);' onclick=\"formatText(edittext,'b');\">
				</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/italic.png);' onclick=\"formatText(edittext,'i');\">
				</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/underline.png);' onclick=\"formatText(edittext,'u')\">
				</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/striked.png);' onclick=\"formatText(edittext,'s');\">
				</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/h1.png);' onclick=\"formatText(edittext,'h3');\"></div>
			</div>
			<textarea rows='15' id='edittext'>$text</textarea>
			<div class='buttons'>
				<!--<form id='imageform'  action='inputupload.php' method='post' enctype='multipart/form-data'>
					<input type='hidden' id='dndimg'>
					<input type='file' name='file' class='hidden' id='openfile'>
					<input type='button' class='inputimgbtn floatl' onclick=\"$('#openfile').click();\" value='Izmeni naslovnu sliku'>
				</form>
				<div class='floatr'>
					<input type='button' class='inputbtngrey mr-10' value='Otkaži' onclick='hidepopup();'>
					<input type='button' class='inputbtn' value='Izmeni' onclick=\"\">
				</div>-->
				<input type='button' class='inputbtngrey mr-10' value='Otkaži' onclick='hidepopup();'>
				<input type='button' class='inputbtn' value='Izmeni' onclick=\"izmeniobjavu2();\">
				
			</div>
		</div>";
	}
	if($a=="obrisiobjavu") {
		$oid = $_POST['oid'];
		$q = mysql_query("SELECT * FROM `objave` WHERE `id`='$oid'");
		$title = mysql_result($q,0,'title');
		echo "<div class='confirm centerit'>
			<div class='title'>Brisanje objave</div>
			<div class='text'>Da li ste sigurni da želite da obrišete ovu objavu:<br><span>$title</span></div>
			<div class='buttons'>
				<input type='button' class='inputbtngrey mr-10' value='Otkaži' onClick='hidepopup();'>
				<input type='button' class='inputbtn' value='Obriši' onClick=\"acceptdeleteobjavu('$oid');\">
			</div>
		</div>";
	}
	if($a=="aktivacionikod") {
		echo "<div class='zabloz centerit'>
			<div class='title'>Pošalji aktivacioni kod</div>
				<div style='height: 40px; margin-top: 15px; margin-bottom: 10px;'>
					<div class='hidden'>
						<div id='akmsg' class='infomessage'><div class='img'></div><div class='text'></div></div>
					</div>
				</div>
				<div>
					<span style='margin-top: 0px'>Email adresa</span>
					<input type='text' id='akemail' class='inputtext'>
				</div>
				<input type='button' value='Pošalji' onClick='posaljiaktivacionikod();' class='inputbtn mt'>
				<nav onClick='loginregister();'>Prijava / Registracija</nav>
			</div>
		</div>";
	}
	if($a=="zaboravljenalozinka") {
		echo "<div class='zabloz centerit'>
			<div class='title'>Zaboravljena lozinka</div>
				<div style='height: 40px; margin-top: 15px; margin-bottom: 10px;'>
					<div class='hidden'>
						<div id='zlmsg' class='infomessage'><div class='img'></div><div class='text'></div></div>
					</div>
				</div>
				<div>
					<span style='margin-top: 0px'>Email adresa</span>
					<input type='text' id='zlemail' class='inputtext'>
				</div>
				<input type='button' value='Pošalji' onClick='posaljizabloz();' class='inputbtn mt'>
				<nav onClick='loginregister();'>Prijava / Registracija</nav>
			</div>
		</div>";
	}
	if($a=="loginreg") {
		echo "<div class='nalog centerit'>
			<div class='login'>
				<div class='title'>Prijava</div>
				<div style='height: 40px; margin-top: 15px; margin-bottom: 10px;'>
					<div class='hidden'>
						<div id='loginmsg' class='infomessage'><div class='img'></div><div class='text'></div></div>
					</div>
				</div>
				<div class='block'>
					<span style='margin-top: 0px'>Korisnicko ime / Email</span>
					<input type='text' id='loginUname' class='inputtext' tabindex='1'>
					<span>Lozinka</span>
					<input type='password' id='loginPass' class='inputtext' tabindex='1'>
				</div>
				<input type='button' value='Prijavi se' onClick='logincheck();' class='inputbtn mt' tabindex='1'>
				<nav onClick='zaboravljenalozinka();'>Zaboravljena lozinka?</nav>
			</div><div class='register'>
				<div class='title'>Registracija</div>
				<div style='height: 40px; margin-top: 15px; margin-bottom: 10px; padding-left: 20px; box-sizing: border-box;'>
					<div class='hidden'>
						<div id='regmsg' class='infomessage'><div class='img'></div><div class='text'></div></div>
					</div>
				</div>
				<div class='block'>
					<span style='margin-top: 0px'>Korisnicko ime</span>
					<input type='text' class='inputtext' id='reguname' tabindex='1'>
					<span>Ime i Prezime</span>
					<input type='text' class='inputtext' id='regname' tabindex='3'>
				</div><div class='block'>
					<span style='margin-top: 0px'>Email adresa</span>
					<input type='text' class='inputtext' id='regemail' tabindex='2'>
					<span>Lozinka</span>
					<input type='password' class='inputtext' id='regpw' tabindex='4'>
					<div onClick='showpwonreg();' id='bigeye' on='0'></div>
				</div>
				<input type='button' value='Registruj se' onClick='register();' class='inputbtn mt' tabindex='5'>
				<nav onClick='aktivacionikod();'>Pošalji aktivacioni kod ponovo?</nav>
			</div>
		</div>";
	}
?>