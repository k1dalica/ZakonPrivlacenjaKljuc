<?php
    prikazi_newpost($strana);
    if(isset($page[1])&&is_numeric($page[1])) {
		$nas = $page[1];
	} else {
		$nas = 1;
	}
	prikazi_hipnoterapije($nas);
?>