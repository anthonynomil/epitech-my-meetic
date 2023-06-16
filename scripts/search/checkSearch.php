<?php
include_once '../../classes/USER.php';
include_once "../../classes/DBB.php";
include_once "../global/globalFunctions.php";
include_once '../global/checkErrors.php';
checkErrors();
session_start();

if (empty($_POST)) {
    exit("No data");
}
$searchInfos = getSearchInfos();
searchUser($searchInfos);

function searchUser($searchInfos): void
{
    $query = setupSearchQuery($searchInfos);
    $db = new DBB();
    $db->connectToDb();
    $result = $db->executeQuery($query["query"], $query["param"]);
    if (empty($result)) {
        echo json_encode(["error" => "No user found"], JSON_THROW_ON_ERROR);
        exit();
    }
    foreach ($result as $key => $row) {
        foreach ($row as $key2 => $value) {
            if (!is_string($key2)) {
                unset($result[$key][$key2]);
            }
        }
        if ($_SESSION["user"]["firstname"] === $result[$key]["firstname"] &&
            $_SESSION["user"]["city"] === $result[$key]["city"] &&
            $_SESSION["user"]["birthdate"] === $result[$key]["birthdate"] &&
            $_SESSION["user"]["sex"] === $result[$key]["sex"]) {
            unset($result[$key]);
        }
    }
    echo json_encode($result, JSON_THROW_ON_ERROR);
}

function setupSearchQuery($searchInfos): array
{
    $query = "select u.firstname, u.city, u.sex, u.birthdate from user u join user_hobbies uh on u.id = uh.id_user where active = 1 and";
    $queryParam = [];
    $param = [];
    handleSex($searchInfos['sex'], $queryParam);
    handleCities($searchInfos['cities'], $queryParam);
    handleAge($searchInfos['age'], $queryParam);
    handleHobbies($searchInfos["hobbies"], $queryParam);
    $queryParamLast = end($queryParam);
    if (empty($queryParam)) {
        $query = "select u . firstname, u . city, u . sex, u . birthdate from user u";
        return ["query" => $query, "param" => []];
    }
    foreach ($queryParam as $value) {
        if ($value === $queryParamLast) {
            $value["query"] = " " . $value["query"];
        } else {
            $value["query"] = " " . $value["query"] . " and";
        }
        $query .= $value["query"];
        $param[] = $value["param"];
    }
    return ["query" => $query, "param" => array_merge(...$param)];
}

function handleSex($sex, &$queryParam): void
{
    if ($sex !== '') {
        $queryParam["sex"]["query"] = "sex like :sex";
        $queryParam["sex"]["param"][":sex"] = $sex;
    }
}

function handleCities($cities, &$queryParam): void
{
    if (!empty($cities)) {
        $queryParam["cities"]["query"] = "city in(";
        $lastElem = end($cities);
        foreach ($cities as $city) {
            $key = ":city" . str_replace(' ', '', $city);
            $queryParam["cities"]["query"] .= ($city === $lastElem) ? $key : "$key,";
            $queryParam["cities"]["param"][$key] = $city;
        }
        $queryParam["cities"]["query"] .= ")";
    }
}

function handleAge($age, &$queryParam): void
{
    if ($age === '') {
        return;
    }
    $ageDate = [];
    if ($age === '45') {
        $ageDate[] = date('Y-m-d', strtotime('-45 years'));
    } else {
        $age = explode('-', $age);
        foreach ($age as $value) {
            $ageDate[] = date('Y-m-d', strtotime(" - $value years"));
        }
    }
    $queryParam["age"]["query"] = (count($ageDate) > 1) ? "birthdate between :ageDate1 and :ageDate2" : "birthdate <= :ageDate1";
    $queryParam["age"]["param"] = (count($ageDate) > 1) ? ["ageDate1" => $ageDate[1], "ageDate2" => $ageDate[0]] : ["ageDate1" => $ageDate[0]];

}

function handleHobbies($hobbies, &$queryParam): void
{
    if (!empty($hobbies)) {
        $hobbiesId = getHobbiesId($hobbies);
        $queryParam["hobbies"]["query"] = "";
        $firstElem = reset($hobbiesId);
        foreach ($hobbiesId as $hobby) {
            $queryParam["hobbies"]["query"] .= ($hobby === $firstElem)
                ? "uh . id_hobbies like :hobby$hobby"
                : " and uh . id_hobbies like :hobby$hobby";
            $queryParam["hobbies"]["param"]["hobby$hobby"] = '% ' . $hobby . ' %';
        }
    }
}

function getHobbiesId(array $hobbies): array
{
    $db = new DBB();
    $query = "select id from hobbies";
    $param = [];
    $query .= " where name in(";
    $lastElem = end($hobbies);
    foreach ($hobbies as $hobby) {
        $query .= ($hobby === $lastElem) ? ":hobby$hobby" : ":hobby$hobby,";
        $param["hobby$hobby"] = $hobby;
    }
    $query .= ")";
    $result = $db->executeQuery($query, $param);
    $hobbiesId = [];
    foreach ($result as $hobby) {
        $hobbiesId[] = $hobby['id'];
    }
    return $hobbiesId;
}
