<?php
    $q = mysql_query("SELECT * FROM `settings` WHERE `value1`='confirm_reg'");
    $confirm_reg = mysql_result($q,0,'value2');
    if($confirm_reg==1) {
        $obvem = "on";
        $bool = 1;
    } else {
        $obvem = "";
        $bool = 0;
    }
    echo "<div class='objava'>
            <div class='title'>
                <h3>Podešavanja</h3>
            </div><div class='cont'>
				<div class='mt-10 ta-l'>
					Članovi moraju da potvrde registraciju preko email-a? 
					<div style='float: right;' class='switch $obvem' id='confirmreg' bool='$bool' onclick=\"switchbtn('confirmreg');\"><div class='circle'></div></div>
					<div class='clear'></div>
				</div>
			</div>
        </div>";
?>