<?php

// automatically login default student user
if(c::get("autologin") == true) {
    $username = "student";
    $password = "student";

    // only login user if user is registered
    if(site()->user() == false) {
        $user = site()->user($username);
        if($user) $user->login($password);
    }
}