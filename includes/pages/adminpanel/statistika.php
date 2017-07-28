<?php
    $objave = count_objave();
	$clanovi = count_users();
	$posete = count_posete();
	$countkome = count_kom();
	$online = trenutno_online();
	$pobjave = pregledane_objave();
	$brvidea = count_video();

	$q = mysql_query("SELECT `ip`,`uid` FROM `visits` WHERE `page` LIKE '%/index.php?objavaid=89%' GROUP BY ip, uid");
	$bru1 = mysql_num_rows($q);

    echo "<div class='statistics'>
        <div class='stat-box'>
            <div class='stat-name'>Stat name</div>
            <div class='stat-value'>Val</div>
        </div>
    </div>";
?>
