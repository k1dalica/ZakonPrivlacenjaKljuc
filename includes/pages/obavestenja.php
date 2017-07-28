<?php
    if(loggedin()) {
        $obvid = $page[1];
        $q = mysql_query("SELECT * FROM `obavestenja` WHERE `id`='$obvid'");
        $obfor = mysql_result($q,0,'uid');
        if(isset($page[1])&&!empty($page[1])&&mysql_num_rows($q)!=0&&$obfor==$u_id) {
            mysql_query("UPDATE `obavestenja` SET `seen`='1' WHERE `id`='$obvid'");
            $title = mysql_result($q,0,'title');
            $desc = mysql_result($q,0,'desc');
            $link = mysql_result($q,0,'link');
            $date = mysql_result($q,0,'date');
            echo "<a href='/obavestenja/'><input type='button' class='inputbtn mb-10 floatl' value='Nazad'></a>
            <div class='clear'></div>
            <div class='objava'>
                <div class='title'>
                    <h3>$title</h3>
                    <font>$date</font>
                    <div class='dropdownoptions'><div class='arrow'></div>
                        <div class='options'>
                            <div class='option' onclick=\"obrisiobavestenje('$obvid');\">Obriši</div>
                        </div>
                    </div>
                </div><div class='cont ta-l'>
                    <div class='mt-10'>$desc</div>
                    <a href='$link'><input type='button' class='inputbtn mt-10' value='Pogledaj'></a>
                </div>
            </div>";
        } else {
            echo "<div class='objava'>
                <div class='title'>
                    <h3>Obaveštenja</h3>
                </div><div class='cont ta-l'>";
                $q = mysql_query("SELECT * FROM `obavestenja` WHERE `uid`='$u_id' ORDER BY `id` DESC");
                if(mysql_num_rows($q)!=0) {
                    while($row = mysql_fetch_assoc($q)) {
                        $id = $row['id'];
                        $title = $row['title'];
                        $link = $row['link'];
                        $seen = $row['seen'];
                        $date = explode(" ", $row['date']);
                        $date = $date[1];
                        
                        if($seen==0) {
                            $seen = "";
                        } else { $seen = "seen"; }
                        echo "<div class='message $seen'>
                            <a href='/obavestenja/$id/'><div class='naslov' style='width: calc(100% - 100px);'>
                                $title
                            </div></a><div class='date'>
                                $date.
                            </div>
                        </div>";
                    }
                } else {
                    no_content();
                }
                echo "</div>
            </div>";
        }
    } else {
        redirect("/objave/");
    }
?>