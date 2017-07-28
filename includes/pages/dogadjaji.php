<?php
    if(loggedin()&&$u_admin>=1) {
        echo "<div class='newpost'>
            <div class='title'>
                <span>Kreiraj događaj</span>
				<div class='pointer'></div>
            </div><div class='npcont'>";
				$date = date('d.m.Y');
                $submit = $_POST['submit'];
                if($submit) {
                    $title = $_POST['title'];
                    $text = $_POST['text'];
                    $mesto = $_POST['mesto'];
                    $date = $_POST['datum'];
                    $dateparts = explode(".",$date);
                    $date = $dateparts[2]."-".$dateparts[1]."-".$dateparts[0];
                    $vreme = $_POST['vreme'];
                    
                    if(empty($title)||empty($text)||empty($mesto)||empty($vreme)) {
                        echo "<div class='infomessage error mb-10 mt-10'><div class='img'></div><div class='text'>Popuni sva polja!</div></div>";
                    } else {
                        echo "<div class='infomessage success mb-10 mt-10'><div class='img'></div><div class='text'>Događaj uspešno kreiran.</div></div>";
                        mysql_query("INSERT INTO `dogadjaji` VALUES('','$title','$text','$mesto','$date','$vreme')");
						$oid = mysql_insert_id();
						send_obavestenje("4", $oid, $title, $date);
                    }
                }
                echo "<form class='mt-10' action='/dogadjaji/' method='post'>
                <input type='text' class='inputtext mr-5' name='mesto' placeholder='Mesto' style='width: calc(100% - 155px);'><input type='text' class='inputtext ta-c' style='width: 150px;' name='vreme' placeholder='Vreme (00:00)'>
                <input type='text' class='inputtext wdth-100 mt-10' name='title' placeholder='Naslov'>
                <textarea rows='5' class='wdth-100 mt-10' name='text' placeholder='Opis...'></textarea>
                <input type='hidden' value='$date' name='datum' id='hiddendogadjajdate'>
                <input type='button' value='$date' id='dogadjajdate' class='datebtn floatl mt-10' onClick=\"choosedate('dogadjajdate');\">
                <input type='submit' name='submit' class='inputbtn floatr mt-10' value='Kreiraj događaj'>
                
                <div class='clear'></div>
            </form>
        </div></div>";
    }
    show_dogadjaji();
?>