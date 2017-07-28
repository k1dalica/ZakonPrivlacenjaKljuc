<?php
    $q = mysql_query("SELECT * FROM `users` WHERE `code`='$onpage2'");
    if(mysql_num_rows($q)!=0) {
        $email = mysql_result($q,0,'email');
        mysql_query("UPDATE `users` SET `subscribed`='0' WHERE `code`='$onpage2'");
        echo "<div id='zlmsg' class='infomessage success'><div class='img'></div><div class='text'>Uspešno ste isključili obaveštenja za email adresu <b>$email</b>!</div></div>";
    } else {
        redirect("/textovi/");
    }
?>