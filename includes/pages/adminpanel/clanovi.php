<?php
    $q = mysql_query("SELECT * FROM `users`");
    $count = mysql_num_rows($q);
    echo "<div class='objava'>
            <div class='title'>
                <h3>Članovi ($count)</h3>
            </div><div class='cont memberslist'>";
			while($row = mysql_fetch_assoc($q)) {
                $uid = $row['id'];
                $username = $row['username'];
                $avatar = $row['avatar'];
                echo "<div class='tr'>
                    <div class='avatar' style='background-image: url(/$avatar);'></div><div class='username'>
                        $username
                        <a href='/profil/$uid/' target='_blank'><input type='button' class='inputbtn floatr' value='Profil'></a>
                    </div>
                </div>";
            }
			echo "</div>
        </div>";
?>