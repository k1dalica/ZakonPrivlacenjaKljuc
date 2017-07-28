<?php
   $onpage = $page[0];
   $onpage2 = $page[1];
   if(empty($onpage) || $onpage=="pocetna") {
      include $includepocetna;
   } else if($onpage=="registracionikod") {
      $q = mysql_query("SELECT * FROM `users` WHERE `code`='$onpage2'");
      if(mysql_num_rows($q)!=0) {
         mysql_query("UPDATE `users` SET `verified`='1' WHERE `code`='$onpage2'");
         $userid = mysql_result($q,0,'id');
         $_SESSION['zpuser'] = $userid;
         redirect("/profil/");
      } else {
         redirect("/textovi/");
      }
   } else if($onpage=="unsubscribe") {
      include "header.php";
      include "meni.php";
      include 'pages/unsubscribe.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="pretraga") {
      include "header.php";
      include "meni.php";
      include 'pages/pretraga.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="livestream") {
      include "header.php";
      include "meni.php";
      include 'pages/stream.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="obavestenja") {
      include "header.php";
      include "meni.php";
      include 'pages/obavestenja.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="dogadjaji") {
      include "header.php";
      include "meni.php";
      include 'pages/dogadjaji.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="hipnoterapija") {
      include "header.php";
      include "meni.php";
      include 'pages/hipnoterapija.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="video") {
      include "header.php";
      include "meni.php";
      include 'pages/video.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="textovi") {
      include "header.php";
      include "meni.php";
      include 'pages/textovi.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="registracionikod") {
      include "header.php";
      include "meni.php";
      include 'pages/registracionikod.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="izemniprofil") {
      include "header.php";
      include "meni.php";
      include 'pages/izemniprofil.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="kontakt") {
      include "header.php";
      include "meni.php";
      include 'pages/kontakt.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="profil") {
      include "header.php";
      include "meni.php";
      include 'pages/profil.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="prodavnica") {
      include "header.php";
      include "meni.php";
      echo "Prodavnica";
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="objava") {
      include "header.php";
      include "meni.php";
      include 'pages/objava.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="adminpanel") {
      include "header.php";
      include "meni.php";
      include 'pages/adminpanel.php';
      include "rightside.php";
      include "footer.php";
   } else if($onpage=="odjava") {
      session_destroy();
      redirect("/textovi/");
   } else {
      include $includepocetna;
      die();
   }
?>