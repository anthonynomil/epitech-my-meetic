<?php
include_once "../scripts/global/checkErrors.php";
include_once '../scripts/global/globalFunctions.php';
include_once "./DBB.php";

class USER
{
    private const EMAIL = ':email';
    private const FIRSTNAME = ':firstname';
    private const LASTNAME = ':lastname';
    private const BIRTHDATE = ':birthdate';
    private const CITY = ':city';
    private const SEX = ':sex';
    private const PASSWORD = ':password';
    private const ID = ':id';
    private const ID_HOBBIES = ':id_hobbies';
    public array $userInfos;

    public function __construct(
        array $userInfos
    )
    {
        $this->userInfos = $userInfos;
    }

    public function register(): bool
    {
        $db = new DBB();
        $db->connectToDb();
        if ($this->checkDuplicateEmail($db)) {
            $query
                = "insert into user (firstname, lastname, email, birthdate, city, sex, password) values (:firstname, :lastname, :email, :birthdate, :city, :sex, :password)";
            $param = [
                self::FIRSTNAME => $this->userInfos["firstname"],
                self::LASTNAME => $this->userInfos["lastname"],
                self::EMAIL => $this->userInfos["email"],
                self::BIRTHDATE => $this->userInfos["birthdate"],
                self::CITY => $this->userInfos["city"],
                self::SEX => $this->userInfos["sex"],
                self::PASSWORD => md5($this->userInfos["password"]),
            ];
            $db->executeQuery($query, $param);
            $this->handleHobbies($db);
            return true;
        }
        return false;
    }

    private function checkDuplicateEmail(DBB $db): bool
    {
        $query = "select email from user where email = :email";
        $param = [
            self::EMAIL => $this->userInfos["email"]
        ];
        $result = $db->executeQuery($query, $param);
        return empty($result);
    }

    private function handleHobbies(DBB $db): void
    {
        $this->setUserId();
        $this->handleNewHobbies($db);
        $dbHobbies = getHobbies();
        $value = ' ';
        foreach ($this->userInfos['hobbies'] as $hobby) {
            $value .= $dbHobbies[$hobby] . ' ';
        }
        $query = "select * from user_hobbies where id_user = :id";
        $param = [
            self::ID => $this->userInfos['id']
        ];
        $result = $db->executeQuery($query, $param);
        $query = (count($result) > 0)
            ? "update user_hobbies set id_hobbies = :id_hobbies where id_user = :id"
            : "insert into user_hobbies (id_user, id_hobbies) values (:id, :id_hobbies)";
        $param = [
            self::ID_HOBBIES => $value,
            self::ID => $this->userInfos['id']
        ];
        $db->executeQuery($query, $param);

    }

    private function setUserId(): void
    {
        $db = new DBB();
        $db->connectToDb();
        $query = "select id from user where email like :email";
        $param = [
            self::EMAIL => $this->userInfos["email"]
        ];
        $result = $db->executeQuery($query, $param);
        $this->userInfos['id'] = $result[0]['id'];
    }

    private function handleNewHobbies(DBB $db): void
    {
        $dbHobbies = getHobbies();
        $userHobbies = $this->userInfos['hobbies'];
        $query = "insert into hobbies (name) value ";
        $count = 0;
        $lastElem = end($userHobbies);
        foreach ($userHobbies as $userHobby) {
            if (!array_key_exists($userHobby, $dbHobbies)) {
                ++$count;
                $query .= ($userHobby === $lastElem) ? "(:hobbies$count);" : "(:hobbies$count), ";
                $param[":hobbies$count"] = $userHobby;
            }
        }
        if ($count > 0) {
            $db->executeQuery($query, $param);
        }
    }

    public function login(): bool
    {
        $db = new DBB();
        $db->connectToDb();
        $query = "select count(*) 'numUser' from user where email like :email and password like :password and active = 1";
        $param = [
            self::EMAIL => $this->userInfos["email"],
            self::PASSWORD => md5($this->userInfos["password"]),
        ];
        $result = $db->executeQuery($query, $param);
        return $result[0]['numUser'] === 1;
    }

    public function deactivateAccount(): void
    {
        $db = new DBB();
        $db->executeQuery("update user set active = 0 where email like :email", [self::EMAIL => $this->userInfos['email']]);
    }

    public function updateProfile($oldEmail): true
    {
        $db = new DBB();
        $db->connectToDb();
        $query = "select id from user where email like :email";
        $param = [
            self::EMAIL => $oldEmail
        ];
        $result = $db->executeQuery($query, $param);
        $this->userInfos['id'] = $result[0]['id'];

        $values = ["firstname" => $this->userInfos["firstname"],
            "lastname" => $this->userInfos["lastname"],
            "birthdate" => $this->userInfos["birthdate"],
            "city" => $this->userInfos["city"],
            "sex" => $this->userInfos["sex"],
            "email" => $this->userInfos["email"],
            "password" => $this->userInfos["password"]];

        $values = array_filter($values);
        $this->handleUpdate($db, $values);
        if (!empty($this->userInfos['hobbies'])) {
            $this->handleHobbies($db);
        }
        if ($this->userInfos["email"] !== $oldEmail && $this->userInfos["email"] === '') {
            $this->userInfos["email"] = $oldEmail;
        }
        $this->setUserSession();
        return true;
    }

    private function handleUpdate(DBB $db, array $values): void
    {
        if (!empty($values)) {
            $params = [];
            $query = "update user u set ";
            $lastValues = end($values);
            foreach ($values as $key => $value) {
                if ($value === $lastValues) {
                    $query .= "$key = :$key ";
                } else {
                    $query .= "$key = :$key, ";
                }
                $params[":" . $key] = ($key === 'password') ? md5($value) : $value;
            }
            $query .= "where id = :id";
            $params[self::ID] = $this->userInfos['id'];
            $db->executeQuery($query, $params);
        }
    }

    public function setUserSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->fillUserInfos();
        $_SESSION['user'] = $this->userInfos;
    }

    private function fillUserInfos(): void
    {
        $db = new DBB();
        $db->connectToDb();
        $query = "select * from user u where u . email like :email";
        $param = [self::EMAIL => $this->userInfos["email"]];
        $result = $db->executeQuery($query, $param);
        if (empty($result)) {
            exit("Unable to find user with email " . $this->userInfos["email"] . "in database");
        }
        $this->userInfos = ["id" => $result[0]['id'],
            "firstname" => $result[0]['firstname'],
            "lastname" => $result[0]['lastname'],
            "birthdate" => $result[0]['birthdate'],
            "sex" => $result[0]['sex'],
            "email" => $result[0]['email'],
            "city" => $result[0]['city'],];

        $query = "select uh.id_hobbies idHobbies from user_hobbies uh where uh.id_user = :id";
        $param = [self::ID => $result[0]['id']];
        $result = $db->executeQuery($query, $param);
        $dbHobbies = getHobbies(true);
        if (!empty($result)) {
            $idHobbies = explode(' ', $result[0]['idHobbies']);
            $idHobbies = array_filter($idHobbies);
            foreach ($idHobbies as $id) {
                $this->userInfos["hobbies"][] = $dbHobbies[(int)$id];
            }
        }
    }
}
