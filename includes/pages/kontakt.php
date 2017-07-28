<div class="contentbox">
    <div class="title">
        <span>Kontakt</span>
    </div><div class="cont profil">
    <form action="/kontakt/" method="post">
        
<?php
    $submit = $_POST['submit'];
    if($submit) {
        $title = $_POST['title'];
        $text = nl2br($_POST['text']);
        $errors = array();
        if(loggedin()) {
            $type = 1;
            if(empty($title)||empty($text)) {
                $errors[] = "Popuni sva polja!";
            }
            $sender = loggedin();
        } else {
            $type = 0;
            $name = $_POST['name'];
            $email = $_POST['email'];
            $text = "Poruka od <b>$name</b><br /><br />$text";
            if(empty($name)||empty($email)||empty($title)||empty($text)) {
                $errors[] = "Popuni sva polja!";
            }
            if(!empty($email)&&!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Unesite ispravnu email adresu!";
            }
             $sender = $email;
        }
        
        if(!$errors) {
            echo "<div class='infomessage success mb-10'><div class='img'></div><div class='text'>Poruka poslata. Potrudićemo se da vam odgovorimo u što kraćem roku.<br />Odgovor će stići na Vašu email adresu.</div></div>";
            mysql_query("INSERT INTO `poruke` VALUES('','$sender','$title','$text','$dateandtime','0','0','$type','0')");
        } else {
            echo "<div class='infomessage error mb-10'><div class='img'></div><div class='text'>";
            foreach($errors as $error) {
                echo "$error<br />";
            }
            echo "</div></div>";
        }
    }

    if(!loggedin()) {
        echo "<input type='text' class='inputtext mb-10' name='name' placeholder='Ime i Prezime' style='width: calc(50% - 5px); margin-right: 10px;'>";  
        echo "<input type='text' class='inputtext mb-10' name='email' placeholder='Email adresa' style='width: calc(50% - 5px);'>";  
    }
?>
    <input type="text" class="inputtext wdth-100" name="title" placeholder="Naslov">
    <textarea rows="5" class="wdth-100 mt-10" name="text" placeholder="Poruka..."></textarea>
    <input type="submit" name="submit" class="inputbtn mt-10" value="Pošalji">
    </form>
    </div>
</div>
<?= show_infstudio_ad(1); ?>