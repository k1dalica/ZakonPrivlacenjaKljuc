
<?php
    $src = $_POST['search'];
    echo "<div class='contentbox'>
        <form action='/pretraga/' method='post'>
            <input type='text' placeholder='Pretraga...' value='$src' name='search' class='inputtext' style='width: calc(100% - 40px);'><input type='submit' name='submit' class='btnsrc' value=''>
        </form>
    </div>";
    if(isset($_POST['submit'])&&!empty($src)){
        $query = "(SELECT `id`,`title`, `desc` AS text, 'video' AS type, `date` AS datum FROM `video` WHERE `title` LIKE '%$src%' OR `desc` LIKE '%$src%') UNION (SELECT `id`, `title`, `text`, 'objava' AS type, `dateandtime` AS datum FROM `objave` WHERE `text` LIKE '%$src%' OR `title` LIKE '%$src%') UNION (SELECT `id`,`name` AS title, `desc` AS text, 'dogadjaj' AS type, `date` AS datum FROM `dogadjaji` WHERE `name` LIKE '%$src%' OR `desc` LIKE '%$src%') ORDER BY datum DESC";
        $q = mysql_query($query);
        $numrows = mysql_num_rows($q);
        if($numrows!=0) {
            echo "<div id='npmsg1' class='infomessage mb-10'><div class='img'></div><div class='text'>Broj rezultata pretrage: $numrows</div></div>";
            while ($row = mysql_fetch_assoc($q)) {
                $type = $row['type'];
                $id = $row['id'];
                $date = $row['datum'];
                
                $title = $row['title'];
                $title = preg_replace('#('.$src.')#i','<mark>\1</mark>',$title) ;
                
                $text = $row['text'];
                $text = strip_tags($text);
                $text = substr($text, 0, 300);
                $text = preg_replace('#('.$src.')#i','<mark>\1</mark>',$text) ;
                
                if($type=="objava") {
                    $naslov = $row['title'];
                    
                    $qo = mysql_query("SELECT * FROM `objave` WHERE `id`='$id'");
                    $img = mysql_result($qo,0,'cover');
                    
                    $qe = mysql_query("SELECT * FROM `links` WHERE `oid`='$id'");
                    $url = mysql_result($qe,0,'url');
                    
                    if(empty($img)) { $showcover = ""; } else { $showcover = "<div class='image'><img src='/$img' alt='$naslov'></div>"; }
                    echo "<div class='objava mb'>
                        <div class='title'>
                            <h3><a href='/objava/$url/'>$title</a></h3>
                            <font>$date</font>
                        </div>$showcover<div class='text'>
                            $text	
                        </div><div class='button'>
                            <a href='/objava/$url/'><input type='button' value='Pogledaj celu objavu...' class='inputbtn'></a>
                        </div>
                    </div>";
                } else if($type=="video") {
                    $qe = mysql_query("SELECT * FROM `video` WHERE `id`='$id'");
                    $url = mysql_result($qe,0,'url');
                    
                    $dnt = explode(" ", $row['datum']);
                    $date = explode("-", $dnt[0]);
                    $datum = $date[0].".".$date[1].".".$date[2].".";
                    $time = $dnt[1];
                    $desc = $row['text'];
                    $desc = preg_replace('#('.$src.')#i','<mark>\1</mark>',$desc) ;
                    echo "<div class='objava mb'>
                        <div class='title'>
                            <h3>$title</h3>
                            <font>$datum u $time</font>
                        </div><div class='text'>
                            $desc
                            <div class='videoWrapper mt-10'><iframe width='632' height='355' src='https://www.youtube.com/embed/$url' frameborder='0' allowfullscreen=''></iframe></div>
                        </div>
                    </div>";
                } else if($type=="dogadjaj"){
                    $qe = mysql_query("SELECT * FROM `dogadjaji` WHERE `id`='$id'");
                    $mesto = mysql_result($qe,0,'mesto');
                    $datep = explode(" ",$row['datum']);
                    $datep = explode("-",$datep[0]);
                    $y = $datep[0]; $m = $datep[1]; $d = $datep[2];
                    $day = mktime(0,0,0,$m,$d,$y);
                    $day = day_to_dan(date('D', $day));
                    $mesec = substr(brmesec_to_mesec($datep[1]), 0, 3);
                    $desc = $row['text'];
                    $desc = preg_replace('#('.$src.')#i','<mark>\1</mark>',$desc) ;
                    
                    $passeddate = new DateTime("$y-$m-$d");
                    $now = new DateTime();
                    if($passeddate >= $now) { $passed = ""; } else { $passed = "passed"; }
                    echo "<div class='objava mb'>
                        <div class='title dogadjaj'>
                            <div class='datum $passed'>
                                <span>$mesec</span>
                                <font>$datep[2]</font>
                            </div>
                            <h3>$title</h3>
                            <font>$mesto</font>
                        </div><div class='text'>
                            $desc
                        </div>
                    </div>";   
                }
            }
        } else {
            echo "<div id='npmsg1' class='infomessage mb-10 notify'><div class='img'></div><div class='text'>Ništa nije pronađeno.</div></div>";
            no_content();
        }
    } else {
        echo "<div id='npmsg1' class='infomessage mb-10'><div class='img'></div><div class='text'>Unesite željene ključne reči za pretragu.</div></div>";
        no_content();
    }
?>