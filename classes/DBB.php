<?php


class DBB
{
    private PDO|null $db;
    private string   $host;
    private string   $dbName;
    private string   $dbType;
    private string   $login;
    private string   $password;

    public function __construct(string $dbName = 'my_meetic', string $host = 'localhost', string $dbType = 'mysql', $login = 'admin', $password = 'admin')
    {
        $this->host     = $host;
        $this->dbName   = $dbName;
        $this->dbType   = $dbType;
        $this->login    = $login;
        $this->password = $password;
    }

    public function executeQuery(string $query, array $param = null): false|array
    {
        $this->connectToDb();
        if (isset($this->db)) {
            try {
                $stmt = $this->db->prepare($query);
                if ($param === null) {
                    $stmt->execute();
                } else {
                    $stmt->execute($param);
                }
                $this->closeDb();
                return $stmt->fetchAll();
            } catch (\PDOException $err) {
                echo $err->getMessage();
                $this->closeDb();
                return false;
            }
        } else {
            return false;
        }
    }

    public function connectToDb(): bool
    {
        if (!isset($this->db)) {
            $connection = $this->dbType . ":host=" . $this->host . ";dbname=" . $this->dbName;
            try {
                $this->db = new PDO($connection, $this->login, $this->password);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $err) {
                echo $err;
                $this->closeDb();
                return false;
            }
            return true;
        }
        return false;
    }

    public function closeDb(): void
    {
        $this->db = null;
    }
}
