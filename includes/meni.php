<div class="header" id="header" align="center">
	<div class="menionsmall">
		<div class="m-mainmenu">
			Meni
		</div><?php
			if(loggedin()) {
				echo "<a href='/profil/'><div class='m-profile'></div></a>";
			} else {
				echo "<div class='m-profile' onClick='loginregister();'></div>";
			}
		?><div class='m-rightmeni'></div>
	</div>
    <div class="meni">
        <div class='left'>
		<?php
			$onpage = $page[0];
			$onpage2 = $page[1];
			$selected = "class='selected2'";
			$submenu = 0;
			if($onpage=="textovi") {
				$p1 = $selected;
			} else if($onpage=="video") {
				$p2 = $selected;
			} else if($onpage=="hipnoterapija") {
				$p3 = $selected;
			} else if($onpage=="dogadjaji") {
				$p4 = $selected;
			} else if($onpage=="prodavnica") {
				//$p3 = "$selected";
			} else if($onpage=="kontakt") {
				$p5 = $selected;
			} else if($onpage=="livestream") {
				$p7 = $selected;
			} else if($onpage=="izemniprofil"||$onpage=="profil") {				
			} else if($onpage=="adminpanel") {
				$p6 = $selected;
				$submenu = 1;
			}
            echo "<a href='/textovi/' $p1>Tekstovi</a><div class='dl'></div>";
            echo "<a href='/video/' $p2>Video</a><div class='dl'></div>";
            echo "<a href='/hipnoterapija/' $p3>Hipnoterapija</a><div class='dl'></div>";
            echo "<a href='/dogadjaji/' $p4>Događaji</a><div class='dl'></div>";
            echo "<a href='/livestream/' $p7>Live stream</a><div class='dl'></div>";
			//echo "<a href='/prodavnica/' $p3>Prodavnica</a><div class='dl'></div>";
			echo "<a href='/kontakt/' $p5>Kontakt</a>";
            
			if(loggedin()&&$u_admin==1) {
				echo "<div class='dl'></div><a href='/adminpanel/' $p6>Admin Panel</a>";
			}
		?>
        </div><div class="right">
        <?php
            if(loggedin()) {
                echo "<a href='/profil/'><div class='loggedin'>
                    <span>$u_username</span>
                    <div class='avatar' style='background-image: url(/$u_avatar);'></div>
                </div></a>";
            } else {
                echo "<div class='nalog' onClick='loginregister();'>Nalog</div>"; 
            }
        ?>
        </div>
    </div>
</div>

<?php
	$q = mysql_query("SELECT * FROM `poruke` WHERE `seen`='0'");
	$count = mysql_num_rows($q);
	if($count!=0) {
		$np = " <font color='#d04460'>($count)</font>";
	} else { $np = ""; }
	
	if($submenu==1) {
		if($onpage2=="clanovi") {
			$sm2 = "class='selected'";
		} else if($onpage2=="poruke") {
			$sm3 = "class='selected'";
		} else if($onpage2=="prodavnica") {
			$sm4 = "class='selected'";
		} else if($onpage2=="statistika") {
			$sm5 = "class='selected'";
		} else if($onpage2=="livestream") {
			$sm6 = "class='selected'";
		} else {
			$sm1 = "class='selected'";
		}
		echo "<div class='submeni'>
			<div class='meni' style='width: 100%'>";
				echo "<a href='/adminpanel/' $sm1>Podešavanja</a>";
				echo "<a href='/adminpanel/clanovi/' $sm2>Članovi</a>";
				echo "<a href='/adminpanel/poruke/' $sm3>Poruke".$np."</a>";
				echo "<a href='/adminpanel/prodavnica/' $sm4>Prodavnica</a>";
				echo "<a href='/adminpanel/statistika/' $sm5>Statistika</a>";
				echo "<a href='/adminpanel/livestream/' $sm6>Live stream</a>";
			echo "</div>
		</div>";
	}
?>

<div class="content" align='left'>
	<div class='cleft' align='center'>