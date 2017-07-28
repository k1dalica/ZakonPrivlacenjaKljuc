<?php
	header('Content-Type: application/json');
	$charset = "asdfghjklzxcvbnmqwertyuiopASDFGHJKLZXCVBNMQWERTYUIOP0123456789";
    $prefix = substr(str_shuffle($charset), 0, 20);
	if(!empty($_FILES['file']['name'])) {
		$name = $_FILES['file']['name'];
		$extension = end(explode(".", $name));
        $location = "uploads/". $prefix .".".$extension;
		if($extension == "jpg" || $extension == "jpeg" || $extension == "png" || $extension == "bmp") {
			if(move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
				echo $location;
			}
		}
	}
?>
