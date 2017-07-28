<?php
    $url = $page[1];
    $q = mysql_query("SELECT * FROM `links` WHERE `url`='$url'");
    if(mysql_num_rows($q)!=0) {
        $oid = mysql_result($q,0,'oid');
        $query = mysql_query("SELECT * FROM `objave` WHERE `id`='$oid'");
        if(mysql_num_rows($query)!=0) {
            $id = mysql_result($query,0,'id');
            $title = mysql_result($query,0,'title');
            $text = mysql_result($query,0,'text');
            $date = mysql_result($query,0,'time');
            $img = mysql_result($query,0,'cover');
            $visited = mysql_result($query,0,'visited');
            $image = "/".$img;
            
            if(empty($img)) { $showcover = ""; } else { $showcover = "<div class='image'><img src='$image' alt=''></div>"; }
            
            $text = make_urls_links($text);
            
            if(loggedin()&&get_users_data(loggedin(),'admin')>=1) {
				$addvisits = " ($visited)";
                $ddap = "<div class='dropdownoptions'><div class='arrow'></div>
                    <div class='options'>
                        <div class='option' onClick=\"izmeniobjavu('$id');\">Izmeni</div>
                        <div class='option' onClick=\"obrisiobjavu('$id');\">Obriši</div>
                    </div>
                </div>";
            } else {
				$addvisits = "";
				$ddap = "";
				$visited = $visited + 1;
				mysql_query("UPDATE `objave` SET `visited`='$visited' WHERE `id`='$id'");
			}
            echo "<div class='objava'>
				<div class='title'>
					<h3>$title $addvisits</h3>
					<font>$date</font>
					$ddap
				</div>$showcover<div class='text'>$text";
					$pageurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				echo "<div class='mt-10'><div class='fb-share-button' data-href='$pageurl' data-layout='button_count' data-size='large' data-mobile-iframe='true'><a class='fb-xfbml-parse-ignore' target='_blank' href='$pageurl'>Share</a></div></div>
				</div>
			</div>";
        } else {
            redirect("/textovi/");
        }
    } else {
        redirect("/textovi/");
    }
?>