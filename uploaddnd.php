<?php
	header('Content-Type: application/json');
	$charset = "asdfghjklzxcvbnmqwertyuiopASDFGHJKLZXCVBNMQWERTYUIOP0123456789";
    $prefix = substr(str_shuffle($charset), 0, 20);
	$uploaded = array();
	if(!empty($_FILES['file']['name'][0])) {
		$name = $_FILES['file']['name']['0'];
		$extension = end(explode(".", $name));
        $location = "uploads/". $prefix .".".$extension;
		if($extension == "jpg" || $extension == "jpeg" || $extension == "png" || $extension == "bmp") {
			if(move_uploaded_file($_FILES['file']['tmp_name']['0'], $location)) {
				echo $location;
			} else {
				echo "/";
			}
		} else {
			echo "/";
		}
	} else {
		echo "/";
	}
?>