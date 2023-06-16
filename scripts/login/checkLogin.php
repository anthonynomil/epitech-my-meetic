<?php

include_once "../../classes/USER.php";
include_once "../../classes/DBB.php";
include_once "../global/globalFunctions.php";
include_once "../global/checkErrors.php";
checkErrors();

if (isset($_POST['email'])) {
    $userInfos = getUserInfos();
    $newUser   = new USER($userInfos);
    $numUser   = $newUser->login();
    if ($numUser) {
        $newUser->setUserSession();
    }
    echo $numUser;
}
