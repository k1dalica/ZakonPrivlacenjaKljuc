</div><div class='cright'>
<?php
    $obq = mysql_query("SELECT * FROM `obavestenja` WHERE `uid`='$u_id' AND `seen`='0'");
	$obavrow = mysql_num_rows($obq);
	if($obavrow>=1) {
		$obavrow = "<font color='#d04460'>($obavrow)</font>";
	} else { $obavrow = ""; }
    if(loggedin()) {
        echo "<div class='contentbox mb'>
            <div class='miniprofile'>
                <div class='avatar' style='background-image: url(/$u_avatar);'>
                </div><div class='text'>
                    <span>$u_username</span>
                    <font>$u_name</font>
                    <font>$u_email</font>
                </div>
            </div><div class='zbuttons mt-10'>
                <a href='/profil/'>Profil</a>
                <a href='/izemniprofil/'>Izmeni profil</a>
                <a href='/obavestenja/'>Obaveštenja $obavrow</a>
                <a href='/odjava/'>Odjavi se</a>
            </div>
        </div>";
    }
?>  
    <div class="contentbox objava mtn">
        <div class="title"> 
            <span>Kalendar događaja</span>
        </div><div id="kalendar">
		<?php
			$date = time();
			$month = date('m',$date);
			$year = date('Y',$date);
			display_calendar($month, $year, "justnormalcalender");
        ?>    
        </div>
    </div>
    
    <?= show_infstudio_ad(1);?>
	
    <div class="contentbox objava mt">
        <div class="title"> 
            <span>Najpopularnije objave</span>
        </div><div class="zbuttons mt-10">
            <?= display_najpopularnije_objave(); ?>
        </div>
    </div>
    
    <div class="contentbox objava mt" style="margin-bottom: 0px;">
        <div class="title"> 
            <span>Najnovije objave</span>
        </div><div class="zbuttons mt-10">
            <?= display_najnovije_objave(); ?>
        </div>
    </div>
</div>