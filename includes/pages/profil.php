<?php
    $uid = $page[1];
    if(loggedin()) {
        if(isset($uid)) {
            if(does_user_exist($uid)) {
                $clan = $uid;
            } else {
                $clan = $u_id;
            }
        } else {
            $clan = $u_id;
        }
    } else {
        if(isset($uid)) {
            if(does_user_exist($uid)) {
                $clan = $uid;
            } else {
                redirect("/clanovi/");
            }
        } else {
            redirect("/clanovi/");
        }
    }
    display_users_profile($clan);
    show_infstudio_ad(1);
?>