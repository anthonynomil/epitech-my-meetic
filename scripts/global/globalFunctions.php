<?php

include_once "../classes/DBB.php";

function getUserInfos(): array
{
    return [
        "firstname" => $_POST['firstname'] ?? '',
        "lastname" => $_POST['lastname'] ?? '',
        "sex" => $_POST['sex'] ?? '',
        "city" => $_POST['city'] ?? '',
        "email" => $_POST['email'] ?? $_SESSION['user']['email'],
        "password" => $_POST['password'] ?? '',
        "birthdate" => $_POST['birthdate'] ?? '',
        "hobbies" => empty($_POST['hobbies']) ? [] : $_POST['hobbies'],
    ];
}

function getSearchInfos(): array
{
    return [
        "sex" => $_POST['sex'] ?? '',
        "cities" => empty($_POST['cities']) ? [] : $_POST['cities'],
        "age" => $_POST['age'] ?? '',
        "hobbies" => empty($_POST['hobbies']) ? [] : $_POST['hobbies'],
    ];
}

function getHobbies(bool $formatted = false): array
{
    $db = new DBB();
    $result = $db->executeQuery("SELECT id `hobbiesId`, name `hobbiesName` FROM hobbies");
    foreach ($result as $value) {
        if ($formatted) {
            $hobbies[$value['hobbiesId']] = $value['hobbiesName'];
        } else {
            $hobbies[$value['hobbiesName']] = $value['hobbiesId'];
        }
    }
    return $hobbies ?? [];
}
