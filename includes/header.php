<!DOCTYPE html>
<html>
	<head>
		<link href="/includes/style.css" rel="stylesheet" type="text/css">
		<link rel="icon" type="image/png" href="http://www.zakonprivlacenjakljuc.com/includes/images/favicon.png">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta content="width=device-width, initial-scale=1" name="viewport">
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
		<?php
			$Ptitle = $website_name;
			$Pdesc = "Zakon akcije i reakcije kroz Univrzalni govor vibracije. Sve o zakonu privlačenja, vibraciji, umu i njegovim svojstvima, unutrašnjem vođstvu kao i o Vortexu";
			$Purl = $website_url;
			$Pimg = "http://www.zakonprivlacenjakljuc.com/ogimg.jpg";
			$objurl = $page[1];
			$query = mysql_query("SELECT * FROM `links` WHERE `url`='$objurl'");
			if($page[0]=="objava"&&isset($page[1])&&!empty($page[1])&&mysql_num_rows($query)!=0) {
				$oid = mysql_result($query,0,'oid');
				$q = mysql_query("SELECT * FROM `objave` WHERE `id`='$oid'");
				$Ptitle = mysql_result($q,0,'title');
				$Pdesc = strip_tags(mysql_result($q,0,'text'));
				$Pdesc = substr($Pdesc, 0, 300);
				$coverimg = mysql_result($q,0,'cover');
				$Purl = $website_url."/objava/$objurl/";
				echo "<title>$Ptitle - Zakon Privlačenja Ključ</title>
				<meta property='og:url'     content='http://zakonprivlacenjakljuc.com/objava/$objurl' />
				<meta property='og:type'          content='website' />
				<meta property='og:title'         content=\"$Ptitle\" />
				<meta property='og:description'   content=\"$Pdesc\" />
				<meta property='og:image'         content='http://zakonprivlacenjakljuc.com/$coverimg' />";
			} else {
				if($page[0]=="textovi") {
					$Ptitle = "Textovi - $website_name";
					$Purl = "$website_url/textovi/";
				} else if($page[0]=="video") {
					$Ptitle = "Video - $website_name";
					$Purl = "$website_url/video/";
				} else if($page[0]=="hipnoterapija") {
					$Ptitle = "Hipnoterapija - $website_name";
					$Purl = "$website_url/hipnoterapija/";
				} else if($page[0]=="dogadjaji") {
					$Ptitle = "Događaji - $website_name";
					$Purl = "$website_url/dogadjaji/";
				} else if($page[0]=="profil") {
					$Ptitle = "Profil člana - $website_name";
					$Purl = "$website_url/profil/";
				} else if($page[0]=="kontakt") {
					$Ptitle = "Kontakt - $website_name";
					$Purl = "$website_url/kontakt/";
				}
				echo "<title>$Ptitle</title>
				<meta content='$Pdesc' name='description'>
				<meta property='og:url' content='$Purl'>
				<meta property='og:type' content='Website'>
				<meta property='og:title' content='$Ptitle'>
				<meta property='og:description' content='$Pdesc'>
				<meta property='og:image' content='http://www.zakonprivlacenjakljuc.com/ogimg.jpg'>";
			}
		?>
	</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=690133077807772";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<center>
	<?php
		if($page[1]=='hipnoterapija') {
			$strana = 2;
		} else {
			$strana = 1;
		}
		if(loggedin()&&$u_admin==1) {
			echo "<div id='writermode'>
				<div class='title'>
					<input type='text' value='' placeholder='Naslov' id='wmtitle'>
					<div class='shrink'></div>
				</div><div class='bbc'>
					<div class='width'>
						<div class='bbcbtn' style='background-image: url(/includes/images/bbc/bold.png);' onclick=\"formatText(wmtext,'b');\">
						</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/italic.png);' onclick=\"formatText(wmtext,'i');\">
						</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/underline.png);' onclick=\"formatText(wmtext,'u');\">
						</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/striked.png);' onclick=\"formatText(wmtext,'s');\">
						</div><div class='bbcbtn' style='background-image: url(/includes/images/bbc/h1.png);' onclick=\"formatText(wmtext,'h3');\">
						</div>
						<input type='button' id='savebtn' class='floatr' value='Objavi' onClick=\"newpost('2','$strana');\">
					</div>
				</div>
				<textarea id='wmtext' placeholder='Text...'></textarea>
			</div>";
		}
	?>
	
	<div id="loading">
		<span>0%</span>
	</div>
	
	<div id="popup">
		<div class="close"></div>
		<div id="popupcontnent"></div>
	</div>
	
	<div id="cover">
		<a href="/pocetna/"><img src="/includes/images/logo.png" class='logo'></a>
		<h1><?= $website_name;?></h1>
		<h2>Zakon akcije i reakcije kroz Univrzalni govor vibracije.<br>Sve o zakonu privlačenja, vibraciji, umu i njegovim svojstvima, unutrašnjem vođstvu kao i o Vortexu</h2>
		<img src="/includes/images/DPhandwriting.png" class="handwriting">
		<div class="social">
			<a href="https://www.facebook.com/SaAbrahamom/" target="_blank"><div class="facebook"></div></a>
			<a href="https://www.youtube.com/channel/UCCWof0VbnA7eqB5gUweKkeQ" target="_blank"><div class="youtube"></div></a>
		</div>
	</div>