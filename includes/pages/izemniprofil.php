<?php
    if(loggedin()) {
        if($_POST['submit']) {
            $uname = $_POST['cpusername'];
            $name = $_POST['cpname'];
            $email = $_POST['cpemail'];
            $pw = $_POST['cppw'];
            $rpw = $_POST['cprpw'];
            $img = $_POST['dndimg'];
            $errors = array();
            $success = array();
            
            if(!empty($img)) {
                mysql_query("SELECT * FROM `users` WHERE `avatar`='$img'");
                $u_avatar = $img;
                $success[] = "Profilna slika je uspešno promenjena.";
            }
            if(!empty($uname)&&$u_username!=$uname) {
                $q1 = mysql_query("SELECT * FROM `users` WHERE `username`='$uname'");
                if(mysql_num_rows($q1)==0) {
                    mysql_query("UPDATE `users` SET `username`='$uname' WHERE `id`='$u_id'");
                    $u_username = $uname;
                    $success[] = "Korisničko ime je uspešno promenjena.";
                } else {
                    $errors[] = "Korisničko ime je već zauzeto!";
                }
            }
            if(!empty($name)) {
                mysql_query("UPDATE `users` SET `name`='$name' WHERE `id`='$u_id'");
            }
            if(!empty($email)&&$email!=$u_email) {
                if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $q2 = mysql_query("SELECT * FROM `users` WHERE `email`='$email'");
                    if(mysql_num_rows($q2)==0) {
                        mysql_query("UPDATE `users` SET `email`='$email' WHERE `id`='$u_id'");
                        $u_email = $email;
                        $success[] = "Email adresa je uspešno promenjena.";
                    } else {
                        $errors[] = "Email adresa je već zauzeta!";
                    }
                } else {
                    $errors[] = "Unesite ispravnu email adresu!";
                }
            }
            if(!empty($pw)&&!empty($rpw)) {
                if(strlen($pw)>5) {
                    if($pw==$rpw) {
                        mysql_query("UPDATE `users` SET `password`='$pw' WHERE `id`='$u_id'");
                        $success[] = "Lozinka je uspešno promenjena.";
                    } else {
                        $errors[] = "Lozinke se ne poklapaju!";
                    }
                } else {
                    $errors[] = "Lozinka mora da sadrži minimum 6 karaktera!";
                }
            }
        }
        echo "<div class='objava'>
            <div class='title'>
                <h3>Osnovne informacije</h3>
            </div><div class='cont'>";
                if($_POST['submit']) {
                    if($errors) {
                        echo "<div class='infomessage error mt-10'><div class='img'></div><div class='text'>";
                        foreach($errors as $error) {
                            echo "$error<br>";
                        }
                        echo "</div></div>";
                    }
                    if($success) {
                        echo "<div class='infomessage success mt-10'><div class='img'></div><div class='text'>";
                        foreach($success as $succ) {
                            echo "$succ<br>";
                        }
                        echo "</div></div>";
                    }
                }
				echo "<div class='mt-10' style='width: 200px; display: inline-block; vertical-align: top;'>
                    <form id='imageform'  action='inputupload.php' method='post' enctype='multipart/form-data' style='height: 200px;'>
                        <div id='dragndrop'>
                            <input type='hidden' id='dndorfile' name='dndorfile' value='0'>
                            <input type='file' id='openfile' name='file' class='hidden' accept='image/*'>
                            <div id='uploads' style='height: 200px;'><span>Ukloni sliku</span></div>
                            <div id='dropzone' class='dropzone' style='height: 200px;'></div>
                        </div>
                    </form>
                </div><form action='/izemniprofil/' method='post' style='margin: 0px; padding: 0px; display: inline-block;'><div class='mt-10' style='width: 200px; display: inline-block; vertical-align: top; text-align: left; margin-left: 15px;'>
                    <input type='hidden' id='dndimg' name='dndimg' value=''>
                    Korisničko ime:<br />
                    <input type='text' value='$u_username' name='cpusername' class='inputtext mt-5 mb-10 wdth-100'><br />
                    Ime i Prezime:<br />
                    <input type='text' value='$u_name' name='cpname' class='inputtext mt-5 mb-10 wdth-100'><br />
                    Email adresa:<br />
                    <input type='text' value='$u_email' name='cpemail' class='inputtext mt-5 wdth-100'><br />
                </div><div class='mt-10' style='width: 200px; display: inline-block; vertical-align: top; text-align: left; margin-left: 15px;'>
                    Nova lozinka:<br />
                    <input type='password' name='cppw' value='' class='inputtext mt-5 mb-10 wdth-100'><br />
                    Ponovi lozinku:<br />
                    <input type='password' name='cprpw' value='' class='inputtext mt-5 mb-10 wdth-100'><br />
                    <input type='submit' name='submit' class='inputbtn floatr wdth-100' style='margin-top: 23px;' value='Sačuvaj'>
                </div></form>
			</div>
        </div>";
		
		if($u_obavestenja==1) {
			$obv = "on";
			$obvb = 1;
		} else {
			$obv = "";
			$obvb = 0;
		}
		if($u_sub==1) {
			$obvem = "on";
			$oemailb = 1;
		} else {
			$obvem = "";
			$oemailb = 0;
		}
		
        echo "<div class='objava mt'>
            <div class='title'>
                <h3>Obaveštenja</h3>
            </div><div class='cont'>
				<div class='mt-10 ta-l'>
					Dopusti da ti obaveštenja stižu na email? 
					<div style='float: right;' class='switch $obvem' id='objemail' bool='$oemailb' onClick=\"switchbtn('objemail');\"><div class='circle'></div></div>
					<div class='clear'></div>
				</div>
				<div class='line'></div>
				<div class='mt-10 ta-l'>
					Dopusti da ti obaveštenja stižu na sajtu? 
					<div style='float: right;' class='switch $obv' id='objsajt' bool='$obvb' onClick=\"switchbtn('objsajt');\"><div class='circle'></div></div>
					<div class='clear'></div>
				</div>
			</div>
        </div>";
    } else {
        redirect("/objave/");
    }
?>