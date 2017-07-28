<?php
    dodaj_video();
    if(isset($page[1])) {
		if(is_numeric($page[1])) {
			$nas = $page[1];
		} else {
			$nas = 1;
		}
	} else {
		$nas = 1;
	}
    prikazi_video($nas);
?>