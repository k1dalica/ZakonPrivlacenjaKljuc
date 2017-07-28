<?php
    $query = mysql_query("SELECT * FROM `poruke` WHERE `id`='$page[2]'");
    if(isset($page[2])&&!empty($page[2])&&mysql_num_rows($query)!=0) {
        $submit = $_POST['submit'];
        mysql_query("UPDATE `poruke` SET `seen`='1' WHERE `id`='$page[2]'");
        $id = mysql_result($query,0,'id');
        $sender = mysql_result($query,0,'sender');
        $member = mysql_result($query,0,'member');
        $seen = mysql_result($query,0,'seen');
        $title = mysql_result($query,0,'title');
        $text = mysql_result($query,0,'text');
        $replied = mysql_result($query,0,'replied');
        $date = explode(" ", mysql_result($query,0,'date'));
        $date = $date[1] ." u ". $date[0];
        if($member==1) {
            $email = get_users_data($sender,'email');
        } else {
            $email = $sender;
        }
        if($submit) {
            $txt = nl2br($_POST['text']);
            if(!empty($txt)) {
                echo "<div class='infomessage mb success'><div class='img'></div><div class='text'>Poruka uspešno poslata!</div></div>";
                $subject = "Zakon Privlačenja Ključ - Odgovor na vašu poruku";
                $headers = "From: Zakon Privlačenja Ključ <office@zakonprivlacenjakljuc.com>" . "\r\n" .
                "Reply-To: office@zakonprivlacenjakljuc.com" . "\r\n" .
                "Content-Type: text/html; charset=ISO-8859-1\r\n";
                mail($email,$subject,$txt,$headers);
                mysql_query("UPDATE `poruke` SET `replied`='1' WHERE `id`='$id'");
				$now = date("d.m.Y H:i");
				mysql_query("INSERT INTO `reply` VALUES('','$id','$now','$txt')");
            } else {
                echo "<div class='infomessage mb notify'><div class='img'></div><div class='text'>Napišite odgovor!</div></div>";
            }
        }
        $repliedmsg = "";
        if($replied==1) {
            $repliedmsg = "<div class='infomessage mt-10 notify'><div class='img'></div><div class='text'>Vec ste odgovorili na ovu poruku!</div></div>";
			$replyq = mysql_query("SELECT * FROM `reply` WHERE `pid`='$id'");
			$rdate = mysql_result($replyq,0,'date');
			$rtext = mysql_result($replyq,0,'text');
			$odgovor =  "<div class='objava mt'>
				<div class='title'>
					<h3>Vaš odgovor</h3>
					<font>$rdate</font>
				</div><div class='cont ta-l' style='padding-top: 10px'>
					$rtext
				</div>
			</div>"; 
        }
        echo "<div class='objava'>
            <div class='title'>
                <h3>$title</h3>
                <font>$date</font>
                <div class='dropdownoptions'><div class='arrow'></div>
					<div class='options'>
						<div class='option' onclick=\"obrisiporuku('$id');\">Obriši</div>
					</div>
				</div>
            </div><div class='cont ta-l' style='padding-top: 10px'>
                $text
            </div>
        </div>$odgovor<div class='objava mt'>
            <div class='title'>
                <h3>Pošalji odgovor</h3>
				<font>Odgovor će biti poslat na $email</font>
            </div><div class='cont'>
            <form action='/adminpanel/poruke/$id/' method='post'>
                $repliedmsg
                <textarea rows='5' class='wdth-100 mt-10' name='text' placeholder='Odgovor...'></textarea>
                <input type='submit' name='submit' class='inputbtn mt-10' value='Pošalji'>
                </form>
            </div>
        </div>"; 
    } else {
		$q = mysql_query("SELECT * FROM `poruke` WHERE `deleted`='0' ORDER BY `id` DESC");
		$brporuka = mysql_num_rows($q);
		echo "<div class='objava'>
            <div class='title'>
                <h3>Poruke</h3>
				<font>Ukupno poruka: $brporuka</font>
				<div class='dropdownoptions'><div class='arrow'></div>
					<div class='options' style='width: 220px;'>
						<div class='option' onclick=\"oznaciporukekao('1');\">Označi kao pročitane</div>
						<div class='option' onclick=\"oznaciporukekao('0');\">Označi kao nepročitane</div>
						<div class='option' onclick=\"deleteapmessagesask();\">Obriši poruke</div>
					</div>
				</div>
            </div><div class='cont ta-l'>";
				echo "<div style='padding: 10px 0 5px 0; border-bottom: 1px solid #e3e3e3;'>
					<input type='checkbox' id='ap-msg-cbx' style='width: 21px; height: 21px; margin: 0px; vertical-align: middle; margin-left: 7px;'> Označi sve?
				</div>";
            if($brporuka!=0) {
				echo "<div id='ap-poruke'>";
                while($row = mysql_fetch_assoc($q)) {
                    $id = $row['id'];
                    $sender = $row['sender'];
                    $member = $row['member'];
                    $seen = $row['seen'];
                    $title = $row['title'];
                    $date = explode(" ", $row['date']);
                    $date = $date[1];
                    $sm = "email";
                    if($member==1) {
                        $sender = "<a href='/profil/$sender/' target='_blank'>".get_users_data($sender,'username')."</a>";
                        $sm = "member";
                    }
                    if($seen==0) {
                        $seen = "";
                    } else { $seen = "seen"; }
                    echo "<div class='message $seen' mid='$id'>
                        <div class='cbx'>
							<input type='checkbox' style='width: 15px; height: 15px; margin: 0px;'>
						</div><a href='/adminpanel/poruke/$id/'><div class='naslov'>
                            $title
                        </div></a><div class='$sm'>
                            $sender
                        </div><div class='date'>
                            $date.
                        </div>
                    </div>";
                }
				echo "</div>";
            } else {
                no_content();
            }
            echo "</div>
        </div>"; 
    }
?>