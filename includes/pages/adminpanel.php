<?php
    if(loggedin()&&$u_admin>=1) {
        if($page[1]=="clanovi") {
            include 'adminpanel/clanovi.php';
        } else if($page[1]=="poruke") {
            include 'adminpanel/poruke.php';
        } else if($page[1]=="prodavnica") {
            
        } else if($page[1]=="statistika") {
            include 'adminpanel/statistika.php';
        } else if($page[1]=="livestream") {
            include 'adminpanel/stream.php';
        } else {
            include 'adminpanel/main.php';
        }
    } else {
        redirect("/textovi/");
    }
?>