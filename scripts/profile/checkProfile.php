<?php
include_once '../../classes/USER.php';
include_once "../../classes/DBB.php";
include_once "../global/globalFunctions.php";
include_once '../global/checkErrors.php';
checkErrors();
session_start();

$userInfos = getUserInfos();

if (isset($_POST['deleteAccount'])) {
    $newUser = new USER($userInfos);
    $newUser->deactivateAccount();
    echo "true";
    exit();
}

if (isset($_POST["updateProfile"])) {
    $newUser = new USER($userInfos);
    echo $newUser->updateProfile($_SESSION['user']["email"]);
}

