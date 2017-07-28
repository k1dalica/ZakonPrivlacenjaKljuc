<?php
function count_video() {
	$q = mysql_query("SELECT * FROM `video`");
	return mysql_num_rows($q);
}
function not_online_anymore() {
	$time = time()-60;
	mysql_query("DELETE FROM `online` WHERE `time`<'$time'");
}
function count_visit($uid) {
	$timestamp = time();
	$users_ip = GetIP();
	$query = mysql_query("SELECT * FROM `online` WHERE `ip`='$users_ip'");
	$rows = mysql_num_rows($query);
	if($rows!=0) {
		mysql_query("UPDATE `online` SET `time`='$timestamp' WHERE `ip`='$users_ip'");
	} else {
		mysql_query("INSERT INTO `online` VALUES ('$users_ip','$timestamp')");
	}
	
	$page = $_SERVER['REQUEST_URI'];
	$uip = GetIP();
	$date = date("d.m.Y H:i");
	
	if(isset($_SESSION['zplogin'])===false) {
		$vquery = mysql_query("SELECT * FROM `visits` WHERE `ip`='$uip'");
		if(mysql_num_rows($vquery)==0) {
			mysql_query("INSERT INTO `visits` VALUES('','$uip','0','$page','$date')");
		}
	}
}
function update_user($u_id) {
	$date = date("d.m.Y H:i");
	mysql_query("UPDATE `users` SET `lastact`='$date' WHERE `id`='$u_id'");
}
function send_obavestenje($oid, $objavaid, $title, $val) {
	$q = mysql_query("SELECT * FROM `users`");
	while($row = mysql_fetch_assoc($q)) {
		$uid = $row['id'];
		$to = $row['email'];
		$ob1 = $row['obavestenja'];
		$ob2 = $row['subscribed'];
		$dateandtime = date("H:i d.m.Y");
		if($oid=="1") {
			$naslov = "Nova objava - $title";
			$desc = "Dodata je nova objava na sajtu pod nazivom <b>$title</b>.";
			$url = "/objava/$val/";
			$var1 = "Zakon Privlačenja Ključ - Nova objava";
			$var2 = "Dodata je nova objava na sajtu <a href='http://www.zakonprivlacenjakljuc.com'>Zakon Privlačenja Ključ</a> pod nazivom <b>$title</b>
				<br /><br />Da pogledas objavu klikni na link ispod<br /><br />
				<a href='http://www.zakonprivlacenjakljuc.com/objava/$val/'>
				<input type='button' value='Pogledaj objavu' style='border: none; outline: none; background-color: #0fa8ef; height: 35px; padding: 0px 20px; color: #fff;'></a>
				<br /><br /><h3>Zakon Privlačenja Ključ</h3>";
		} else if($oid=="3") {
			$naslov = "Novi video - $title";
			$desc = "Dodat je novi video na sajtu pod nazivom <b>$title</b>.";
			$url = "/objave/video/";
			$var1 = "Zakon Privlačenja Ključ - Novi video";
			$var2 = "Dodat je novi video na sajtu <a href='http://www.zakonprivlacenjakljuc.com'>Zakon Privlačenja Ključ</a> pod nazivom <b>$title</b>
				<br /><br />Da pogledas video klikni na link ispod<br /><br />
				<a href='http://www.zakonprivlacenjakljuc.com/objave/video/'>
				<input type='button' value='Pogledaj video' style='border: none; outline: none; background-color: #0fa8ef; height: 35px; padding: 0px 20px; color: #fff;'></a>
				<br /><br /><h3>Zakon Privlačenja Ključ</h3>";
		} else if($oid=="2") {
			$naslov = "Nova hipnoterapija - $title";
			$desc = "Dodat je nova hipnoterapija na sajtu pod nazivom <b>$title</b>.";
			$url = "/objava/$val/";
			$var1 = "Zakon Privlačenja Ključ - Dodata je nova hipnoterapija";
			$var2 = "Dodata je nova hipnoterapija na sajtu <a href='http://www.zakonprivlacenjakljuc.com'>Zakon Privlačenja Ključ</a> pod nazivom <b>$title</b>
				<br /><br />Da pogledas hipnoterapiju klikni na link ispod<br /><br />
				<a href='http://www.zakonprivlacenjakljuc.com/objava/$val/'>
				<input type='button' value='Pogledaj hipnoterapiju' style='border: none; outline: none; background-color: #0fa8ef; height: 35px; padding: 0px 20px; color: #fff;'></a>
				<br /><br /><h3>Zakon Privlačenja Ključ</h3>";
		} else if($oid=="4") {
			$naslov = "Dogadjaj - $title";
			$desc = "Organizovan je novi dogadjaj pod nazivom <b>$title</b>.";
			$url = "/objave/dogadjaji/";
			$var1 = "Zakon Privlačenja Ključ - Organizovan je novi dogadjaj";
			$var2 = "Organizovan je novi dogadjaj na sajtu <a href='http://www.zakonprivlacenjakljuc.com'>Zakon Privlačenja Ključ</a> pod nazivom <b>$title</b>
				<br /><br />Da pogledas hipnoterapiju klikni na link ispod<br /><br />
				<a href='http://www.zakonprivlacenjakljuc.com/objave/dogadjaji/'>
				<input type='button' value='Pogledaj dogadjaje' style='border: none; outline: none; background-color: #0fa8ef; height: 35px; padding: 0px 20px; color: #fff;'></a>
				<br /><br /><h3>Zakon Privlačenja Ključ</h3>";
		}
		$email = "office@zakonprivlacenjakljuc.com";
		$headers = "From: $email" . "\r\n" .
			"Reply-To: $email" . "\r\n" .
			"Content-Type: text/html; charset=UTF-8\r\n";
		if($ob1==1) {
			mysql_query("INSERT INTO `obavestenja` VALUES('','$uid', '$objavaid','$naslov','$desc','$dateandtime','$url','0')");
		}
		if($ob2==1) {
			ini_set("SMTP","mail.reikonkarate.com");
			ini_set('smtp_port', 587);
			ini_set('sendmail_from', "office@zakonprivlacenjakljuc.com");
			mail($to,$var1,$var2,$headers);
		}
	}
}
function yt_exists($videoID) {
    $theURL = "http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=$videoID&format=json";
    $headers = get_headers($theURL);

    if (substr($headers[0], 9, 3) !== "404") {
        return true;
    } else {
        return false;
    }
}
function no_content() {
	echo "<div class='mt mb ta-c'>
		<!--<font style='color: #d04460; font-size: 30px;'>Nažalost ova strana nema sadržaj...</font><br>-->
		<img class='mt' src='/includes/images/sadsmiley.png'>
	</div>";
}

function prikazi_video($page) {
	$per_page = 5;
	$pages_query = mysql_query("SELECT COUNT(`id`) FROM `video`");
	$pages = ceil(mysql_result($pages_query,0) / $per_page);
	$founded = mysql_result($pages_query,0);
	$start = ($page - 1) * $per_page;
	$query = mysql_query("SELECT * FROM `video` ORDER BY `id` DESC LIMIT $start ,$per_page");
	$ddap = "";
	if(mysql_num_rows($query)!=0) {
		echo "<div class='mt mb'>".showbtns($page, $pages,'/video')."</div>";
		while($row = mysql_fetch_assoc($query)) {
			$id = $row['id'];
			$title = $row['title'];
			$desc = $row['desc'];
			$dnt = explode(" ", $row['date']);
			$date = explode("-", $dnt[0]);
			$datum = $date[0].".".$date[1].".".$date[2].".";
			$time = $dnt[1];
			$url = $row['url'];
			if(!empty($desc)) {
				$desc = "<div class='mb-10'>$desc</div>";
			}
			if(loggedin()&&get_users_data(loggedin(),'admin')>=1) {
				$ddap = "<div class='dropdownoptions'><div class='arrow'></div>
					<div class='options'>
						<div class='option' onClick=\"izmenivideo('$id');\">Izmeni</div>
						<div class='option' onClick=\"obrisivideo('$id');\">Obriši</div>
					</div>
				</div>";
			}
			echo "<div class='objava mb'>
				<div class='title'>
					<h3>$title</h3>
					<font>$datum u $time</font>
					$ddap
				</div><div class='text'>
                    $desc
					<div class='videoWrapper'><iframe width='632' height='355' src='https://www.youtube.com/embed/$url' frameborder='0' allowfullscreen=''></iframe></div>
                </div>
			</div>";
        }
		echo "<div class='mt'>".showbtns($page, $pages,'/video')."</div>";
    } else {
        no_content();
    }
	
}
function brmesec_to_mesec($mesec) {
	switch($mesec) {
		case "01": $mesec = "Januar"; break;
		case "02": $mesec = "Februar"; break;
		case "03": $mesec = "Mart"; break;
		case "04": $mesec = "April"; break;
		case "05": $mesec = "Maj"; break;
		case "06": $mesec = "Jun"; break;
		case "07": $mesec = "Jul"; break;
		case "08": $mesec = "Avgust"; break;
		case "09": $mesec = "Septembar"; break;
		case "10": $mesec = "Oktobar"; break;
		case "11": $mesec = "Novembar"; break;
		case "12": $mesec = "Decembar"; break;
	}
	return $mesec;
}
function day_to_dan($day) {
	switch($day) {
		case "Mon": $day = "Ponedeljak"; break;
		case "Tue": $day = "Utorak"; break;
		case "Wen": $day = "Sreda"; break;
		case "Thu": $day = "Četvrtak"; break;
		case "Fri": $day = "Petak"; break;
		case "Sat": $day = "Subota"; break;
		case "Sun": $day = "Nedelja"; break;
	}
	return $day;
}
function show_dogadjaji() {
	$q = mysql_query("SELECT * FROM `dogadjaji` ORDER BY `date` DESC");
	if(mysql_num_rows($q)!=0) {
		while($row = mysql_fetch_assoc($q)) {
			$id = $row['id'];
			$name = $row['name'];
			$mesto = $row['mesto'];
			$time = $row['time'];
			$desc = $row['desc'];
			$datep = explode("-",$row['date']);
			$y = $datep[0]; $m = $datep[1]; $d = $datep[2];
			$day = mktime(0,0,0,$m,$d,$y);
			$day = day_to_dan(date('D', $day));
			$mesec = substr(brmesec_to_mesec($datep[1]), 0, 3);
			
			if(loggedin()&&get_users_data(loggedin(),'admin')>=1) {
				$adminopcije = "<div class='dropdownoptions'><div class='arrow'></div><div class='options'><div class='option' onclick=\"editdogadjaj('$id')\">Izmeni</div><div class='option' onclick=\"deletedogadjaj('$id')\">Obriši</div></div></div>";
			} else {
				$adminopcije = "";
			}
			$passeddate = new DateTime("$y-$m-$d");
			$now = new DateTime();
			if($passeddate >= $now) {
				$passed = "";
			} else {
				$passed = "passed";
			}
			echo "<div class='objava mb'>
				<div class='title dogadjaj'>
					<div class='datum $passed'>
						<span>$mesec</span>
						<font>$datep[2]</font>
					</div>
					<h3>$name</h3>
					<font>$mesto</font>	
					$adminopcije
				</div><div class='text'>
                    $desc
                </div>
			</div>";
		}
	} else {
		no_content();
	}
}
function display_calendar($month, $year, $did) {
	$date = time();
	$day = date('d',$date);
	$today = date('d.m.Y');
	$todaymy = date('m.Y');
	$first_day = mktime(0,0,0,$month,1,$year);
	$days_week = date('D', $first_day);
	$days_in_month = cal_days_in_month(0, $month, $year);
	
	switch($days_week) {
		case "Mon": $blank = 0; break;
		case "Tue": $blank = 1; break;
		case "Wen": $blank = 2; break;
		case "Thu": $blank = 3; break;
		case "Fri": $blank = 4; break;
		case "Sat": $blank = 5; break;
		case "Sun": $blank = 6; break;
	}
	
	switch($month) {
		case "01": $mesec = "Januar"; break;
		case "02": $mesec = "Februar"; break;
		case "03": $mesec = "Mart"; break;
		case "04": $mesec = "April"; break;
		case "05": $mesec = "Maj"; break;
		case "06": $mesec = "Jun"; break;
		case "07": $mesec = "Jul"; break;
		case "08": $mesec = "Avgust"; break;
		case "09": $mesec = "Septembar"; break;
		case "10": $mesec = "Oktobar"; break;
		case "11": $mesec = "Novembar"; break;
		case "12": $mesec = "Decembar"; break;
	}
	//<div class='date' >$mesec $year</div>
	echo "<input type='hidden' id='date' month='$month' year='$year' did='$did'>
	<div class='datum mb' align='center'>
		<div class='prev floatl' onClick='prevdate();'></div>
		<input type='button' value='$mesec $year' class='inputbtn' onClick='todaydate();'>
		<div class='next floatr' onClick='nextdate();'></div>
	</div>
	
	
	<table id='calendar' class='mt'>
    <tr><th>Pon</th><th>Uto</th><th>Sre</th><th>Cet</th><th>Pet</th><th>Sub</th><th>Ned</th></tr>";
	$day_count = 1;
	while($blank > 0) {
		echo "<td></td>";
		$blank = $blank-1;
		$day_count++;
	}
	$day_num = 1;
	while($day_num <= $days_in_month) {
		if($day_num<=9) { $dnss = "0".$day_num; } else { $dnss = $day_num; }
		$onsetdate = "$day_num.$month.$year";
		$onsetdate2 = "$dnss.$month.$year";
		
		$passeddate = new DateTime("$year-$month-$dnss");
		$now = new DateTime();
		$fdtm = "$year-$month-1";
		$ldtm = "$year-$month-$days_in_month";
		$asdasdq1 = "$year-$month-$day_num";
		
		if($did=="justnormalcalender") {
			$dogadjajiquery = mysql_query("SELECT * FROM `dogadjaji` WHERE `date`='$asdasdq1'");
			if(mysql_num_rows($dogadjajiquery)!=0) {
				$dogid = mysql_result($dogadjajiquery,0,'id');
				if($passeddate >= $now) {
					$event = "event";
				} else {
					$event = "passedevent";
				}
				echo "<td class='$event' onClick=\"showdogadjaj('$dogid');\">$day_num</td>";
			} else {
				if($today==$onsetdate2) {
					echo "<td class='today'>$day_num</td>";
				} else {
					echo "<td>$day_num</td>";
				}
			}
		} else {
			if($today==$onsetdate2) {
				echo "<td class='h today' onClick=\"updatedate('$did','$onsetdate');\">$day_num</td>";
			} else if($passeddate > $now) {
				echo "<td class='h' onClick=\"updatedate('$did','$onsetdate');\">$day_num</td>";
			} else {
				echo "<td class='passed'>$day_num</td>";
			}
		}
		
		$day_num++;
		$day_count++;
		
		if($day_count > 7) {
			echo "</tr><tr>";
			$day_count = 1;
		}
	}
	while($day_count > 1 && $day_count <= 7) {
		echo "<td></td>";
		$day_count++;
	}
	echo "</tr>
	</table>";
}

function videos_to_objave() {
	$q = mysql_query("SELECT * FROM `video`");
	while($row = mysql_fetch_assoc($q)) {
		$title = $row['title'];
		$desc = $row['desc'];
		$date = $row['date'];
		$url = $row['url'];
		$admin = $row['admin'];
		
		$split = explode(" ",$date);
		$time = $split[1];
		$time = $time.":00";
		$date = strtotime($split[0]);
		$newdate = date('Y-m-d',$date);
		$newdateandtime = "$newdate $time";
		mysql_query("INSERT INTO `objave` VALUES('','$title','$desc','$newdateandtime','$newdateandtime','$url','$admin','3','0')");
	}
}
function date_to_mysql_date($date) {
	$split = explode(" ",$date);
	$time = $split[0];
	$time = $time.":00";
	$date = strtotime($split[1]);
	$newdate = date('Y-m-d',$date);
	return "$newdate $time";
}

function objave_change_date($text) {
	$q = mysql_query("SELECT * FROM `objave` WHERE `dateandtime`='0000-00-00 00:00:00'");
	while ($row = mysql_fetch_assoc($q)) {
		$id = $row['id'];
		$dnt = date_to_mysql_date($row['time']);
		mysql_query("UPDATE `objave` SET `dateandtime`='$newdateandtime' WHERE `id`='$id'");
	}		
}
function make_urls_links($text) {
	return preg_replace(array(
		  '/(^|\s|>)(www.[^<> \n\r]+)/iex',
		  '/(^|\s|>)([_A-Za-z0-9-]+(\\.[A-Za-z]{2,3})?\\.[A-Za-z]{2,4}\\/[^<> \n\r]+)/iex',
		  '/(?(?=<a[^>]*>.+<\/a>)(?:<a[^>]*>.+<\/a>)|([^="\']?)((?:https?):\/\/([^<> \n\r]+)))/iex'
		), array(
		  "stripslashes((strlen('\\2')>0?'\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>&nbsp;\\3':'\\0'))",
		  "stripslashes((strlen('\\2')>0?'\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>&nbsp;\\4':'\\0'))",
		  "stripslashes((strlen('\\2')>0?'\\1<a href=\"\\2\" target=\"_blank\">\\3</a>&nbsp;':'\\0'))",
		), $text );
}
function make_urls_from_objave() {
	$q = mysql_query("SELECT * FROM `objave`");
    while($row = mysql_fetch_assoc($q)) {
        $id = $row['id'];
        $title = $row['title'];
        $query = mysql_query("SELECT * FROM `links` WHERE `oid`='$id'");
        if(mysql_num_rows($query)==0) {
            $title = titletourlconverter($title);
            mysql_query("INSERT INTO `links` VALUES('','$id','$title','1')");
        }
        
    }
}

function dodaj_video() {
	if(loggedin()&&get_users_data(loggedin(),'admin')>=1) {
		echo "<div class='newpost'>
            <div class='title'>
                <span>Dodaj video</span>
				<div class='pointer'></div>
            </div><div class='npcont'>
				<div class='cont'>
					<iframe class='' id='attachedvid' style='display: none;' width='632' height='355' src='https://www.youtube.com/embed/$url' frameborder='0' allowfullscreen=''></iframe>
					<div class='hidden'><div id='vidmsg' class='infomessage mb-10 mt-10'><div class='img'></div><div class='text'></div></div></div>
						<div class='bbc'>
							<div class='bbcbtn' style='background-image: url(/includes/images/bbc/bold.png);' onclick=\"formatText(vtext,'b');\">
							</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/italic.png);' onclick=\"formatText(vtext,'i');\">
							</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/underline.png);' onclick=\"formatText(vtext,'u')\">
							</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/striked.png);' onclick=\"formatText(vtext,'s');\">
							</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/h1.png);' onclick=\"formatText(vtext,'h3');\"></div>
						</div>
						<input type='text' class='inputtext mr-5 mt-10' id='vtitle' placeholder='Naslov' style='width: calc(100% - 205px);'
						><input type='text' class='inputtext ta-c mt-10' style='width: 200px;' id='vid' placeholder='YouTube video ID'>
						<br/>
						
						<textarea rows='4' class='wdth-100 mt-10' id='vtext' placeholder='Text...'></textarea></div>
						
						<input type='button' class='inputbtn mt-10' value='Dodaj' onClick=\"dodajvideo();\">
				</div>
			</div>";
	}
}
function prikazi_newpost($strana) {
	if(loggedin()&&get_users_data(loggedin(),'admin')>=1) {	
		echo "<div class='newpost'>
            <div class='title'>
                <span>Nova objava</span>
				<div class='pointer'></div>
            </div><div class='npcont'>
				<div class='cont'>
					<div class='hidden'><div id='npmsg1' class='infomessage mb-10'><div class='img'></div><div class='text'></div></div></div>
						<div class='bbc'>
							<div class='bbcbtn' style='background-image: url(/includes/images/bbc/bold.png);' onclick=\"formatText(notext,'b');\">
							</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/italic.png);' onclick=\"formatText(notext,'i');\">
							</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/underline.png);' onclick=\"formatText(notext,'u')\">
							</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/striked.png);' onclick=\"formatText(notext,'s');\">
							</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/h1.png);' onclick=\"formatText(notext,'h3');\"></div>
							<div class='expand' id='expand'></div>
						</div>
						<input type='text' class='inputtext wdth-100 mt-10' id='notitle' placeholder='Naslov' value=''><br/>
						
						<textarea rows='4' class='wdth-100 mt-10' id='notext' placeholder='Text...'></textarea></div><div class='buttons'>
						<form id='imageform'  action='inputupload.php' method='post' enctype='multipart/form-data'>
							<input type='hidden' id='dndimg'>
							<input type='file' name='file' class='hidden' id='openfile'>
							<input type='button' class='inputimgbtn floatl mr-5' onClick=\"$('#openfile').click();\" value='Izaberi naslovnu sliku'>
							<input type='button' class='inputbtn floatl' style='display: none;' value='X' id='hidenewpostimg'>
						</div>
						<input type='button' class='inputbtn floatr' value='Objavi' onClick=\"newpost('1','$strana');\">
						<div class='clear'></div>
						<div id='newpostimg' class='hidden mt-15'></div>
					</form>
				</div>
			</div>";
    }
}

function showbtn($x, $page, $url) {
	echo ($x == $page) ? "<input type='button' value='$x' class='pagebtnon' disabled>" : "<a href='$url/$x/'><input type='button' value='$x' class='pagebtn'></a>";
}
function showbtns($page, $pages, $url) {
	echo "<div class='pagenation'>";
	if($page==1) {
		echo "<div class='prev'><input type='button' value='Prethodna' class='prevbtn btndisabled' disabled></div>";
	} else {
		$prevpage = $page - 1;
		echo "<div class='prev'><a href='$url/$prevpage/'><input type='button' value='Prethodna' class='prevbtn'></a></div>";
	}
	echo "<div class='buttons'>";
	if ($pages >= 1 && $page <= $pages) {
		if($pages<=5) {
			for($x=1; $x<=$pages; $x++) {
				showbtn($x, $page, $url);
			}
		} else {
			if($page==1 || $page==2) {
				for($x=1; $x<=3; $x++) {
					showbtn($x, $page, $url);
				}
				echo "<div class='skippagenation'></div>";
				showbtn($pages, $page, $url);
			} else if($page==3) {
				for($x=1; $x<=4; $x++) {
					showbtn($x, $page, $url);
				}
				echo "<div class='skippagenation'></div>";
				showbtn($pages, $page, $url);
			} else if($page==$pages||$page==$pages-1) {
				showbtn(1, $page, $url);
				echo "<div class='skippagenation'></div>";
				for($x=$pages-2;$x<=$pages;$x++) {
					showbtn($x, $page, $url);
				}
			} else if($page==$pages-2) {
				showbtn(1, $page, $url);
				echo "<div class='skippagenation'></div>";
				for($x=$pages-3;$x<=$pages;$x++) {
					showbtn($x, $page, $url);
				}
			} else {
				showbtn(1, $page, $url);
				echo "<div class='skippagenation'></div>";
				for($x=$page-1;$x<=$page+1;$x++) {
					showbtn($x, $page, $url);
				}
				echo "<div class='skippagenation'></div>";
				showbtn($pages, $page, $url);
			}
		}
	}
	echo "</div>";
	if($page==$pages) {
		echo "<div class='next'><input type='button' value='Sledeća' class='nextbtn btndisabled' disabled></div>";
	} else {
		$nextpage = $page + 1;
		echo "<div class='next'><a href='$url/$nextpage/'><input type='button' value='Sledeća' class='nextbtn'></a></div>";
	}
	echo "</div>";
}
function prikazi_sve($page) {
	
}
function prikazi_hipnoterapije($page) {
	$per_page = 5;
	$pages_query = mysql_query("SELECT COUNT(`id`) FROM `objave` WHERE `page`='2'");
	$pages = ceil(mysql_result($pages_query,0) / $per_page);
	$founded = mysql_result($pages_query,0);
	$start = ($page - 1) * $per_page;
	$query = mysql_query("SELECT * FROM `objave` WHERE `page`='2' ORDER BY `id` DESC LIMIT $start ,$per_page");
	$ddap = "";
	if(mysql_num_rows($query)!=0) {
		echo showbtns($page, $pages,'/hipnoterapija');
		while($row = mysql_fetch_assoc($query)) {
			$id = $row['id'];
            $title = $row['title'];
            $text = $row['text'];
            $datum = explode(" ",$row['dateandtime']);
            $time = explode(":",$datum[1]);
			$time = $time[0].":".$time[1];
			$datum = explode("-", $datum[0]);
			$date = $datum[2].".".$datum[1].".".$datum[0].". u ".$time;
            $img = $row['cover'];
            $image = "/".$img;
            
			if(loggedin()&&get_users_data(loggedin(),'admin')>=1) {
				$ddap = "<div class='dropdownoptions'><div class='arrow'></div>
					<div class='options'>
						<div class='option' onClick=\"izmeniobjavu('$id');\">Izmeni</div>
						<div class='option' onClick=\"obrisiobjavu('$id');\">Obriši</div>
					</div>
				</div>";
			}
			
			$q = mysql_query("SELECT * FROM `links` WHERE `oid`='$id' AND type='1'");
			$url = mysql_result($q,0,'url');
			
			if(empty($img)) { $showcover = ""; } else { $showcover = "<div class='image'><img src='$image' alt=''></div>"; }
			
            $text = strip_tags($text);
            $text = substr($text, 0, 300);
			echo "<div class='objava mt'>
				<div class='title'>
					<h3>$title</h3>
					<font>$date</font>
					$ddap
				</div>$showcover<div class='text'>
                    $text	
                </div><div class='button'>
					<a href='/objava/$url/'><input type='button' value='Pogledaj celu objavu...' class='inputbtn'></a>
				</div>
			</div>";
        }
		echo "<div class='mt'>";
		echo showbtns($page, $pages,'/hipnoterapija');
		echo "</div>";
    } else {
        no_content();
    }
}
function prikazi_objave($page) {
	$per_page = 5;
	$pages_query = mysql_query("SELECT COUNT(`id`) FROM `objave` WHERE `page`='1'");
	$pages = ceil(mysql_result($pages_query,0) / $per_page);
	$founded = mysql_result($pages_query,0);
	
	$start = ($page - 1) * $per_page;
	
	$query = mysql_query("SELECT * FROM `objave` WHERE `page`='1' ORDER BY `id` DESC LIMIT $start ,$per_page");
	
	$ddap = "";
	if(mysql_num_rows($query)!=0) {
		echo showbtns($page, $pages,'/textovi');
		while($row = mysql_fetch_assoc($query)) {
			$id = $row['id'];
            $title = $row['title'];
            $text = $row['text'];
            $datum = explode(" ",$row['dateandtime']);
            $time = explode(":",$datum[1]);
			$time = $time[0].":".$time[1];
			$datum = explode("-", $datum[0]);
			$date = $datum[2].".".$datum[1].".".$datum[0].". u ".$time;
            $img = $row['cover'];
            $image = "/".$img;
            
			if(loggedin()&&get_users_data(loggedin(),'admin')>=1) {
				$ddap = "<div class='dropdownoptions'><div class='arrow'></div>
					<div class='options'>
						<div class='option' onClick=\"izmeniobjavu('$id');\">Izmeni</div>
						<div class='option' onClick=\"obrisiobjavu('$id');\">Obriši</div>
					</div>
				</div>";
			}
			
			$q = mysql_query("SELECT * FROM `links` WHERE `oid`='$id' AND type='1'");
			$url = mysql_result($q,0,'url');
			
			if(empty($img)) { $showcover = ""; } else { $showcover = "<div class='image'><img src='$image' alt='$title'></div>"; }
			
            $text = strip_tags($text);
            $text = substr($text, 0, 300);
			echo "<div class='objava mt'>
				<div class='title'>
					<h3><a href='/objava/$url/'>$title</a></h3>
					<font>$date</font>
					$ddap
				</div>$showcover<div class='text'>
                    $text	
                </div><div class='button'>
					<a href='/objava/$url/'><input type='button' value='Pogledaj celu objavu...' class='inputbtn'></a>
				</div>
			</div>";
        }
		echo "<div class='mt'>";
		echo showbtns($page, $pages,'/textovi');
		echo "</div>";
    } else {
        no_content();
    }
}

function titletourlconverter($title) {
	$title = strip_tags($title);
	$title = strtolower($title);
    $title = preg_replace('/[^\p{L}\p{N}\s]/u', '', $title);
	$title = str_replace(" ","-",$title);
	$title = str_replace("č","c",$title);
	$title = str_replace("ć","c",$title);
	$title = str_replace("đ","dj",$title);
	$title = str_replace("š","s",$title);
	$title = str_replace("ž","z",$title);
	$title = str_replace("Č","c",$title);
	$title = str_replace("Ć","c",$title);
	$title = str_replace("Đ","dj",$title);
	$title = str_replace("Š","s",$title);
	$title = str_replace("Ž","z",$title);
	$title = str_replace("---","-",$title);
	$title = str_replace("--","-",$title);
	$title = str_replace("/","-",$title);
	$title = substr($title, 0, 60);
	$q = mysql_query("SELECT * FROM `links` WHERE `url`='$title'");
	if(mysql_num_rows($q)!=0) {
		$charset = "asdfghjklzxcvbnmqwertyuiopASDFGHJKLZXCVBNMQWERTYUIOP0123456789";
		$code = substr(str_shuffle($charset), 0, 4);
		$title = "$title-$code";
	}
	return $title;
}
function display_najnovije_objave() {
	$q = mysql_query("SELECT * FROM `objave` ORDER BY `id` DESC LIMIT 5");
	if(mysql_num_rows($q)!=0) {
		while($row = mysql_fetch_assoc($q)) {
			$oid = $row['id'];
			$title = $row['title'];
			$query = mysql_query("SELECT * FROM `links` WHERE `oid`='$oid'");
			$url = mysql_result($query,0,'url');
			echo "<a href='/objava/$url/'>$title</a>";
		}
	} else {
		echo "<font color='#ab1414'>Nije pronađena ni jedna objava!</font>";
	}
}
function display_najpopularnije_objave() {
	$q = mysql_query("SELECT * FROM `objave` ORDER BY `visited` DESC LIMIT 5");
	if(mysql_num_rows($q)!=0) {
		while($row = mysql_fetch_assoc($q)) {
			$oid = $row['id'];
			$title = $row['title'];
			$query = mysql_query("SELECT * FROM `links` WHERE `oid`='$oid'");
			$url = mysql_result($query,0,'url');
			echo "<a href='/objava/$url/'>$title</a>";
		}
	} else {
		echo "<font color='#ab1414'>Nije pronađena ni jedna objava!</font>";
	}
}

function show_infstudio_ad($num) {
	if($num==1) {
		$ad = "/includes/images/infstudio.jpg";
	} else {
		$ad = "/includes/images/infstudio2.jpg";
	}
	echo "<div class='contentbox mt'>
        <a href='http://www.infstudio.com' target='_blank' alt='Infinity Studio'><img src='$ad' width='100%'></a>
    </div>";
}

function display_users_profile($uid) {
	$q = mysql_query("SELECT * FROM `users` WHERE `id`='$uid'");
	if(mysql_num_rows($q)!=0) {
		$avatar = mysql_result($q,0,'avatar');
		$username = mysql_result($q,0,'username');
		$name = mysql_result($q,0,'name');
		$email = mysql_result($q,0,'email');
		$registered = mysql_result($q,0,'registered');
		$lastact = mysql_result($q,0,'lastact');
		echo "<div class='contentbox'>
			<div class='title'>
				<span>Profil člana</span>
			</div><div class='cont profil'>
				<div class='avatar' style='background-image: url(/$avatar);' img='$avatar'></div><div class='info'>
					<span>$username</span>
					<font>Ime i prezime: <b>$name</b></font>
					<font>Email adresa: <b>$email</b></font>
					<font>Registrovan: <b>$registered</b></font>
					<font>Poslednja poseta: <b>$lastact</b></font>
				</div>
			</div>
		</div>";
	} else {
		redirect("/clanovi/");
	}
}

function does_user_exist($uid) {
	$q = mysql_query("SELECT * FROM `users` WHERE `id`='$uid'");
	if(mysql_num_rows($q)!=0) {
		return true;
	}
}
function pregledane_objave() {
	$query = mysql_query("SELECT * FROM `objave`");
	$broj = 0;
	while($row = mysql_fetch_assoc($query)) {
		$visits = $row['visited'];
		$broj = $broj + $visits;
	}
	return $broj;
}
function count_objave() {
	$query = mysql_query("SELECT * FROM `objave`");
	return mysql_num_rows($query);
}
function count_users() {
	$query = mysql_query("SELECT * FROM `users`");
	return mysql_num_rows($query);
}
function count_kom() {
	$query = mysql_query("SELECT * FROM `komentari`");
	return mysql_num_rows($query);
}
function trenutno_online() {
	$query = mysql_query("SELECT * FROM `online`");
	return mysql_num_rows($query);
}
function count_posete() {
	$prijavljeni = mysql_query("SELECT DISTINCT `uid` FROM `visits` WHERE `uid`!='0'");
	$b1 = mysql_num_rows($prijavljeni);
	$neprijavljeni = mysql_query("SELECT DISTINCT `ip` FROM `visits` WHERE `uid`='0' AND `ip` NOT IN (SELECT `ip` FROM `visits` WHERE `uid`!='0')");
	$b2 = mysql_num_rows($neprijavljeni);
	$ukupno = $b1+$b2;
	return $ukupno;
}
function is_image($path) {
	$info = pathinfo($path);
	if ($info["extension"] == "jpg" || $info["extension"] == "png" || $info["extension"] == "gif" || $info["extension"] == "jpeg") {
		return true;
	}
}
function GetIP() {
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		$ip = getenv("REMOTE_ADDR");
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		$ip = $_SERVER['REMOTE_ADDR'];
	else
		$ip = "unknown";
	return($ip);
}
function get_users_data($uid,$data) {
	$q = mysql_query("SELECT * FROM `users` WHERE `id`='$uid'");
	return mysql_result($q,0,$data);
}

function redirect($location) {
	if($location=="reload") {
		header("Refresh:0");
		echo "<script> location.reload(); </script>";
	} else {
		header("Location: $location");
		echo "<script> window.location='$location';</script>";
	}
}

function loggedin() {
	if(isset($_SESSION['zpuser'])) {
		$id = $_SESSION['zpuser'];
		$q = mysql_query("SELECT * FROM `users` WHERE `id`='$id'");
		if(mysql_num_rows($q)!=0) {
			return $_SESSION['zpuser'];
		} else {
			session_destroy();
			redirect("reload");
		}
	} else {
		return false;
	}
}
?>