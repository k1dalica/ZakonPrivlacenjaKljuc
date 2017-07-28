<?php
    if(!loggedin()&&!empty($onpage2)) {
        $q = mysql_query("SELECT * FROM `users` WHERE `code`='$onpage2'");
        if(mysql_num_rows($q)!=0) {
            if(mysql_result($q,0,'verified')==0) {
                mysql_query("UPDATE `users` SET `verified`='1' WHERE `code`='$onpage2'");
                $_SESSION['zpuser'] = mysql_result($q,0,'id');
                redirect("/objave/");
            } else {
               echo "<div class='infomessage error'><div class='img'></div><div class='text'>Tvoj nalog je već aktiviran!</div></div>";
            }
        } else {
           redirect("/objave/");
        }
    } else {
       redirect("/objave/");
    }
?>